<?php
session_set_cookie_params([
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict',
]);
session_start();

require_once __DIR__ . '/router.php';
require_once __DIR__ . '/../app/auth/auth.controller.php';
require_once __DIR__ . '/../app/user/user.controller.php';

route('GET', '/login', function () {
  (new AuthController())->login();
});

route('POST', '/login', function () {
  (new AuthController())->handleLogin();
});

route('GET', '/register', function () {
  (new AuthController())->register();
});

route('POST', '/register', function () {
  (new AuthController())->handleRegister();
});

route('POST', '/logout', function () {
  (new AuthController())->logout();
});

route('GET', '/profile', function () {
  (new UserController())->showProfile();
});

route('POST', '/profile/update', function () {
  (new UserController())->handleUpdateProfile();
});

route('GET', '/migrate', function () {
  require_once __DIR__ . '/../views/migrate.php';
});

route('POST', '/migrate', function () {
  require_once __DIR__ . '/../views/migrate.php';
});

$action = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

dispatch($method, $action);
