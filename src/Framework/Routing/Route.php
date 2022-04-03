<?php

namespace App\Framework\Routing;

class Route
{
    private string $name;

    private string $path;

    private array $methods;

    private string $controller;

    private string $action;

    private ?string $parameter;

    public function __construct(string $name, string $url, array $methods, string $controller, string $action)
    {
        $this->name = $name;
        $this->path = $url;
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

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
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

    public function getParameter(): ?string
    {
        return $this->parameter;
    }

    public function setParameter(?string $parameter = null): void
    {
        $this->parameter = $parameter;
    }

}
