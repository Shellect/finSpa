<?php

namespace engine\db;

use engine\db\types\Select;
use PDO;

abstract class ActiveRecord extends Model
{

    protected static string $tableName;
    protected string $primary_key;
    protected array $visible;

    public static function getTableName(): string
    {
        if (static::$tableName === "") {
            $class_parts = explode('\\', static::class);
            static::$tableName = end($class_parts);
        }
        return static::$tableName;
    }

    public function save(): int|null
    {
        $query = self::prepareQuery();
        $instanceFields = $this->attributes();
        if (isset($this->{$this->primary_key})) {
            return $query->update($instanceFields)->upload();
        } else {
            return $query->insert($instanceFields)->upload();
        }
    }

    public function delete(): bool
    {
        if (isset($this->{$this->primary_key})) {
            $query = self::prepareQuery();
            $query->delete();
            $query->from();
            $query->where($this->primary_key, $this->{$this->primary_key});
            return $query->runDelete();
        }
        return false;
    }

    /**
     * Метод поиска данных в базе
     * Данные передаются в виде скалярного значения,
     * тогда они интерпретируются, как первичный ключ
     *
     * ```
     * // SELECT * FROM `users` WHERE `id` = 123;
     * $user = User::find(123);
     * ```
     *
     * Данные передаются в виде числового массива
     * ```
     * // SELECT * FROM `users` WHERE `id` IN (100, 101, 102, 103);
     * $users = User::find([100, 101, 102, 103]);
     * ```
     *
     * Данные передаются в виде ассоциативного массива
     * ```
     * // SELECT * FROM `users` WHERE `username` = `John` AND `age` = 30
     * $users = User::find(['username' => John, 'age' => 30]]);
     * ```
     *
     * @param array $condition
     */
    public static function find(array $condition=[]): Query
    {
        return self::prepareQuery()->from(static::getTableName())->where($condition);
    }

    public static function where(...$arg): Query
    {
        $query = self::prepareQuery();
        $query->select();
        $query->where(...$arg);
        return $query;
    }

    public function select($fields = null): Query
    {
        $query = new Select();
        $query->from($this);
        return $query->select($fields);
    }

    public static function exists(string $_primary_key): bool
    {
        $query = self::prepareQuery();
        $query->select($_primary_key);
        $query->from();
        $query->where($query->primary_key, $_primary_key);
        return $query->count() > 0;
    }

    protected static function prepareQuery(string $class = null): Query
    {
        if (!$class) {
            $class = get_called_class();
        }
        $vars = get_class_vars($class);
        $table = $vars['tableName'];
        $query = new Query();
        $query->setFetchMode(PDO::FETCH_CLASS);
        $query->setFetchArgument($class);
        $query->from(static::getTableName());
        $query->primary_key = ($vars['primary_key']);
        return $query;
    }

    public function jsonSerialize(): array
    {
        $result = [];
        foreach ($this->visible as $visible_field) {
            if (isset($this->{$visible_field})) {
                $result[$visible_field] = $this->{$visible_field};
            }
        }
        return $result;
    }

    public function run($stmt)
    {

        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        return $stmt->fetch();
    }

}