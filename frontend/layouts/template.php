<!DOCTYPE html>
<html 
  lang="en" 
  class="layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-skin="default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-starter"
  data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Tabel Pendaftaran</title>
  <meta name="description" content="" />
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/vendor/fonts/iconify-icons.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/pickr/pickr-themes.css" />
  <link rel="stylesheet" href="../assets/vendor/css/core.css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/flatpickr/flatpickr.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/@form-validation/form-validation.css" />

  <link rel="stylesheet" href="../assets/vendor/libs/chartjs/chartjs.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/leaflet/leaflet.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
  <style>
    /* Tambahkan custom style di sini jika ada */
  </style>
  <script src="../assets/vendor/js/helpers.js"></script>
  <script src="../assets/vendor/js/template-customizer.js"></script>
  <script src="../assets/js/config.js"></script>
  <script src="../assets/vendor/libs/chartjs/chartjs.js"></script>
  <script src="../assets/vendor/libs/leaflet/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
  <script src="dashboard.js" type="module"></script>
</head>
<body class="<?= $bodyClass ?? '' ?>">
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <?php include __DIR__ . '/sidebar.php'; ?>
      <div class="layout-page">
        <?php include __DIR__ . '/header.html'; ?>

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <?= $content ?? '' ?>
          </div>
          <?php include __DIR__ . '/footer.html'; ?>
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>

    <!-- ELEMEN WAJIB ADA DI LUAR CONTAINER UTAMA -->
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
  </div>

  <!-- SCRIPT DENGAN URUTAN YANG BENAR -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/@algolia/autocomplete-js.js"></script>
  <script src="../assets/vendor/libs/pickr/pickr.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/libs/hammer/hammer.js"></script>
  <script src="../assets/vendor/libs/i18n/i18n.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  <!-- Vendors JS -->
  <script src="../assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <!-- Flat Picker -->
  <script src="../assets/vendor/libs/moment/moment.js"></script>
  <script src="../assets/vendor/libs/flatpickr/flatpickr.js"></script>

  <script src="../assets/js/main.js"></script>


  <!-- Tambahkan script berikut untuk handle menu toggle -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Handle menu toggle
      const menuToggles = document.querySelectorAll('.layout-menu-toggle');
      
      menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
          const menu = document.getElementById('layout-menu');
          const overlay = document.querySelector('.layout-overlay');
          
          if (menu && overlay) {
            menu.classList.toggle('layout-menu-collapsed');
            overlay.classList.toggle('show');
          }
        });
      });

      // Handle overlay click
      const overlay = document.querySelector('.layout-overlay');
      if (overlay) {
        overlay.addEventListener('click', function() {
          const menu = document.getElementById('layout-menu');
          if (menu) {
            menu.classList.remove('layout-menu-collapsed');
            overlay.classList.remove('show');
          }
        });
      }
    });
  </script>
</body>
</html>
