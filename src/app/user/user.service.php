<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/user.repository.php';
require_once __DIR__ . '/../../common/utils/input-validator.util.php';
require_once __DIR__ . '/../../common/utils/redirect.util.php';

class UserService
{
  protected $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function getUserProfile($userId)
  {
    return $this->userModel->getUserById($userId);
  }

  public function updateProfile(int $userId, array $data): bool
  {
    $username = InputValidator::sanitizeInput($data['username']);
    $phone = InputValidator::sanitizeInput($data['phone']);
    $email = InputValidator::sanitizeInput($data['email']);

    $validationResult = $this->validateProfileData(
      $userId,
      $username,
      $email,
      $phone,
      $data
    );
    if ($validationResult !== true) {
      Redirect::redirectWithMessage('/profile', $validationResult, 'error');
      return false;
    }

    $password = !empty($data['password'])
      ? password_hash($data['password'], PASSWORD_DEFAULT)
      : null;

    if (
      $this->userModel->updateUser(
        $userId,
        $username,
        $email,
        $phone,
        $password
      )
    ) {
      Redirect::redirectWithMessage(
        '/profile',
        'Профиль успешно обновлен',
        'success'
      );
      return true;
    }

    Redirect::redirectWithMessage(
      '/profile',
      'Ошибка обновления профиля',
      'error'
    );
    return false;
  }

  protected function validateProfileData(
    $userId,
    $username,
    $email,
    $phone,
    $data
  ) {
    if (empty($username) || empty($phone) || empty($email)) {
      return 'Заполните все поля';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return 'Некорректный email';
    }

    $currentUser = $this->userModel->getUserById($userId);

    if (
      $username !== $currentUser['username'] &&
      $this->userModel->getUserByUsername($username)
    ) {
      return 'Пользователь с таким именем уже существует';
    }

    if (
      $email !== $currentUser['email'] &&
      $this->userModel->getUserByEmail($email)
    ) {
      return 'Пользователь с таким email уже существует';
    }

    if (
      $phone !== $currentUser['phone'] &&
      $this->userModel->getUserByPhone($phone)
    ) {
      return 'Пользователь с таким телефоном уже существует';
    }

    if (!empty($data['password'])) {
      if ($data['password'] !== $data['confirm_password']) {
        return 'Пароли не совпадают';
      }
    }

    return true;
  }
}
