<?php require_once 'require_auth.php'; ?>
<?php
ob_start(); // mulai tangkap output
?>
<style>
    /* Custom Date Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid #f1f1f1;
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #f1f1f1;
        padding: 1rem 1.5rem;
    }
    
    .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25);
    }
    
    .btn-primary {
        background-color: #696cff;
        border-color: #696cff;
    }
    
    .btn-primary:hover {
        background-color: #5f61e6;
        border-color: #5f61e6;
    }
</style>
<!-- Header Halaman -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="fw-bold mb-1">Dashboard Analitik</h4>
                                        <p class="text-muted mb-0">Wawasan data pendaftaran secara real-time</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <select class="form-select form-select-sm" id="periodSelect" style="width: 200px;">
                                            <option value="week">Data Minggu Ini</option>
                                            <option value="month" selected>Data Bulan Ini</option>
                                            <option value="year">Data Tahun Ini</option>
                                            <option value="all">Semua Data</option>
                                            <option value="custom">Filter Kustom...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu KPI -->
                        <div class="row g-4 mb-4" id="kpiCards">
                            <!-- Loading skeleton -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card h-100 skeleton-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="content-placeholder">
                                                <div class="placeholder-glow">
                                                    <span class="placeholder col-6"></span>
                                                </div>
                                                <div class="placeholder-glow mt-2">
                                                    <span class="placeholder col-4"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card h-100 skeleton-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="content-placeholder">
                                                <div class="placeholder-glow">
                                                    <span class="placeholder col-6"></span>
                                                </div>
                                                <div class="placeholder-glow mt-2">
                                                    <span class="placeholder col-4"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card h-100 skeleton-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="content-placeholder">
                                                <div class="placeholder-glow">
                                                    <span class="placeholder col-6"></span>
                                                </div>
                                                <div class="placeholder-glow mt-2">
                                                    <span class="placeholder col-4"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card h-100 skeleton-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="content-placeholder">
                                                <div class="placeholder-glow">
                                                    <span class="placeholder col-6"></span>
                                                </div>
                                                <div class="placeholder-glow mt-2">
                                                    <span class="placeholder col-4"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Grafik -->
                        <div class="row g-4 mb-4">
                            <!-- Grafik Tren -->
                            <div class="col-lg-8">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Tren Pendaftaran</h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-download"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="downloadChart('trendChart', 'png')">Unduh PNG</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="downloadChart('trendChart', 'pdf')">Unduh PDF</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="trendChartContainer" style="height: 350px;">
                                            <div class="skeleton-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Distribusi Lokasi -->
                            <div class="col-lg-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">Distribusi Lokasi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="locationChartContainer" style="height: 350px;">
                                            <div class="skeleton-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris Grafik Kedua -->
                        <div class="row g-4 mb-4">
                            <!-- Penggunaan Layanan -->
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">Penggunaan Layanan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="serviceChartContainer" style="height: 300px;">
                                            <div class="skeleton-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cara Mengetahui -->
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">Cara Mengetahui Kami</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="tahuChartContainer" style="height: 300px;">
                                            <div class="skeleton-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Batang Analitik -->
                        <div class="row g-4 mb-4">
                            <!-- Grafik Batang Distribusi Lokasi -->
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Distribusi Lokasi</h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-download"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="downloadChart('locationBarChart', 'png')">Unduh PNG</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="downloadChart('locationBarChart', 'pdf')">Unduh PDF</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="locationBarChartContainer" style="height: 350px;">
                                            <div class="skeleton-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Grafik Batang Sumber Informasi -->
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Tren Sumber Informasi</h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-download"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="downloadChart('sourceBarChart', 'png')">Unduh PNG</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="downloadChart('sourceBarChart', 'pdf')">Unduh PDF</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="sourceBarChartContainer" style="height: 350px;">
                                            <div class="skeleton-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Peta -->
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Peta Lokasi</h5>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary" onclick="centerMap()">
                                                <i class="bx bx-target-lock me-1"></i>Pusatkan Peta
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleHeatmap()">
                                                <i class="bx bx-layer me-1"></i>Toggle Heatmap
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="locationMap" style="height: 400px; border-radius: 0 0 0.375rem 0.375rem;">
                                            <div class="skeleton-map"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Aktivitas Terbaru -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Pendaftaran Terbaru</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="recentRegistrations">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Lokasi</th>
                                                        <th>Layanan</th>
                                                        <th>Tanggal</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="recentTableBody">
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">
                                                            <div class="spinner-border text-primary" role="status">
                                                                <span class="visually-hidden">Memuat...</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Konten -->
<?php
$content = ob_get_clean();
$title   = "Dashboard Diperbarui";
include __DIR__ . '/layouts/template.php';
?>
