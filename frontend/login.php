<?php
session_start();
if (isset($_COOKIE['jwt']) && !empty($_COOKIE['jwt'])) {
  header('Location: dashboard_Pendaftaran.php');
  exit();
}
?>
<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr"
  data-skin="default" data-assets-path="assets/" data-template="horizontal-menu-template" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Naratel Internet Service Portal</title>
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/vendor/fonts/iconify-icons.css" />
  <link rel="stylesheet" href="assets/vendor/libs/pickr/pickr-themes.css" />
  <link rel="stylesheet" href="assets/vendor/css/core.css" />
  <link rel="stylesheet" href="assets/css/demo.css" />
  <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="assets/vendor/libs/@form-validation/form-validation.css" />
  <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
  <script src="assets/vendor/js/helpers.js"></script>
  <script src="assets/vendor/js/template-customizer.js"></script>
  <script src="assets/js/config.js"></script>
</head>
<body>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <div class="app-brand justify-content-center">
              <a href="#" class="app-brand-link gap-2 align-items-center">
                <span class="app-brand-logo demo">
                  <img src="https://wo.naraya.co.id/beta/img/logoq.png" alt="Logo" width="100" height="100" />
                </span>
              </a>
            </div>

            <div class="text-center">
              <h4 class="mb-1">MyKapten 👋</h4>
              <p class="mb-6">Kapten Management Dashboard.</p>
            </div>

            <div id="messageContainer" class="d-none mb-4">
              <div id="alertBox" class="alert" role="alert">
                <i id="alertIcon" class="me-2"></i>
                <span id="alertText"></span>
              </div>
            </div>

            <form id="formAuthentication" class="mb-6">
              <div class="mb-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" class="form-control" name="username" required />
              </div>
              <div class="mb-6">
                <label for="password" class="form-label">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" required />
                  <span class="input-group-text cursor-pointer" id="togglePassword">
                    <i class="icon-base bx bx-hide"></i>
                  </span>
                </div>
              </div>
              <div class="mb-7 d-flex justify-content-between">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" />
                  <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
                <a href="#"><span>Forgot Password?</span></a>
              </div>
              <div>
                <button class="btn btn-primary d-grid w-100" type="submit" id="loginBtn">
                  <span id="loginText">Login</span>
                  <span id="loginSpinner" class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                </button>
              </div>
            </form>

            <p class="text-center mt-4">
              <span>New on our platform?</span>
              <a href="register.php"><span>Create an account</span></a>
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Core JS -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="assets/vendor/js/menu.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('formAuthentication');
      const loginBtn = document.getElementById('loginBtn');
      const loginText = document.getElementById('loginText');
      const loginSpinner = document.getElementById('loginSpinner');
      const usernameField = document.getElementById('username');
      const passwordField = document.getElementById('password');
      const rememberMe = document.getElementById('remember-me');
      const messageContainer = document.getElementById('messageContainer');
      const alertBox = document.getElementById('alertBox');
      const alertIcon = document.getElementById('alertIcon');
      const alertText = document.getElementById('alertText');

      // Toggle password visibility
      document.getElementById('togglePassword').addEventListener('click', function () {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.querySelector('i').className = `icon-base bx ${type === 'password' ? 'bx-hide' : 'bx-show'}`;
      });

      // Autofill username if remembered
      const remembered = localStorage.getItem("rememberedUsername");
      if (remembered) {
        usernameField.value = remembered;
        rememberMe.checked = true;
      }

      form.addEventListener('submit', async e => {
        e.preventDefault();

        const username = usernameField.value.trim();
        const password = passwordField.value;

        if (!username || !password) return showMsg('error', 'Username and password required');

        loginBtn.disabled = true;
        loginText.textContent = 'Logging in...';
        loginSpinner.classList.remove('d-none');

        try {
          const res = await fetch('http://localhost:8000/?path=login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
          });

          const data = await res.json();

          if (res.ok && data.token) {
            document.cookie = `jwt=${data.token}; path=/; Secure; SameSite=Lax`;

            if (rememberMe.checked) {
              localStorage.setItem("rememberedUsername", username);
            } else {
              localStorage.removeItem("rememberedUsername");
            }

            showMsg('success', 'Login successful. Redirecting...');
            setTimeout(() => window.location.href = 'dashboard_Pendaftaran.php', 1000);
          } else {
            showMsg('error', data.message || 'Login failed');
          }
        } catch (err) {
          showMsg('error', 'Server error or invalid response.');
        }

        loginBtn.disabled = false;
        loginText.textContent = 'Login';
        loginSpinner.classList.add('d-none');
      });

      function showMsg(type, msg) {
        messageContainer.classList.remove('d-none');
        alertBox.className = `alert alert-${type === 'error' ? 'danger' : 'success'}`;
        alertIcon.className = `${type === 'error' ? 'bx bx-error-circle' : 'bx bx-check-circle'} me-2`;
        alertText.textContent = msg;
      }
    });
  </script>
</body>
</html>
