<?php

class InputValidator
{
  public static function sanitizeInput(string $input): string
  {
    return htmlspecialchars(trim($input));
  }

  public static function hasEmptyFields(array $fields): bool
  {
    foreach ($fields as $field) {
      if (empty($field)) {
        return true;
      }
    }
    return false;
  }

  public static function isValidPhoneNumber(string $phone): bool
  {
    return preg_match('/^\+7-[0-9]{3}-[0-9]{3}-[0-9]{2}-[0-9]{2}$/', $phone);
  }

  public static function isValidEmail(string $email): bool
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }
}
