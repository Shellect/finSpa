<?php


namespace engine\db\types;


class TypeDelete implements QueryType
{

    public function getCommand(): string
    {
        return 'DELETE';
    }
}