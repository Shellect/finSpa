<?php


namespace engine\db\types;


use core\db\parts\QueryTable;

class TypeUpdate implements QueryType
{
    private string $set = '';

    public function __construct(private QueryTable $table)
    {

    }

    public function getCommand(): string
    {
        return 'UPDATE ' . $this->table->getTableName() . ' SET ' . $this->set;
    }
}