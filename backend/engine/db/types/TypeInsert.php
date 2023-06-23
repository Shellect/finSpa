<?php


namespace engine\db\types;


class TypeInsert implements QueryType
{

    public function getCommand(): string
    {
        return 'INSERT';
    }
}