<?php

namespace App\Framework\Routing;

class Route
{
    private string $name;

    private string $url;

    private array $methods;

    private string $controller;

    private string $action;

    public function __construct(string $name, string $url, array $methods, string $controller, string $action)
    {
        $this->name = $name;
        $this->url = $url;
        $this->methods = $methods;
        $this->controller = $controller;
        $this->action = $action;

    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }



}
