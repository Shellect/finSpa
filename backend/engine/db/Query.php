<?php
namespace engine\db;

use engine\App;
use engine\helpers\ArrayHelper;
use ErrorException;
use PDO;
use PDOStatement;
use ReflectionException;

class Query
{
    private PDO $connection;

    protected string $sql = '';

    private const AND = ' AND ';
    private const OR = ' OR ';

    private const QUERY_READ = 0;
    private const QUERY_READ_D = 1;
    private const QUERY_CREATE = 2;
    private const QUERY_UPDATE = 3;
    private const QUERY_DELETE = 4;

    private const LEFT_JOIN = ' LEFT JOIN ';
    private const RIGHT_JOIN = ' RIGHT JOIN ';
    private const INNER_JOIN = ' INNER JOIN ';

    protected int $query_type = self::QUERY_READ;

    private string $operator = self::AND;

    private array $query_parts = [
        ['SELECT ', ' FROM '], ['SELECT DISTINCT ', ' FROM '], ['INSERT INTO', ' '], ['UPDATE', ' '], ['DELETE', ' FROM ']
    ];
    private array $comparison_operators = ['=', '!=', '<', '>', '<=', '>=', '<>', 'IN', 'OR', 'IS'];
    private int $fetch_mode = PDO::FETCH_CLASS;

    private mixed $fetch_argument;
    private array $bind_types = [
        'NULL' => PDO::PARAM_NULL,
        'integer' => PDO::PARAM_INT,
        'string' => PDO::PARAM_STR,
        'boolean' => PDO::PARAM_BOOL,
        'double ' => PDO::PARAM_INT
    ];

    /**
     * The name of the DB table FROM what we get query
     *
     * @var string|null
     */
    protected ?string $tableName;

    protected array $alias = [];

    /**
     * Fields that we need to SELECT from the DB
     *
     * @var array
     */
    protected array $fields = [];

    /**
     * Values to INSERT INTO the table
     *
     * @var array
     */
    protected array $values = [];

    /**
     * Часть запроса после WHERE
     *
     * @var string
     */
    protected string $where = '';

    /**
     * Associate array contains pairs marker:value
     *
     * @var array
     */
    protected array $where_markers = [];

    /**
     * Array to specify the query with parameters
     *
     * @var array
     */
    protected array $order;

    /**
     * Number of rows LIMIT
     *
     * @var array $limit
     */
    protected array $limit = [];

    protected string $group = '';

//    protected array $_like;

    protected array $joins = [];

    public string|null $primary_key;

    /**
     * @throws ReflectionException
     * @throws ErrorException
     */
    public function __construct()
    {
        $this->connection = App::createObject(DB::class, [
            'driver' => App::$config['driver'],
            'host' => App::$config['host'],
            'user' => App::$config['username'],
            'pass' => App::$config['password'],
            'dbname' => App::$config['dbname'],
        ])->connect();
    }

    public function setFetchMode(string $mode): void
    {
        $this->fetch_mode = $mode;
    }

    public function setFetchArgument(string $argument): void
    {
        $this->fetch_argument = $argument;
    }

    public function rawQuery(string $sql, array $placeholders = []): bool|PDOStatement
    {
        $this->sql = $sql;
        $this->fetch_mode = PDO::FETCH_OBJ;
        $this->where_markers = $placeholders;
        return $this->get();
    }

    /**
     * First method of the constructor
     * Adds db fields to the query
     *
     * @param array|string|null $fields
     * @param bool $distinct
     * @return $this
     */
    public function select(array|string $fields = null, bool $distinct = false): Query
    {
        if (is_null($this->sql)) {
            $this->query_type = $distinct ? self::QUERY_READ_D : self::QUERY_READ;
        }
        return $this->setFields($fields);
    }

    public function selectDistinct($fields = null): Query
    {
        return $this->select($fields, true);
    }

    /**
     * Метод сопоставляет длинные имена таблиц с псевдонимами
     *
     * @param array|string $table
     * @param null $alias
     * @return $this
     */
    public function as(array|string $table, $alias = null): Query
    {
        if (is_array($table)) {
            if (empty($table)) {
                return $this;
            }
            if (ArrayHelper::is_multidimensional($table)) {
                array_walk($table, [$this, 'as']);
                return $this;
            }
            if (ArrayHelper::all_numeric_keys($table)) {
                return $this->as(...$table);
            }
            foreach ($table as $table_name => $alias_name) {
                $this->as($table_name, $alias_name);
            }
            return $this;
        }

        $this->alias[$table] = "`$alias`";
        return $this;
    }

    public function count($column_name = '*')
    {
        if (isset($this->fields[$column_name])) {
            $this->fields[$column_name] = "COUNT($column_name)";
        } else {
            $this->fields[] = "COUNT($column_name)";
        }
        return $this->one()["COUNT($column_name)"];
    }

    public function create($fields, $values = null): static
    {
        $this->query_type = self::QUERY_CREATE;
        return $this->insert($fields, $values);
    }

    public function update(array|string $fields, array|string $values = null): Query
    {
        $this->query_type = self::QUERY_UPDATE;
        return $this->insert($fields, $values);
    }

    /**
     * @param array|string $fields Имена полей для вставки
     * @param array|string|null $values Значения полей для вставки
     * @return Query|$this
     */
    public function insert(array|string $fields, array|string $values = null): Query
    {
        $this->query_type = self::QUERY_CREATE;
        if (is_array($fields)) {
            if (ArrayHelper::is_multidimensional($fields)) {
                array_walk($fields, [$this, 'insert']);
                return $this;
            }
            if (ArrayHelper::all_numeric_keys($fields)) {
                return $this->insert(...$fields);
            }
            foreach ($fields as $field => $field_value) {
                $this->setUpdate($field, $field_value);
            }
            return $this;
        }

        $this->setUpdate($fields, $values);
        return $this;
    }

    public function setUpdate($field, $value): void
    {
        $id = ':' . uniqid('', false);
        $this->fields[$id] = $field;
        $this->values[$id] = $value;
    }

    public function delete(): static
    {

        $this->query_type = self::QUERY_DELETE;
        return $this;
    }

    /**
     * The method, that sets the name of the table
     */
    public function from(string $table_name): Query
    {
//        if ($table instanceof Model) {
////            $this->_model = $table;
//            $this->fetch_argument = get_class($table);
//            $this->tableName = $table->getTableName();
//            return $this;
//        }
        $this->tableName = $table_name;
        return $this;
    }

    /**
     * Specify the query by parameters
     *
     * You can use query as:
     *
     * ```
     *  $user = new Query::select()->from('users')->where([['name', 'John'])->where(['age', '>', '30']);
     * ```
     *
     * @param array|string $fields
     * @param string $comparison
     * @param array|string|null $value
     * @return Query
     */
    public function where(array|string $fields, string $comparison = ' = ', array|string $value = null): Query
    {
        if (is_array($fields)) {
            if (empty($fields)) {
                return $this;
            }
            if (ArrayHelper::is_multidimensional($fields)) {
                array_walk($fields, [$this, 'where'], $comparison);
                return $this;
            }
            if (ArrayHelper::all_numeric_keys($fields)) {
                return $this->where(...$fields);
            }
            foreach ($fields as $field => $field_value) {
                $addWhere = $this->addWhere($field, $comparison, $field_value);
                $this->setWhere($addWhere);
            }
            return $this;
        }

        if (in_array($comparison, $this->comparison_operators, true)) {
            $addWhere = $this->addWhere($fields, $comparison, $value);
        } else {
            $addWhere = $this->addWhere($fields, '=', $comparison);
        }
        $this->setWhere($addWhere);
        return $this;
    }

    private function addWhere(string $field, string $comparison, $field_value): string
    {
        if ($field_value instanceof self){

            $field_value =  $field_value->getSql();
        }

        if (is_array($field_value)) {
            // При передаче массива в качестве значения поля, оператор сравнения автоматически считается `IN`
            $markers = [];
            foreach ($field_value as $value) {
                $marker = ':' . uniqid('', false);
                $this->where_markers[$marker] = $value;
                $markers[] = $marker;
            }
            $range = implode(",", $markers);
            $field = self::escape($field);
            return "$field IN ($range)";
        }

        $marker = ':' . uniqid('', false);
        $this->where_markers[$marker] = $field_value;
        $field = self::escape($field);
        return "$field $comparison $marker";
    }

    private function setWhere($addWhere): void
    {
        $this->where = empty($this->where)
            ? $addWhere
            : $this->where . ' ' . $this->operator . ' ' . $addWhere;
    }

    public function andWhere($fields, $comparison = '=', $value = null): Query
    {
        $this->operator = self:: AND;
        $this->where($fields, $comparison, $value);
        return $this;
    }

    public function orWhere($fields, $comparison = '=', $value = null): Query
    {
        $this->operator = self:: OR;
        $this->where($fields, $comparison, $value);
        return $this;
    }

    public function rawWhere($sql, $comparison = '', $value = null): static
    {
        $this->setWhere($sql . $comparison . $value);
        return $this;
    }

    public function order($fields, $direction = null): static
    {
        if (is_array($fields)) {
            foreach ($fields as $key => $value) {
                $this->order[] = $key . ' ' . $value;
            }
        } else {
            $this->order[] = $fields . ($direction ? ' ' . $direction : ' DESC');
        }
        return $this;
    }

    public function leftJoin($table, $on): Query
    {
        return $this->join($table, $on);
    }

    public function rightJoin($table, $on): Query
    {
        return $this->join($table, $on, self::RIGHT_JOIN);
    }

    public function innerJoin($table, $on): Query
    {
        return $this->join($table, $on, self::INNER_JOIN);
    }

    public function join($table, array $on, $join_type=self::LEFT_JOIN): Query
    {
        if (ArrayHelper::all_numeric_keys($on)) {
            $on_statement = implode(' = ', array_map([static::class, 'escape'], $on));
        } else {
            $on_parts = [];
            foreach ($on as $first_field => $second_field){
                $on_parts[] = self::escape($first_field) . ' = ' . self::escape($second_field);
            }

            $on_statement = implode(' AND ', $on_parts);
        }

        $this->joins[] = $join_type . $this->getAlias($table) . ' ON ' . $on_statement;
        return $this;
    }

    public function take(int $limit): Query
    {
        $this->limit = [0, $limit];
        return $this;
    }

    public function limit($start, $end): Query
    {
        $this->limit = [$start, $end];
        return $this;
    }

    public function group($group): Query
    {
        $this->group = $group;
        return $this;
    }

    private function buildQuery(): string
    {
        $query_parts = [
            $this->buildStatement(),
            $this->buildTable(),
            $this->buildValues(),
            $this->buildJoin(),
            $this->buildSet(),
            $this->buildWhere(),
            $this->buildOrder(),
            $this->buildGroup(),
            $this->buildLimit(),

        ];

        $sql = implode(' ', array_filter($query_parts));

        $this->sql = preg_replace('/\s\s+|\t\t+/', ' ', trim($sql));
        return $this->sql;


    }

    private function buildStatement(): string
    {
        $sql = $this->query_parts[$this->query_type][0];
        if (in_array($this->query_type, [self::QUERY_UPDATE, self::QUERY_DELETE, self::QUERY_CREATE], true)) {
            return $sql;
        }

        if (
            $this->query_type === self::QUERY_READ
            && empty($this->fields)
        ) {
            $this->fields[] = '*';
        }

        $statement = array_map(
            static function ($el) {
                if (preg_match('%\*|GROUP|COUNT|DATE_FORMAT%', $el)) {
                    return $el;
                }
                return self::escape($el);
            },
            $this->fields);
        return $sql . implode(', ', $statement);
    }

    private function buildTable(): string
    {
        if ($this->tableName === null) {
            throw new ErrorException();
        }
        return $this->query_parts[$this->query_type][1] . $this->getAlias($this->tableName);
    }

    private function buildValues(): string
    {
        $values = '';
        if ($this->query_type === self::QUERY_CREATE && $this->values) {
            $i = 0;
            foreach ($this->values as $key => $value) {
                if ($value === null) {
                    unset($this->values[$key], $this->fields[$i]);
                }
                $i++;
            }
            $values .= ' ('
                . implode(', ', array_map([self::class, 'escape'], $this->fields))
                . ') VALUES ('
                . implode(', ', array_keys($this->values))
                . ')';

        }
        return $values;
    }

    private function buildSet(): string
    {
        $set = '';
        if ($this->query_type === self::QUERY_UPDATE && $this->values) {
            $set = ' SET ';
            $length = count($this->fields);
            $values = array_keys($this->values);
            for ($i = 0; $i < $length; $i++) {
                $values[$i] = $this->fields[$i] . ' = ' . $values[$i];
            }
            $set .= implode(', ', $values);
        }
        return $set;
    }

    private function buildJoin(): string
    {
        return implode('', $this->joins);
    }

    private function buildGroup(): string
    {
        return $this->group;
    }

    private function buildWhere(): string
    {
        if (empty($this->where) && in_array($this->query_type, [self::QUERY_UPDATE, self::QUERY_DELETE])) {
            throw new ErrorException();
        }
        return empty($this->where) ? '' : ' WHERE ' . $this->where;
    }

    private function buildOrder(): string
    {
        return empty($this->order) ? '' : ' ORDER BY ' . implode(' ', $this->order);
    }

    private function buildLimit(): string
    {
        return empty($this->limit) ? '' : ' LIMIT :start, :end';
    }

    /**
     * Moved to Select Type
     */
    protected function setFields($fields): Query
    {
        if ($fields === null && empty($this->fields)) {
            return $this;
        }
        if (is_array($fields)) {
            $addFields = array_merge($this->fields, $fields);
            $this->fields = array_flip(array_flip($addFields));
            return $this;
        }
        $this->fields[] = $fields;
        return $this;
    }

    private function getAlias($table_name): string
    {
        $format_table_name = "`$table_name`";
        if (array_key_exists($table_name, $this->alias)) {
            $format_table_name .= " AS " . $this->alias[$table_name];
        }
        return $format_table_name;
    }

    private static function escape($str): string
    {
        return '`' . str_replace('.', '`.`', $str) . '`';
    }

    public function upload(): int|null
    {
        $stmt = $this->connection->prepare($this->buildQuery());
        $this->bindValues($stmt);
        $this->bindWhere($stmt);
        $return = $stmt->execute();
        if ($return) {
            return (int)$this->connection->lastInsertId();
        }
        return null;
    }

    public function all($fetch_mode = null, $fetch_argument = null): array
    {
        $mode = $fetch_mode ?? $this->fetch_mode;

        $stmt = $this->get();
        // int 8 соответствует PDO::FETCH_CLASS, int 5 соответствует PDO::FETCH_OBJ
        if (preg_match('%[85]%', $mode)) {
            $fetch_argument = $fetch_argument ?? $this->fetch_argument;
            return $stmt->fetchAll($mode, $fetch_argument);
        }
        return $stmt->fetchAll($mode);
    }

    public function one($fetch_mode = null, $fetch_argument = null): mixed
    {
        $mode = $fetch_mode ?? $this->fetch_mode;

        $stmt = $this->get();
        if (preg_match('%[85]%', $mode)) {
            $fetch_argument = $fetch_argument ?? $this->fetch_argument;
            $stmt->setFetchMode($mode, $fetch_argument);
        } else {
            $stmt->setFetchMode($mode);
        }
        return $stmt->fetch();
    }

    private function get(): bool|PDOStatement
    {
        $sql = $this->buildQuery();
        $stmt = $this->connection->prepare($sql);
        $this->bindWhere($stmt);
        $this->bindLimit($stmt);
        $stmt->execute();
        return $stmt;
    }

    public function getSql(): array
    {
        return [
            "sql" => $this->buildQuery(),
            "bind" => $this->where_markers
        ];
    }

    private function bindValues($stmt): void
    {
        foreach ($this->values as $bindParam => $bindValue) {
            $stmt->bindValue($bindParam, $bindValue, $this->bind_types[gettype($bindValue)]);
        }
    }

    private function bindWhere($stmt): void
    {
        foreach ($this->where_markers as $bindParam => $bindValue) {
            $stmt->bindValue($bindParam, $bindValue, $this->bind_types[gettype($bindValue)]);
        }
    }

    private function bindLimit($stmt): void
    {
        if (!empty($this->limit)) {
            $stmt->bindValue(':start', $this->limit[0], $this->bind_types['integer']);
            $stmt->bindValue(':end', $this->limit[1], $this->bind_types['integer']);
        }
    }

}