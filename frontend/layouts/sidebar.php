<?php
  $current = basename($_SERVER['PHP_SELF']);
?>

<style>
/* BRAND AREA DEFAULT */
.app-brand.demo {
  padding: 0.75rem 1rem;
  margin: 0;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.25s ease;
  justify-content: flex-start;
}

/* Geser logo sedikit ke tengah saat sidebar terbuka */
.app-brand.demo .app-brand-link {
  margin-left: 8px; /* Atur sesuai selera */
}

/* LINK & LOGO DEFAULT */
.app-brand-logo {
  max-width: 110px;
  height: auto;
  transition: all 0.25s ease;
}

/* Saat sidebar collapse */
.layout-menu-collapsed .app-brand.demo {
  justify-content: center;
}
.layout-menu-collapsed .app-brand-link {
  margin-left: 0; /* Hilangkan offset */
}
.layout-menu-collapsed .app-brand-logo {
  max-width: 48px;
}
.layout-menu-collapsed .app-brand-text {
  opacity: 0;
  transform: translateX(-6px);
  pointer-events: none;
  transition: opacity 0.2s ease, transform 0.2s ease;
}
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu">
  <div class="app-brand demo px-3 py-2">
    <a href="#" class="app-brand-link">
      <img
        src="https://wo.naraya.co.id/beta/img/logoq.png"
        alt="Kapten Naratel Logo"
        class="app-brand-logo img-fluid"
      />
    </a>
    <a
      href="javascript:void(0);"
      class="layout-menu-toggle menu-link text-large ms-auto"
    >
      <i class="icon-base bx bx-chevron-left"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <li class="menu-item<?= $current === 'dashboard.php' ? ' active' : '' ?>">
      <a href="dashboard.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-home"></i>
        <div>Dashboard</div>
      </a>
    </li>
    <li class="menu-item<?= $current === 'pendaftar.php' ? ' active' : '' ?>">
      <a href="pendaftar.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-user-check"></i>
        <div>Data Pendaftar</div>
      </a>
    </li>
    <li class="menu-item<?= $current === 'statusLokasi.php' ? ' active' : '' ?>">
      <a href="statusLokasi.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-map"></i>
        <div>Status Lokasi</div>
      </a>
    </li>
    <li class="menu-item<?= $current === 'layananDigunakan.php' ? ' active' : '' ?>">
      <a href="layananDigunakan.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-conversation"></i>
        <div>Layanan</div>
      </a>
    </li>
    <li class="menu-item<?= $current === 'tahuLayanan.php' ? ' active' : '' ?>">
      <a href="tahuLayanan.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-question-mark"></i>
        <div>Tahu Layanan</div>
      </a>
    </li>
    <li class="menu-item<?= $current === 'alasan.php' ? ' active' : '' ?>">
      <a href="alasan.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-comment-dots"></i>
        <div>Alasan</div>
      </a>
    </li>
    <li class="menu-item">
      <a href="../logout.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-power-off"></i>
        <div>Logout</div>
      </a>
    </li>
  </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">k
  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
    <i class="bx bx-menu icon-base"></i>
    <i class="bx bx-chevron-right icon-base"></i>
  </a>
</div>
