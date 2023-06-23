<?php

namespace engine;

class Request
{
    public string $uri;
    public array $queryParams;
    public array $postParams;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->queryParams = $_GET ?? [];
        $this->postParams = $_POST ?? [];
        $json_request = file_get_contents('php://input');
        $json_data = json_decode($json_request, true);
        if ($json_data){
            $this->postParams = array_merge($this->postParams, $json_data);
        }
    }
}