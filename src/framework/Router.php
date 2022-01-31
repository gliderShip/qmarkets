<?php

namespace App\framework;

class Router
{
  public const ROUTES = [
    'home' => [
      'controller' => 'HomeController',
      'action' => 'index',
    ],
    'posts' => [
      'controller' => 'PostsController',
      'action' => 'index',
    ],
    'posts.show' => [
      'controller' => 'PostsController',
      'action' => 'show',
    ]
  ];


  public static function getRoutes(): array
  {
    return self::ROUTES;
  }
}
