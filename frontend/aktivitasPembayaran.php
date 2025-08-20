<?php require_once 'require_auth.php'; ?>
<?php
ob_start();
$bodyClass = 'payment-activity-page';
?>

<!-- Header dan Subjudul -->
<div class="d-flex justify-content-between align-items-center px-3 pt-4 pb-2 border-bottom">
  <div>
    <h4 class="fw-bold mb-0">
      <i class="bx bx-bar-chart text-primary me-1"></i>
      Aktivitas Pembayaran
    </h4>
    <small class="text-muted">
      Monitor aktivitas pembayaran dan request berdasarkan unit kerja dan periode waktu.
    </small>
  </div>
  <div>
    <button id="exportBtn" class="btn btn-primary shadow-primary">
      <i class="bx bx-download me-1"></i>Export Data
    </button>
  </div>
</div>

<!-- Statistics Cards -->
<div class="container-fluid mt-3">
  <div class="row" id="statsContainer">
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar bg-primary me-3">
              <i class="bx bx-calendar"></i>
            </div>
            <div>
              <h5 class="mb-0" id="totalDays">-</h5>
              <small class="text-muted">Hari Aktif</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar bg-success me-3">
              <i class="bx bx-trending-up"></i>
            </div>
            <div>
              <h5 class="mb-0" id="totalRequests">-</h5>
              <small class="text-muted">Total Request</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar bg-warning me-3">
              <i class="bx bx-building"></i>
            </div>
            <div>
              <h5 class="mb-0" id="totalUnits">-</h5>
              <small class="text-muted">Unit Aktif</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="avatar bg-info me-3">
              <i class="bx bx-bar-chart-alt-2"></i>
            </div>
            <div>
              <h5 class="mb-0" id="avgRequests">-</h5>
              <small class="text-muted">Rata-rata/Hari</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Data Table -->
<div class="card mt-3 shadow-sm border">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="paymentTable" class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th class="text-center">No</th>
            <th>Tanggal</th>
            <th>Unit Kerja</th>
            <th class="text-center">Total Request</th>
            <th class="text-center">Status</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr>
            <td colspan="5" class="text-center py-5">
              <div class="text-muted">
                <i class="bx bx-loader-alt bx-sp极 me-2"></i>
                <span>Memuat data...</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="module">
  import { getPaymentActivity } from '/api.js';

  const tableBody = document.getElementById('table-body');
  const exportBtn = document.getElementById('exportBtn');
  
  let currentData = [];

  // Load data
  async function loadPaymentData() {
    try {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center py-5">
            <div class="text-muted">
              <i class="bx bx-loader-alt bx-spin me-2"></i>
              <span>Memuat data...</span>
            </div>
          </td>
        </tr>
      `;

      // Dapatkan bulan dan tahun saat ini
      const now = new Date();
      const bulan = now.getMonth() + 1; // getMonth() returns 0-11
      const tahun = now.getFullYear();

      const response = await getPaymentActivity(bulan, tahun);
      
      if (response.status !== 'success') {
        throw new Error(response.message || 'API returned error status');
      }

      currentData = response.data || [];
      
      // Update statistics
      updateStatistics(currentData);
      
      // Update table
      updateTable(currentData);
      
    } catch (error) {
      console.error('Error loading payment data:', error);
      
      let errorMessage = 'Gagal memuat data aktivitas pembayaran';
      
      // Provide more specific error messages
      if (error.message.includes('Failed to fetch')) {
        errorMessage = 'Tidak dapat terhubung ke server. Pastikan backend server berjalan.';
      } else if (error.message.includes('404')) {
        errorMessage = 'Endpoint tidak ditemukan. Periksa konfigurasi server.';
      } else if (error.message.includes('500')) {
        errorMessage = 'Terjadi kesalahan pada server. Periksa log server.';
      } else if (error.message.includes('CORS')) {
        errorMessage = 'Masalah CORS. Gunakan proxy server untuk mengatasi masalah ini.';
      } else if (error.message) {
        errorMessage += ': ' + error.message;
      }
      
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center text-danger py-5">
            <div>
              <i class="bx bx-error-circle" style="font-size: 3rem; opacity: 0.5;"></i>
              <p class="mt-2 mb-2">${errorMessage}</p>
              <button class="btn btn-outline-primary btn-sm" onclick="loadPaymentData()">
                <i class="bx bx-refresh me-1"></i>Coba Lagi
              </button>
            </div>
          </td>
        </tr>
      `;
      showNotification(errorMessage, 'error');
    }
  }

  // Update statistics
  function updateStatistics(data) {
    const totalDaysSet = new Set();
    const totalUnitsSet = new Set();
    let totalRequests = 0;

    data.forEach(item => {
      totalDaysSet.add(item.tanggal);
      totalUnitsSet.add(item.unit);
      totalRequests += parseInt(item.total_request || 0);
    });

    const totalDays = totalDaysSet.size;
    const totalUnits = totalUnitsSet.size;
    const avgRequests = totalDays > 0 ? Math.round(totalRequests / totalDays) : 0;

    document.getElementById('totalDays').textContent = totalDays;
    document.getElementById('totalRequests').textContent = totalRequests.toLocaleString('id-ID');
    document.getElementById('totalUnits').textContent = totalUnits;
    document.getElementById('avgRequests').textContent = avgRequests.toLocaleString('id-ID');
  }

  // Update table
  function updateTable(data) {
    if (data.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center py-5">
            <div class="text-muted">
              <i class="bx bx-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
              <p class="mt-2 mb-0">Tidak ada data aktivitas pembayaran</p>
              <small>Periksa koneksi server atau coba lagi nanti</small>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    tableBody.innerHTML = '';
    
    data.forEach((item, index) => {
      const date = new Date(item.tanggal);
      const formattedDate = date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });

      const dayOfWeek = date.toLocaleDateString('id-ID', { weekday: 'long' });
      const requestCount = parseInt(item.total_request || 0);
      
      // Status berdasarkan jumlah request
      let statusBadge = '';
      let statusClass = '';
      
      if (requestCount >= 20) {
        statusBadge = 'Tinggi';
        statusClass = 'bg-success';
      } else if (requestCount >= 10) {
        statusBadge = 'Sedang';
        statusClass = 'bg-warning';
      } else if (requestCount > 0) {
        statusBadge = 'Rendah';
        statusClass = 'bg-info';
      } else {
        statusBadge = 'Tidak Ada';
        statusClass = 'bg-secondary';
      }

      const row = document.createElement('tr');
      row.innerHTML = `
        <td class="text-center">${index + 1}</td>
        <td>
          <div>${formattedDate}</div>
          <small class="text-muted">${dayOfWeek}</small>
        </td>
        <td>${item.unit}</td>
        <td class="text-center">${requestCount.toLocaleString('id-ID')}</td>
        <td class="text-center">
          <span class="badge ${statusClass}">${statusBadge}</span>
        </td>
      `;
      
      tableBody.appendChild(row);
    });

    // Initialize DataTable if not already initialized
    if ($.fn.DataTable.isDataTable('#paymentTable')) {
      $('#paymentTable').DataTable().destroy();
    }
    
    $('#paymentTable').DataTable({
      order: [[1, 'desc']], // Sort by date descending
      pageLength: 25,
    });
  }

  // Export functionality
  function exportData() {
    if (currentData.length === 0) {
      showNotification('Tidak ada data untuk diekspor', 'error');
      return;
    }

    const now = new Date();
    const bulan = now.toLocaleString('id-ID', { month: 'long' });
    const tahun = now.getFullYear();
    const unit = 'Semua Unit';
    
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "No,Tanggal,Unit Kerja,Total Request\n";
    
    currentData.forEach((item, index) => {
      const date = new Date(item.tanggal);
      const formattedDate = date.toLocaleDateString('id-ID');
      csvContent += `${index + 1},"${formattedDate}","${item.unit}",${item.total_request}\n`;
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `aktivitas_pembayaran_${bulan}_${tahun}_${unit.replace(/\s+/g, '_')}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('Data berhasil diekspor!', 'success');
  }

  // Notification function
  function showNotification(message, type = 'info') {
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `notification alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      min-width: 300px;
    `;
    
    const icon = type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-error-circle' : 'bx-info-circle';
    
    notification.innerHTML = `
      <div class="d-flex align-items-center">
        <i class="bx ${icon} me-2"></i>
        <span>${message}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
      </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
      if (notification.parentNode) {
        notification.classList.add('fade');
        setTimeout(() => notification.remove(), 300);
      }
    }, 5000);
  }

  // Event listeners
  exportBtn.addEventListener('click', exportData);

  // Initial load
  loadPaymentData();
</script>

<?php
$content = ob_get_clean();
$title = "Aktivitas Pembayaran";
include 'layouts/template.php';
?>
