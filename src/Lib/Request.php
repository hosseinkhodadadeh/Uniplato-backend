<?php


namespace App\Lib;


class Request
{

    private array $get;

    private array $post;

    private array $server;

    public function __construct(array $get = [], array $post = [], array $server = [])
    {

        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }

    public function get(string $key)
    {
        return $this->get[$key] ?? null;
    }
    public function post(string $key)
    {
        return $this->post[$key] ?? null;
    }
    public function server (string $key)
    {
        return $this->server[$key] ?? null;
    }

}