<?php

$routes = [];

function route($method, $action, Closure $callback)
{
  global $routes;
  $action = ltrim($action, '/');
  $routes[$method][$action] = $callback;
}

function dispatch($method, $action)
{
  global $routes;
  $action = ltrim($action, '/');

  if (!isset($routes[$method][$action])) {
    header('Location: /login');
    return;
  }

  $callback = $routes[$method][$action];

  if (is_callable($callback)) {
    $callback();
  }
}
