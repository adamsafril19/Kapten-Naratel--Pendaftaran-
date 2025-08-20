<?php require_once 'require_auth.php'; ?>
<?php
ob_start();
$bodyClass = 'payment-activity-page';
?>

<!-- Modern Styling -->
<style>
.payment-activity-page {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
}

.modern-header {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  margin: 20px;
  padding: 30px;
  border: none;
  backdrop-filter: blur(10px);
  position: relative;
  overflow: hidden;
}

.modern-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
  border-radius: 20px 20px 0 0;
}

.modern-header h4 {
  color: #2d3748;
  font-weight: 700;
  font-size: 1.8rem;
  margin-bottom: 8px;
}

.modern-header .subtitle {
  color: #718096;
  font-size: 0.95rem;
  line-height: 1.5;
}

.modern-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 12px;
  padding: 12px 24px;
  font-weight: 600;
  color: white;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.modern-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  color: white;
}

.modern-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  border: none;
  margin: 20px;
  backdrop-filter: blur(10px);
  overflow: hidden;
}

.stats-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stats-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #667eea, #764ba2);
}

.stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.stats-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  color: white;
  margin-bottom: 15px;
}

.stats-number {
  font-size: 2.2rem;
  font-weight: 700;
  color: #2d3748;
  margin-bottom: 5px;
}

.stats-label {
  color: #718096;
  font-size: 0.9rem;
  font-weight: 500;
}

.filter-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 15px;
  padding: 20px;
  margin: 20px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  backdrop-filter: blur(10px);
}

.modern-form-control {
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 12px 16px;
  transition: all 0.3s ease;
  background: white;
  font-size: 0.95rem;
}

.modern-form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  background: white;
}

.modern-form-label {
  font-weight: 600;
  color: #4a5568;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.modern-table {
  border-radius: 15px;
  overflow: hidden;
}

.modern-table thead th {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  color: white;
  font-weight: 600;
  padding: 18px 15px;
  border: none;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.modern-table tbody tr {
  transition: all 0.3s ease;
  border: none;
  border-left: 3px solid transparent;
}

.modern-table tbody tr:hover {
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
  transform: scale(1.01);
  border-left-color: #667eea;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.modern-table tbody td {
  padding: 15px;
  border: none;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  vertical-align: middle;
}

.unit-badge {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.request-count {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
  padding: 10px 20px;
  border-radius: 25px;
  font-size: 1.1rem;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.loading-spinner {
  color: #667eea;
  font-size: 1.1rem;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

.chart-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 20px;
  padding: 30px;
  margin: 20px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(10px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .modern-header {
    margin: 10px;
    padding: 20px;
  }
  
  .modern-header h4 {
    font-size: 1.4rem;
  }
  
  .modern-card, .filter-card, .chart-card {
    margin: 10px;
  }
  
  .stats-card {
    margin-bottom: 15px;
  }
  
  .stats-number {
    font-size: 1.8rem;
  }
}
</style>

<!-- Modern Header Design -->
<div class="modern-header fade-in-up">
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    <div class="mb-2 mb-md-0">
      <h4 class="mb-2">
        <i class="bx bx-bar-chart me-2" style="color: #667eea;"></i>
        Aktivitas Pembayaran
      </h4>
      <p class="subtitle mb-0">
        <i class="bx bx-info-circle me-1"></i>
        Monitor aktivitas pembayaran dan request berdasarkan unit kerja dan periode waktu.
      </p>
    </div>
    <div>
      <button id="exportBtn" class="modern-btn">
        <i class="bx bx-download me-2"></i>
        <span>Export Data</span>
      </button>
    </div>
  </div>
</div>

<!-- Filter Card -->
<div class="filter-card fade-in-up">
  <div class="row align-items-end">
    <div class="col-md-3 mb-3">
      <label class="modern-form-label">
        <i class="bx bx-calendar me-1"></i>Bulan
      </label>
      <select class="form-select modern-form-control" id="bulanFilter">
        <option value="1">Januari</option>
        <option value="2">Februari</option>
        <option value="3">Maret</option>
        <option value="4">April</option>
        <option value="5">Mei</option>
        <option value="6">Juni</option>
        <option value="7">Juli</option>
        <option value="8" selected>Agustus</option>
        <option value="9">September</option>
        <option value="10">Oktober</option>
        <option value="11">November</option>
        <option value="12">Desember</option>
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <label class="modern-form-label">
        <i class="bx bx-calendar-alt me-1"></i>Tahun
      </label>
      <select class="form-select modern-form-control" id="tahunFilter">
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025" selected>2025</option>
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <label class="modern-form-label">
        <i class="bx bx-building me-1"></i>Unit Kerja
      </label>
      <select class="form-select modern-form-control" id="unitFilter">
        <option value="">Semua Unit</option>
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <button id="filterBtn" class="modern-btn w-100">
        <i class="bx bx-search me-2"></i>
        Filter Data
      </button>
    </div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="container-fluid">
  <div class="row" id="statsContainer">
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card fade-in-up">
        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <i class="bx bx-calendar"></i>
        </div>
        <div class="stats-number" id="totalDays">-</div>
        <div class="stats-label">Hari Aktif</div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card fade-in-up">
        <div class="stats-icon" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
          <i class="bx bx-trending-up"></i>
        </div>
        <div class="stats-number" id="totalRequests">-</div>
        <div class="stats-label">Total Request</div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card fade-in-up">
        <div class="stats-icon" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);">
          <i class="bx bx-building"></i>
        </div>
        <div class="stats-number" id="totalUnits">-</div>
        <div class="stats-label">Unit Aktif</div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stats-card fade-in-up">
        <div class="stats-icon" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);">
          <i class="bx bx-bar-chart-alt-2"></i>
        </div>
        <div class="stats-number" id="avgRequests">-</div>
        <div class="stats-label">Rata-rata/Hari</div>
      </div>
    </div>
  </div>
</div>

<!-- Data Table -->
<div class="modern-card fade-in-up">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="paymentTable" class="table modern-table mb-0">
        <thead>
          <tr>
            <th class="text-center">
              <i class="bx bx-hash me-1"></i>No
            </th>
            <th>
              <i class="bx bx-calendar me-1"></i>Tanggal
            </th>
            <th>
              <i class="bx bx-building me-1"></i>Unit Kerja
            </th>
            <th class="text-center">
              <i class="bx bx-trending-up me-1"></i>Total Request
            </th>
            <th class="text-center">
              <i class="bx bx-time me-1"></i>Status
            </th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr>
            <td colspan="5" class="text-center py-5">
              <div class="loading-spinner">
                <i class="bx bx-loader-alt bx-spin me-2"></i>
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
  const bulanFilter = document.getElementById('bulanFilter');
  const tahunFilter = document.getElementById('tahunFilter');
  const unitFilter = document.getElementById('unitFilter');
  const filterBtn = document.getElementById('filterBtn');
  const exportBtn = document.getElementById('exportBtn');
  
  let currentData = [];
  let allUnits = new Set();

  // Nama bulan untuk display
  const namaBuilan = [
    '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  // Load data
  async function loadPaymentData() {
    try {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center py-5">
            <div class="loading-spinner">
              <i class="bx bx-loader-alt bx-spin me-2"></i>
              <span>Memuat data...</span>
            </div>
          </td>
        </tr>
      `;

      const bulan = parseInt(bulanFilter.value);
      const tahun = parseInt(tahunFilter.value);
      const selectedUnit = unitFilter.value;

      const response = await getPaymentActivity(bulan, tahun);
      
      if (response.status !== 'success') {
        throw new Error(response.message || 'API returned error status');
      }

      currentData = response.data || [];
      
      // Show fetch timestamp if available (from proxy)
      if (response.fetched_at) {
        console.log('Data fetched at:', response.fetched_at);
      }
      
      // Update units for filter
      updateUnitsFilter();
      
      // Filter data if unit selected
      let filteredData = currentData;
      if (selectedUnit) {
        filteredData = currentData.filter(item => item.unit === selectedUnit);
      }
      
      // Update statistics
      updateStatistics(filteredData);
      
      // Update table
      updateTable(filteredData);
      
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

  // Update units filter
  function updateUnitsFilter() {
    allUnits.clear();
    currentData.forEach(item => allUnits.add(item.unit));
    
    const currentSelection = unitFilter.value;
    unitFilter.innerHTML = '<option value="">Semua Unit</option>';
    
    Array.from(allUnits).sort().forEach(unit => {
      const option = document.createElement('option');
      option.value = unit;
      option.textContent = unit;
      if (unit === currentSelection) option.selected = true;
      unitFilter.appendChild(option);
    });
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
              <small>Coba ubah filter periode atau unit kerja</small>
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
      row.className = 'table-row-hover';
      row.innerHTML = `
        <td class="text-center fw-bold">${index + 1}</td>
        <td>
          <div>
            <i class="bx bx-calendar text-muted me-1"></i>
            <span class="fw-semibold">${formattedDate}</span>
          </div>
          <small class="text-muted">${dayOfWeek}</small>
        </td>
        <td>
          <div class="unit-badge">
            <i class="bx bx-building"></i>
            ${item.unit}
          </div>
        </td>
        <td class="text-center">
          <div class="request-count">
            <i class="bx bx-trending-up"></i>
            ${requestCount.toLocaleString('id-ID')}
          </div>
        </td>
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
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
      }
    });
  }

  // Export functionality
  function exportData() {
    if (currentData.length === 0) {
      showNotification('Tidak ada data untuk diekspor', 'error');
      return;
    }

    const bulan = namaBuilan[parseInt(bulanFilter.value)];
    const tahun = tahunFilter.value;
    const unit = unitFilter.value || 'Semua Unit';
    
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "No,Tanggal,Unit Kerja,Total Request\n";
    
    const dataToExport = unitFilter.value ? 
      currentData.filter(item => item.unit === unitFilter.value) : 
      currentData;
    
    dataToExport.forEach((item, index) => {
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
    const existingNotifications = document.querySelectorAll('.modern-notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `modern-notification alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      min-width: 300px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      backdrop-filter: blur(10px);
      animation: slideInRight 0.3s ease-out;
    `;
    
    const icon = type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-error-circle' : 'bx-info-circle';
    
    notification.innerHTML = `
      <div class="d-flex align-items-center">
        <i class="bx ${icon} me-2" style="font-size: 1.2rem;"></i>
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
  filterBtn.addEventListener('click', loadPaymentData);
  exportBtn.addEventListener('click', exportData);

  // Auto-load on filter change
  bulanFilter.addEventListener('change', loadPaymentData);
  tahunFilter.addEventListener('change', loadPaymentData);
  unitFilter.addEventListener('change', () => {
    const bulan = parseInt(bulanFilter.value);
    const tahun = parseInt(tahunFilter.value);
    const selectedUnit = unitFilter.value;
    
    let filteredData = currentData;
    if (selectedUnit) {
      filteredData = currentData.filter(item => item.unit === selectedUnit);
    }
    
    updateStatistics(filteredData);
    updateTable(filteredData);
  });

  // Add keyframe animation for notifications
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    
    .table-row-hover {
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
    }

    .table-row-hover:hover {
      border-left-color: #667eea;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
  `;
  document.head.appendChild(style);

  // Initial load
  loadPaymentData();
</script>

<?php
$content = ob_get_clean();
$title = "Aktivitas Pembayaran";
include 'layouts/template.php';
?>
