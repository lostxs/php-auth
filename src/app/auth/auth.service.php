<?php
$config = require __DIR__ . '/../../../env.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../user/user.repository.php';
require_once __DIR__ . '/../../common/utils/input-validator.util.php';
require_once __DIR__ . '/../../common/utils/redirect.util.php';

class AuthService
{
  protected $config;
  protected $userModel;

  public function __construct($config)
  {
    $this->config = $config;
    $this->userModel = new User();
  }

  public function register(array $data): void
  {
    $username = InputValidator::sanitizeInput($data['username']);
    $phone = InputValidator::sanitizeInput($data['phone']);
    $email = InputValidator::sanitizeInput($data['email']);
    $password = $data['password'];
    $confirm_password = $data['confirm_password'];

    if (
      $this->validateRegistrationUser(
        $username,
        $phone,
        $email,
        $password,
        $confirm_password
      )
    ) {
      return;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if (
      $this->userModel->createUser($username, $email, $phone, $hashed_password)
    ) {
      Redirect::redirectWithMessage(
        '/login',
        'Регистрация прошла успешно! Теперь вы можете войти',
        'success'
      );
    } else {
      Redirect::redirectWithMessage(
        '/register',
        'Ошибка при регистрации',
        'error'
      );
    }
  }

  public function login(array $data): void
  {
    $emailOrPhone = htmlspecialchars(trim($data['emailOrPhone']));
    $password = $data['password'];

    if (
      $this->validateLoginUser($emailOrPhone, $password, $data['smart-token'])
    ) {
      return;
    }

    $user = $this->userModel->getUserByEmailOrPhone($emailOrPhone);

    if (!$user) {
      Redirect::redirectWithMessage(
        '/login',
        'Пользователь не найден',
        'error'
      );
      return;
    }

    if (!password_verify($password, $user['password'])) {
      Redirect::redirectWithMessage('/login', 'Неверный пароль', 'error');
      return;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    Redirect::redirectWithMessage(
      '/profile',
      'Вы успешно вошли в систему',
      'success'
    );
  }

  public function logout(): void
  {
    session_unset();
    session_destroy();
  }

  private function validateRegistrationUser(
    $username,
    $phone,
    $email,
    $password,
    $confirm_password
  ): bool {
    if (
      InputValidator::hasEmptyFields([
        $username,
        $phone,
        $email,
        $password,
        $confirm_password,
      ])
    ) {
      Redirect::redirectWithMessage('/register', 'Заполните все поля', 'error');
      return true;
    }

    if (!InputValidator::isValidPhoneNumber($phone)) {
      Redirect::redirectWithMessage(
        '/register',
        'Некорректный номер телефона',
        'error'
      );
      return true;
    }

    if (!InputValidator::isValidEmail($email)) {
      Redirect::redirectWithMessage('/register', 'Некорректный email', 'error');
      return true;
    }

    if ($password !== $confirm_password) {
      Redirect::redirectWithMessage(
        '/register',
        'Пароли не совпадают',
        'error'
      );
      return true;
    }

    if ($this->userModel->getUserByUsername($username)) {
      Redirect::redirectWithMessage(
        '/register',
        'Пользователь с таким именем уже существует',
        'error'
      );
      return true;
    }

    if ($this->userModel->getUserByEmail($email)) {
      Redirect::redirectWithMessage(
        '/register',
        'Пользователь с таким email уже существует',
        'error'
      );
      return true;
    }

    if ($this->userModel->getUserByPhone($phone)) {
      Redirect::redirectWithMessage(
        '/register',
        'Пользователь с таким телефоном уже существует',
        'error'
      );
      return true;
    }

    return false;
  }

  private function validateLoginUser(
    $emailOrPhone,
    $password,
    $captchaToken
  ): bool {
    if (InputValidator::hasEmptyFields([$emailOrPhone, $password])) {
      Redirect::redirectWithMessage('/login', 'Заполните все поля', 'error');
      return true;
    }

    if (!$this->checkCaptcha($captchaToken)) {
      Redirect::redirectWithMessage('/login', 'Неверная капча', 'error');
      return true;
    }

    return false;
  }

  private function checkCaptcha($captchaToken): bool
  {
    $secretKey = $this->config['yandex']['secret_key'];

    $ch = curl_init();
    $args = http_build_query([
      'secret' => $secretKey,
      'token' => $captchaToken,
      'ip' => $_SERVER['REMOTE_ADDR'],
    ]);
    curl_setopt(
      $ch,
      CURLOPT_URL,
      "https://smartcaptcha.yandexcloud.net/validate?$args"
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
      echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
      return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === 'ok';
  }
}
