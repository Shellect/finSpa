<?php


namespace engine\db;

use ArrayAccess;

abstract class Model implements ArrayAccess
{
    protected array $errors = [];
    protected array $attr = [];

    public function __set($name, $value)
    {
        $this->attr[$name] = $value;
    }

    public function __get($name)
    {
        return $this->attr[$name];
    }

    public function __isset($name)
    {
        return $this->attr[$name]??null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->attr[] = $value;
        } else {
            $this->attr[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attr[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->attr[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->attr[$offset] ?? null;
    }

    public function attributes(): array{
        return $this->attr;
    }
}