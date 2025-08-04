'use strict';
console.log("pages-auth.js loaded");
document.addEventListener('DOMContentLoaded', function () {
  (() => {
    const formAuthentication = document.querySelector('#formAuthentication');
console.log("Form found:", formAuthentication);
console.log("FormValidation available:", typeof FormValidation);

    if (formAuthentication && typeof FormValidation !== 'undefined') {
      const validationInstance = FormValidation.formValidation(formAuthentication, {
        fields: {
          username: {
            validators: {
              notEmpty: {
                message: 'Please enter username'
              },
              stringLength: {
                min: 4,
                message: 'Username must be at least 4 characters'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'Please enter your password'
              },
              stringLength: {
                min: 6,
                message: 'Password must be at least 6 characters'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.form-control-validation'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', e => {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        },

        // ✅ Gantikan default submit dengan fetch
        onValid: async function () {
          const username = document.getElementById('username').value;
          const password = document.getElementById('password').value;

          try {
            const response = await fetch("http://localhost:8000/routes/api.php?path=login", {
              method: "POST",
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify({ username, password })
            });

            const result = await response.json();

            if (response.ok && result.token) {
              alert("✅ Login berhasil!");
              localStorage.setItem("token", result.token);
              localStorage.setItem("isLoggedIn", "true");
              window.location.href = "table.html";
            } else {
              alert("❌ Login gagal: " + (result.error || result.message));
            }
          } catch (error) {
            console.error("Error:", error);
            alert("❌ Gagal terhubung ke server.");
          }
        }
      });
    }
  })();
});
