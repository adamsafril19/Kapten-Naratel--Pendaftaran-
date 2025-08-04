// assets/js/auth-guard.js

(function () {
  const isLoggedIn = localStorage.getItem('isLoggedIn');
  const token = localStorage.getItem('token');

  const protectedContent = document.getElementById('protectedContent');

  // Fungsi redirect jika tidak login
  const redirectToLogin = () => {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('token');
    window.location.href = 'login.html';
  };

  // Jika tidak login
  if (isLoggedIn !== "true" || !token) {
    redirectToLogin();
  } else {
    // Validasi lolos, tampilkan isi halaman
    if (protectedContent) {
      protectedContent.style.display = 'block';
    }
  }

  // Tangani back navigation dari cache browser
  window.addEventListener("pageshow", function (event) {
    const fromCache = event.persisted || performance.getEntriesByType("navigation")[0]?.type === "back_forward";
    if (fromCache && (localStorage.getItem('isLoggedIn') !== "true")) {
      redirectToLogin();
    }
  });
})();
