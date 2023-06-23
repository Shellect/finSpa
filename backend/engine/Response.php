<?php

namespace engine;

class Response
{
    public function __construct(private readonly string $content)
    {
    }

    public function send()
    {
        echo $this->content;
    }
}