<?php

declare(strict_types=1);

namespace App;

class Request
{
    private const DEFAULT_TASK = 'start';

    private array $get = [];
    private array $post = [];
    private array $server = [];

    public function __construct(array $get, array $post, array $server)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }

    public function hasPost(): bool
    {
        return !empty($this->post);
    }

    public function isPost(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'POST';
    }
    public function isGet(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'GET';
    }

    public function paramGet(string $name, $default = self::DEFAULT_TASK): string
    {
        return $this->get[$name] ?? $default;
    }
    public function paramPost(string $name, $default = null): string
    {
        return $this->post[$name] ?? $default;
    }
}
