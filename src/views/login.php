<?php
$config = include '../../env.php';

$clientKey = $config['yandex']['client_key'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Авторизация</title>
  <link rel="stylesheet" href="assets/styles/index.css">
  <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>
<body>
  <main class="root">
    <div class="flex-flex flex-direction--column justify-center" style="flex-grow: 1; z-index: 9;">
      <div class="formbg-outer">
        <div class="formbg">
          <div class="formbg-inner padding-horizontal--48">
            <div class="padding-bottom--24 flex-flex flex-justifyContent--center">
              <h1>Вход в аккаунт</h1>
            </div>
            <form action="/login" method="post">
              <div class="field padding-bottom--24">
                <label for="emailOrPhone">Email или телефон</label>
                <input type="text" name="emailOrPhone" id="emailOrPhone" autocomplete="email" required>
              </div>
              <div class="field padding-bottom--24">
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" autocomplete="off" required>
              </div>
              <div class="field padding-bottom--24">
                  <?php if (isset($_SESSION['message'])): ?>
                      <p class="message <?= htmlspecialchars(
                        $_SESSION['message']['type']
                      ) ?>">
                          <?= htmlspecialchars($_SESSION['message']['text']) ?>
                      </p>
                      <?php unset($_SESSION['message']); ?>
                  <?php endif; ?>
              </div>
              <div class="field padding-bottom--24">
                <button type="submit">Войти</button>
              </div>
              <div class="field padding-bottom--24">
                <div 
                style="height: 100px"
                id="captcha-container"
                class="smart-captcha"
                data-sitekey="<?php echo $clientKey; ?>"
              ></div>
              </div>
              <div class="footer-link">
                <span>Нет аккаунта? <a href="/register">Регистрация</a></span>
                <span>Совершить <a href="/migrate">Миграцию</a></span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>