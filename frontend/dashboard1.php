<?php require_once 'require_auth.php'; ?>
<?php ob_start(); ?>
<!-- CSS Custom untuk Dashboard -->
<style>
  .card-metric {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .card-metric:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  .chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 2rem;
  }
  .map-container {
    height: 400px;
    margin-bottom: 2rem;
  }
  #locationMap {
    width: 100%;
    height: 400px;
  }
  .refresh-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1000;
  }
  .section-title {
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.1);
  }
  .horizontal-scroll {
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 1rem;
  }
  .age-histogram {
    height: 350px;
  }
  .loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;
  }
  .loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-12">
      <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Analytics /</span> Dashboard
      </h4>
    </div>
  </div>

  <!-- KPI Cards -->
  <div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card card-metric">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <p class="mb-1 small text-muted">Total Registrations</p>
              <h4 class="mb-0" id="total-registrations">--</h4>
            </div>
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-users ti-sm"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card card-metric">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <p class="mb-1 small text-muted">Today's Registrations</p>
              <h4 class="mb-0" id="today-registrations">--</h4>
            </div>
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-success"><i class="ti ti-calendar-event ti-sm"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card card-metric">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <p class="mb-1 small text-muted">This Week</p>
              <h4 class="mb-0" id="week-registrations">--</h4>
            </div>
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-chart-bar ti-sm"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card card-metric">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <p class="mb-1 small text-muted">This Month</p>
              <h4 class="mb-0" id="month-registrations">--</h4>
            </div>
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-info"><i class="ti ti-chart-pie-2 ti-sm"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Area -->
  <div class="row">
    <!-- Left Column (2/3 width) -->
    <div class="col-xl-8">
      <!-- Trend Time Registration -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 section-title">Registration Trends</h5>
          <button class="btn btn-sm btn-outline-primary refresh-btn" id="refresh-trends">
            <i class="ti ti-refresh"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-outline-primary active" data-period="daily">Daily</button>
              <button type="button" class="btn btn-outline-primary" data-period="weekly">Weekly</button>
              <button type="button" class="btn btn-outline-primary" data-period="monthly">Monthly</button>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="trendChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Status Location -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 section-title">Status Location Distribution</h5>
          <button class="btn btn-sm btn-outline-primary refresh-btn" id="refresh-location">
            <i class="ti ti-refresh"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="chart-container">
                <canvas id="locationChart"></canvas>
              </div>
            </div>
            <div class="col-md-6">
              <div id="locationMap" class="map-container"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column (1/3 width) -->
    <div class="col-xl-4">
      <!-- Cara Tahu Layanan -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 section-title">How Users Found Service</h5>
          <button class="btn btn-sm btn-outline-primary refresh-btn" id="refresh-tahu">
            <i class="ti ti-refresh"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="chart-container">
            <canvas id="tahuChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Layanan yang Digunakan -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 section-title">Services Used</h5>
          <button class="btn btn-sm btn-outline-primary refresh-btn" id="refresh-layanan">
            <i class="ti ti-refresh"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="chart-container">
            <canvas id="layananChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Alasan Memilih Layanan -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 section-title">Reasons for Choosing Service</h5>
          <button class="btn btn-sm btn-outline-primary refresh-btn" id="refresh-alasan">
            <i class="ti ti-refresh"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="chart-container">
            <canvas id="alasanChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Profil Pelanggan -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 section-title">Customer Profile</h5>
          <button class="btn btn-sm btn-outline-primary refresh-btn" id="refresh-profil">
            <i class="ti ti-refresh"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <h6 class="text-center">Location Status</h6>
              <div class="chart-container">
                <canvas id="statusChart"></canvas>
              </div>
            </div>
            <div class="col-md-4">
              <h6 class="text-center">Gender Distribution</h6>
              <div class="chart-container">
                <canvas id="genderChart"></canvas>
              </div>
            </div>
            <div class="col-md-4">
              <h6 class="text-center">Age Distribution</h6>
              <div class="chart-container age-histogram">
                <canvas id="ageChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Load required libraries with correct paths -->

<!-- Import API functions and initialize dashboard -->
<script type="module">
// Import API functions
import { 
  getPendaftaran, 
  getStatusLokasi, 
  getTahuLayanan, 
  getLayananDigunakan, 
  getAlasan 
} from './api.js';

// Global variables
let registrasiData = [];
let statusLokasiData = [];
let tahuLayananData = [];
let layananDigunakanData = [];
let alasanData = [];

// Helper functions
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID');
}

function getToday() {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return today;
}

function isToday(dateString) {
  const date = new Date(dateString);
  const today = getToday();
  return date >= today;
}

function isThisWeek(dateString) {
  const date = new Date(dateString);
  const today = getToday();
  const day = today.getDay();
  const diff = today.getDate() - day + (day === 0 ? -6 : 1);
  const monday = new Date(today.setDate(diff));
  return date >= monday;
}

function isThisMonth(dateString) {
  const date = new Date(dateString);
  const today = getToday();
  const thisMonth = new Date(today.getFullYear(), today.getMonth(), 1);
  return date >= thisMonth;
}

// Calculate KPI metrics
function calculateKPIs() {
  const total = registrasiData.length;
  const today = registrasiData.filter(item => isToday(item.tanggal)).length;
  const week = registrasiData.filter(item => isThisWeek(item.tanggal)).length;
  const month = registrasiData.filter(item => isThisMonth(item.tanggal)).length;

  document.getElementById('total-registrations').textContent = total;
  document.getElementById('today-registrations').textContent = today;
  document.getElementById('week-registrations').textContent = week;
  document.getElementById('month-registrations').textContent = month;
}
function ensureMapElementExists() {
  if (!document.getElementById('locationMap')) {
    const col = document.querySelector('#locationChart').closest('.row').children[1];
    const div = document.createElement('div');
    div.id = 'locationMap';
    div.className = 'map-container';
    div.style.height = '400px';
    col.appendChild(div);
    console.log('↻ #locationMap re‑injected');
  }
}

// Modified chart creation to ensure proper timing
function createAllCharts() {
  // Create other charts first
  try {
    if (document.getElementById('trendChart')) {
      createTrendChart();
    }
  } catch (e) {
    console.error('Error creating trend chart:', e);
  }
  
  try {
    if (document.getElementById('locationChart')) {
      createLocationChart();
    }
  } catch (e) {
    console.error('Error creating location chart:', e);
  }
  
  try {
    if (document.getElementById('tahuChart')) {
      createTahuChart();
    }
  } catch (e) {
    console.error('Error creating tahu chart:', e);
  }
  
  try {
    if (document.getElementById('layananChart')) {
      createLayananChart();
    }
  } catch (e) {
    console.error('Error creating layanan chart:', e);
  }
  
  try {
    if (document.getElementById('alasanChart')) {
      createAlasanChart();
    }
  } catch (e) {
    console.error('Error creating alasan chart:', e);
  }
  
  try {
    if (document.getElementById('statusChart')) {
      createStatusChart();
    }
  } catch (e) {
    console.error('Error creating status chart:', e);
  }
  
  try {
    if (document.getElementById('genderChart')) {
      createGenderChart();
    }
  } catch (e) {
    console.error('Error creating gender chart:', e);
  }
  
  try {
    if (document.getElementById('ageChart')) {
      createAgeChart();
    }
  } catch (e) {
    console.error('Error creating age chart:', e);
  }
  
  // 1) pastikan div-nya ada
  ensureMapElementExists();
  // Create map last with proper timing
  if (document.getElementById('locationMap')) {
    ensureMapContainerReady(createLocationMap);
  }
}
// Create trend chart
function createTrendChart(period = 'daily') {
  const canvas = document.getElementById('trendChart');
  if (!canvas) return;
  
  // Check if Chart.js is loaded
  if (typeof Chart === 'undefined') {
    console.error('Chart.js is not loaded');
    return;
  }
  
  const ctx = canvas.getContext('2d');
  
  let labels = [];
  let data = [];
  const today = new Date();
  
  // Generate data based on period
  for (let i = 29; i >= 0; i--) {
    const date = new Date(today);
    date.setDate(date.getDate() - i);
    labels.push(date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
    
    const count = registrasiData.filter(item => {
      const itemDate = new Date(item.tanggal);
      return itemDate.toDateString() === date.toDateString();
    }).length;
    data.push(count);
  }
  
  // Destroy existing chart
  if (window.trendChart && typeof window.trendChart.destroy === 'function') {
    window.trendChart.destroy();
  }
  
  // Create new chart
  window.trendChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Registrations',
        data: data,
        borderColor: '#3b7ddd',
        backgroundColor: 'rgba(59, 125, 221, 0.1)',
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
}

// Create location chart
function createLocationChart() {
  const canvas = document.getElementById('locationChart');
  if (!canvas) return;
  
  // Check if Chart.js is loaded
  if (typeof Chart === 'undefined') {
    console.error('Chart.js is not loaded');
    return;
  }
  
  const ctx = canvas.getContext('2d');
  
  const counts = {};
  registrasiData.forEach(item => {
    const id = item.status_lokasi_id;
    counts[id] = (counts[id] || 0) + 1;
  });
  
  const labels = [];
  const data = [];
  const backgroundColors = ['#3b7ddd', '#2ca87f', '#e6a024', '#e95f5d', '#8f6ed5'];
  
  statusLokasiData.forEach(item => {
    labels.push(item.nama);
    data.push(counts[item.id] || 0);
  });
  
  if (window.locationChart && typeof window.locationChart.destroy === 'function') {
    window.locationChart.destroy();
  }
  
  window.locationChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Count',
        data: data,
        backgroundColor: backgroundColors.slice(0, labels.length),
        borderColor: 'white',
        borderWidth: 2
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
}

// Improved map creation function
function createLocationMap() {
  const mapContainer = document.getElementById('locationMap');
  if (!mapContainer) {
    console.log('Map container #locationMap not found');
    return;
  }
  
  // Check if Leaflet is loaded
  if (typeof L === 'undefined') {
    console.error('Leaflet is not loaded');
    mapContainer.innerHTML = '<div class="alert alert-warning text-center" style="margin: 20px; padding: 20px;">Leaflet library belum dimuat. Silakan refresh halaman.</div>';
    return;
  }
  
  // Remove existing map if it exists
  if (window.locationMap) {
    try {
      window.locationMap.remove();
      window.locationMap = null;
    } catch (e) {
      console.warn('Error removing existing map:', e);
    }
  }
  
  mapContainer.style.height = '400px';
  
  try {
    // Create map directly without timeout
    const map = L.map(mapContainer, {
      center: [-2.5489, 118.0149],
      zoom: 5,
      zoomControl: true
    });
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 18
    }).addTo(map);
    
    // Add markers
    let markerCount = 0;
    registrasiData.forEach(function(item) {
      if (item.longlat) {
        try {
          const coords = item.longlat.split(',').map(function(coord) { 
            return parseFloat(coord.trim()); 
          });
          
          if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            // Validate coordinate ranges
            if (coords[0] >= -90 && coords[0] <= 90 && coords[1] >= -180 && coords[1] <= 180) {
              L.marker([coords[0], coords[1]]).addTo(map)
                .bindPopup('<b>' + item.nama_lengkap + '</b><br>' + item.alamat + '<br>' + formatDate(item.tanggal));
              markerCount++;
            } else {
              console.warn('Invalid coordinate range:', coords);
            }
          }
        } catch (e) {
          console.warn('Error processing coordinates:', item.longlat, e);
        }
      }
    });
    
    // Store map reference
    window.locationMap = map;
    
    // Force map to resize and invalidate after DOM is ready
    requestAnimationFrame(function() {
      if (map && typeof map.invalidateSize === 'function') {
        map.invalidateSize();
      }
    });
    
    console.log(`Map created successfully with ${markerCount} markers`);
    
  } catch (error) {
    console.error('Error creating map:', error);
    mapContainer.innerHTML = '<div class="alert alert-danger text-center" style="margin: 20px; padding: 20px;">Gagal memuat peta: ' + error.message + '</div>';
  }
}

// Alternative approach: Check if container is ready before creating map
function ensureMapContainerReady(callback) {
  const mapContainer = document.getElementById('locationMap');
  if (!mapContainer) {
    console.error('Map container not found');
    return;
  }
  
  // Check if container is visible and has dimensions
  const rect = mapContainer.getBoundingClientRect();
  if (rect.width === 0 || rect.height === 0) {
    console.log('Map container not visible yet, retrying...');
    setTimeout(() => ensureMapContainerReady(callback), 100);
    return;
  }
  
  callback();
}

function createTahuChart() {
  const canvas = document.getElementById('tahuChart');
  if (!canvas) return;
  
  if (typeof Chart === 'undefined') {
    console.error('Chart.js is not loaded');
    return;
  }
  
  const ctx = canvas.getContext('2d');
  
  const counts = {};
  registrasiData.forEach(item => {
    const id = item.tahu_layanan_id;
    counts[id] = (counts[id] || 0) + 1;
  });
  
  const labels = [];
  const data = [];
  const backgroundColors = ['#3b7ddd', '#2ca87f', '#e6a024', '#e95f5d', '#8f6ed5'];
  
  tahuLayananData.forEach(item => {
    labels.push(item.nama);
    data.push(counts[item.id] || 0);
  });
  
  if (window.tahuChart && typeof window.tahuChart.destroy === 'function') {
    window.tahuChart.destroy();
  }
  
  window.tahuChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: backgroundColors.slice(0, labels.length),
        borderWidth: 2,
        borderColor: 'white'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
}

function createLayananChart() {
  const canvas = document.getElementById('layananChart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  const counts = {};
  registrasiData.forEach(item => {
    const id = item.layanan_digunakan_id;
    counts[id] = (counts[id] || 0) + 1;
  });
  
  const labels = [];
  const data = [];
  const backgroundColors = ['#3b7ddd', '#2ca87f', '#e6a024', '#e95f5d', '#8f6ed5'];
  
  layananDigunakanData.forEach(item => {
    labels.push(item.nama);
    data.push(counts[item.id] || 0);
  });
  
  if (window.layananChart && typeof window.layananChart.destroy === 'function') {
    window.layananChart.destroy();
  }
  
  window.layananChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Count',
        data: data,
        backgroundColor: backgroundColors.slice(0, labels.length),
        borderColor: 'white',
        borderWidth: 2
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
}

function createAlasanChart() {
  const canvas = document.getElementById('alasanChart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  const counts = {};
  registrasiData.forEach(item => {
    const id = item.alasan_id;
    counts[id] = (counts[id] || 0) + 1;
  });
  
  const items = [];
  alasanData.forEach(item => {
    items.push({
      label: item.nama,
      count: counts[item.id] || 0
    });
  });
  
  items.sort(function(a, b) { return b.count - a.count; });
  
  const labels = items.map(function(item) { return item.label; });
  const data = items.map(function(item) { return item.count; });
  const backgroundColors = Array(labels.length).fill('#3b7ddd');
  
  if (window.alasanChart && typeof window.alasanChart.destroy === 'function') {
    window.alasanChart.destroy();
  }
  
  window.alasanChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Count',
        data: data,
        backgroundColor: backgroundColors,
        borderColor: 'white',
        borderWidth: 2
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
}

function createStatusChart() {
  const canvas = document.getElementById('statusChart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  const counts = {};
  registrasiData.forEach(item => {
    const id = item.status_lokasi_id;
    counts[id] = (counts[id] || 0) + 1;
  });
  
  const labels = [];
  const data = [];
  const backgroundColors = ['#3b7ddd', '#2ca87f', '#e6a024', '#e95f5d'];
  
  statusLokasiData.forEach(item => {
    labels.push(item.nama);
    data.push(counts[item.id] || 0);
  });
  
  if (window.statusChart && typeof window.statusChart.destroy === 'function') {
    window.statusChart.destroy();
  }
  
  window.statusChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: backgroundColors.slice(0, labels.length),
        borderWidth: 2,
        borderColor: 'white'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
}

function createGenderChart() {
  const canvas = document.getElementById('genderChart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  let maleCount = 0;
  let femaleCount = 0;
  
  registrasiData.forEach(item => {
    const name = item.nama_lengkap.toLowerCase();
    const firstName = name.split(' ')[0];
    
    // Simple gender detection based on common Indonesian names
    if (firstName.includes('budi') || firstName.includes('andi') || firstName.includes('joko')) {
      maleCount++;
    } else if (firstName.includes('siti') || firstName.includes('dewi') || firstName.includes('ani')) {
      femaleCount++;
    } else {
      // Random assignment for demo
      if (Math.random() > 0.5) {
        maleCount++;
      } else {
        femaleCount++;
      }
    }
  });
  
  const labels = ['Male', 'Female'];
  const data = [maleCount, femaleCount];
  const backgroundColors = ['#3b7ddd', '#e6a024'];
  
  if (window.genderChart && typeof window.genderChart.destroy === 'function') {
    window.genderChart.destroy();
  }
  
  window.genderChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: backgroundColors,
        borderWidth: 2,
        borderColor: 'white'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
}

function createAgeChart() {
  const canvas = document.getElementById('ageChart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  // Simple age categorization for demo
  const ageCategories = {
    '18-25': Math.floor(Math.random() * 20) + 10,
    '26-35': Math.floor(Math.random() * 30) + 15,
    '36-45': Math.floor(Math.random() * 25) + 10,
    '46-55': Math.floor(Math.random() * 15) + 5,
    '56-65': Math.floor(Math.random() * 10) + 3,
    '66+': Math.floor(Math.random() * 5) + 1
  };
  
  const labels = Object.keys(ageCategories);
  const data = Object.values(ageCategories);
  const backgroundColors = ['#3b7ddd', '#2ca87f', '#e6a024', '#e95f5d', '#8f6ed5', '#12c5c5'];
  
  if (window.ageChart && typeof window.ageChart.destroy === 'function') {
    window.ageChart.destroy();
  }
  
  window.ageChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Count',
        data: data,
        backgroundColor: backgroundColors,
        borderColor: 'white',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
}

// Fetch all data
async function fetchData() {
  try {
    console.log('Fetching data...');
    
    // Fetch all data in parallel
    const results = await Promise.all([
      getPendaftaran(),
      getStatusLokasi(),
      getTahuLayanan(),
      getLayananDigunakan(),
      getAlasan()
    ]);
    
    // Assign the fetched data
    registrasiData = results[0];
    statusLokasiData = results[1];
    tahuLayananData = results[2];
    layananDigunakanData = results[3];
    alasanData = results[4];
    
    console.log('Data fetched successfully');
    
    // Update KPIs and create charts
    calculateKPIs();
    createAllCharts();
    
  } catch (error) {
    console.error('Error fetching data:', error);
  }
}

// Modified initialization with better error handling
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM loaded, initializing dashboard...');
  
  // Check if required libraries are loaded
  const checkLibraries = () => {
    const chartLoaded = typeof Chart !== 'undefined';
    const leafletLoaded = typeof L !== 'undefined';
    
    console.log('Chart.js loaded:', chartLoaded);
    console.log('Leaflet loaded:', leafletLoaded);
    
    return { chartLoaded, leafletLoaded };
  };
  
  // Wait for libraries with timeout
  let attempts = 0;
  const maxAttempts = 50; // 5 seconds max wait
  
  const waitForLibraries = () => {
    const { chartLoaded } = checkLibraries();
    attempts++;
    
    if (chartLoaded || attempts >= maxAttempts) {
      if (!chartLoaded) {
        console.warn('Chart.js not loaded after timeout, some charts may not work');
      }
      
      // Initialize dashboard
      fetchData();
      
      // Set up event listeners
      setupEventListeners();
    } else {
      setTimeout(waitForLibraries, 100);
    }
  };
  
  waitForLibraries();
});

// Separate function for event listeners
function setupEventListeners() {
  // Set up period buttons
  const periodButtons = document.querySelectorAll('[data-period]');
  periodButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      periodButtons.forEach(function(btn) {
        btn.classList.remove('active');
      });
      this.classList.add('active');
      
      if (typeof createTrendChart === 'function') {
        createTrendChart(this.dataset.period);
      }
    });
  });
  
  // Set up refresh buttons
  const refreshButtons = document.querySelectorAll('.refresh-btn');
  refreshButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      const icon = this.querySelector('i');
      if (!icon) return;
      
      const originalClass = icon.className;
      icon.className = 'ti ti-loader';
      
      fetchData();
      
      setTimeout(function() {
        icon.className = originalClass;
      }, 2000);
    });
  });
}
</script>

<?php
$content = ob_get_clean();
$page_title = "Dashboard Analytics";
include __DIR__ . '/layouts/template.php';
?>