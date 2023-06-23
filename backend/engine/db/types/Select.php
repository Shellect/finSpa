<?php


namespace engine\db\types;


use engine\db\Query;
//use engine\db\QueryHelper;

class Select extends Query implements QueryType
{
    private array $statement = [];
    private string $keyword = 'SELECT ';

    public function __construct($fields=null)
    {
        parent::__construct();
        if (empty($fields)){
            $this->statement[] = '*';
        } elseif (is_array($fields)){
            $this->statement = array_map(
                static function ($el) {
                    if (preg_match('%\*|GROUP|COUNT|DATE_FORMAT%', $el)) {
                        return $el;
                    }
                    return '`' . str_replace('.', '`.`', $el) . '`';
                },
                $fields);
        } else {
            $this->statement[] = $fields;
        }
    }

    public function select(array|string $fields = null, bool $distinct = false): Query
    {
        return $this->setFields($fields);
    }

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

    public function getSQL(): array
    {
        return [$this->keyword . implode(', ', $this->statement) .  ' FROM ' . $this->tableName];
    }
}