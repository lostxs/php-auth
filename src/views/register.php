<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Авторизация</title>
  <link rel="stylesheet" href="assets/styles/index.css">
</head>
<body>
 <main class="root">
  <div class="flex-flex flex-direction--column justify-center" style="flex-grow: 1; z-index: 9;">
    <div class="formbg-outer">
      <div class="formbg">
        <div class="formbg-inner padding-horizontal--48">
          <div class="padding-bottom--24 flex-flex flex-justifyContent--center">
            <h1>Регистрация</h1>
          </div>
          <form action="/register" method="post">
            <div class="field padding-bottom--24">
              <label for="username">Имя пользователя</label>
              <input type="text" name="username" id="username" autocomplete="off" placeholder="myusername" required>
            </div>
            <div class="field padding-bottom--24">
              <label for="phone">Телефон</label>
              <input type="tel" name="phone" id="phone" autocomplete="off" pattern="\+7-[0-9]{3}-[0-9]{3}-[0-9]{2}-[0-9]{2}" placeholder="+7-999-999-99-99" required>
            </div>
            <div class="field padding-bottom--24">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" autocomplete="off" placeholder="user@example.com" required>
            </div>
            <div class="field padding-bottom--24">
              <label for="password">Пароль</label>
              <input type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <div class="field padding-bottom--24">
              <label for="confirm_password">Подтвердите пароль</label>
              <input type="password" name="confirm_password" id="confirm_password" autocomplete="off" required>
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
                <button type="submit">Регистрация</button>
              </div>
              <div class="footer-link">
                <span>Уже есть аккаунт? <a href="/login">Войти</a></span>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
 </main>
</body>
</html>