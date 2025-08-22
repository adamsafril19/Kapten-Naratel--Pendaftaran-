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

/* Dropdown Menu Styles */
.menu-sub {
  padding-left: 0;
  margin-left: 0;
  overflow: hidden;
  display: none; /* Hidden by default */
}

.menu-item.open .menu-sub {
  display: block; /* Show when parent is open */
}

.menu-sub .menu-item .menu-link {
  padding-left: 3.5rem;
  font-size: 0.9rem;
  color: #697a8d;
  transition: all 0.2s ease;
}

.menu-sub .menu-item.active .menu-link {
  background-color: rgba(105, 108, 255, 0.1);
  color: #696cff;
  font-weight: 500;
  border-right: 2px solid #696cff;
}

.menu-sub .menu-item .menu-link:hover {
  background-color: rgba(105, 108, 255, 0.04);
  color: #696cff;
}

/* Menu toggle arrow */
.menu-toggle {
  position: relative;
}

.menu-toggle::after {
  content: "";
  position: absolute;
  right: 1.5rem;
  top: 50%;
  transform: translateY(-50%) rotate(0deg);
  width: 0;
  height: 0;
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 6px solid #a8b1bb;
  transition: transform 0.3s ease;
}

.menu-item.open .menu-toggle::after {
  transform: translateY(-50%) rotate(180deg);
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
    <!-- Dropdown Menu untuk Manajemen Data -->
    <li class="menu-item<?= in_array($current, ['dashboard_Pendaftaran.php', 'pendaftar.php', 'statusLokasi.php', 'layananDigunakan.php', 'tahuLayanan.php', 'alasan.php', 'aktivitasPembayaran.php']) ? ' active open' : '' ?>">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-collection"></i>
        <div>Manajemen Pendaftar</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item<?= $current === 'dashboard_Pendaftaran.php' ? ' active' : '' ?>">
          <a href="dashboard_Pendaftaran.php" class="menu-link">
            <div>Dashboard</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'pendaftar.php' ? ' active' : '' ?>">
          <a href="pendaftar.php" class="menu-link">
            <div>Data Pendaftar</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'statusLokasi.php' ? ' active' : '' ?>">
          <a href="statusLokasi.php" class="menu-link">
            <div>Status Lokasi</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'layananDigunakan.php' ? ' active' : '' ?>">
          <a href="layananDigunakan.php" class="menu-link">
            <div>Layanan</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'tahuLayanan.php' ? ' active' : '' ?>">
          <a href="tahuLayanan.php" class="menu-link">
            <div>Tahu Layanan</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'alasan.php' ? ' active' : '' ?>">
          <a href="alasan.php" class="menu-link">
            <div>Alasan</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'aktivitasPembayaran.php' ? ' active' : '' ?>">
          <a href="aktivitasPembayaran.php" class="menu-link">
            <div>Aktifitas Pembayaran</div>
          </a>
        </li>
      </ul>
    </li>
    <!-- Dropdown Menu untuk Manajemen Data Pelanggan -->
    <li class="menu-item<?= in_array($current, ['dashboard.php', 'paket.php', 'pelanggan.php', 'unit.php']) ? ' active open' : '' ?>">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base bx bx-group"></i>
        <div>Manajemen Data Pelanggan</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item<?= $current === 'dashboard.php' ? ' active' : '' ?>">
          <a href="dashboard.php" class="menu-link">
            <div>Dashboard</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'paket.php' ? ' active' : '' ?>">
          <a href="paket.php" class="menu-link">
            <div>Paket</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'pelanggan.php' ? ' active' : '' ?>">
          <a href="pelanggan.php" class="menu-link">
            <div>Pelanggan</div>
          </a>
        </li>
        <li class="menu-item<?= $current === 'unit.php' ? ' active' : '' ?>">
          <a href="unit.php" class="menu-link">
            <div>Unit</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item">
      <a href="../logout.php" class="menu-link">
        <i class="menu-icon icon-base bx bx-power-off"></i>
        <div>Logout</div>
      </a>
    </li>
  </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
    <i class="bx bx-menu icon-base"></i>
    <i class="bx bx-chevron-right icon-base"></i>
  </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM loaded, initializing dropdown menu...');
  
  // Toggle dropdown menu
  const menuToggle = document.querySelector('.menu-toggle');
  
  if (menuToggle) {
    console.log('Menu toggle found');
    const menuItem = menuToggle.closest('.menu-item');
    const menuSub = menuItem.querySelector('.menu-sub');
    
    console.log('Menu item:', menuItem);
    console.log('Menu sub:', menuSub);
    
    // Set initial state - open if any submenu is active
    if (menuItem.classList.contains('open')) {
      menuSub.style.display = 'block';
      console.log('Initial state: open');
    } else {
      menuSub.style.display = 'none';
      console.log('Initial state: closed');
    }
    
    menuToggle.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      console.log('Menu toggle clicked');
      console.log('Current open state:', menuItem.classList.contains('open'));
      
      if (menuItem.classList.contains('open')) {
        // Close menu
        menuItem.classList.remove('open');
        menuSub.style.display = 'none';
        console.log('Menu closed');
      } else {
        // Open menu
        menuItem.classList.add('open');
        menuSub.style.display = 'block';
        console.log('Menu opened');
      }
    });
  } else {
    console.log('Menu toggle not found!');
  }
});
</script>
