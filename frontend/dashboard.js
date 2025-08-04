import { getPendaftaran, getStatusLokasi, getLayananDigunakan, getTahuLayanan } from './api.js';

/**
 * Enhanced Dashboard JavaScript
 * Modern data loading, charts, and interactive features
 */

class DashboardEnhanced {
    constructor() {
        this.charts = {};
        this.map = null;
        this.markers = [];
        this.heatmapLayer = null;
        this.refreshInterval = null;
        this.isLoading = false;
    }

    static init() {
        const dashboard = new DashboardEnhanced();
        window.DashboardEnhanced = dashboard;
        return dashboard;
    }

    async init() {
        this.setupEventListeners();
        await this.loadAllData();
    }

    setupEventListeners() {
        // Period selector
        const periodSelect = document.getElementById('periodSelect');
        if (periodSelect) {
            periodSelect.addEventListener('change', (e) => {
                this.loadAllData(parseInt(e.target.value));
            });
        }

        // Theme change listener
        document.addEventListener('themeChanged', () => {
            this.updateChartThemes();
        });

        // Resize handler
        window.addEventListener('resize', this.debounce(() => {
            this.resizeCharts();
        }, 250));
    }

    async loadAllData(days = 30) {
        this.showLoading();
        
        try {
            const [
                kpiData,
                trendData,
                locationData,
                serviceData,
                tahuData,
                recentData,
                mapData
            ] = await Promise.all([
                this.loadKPIData(days),
                this.loadTrendData(days),
                this.loadLocationData(),
                this.loadServiceData(),
                this.loadTahuData(),
                this.loadRecentData(days),
                this.loadMapData()
            ]);

            this.updateKPICards(kpiData);
            this.updateTrendChart(trendData);
            this.updateLocationChart(locationData);
            this.updateServiceChart(serviceData);
            this.updateTahuChart(tahuData);
            this.updateRecentTable(recentData);
            this.updateMap(mapData);

        } catch (error) {
            console.error('Error loading dashboard data:', error);
            this.showError('Failed to load dashboard data. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    async loadKPIData(days) {
        const registrations = await getPendaftaran();
        const allData = registrations.data || registrations;

        // Hari ini pada jam 00:00
        const startOfToday = new Date();
        startOfToday.setHours(0, 0, 0, 0);

        // Jumlah pendaftaran hari ini
        const todayCount = allData.filter(item => {
            const itemDate = new Date(item.tanggal);
            return itemDate >= startOfToday;
        }).length;

        // Jumlah 7 hari terakhir
        const weekCutoff = new Date();
        weekCutoff.setDate(weekCutoff.getDate() - 7);
        const weekly = allData.filter(item => new Date(item.tanggal) >= weekCutoff).length;

        // Jumlah 30 hari terakhir
        const monthCutoff = new Date();
        monthCutoff.setMonth(monthCutoff.getMonth() - 1);
        const monthly = allData.filter(item => new Date(item.tanggal) >= monthCutoff).length;

        return {
            total: allData.length,
            daily: todayCount,
            weekly,
            monthly
        };
    }


    async loadTrendData(days) {
        const registrations = await getPendaftaran();
        const allData = registrations.data || registrations;
        
        // Group by date for trend data
        const dateGroups = {};
        allData.forEach(item => {
            const date = new Date(item.created_at || item.tanggal || item.date);
            const dateKey = date.toISOString().split('T')[0];
            dateGroups[dateKey] = (dateGroups[dateKey] || 0) + 1;
        });
        
        // Get last N days
        const trendData = [];
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            const dateKey = date.toISOString().split('T')[0];
            trendData.push({
                date: dateKey,
                count: dateGroups[dateKey] || 0
            });
        }
        
        return trendData;
    }

    async loadLocationData() {
        const regs = await getPendaftaran();
        const all = regs.data || regs;
        // Group by nama status_lokasi
        const groups = all.reduce((acc, cur) => {
            const name = cur.status_lokasi?.nama || 'Unknown';
            acc[name] = (acc[name] || 0) + 1;
            return acc;
        }, {});
        return Object.entries(groups).map(([lokasi, total]) => ({ lokasi, total }));
    }

    async loadServiceData() {
        const regs = (await getPendaftaran()).data || (await getPendaftaran());
        // hitung jumlah per layanan_digunakan.nama
        const counts = regs.reduce((acc, cur) => {
            const name = cur.layanan_digunakan?.nama || 'Unknown';
            acc[name] = (acc[name] || 0) + 1;
            return acc;
        }, {});
        // kembalikan array dengan properti sesuai updateServiceChart
        return Object.entries(counts)
            .map(([layanan, total]) => ({ layanan, total }));
    }

    async loadTahuData() {
        const regs = (await getPendaftaran()).data || (await getPendaftaran());
        const counts = regs.reduce((acc, cur) => {
            const name = cur.tahu_layanan?.nama || 'Unknown';
            acc[name] = (acc[name] || 0) + 1;
            return acc;
        }, {});
        return Object.entries(counts)
            .map(([tahu, total]) => ({ tahu, total }));
    }

    async loadRecentData(days) {
        const registrations = await getPendaftaran();
        const allData = registrations.data || registrations;
        
        // Sort by date and get recent entries
        const sortedData = allData
            .sort((a, b) => {
                const dateA = new Date(a.created_at || a.tanggal || a.date);
                const dateB = new Date(b.created_at || b.tanggal || b.date);
                return dateB - dateA;
            })
            .slice(0, 10);
            
        return sortedData;
    }

    _parseLonglat(str) {
        if (typeof str !== 'string') return null;
        const [lat, lng] = str.split(',').map(s => parseFloat(s.trim()));
        if (isNaN(lat) || isNaN(lng)) return null;
        return { latitude: lat, longitude: lng };
    }

    async loadMapData() {
        const regs = (await getPendaftaran()).data || (await getPendaftaran());
        const mapItems = [];

        regs.forEach(item => {
            const coords = this._parseLonglat(item.longlat);
            if (!coords) return;
            mapItems.push({
            latitude:  coords.latitude,
            longitude: coords.longitude,
            // Bawa juga field yang diperlukan di popup
            nama_lengkap:       item.nama_lengkap,
            layanan_nama:       item.layanan_digunakan?.nama,
            tanggal:            item.tanggal,
            tahu_layanan_nama:  item.tahu_layanan?.nama
            });
            // dan juga simpan untuk heatmap:
            mapItems.heatmapPoints = mapItems.heatmapPoints || [];
            mapItems.heatmapPoints.push([coords.latitude, coords.longitude, 1]);
        });

        // simpan heatmapPoints di instance
        this.heatmapPoints = mapItems.heatmapPoints;

        return mapItems;
    }


    updateKPICards(data) {
        const container = document.getElementById('kpiCards');
        if (!container) return;

        const cards = [
            {
                title: 'Total Pendaftar',
                value: this.formatNumber(data.total),
                icon: 'bx-user',
                color: 'primary'
            },
            {
                title: 'Pendaftar Hari Ini',
                value: this.formatNumber(data.daily),
                icon: 'bx-calendar',
                color: 'info'
            },
            {
                title: 'Pendaftar Minggu Ini',
                value: this.formatNumber(data.weekly),
                icon: 'bx-calendar-week',
                color: 'success'
            },
            {
                title: 'Pendaftar Bulan Ini',
                value: this.formatNumber(data.monthly),
                icon: 'bx-calendar-month',
                color: 'warning'
            }
        ];

        container.innerHTML = cards.map(card => `
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card border-start border-start-${card.color} border-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="kpi-label">${card.title}</div>
                                <div class="kpi-value text-${card.color}">${card.value}</div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-${card.color}">
                                    <i class="bx ${card.icon}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        // Add fade-in animation
        container.querySelectorAll('.card').forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('fade-in');
            }, index * 100);
        });
    }

    updateTrendChart(data) {
        const ctx = document.createElement('canvas');
        const container = document.getElementById('trendChartContainer');
        
        if (!container) return;
        
        container.innerHTML = '';
        container.appendChild(ctx);

        if (this.charts.trend) {
            this.charts.trend.destroy();
        }

        const labels = data.map(item => this.formatDate(item.date));
        const values = data.map(item => item.count);

        this.charts.trend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registrations',
                    data: values,
                    borderColor: '#696cff',
                    backgroundColor: 'rgba(105, 108, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#696cff',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#696cff',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    updateLocationChart(data) {
        const ctx = document.createElement('canvas');
        const container = document.getElementById('locationChartContainer');
        
        if (!container) return;
        
        container.innerHTML = '';
        container.appendChild(ctx);

        if (this.charts.location) {
            this.charts.location.destroy();
        }

        const labels = data.map(item => item.lokasi);
        const values = data.map(item => item.total);

        this.charts.location = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#696cff',
                        '#03c3ec',
                        '#71dd37',
                        '#ffab00',
                        '#ff3e1d',
                        '#8592a3'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    updateServiceChart(data) {
        const ctx = document.createElement('canvas');
        const container = document.getElementById('serviceChartContainer');
        
        if (!container) return;
        
        container.innerHTML = '';
        container.appendChild(ctx);

        if (this.charts.service) {
            this.charts.service.destroy();
        }

        const labels = data.map(item => item.layanan);
        const values = data.map(item => item.total);

        this.charts.service = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Usage',
                    data: values,
                    backgroundColor: 'rgba(105, 108, 255, 0.8)',
                    borderColor: '#696cff',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }

    updateTahuChart(data) {
        const ctx = document.createElement('canvas');
        const container = document.getElementById('tahuChartContainer');
        
        if (!container) return;
        
        container.innerHTML = '';
        container.appendChild(ctx);

        if (this.charts.tahu) {
            this.charts.tahu.destroy();
        }

        const labels = data.map(item => item.tahu);
        const values = data.map(item => item.total);

        this.charts.tahu = new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        'rgba(105, 108, 255, 0.8)',
                        'rgba(3, 195, 236, 0.8)',
                        'rgba(113, 221, 55, 0.8)',
                        'rgba(255, 171, 0, 0.8)',
                        'rgba(255, 62, 29, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }

    updateMap(data) {
        const container = document.getElementById('locationMap');
        if (!container) return;
        
        if (!this.map) {
            this.map = L.map('locationMap').setView([-6.2088, 106.8456], 11);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);
        }

        // Clear existing markers
        this.markers.forEach(marker => this.map.removeLayer(marker));
        this.markers = [];

        // Add new markers
        data.forEach(item => {
            if (item.latitude && item.longitude) {
                const marker = L.marker([item.latitude, item.longitude])
                    .addTo(this.map)
                    .bindPopup(`
                        <div class="popup-content" style="font-size:0.9rem; line-height:1.2;">
                            <strong>${item.nama_lengkap}</strong><br/>
                            Service: ${item.layanan_nama || '–'}<br/>
                            Date: ${this.formatDate(item.tanggal)}<br/>
                            Source: ${item.tahu_layanan_nama || '–'}
                        </div>
                    `);
                
                this.markers.push(marker);
            }
        });

        // Fit map to markers
        if (this.markers.length > 0) {
            const group = new L.featureGroup(this.markers);
            this.map.fitBounds(group.getBounds().pad(0.1));
        }
    }

    updateRecentTable(data) {
        const tbody = document.getElementById('recentTableBody');
        if (!tbody) return;
        
        if (!data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="bx bx-inbox text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No recent registrations found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded-circle bg-primary">
                                ${item.nama_lengkap ? item.nama_lengkap.charAt(0).toUpperCase() : '?'}
                            </span>
                        </div>
                        <div>
                            <div class="fw-semibold">${item.nama_lengkap || 'Unknown'}</div>
                            <small class="text-muted">${item.whatsapp?.nomor || ''}</small>
                        </div>
                    </div>
                </td>
                <td>${item.status_lokasi?.nama || 'Unknown'}</td>
                <td>${item.layanan_digunakan?.nama || 'Unknown'}</td>
                <td>${ this.formatDate(item.tanggal) }</td>
                <td>
                    <span class="status-badge status-active">Active</span>
                </td>
            </tr>
        `).join('');

        // Add slide-up animation
        tbody.querySelectorAll('tr').forEach((row, index) => {
            setTimeout(() => {
                row.classList.add('slide-up');
            }, index * 50);
        });
    }

    showLoading() {
        this.isLoading = true;
    }

    hideLoading() {
        this.isLoading = false;
    }

    showError(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-danger border-0';
        toast.setAttribute('role', 'alert');
        toast.style.position = 'fixed';
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bx bx-error me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    refreshData() {
        const periodSelect = document.getElementById('periodSelect');
        const days = periodSelect ? parseInt(periodSelect.value) : 30;
        this.loadAllData(days);
    }

    updateChartThemes() {
        Object.values(this.charts).forEach(chart => {
            if (chart) {
                chart.update('none');
            }
        });
    }

    resizeCharts() {
        Object.values(this.charts).forEach(chart => {
            if (chart) {
                chart.resize();
            }
        });
    }

    centerMap() {
        if (this.map && this.markers.length > 0) {
            const group = new L.featureGroup(this.markers);
            this.map.fitBounds(group.getBounds().pad(0.1));
        }
    }

    toggleHeatmap() {
    if (!this.heatmapPoints) return;

        if (this.heatmapLayer) {
            // Kalau sudah ada, hapus layer
            this.map.removeLayer(this.heatmapLayer);
            this.heatmapLayer = null;
        } else {
            // Buat heatmap layer dengan intensity 1 per point
            this.heatmapLayer = L.heatLayer(
            this.heatmapPoints, 
            { radius: 25, blur: 15, maxZoom: 17 }
            ).addTo(this.map);
        }
    }

    formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    formatDate(dateString) {
        if (!dateString) return '-';

        // Jika format datang “YYYY-MM-DD hh:mm:ss”, ubah spasi jadi ‘T’
        let normalized = String(dateString).replace(' ', 'T');

        const date = new Date(normalized);
        if (isNaN(date.getTime())) {
            console.warn('Invalid date passed to formatDate:', dateString);
            return dateString;  // atau return '-' jika Anda mau placeholder
        }

        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }).format(date);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        
        Object.values(this.charts).forEach(chart => {
            if (chart) {
                chart.destroy();
            }
        });
        
        if (this.map) {
            this.map.remove();
        }
    }
}

// Chart download functionality
window.downloadChart = function(chartId, format) {
    const chart = window.DashboardEnhanced?.charts[chartId];
    if (!chart) return;

    const link = document.createElement('a');
    link.download = `${chartId}-${new Date().toISOString().split('T')[0]}.${format}`;
    
    if (format === 'png') {
        link.href = chart.toBase64Image();
    } else if (format === 'pdf') {
        // For PDF, we'll use a library like jsPDF in production
        console.log('PDF download would require jsPDF library');
        return;
    }
    
    link.click();
};

// Global functions for map controls
window.centerMap = function() {
    if (window.DashboardEnhanced) {
        window.DashboardEnhanced.centerMap();
    }
};

window.toggleHeatmap = function() {
    if (window.DashboardEnhanced) {
        window.DashboardEnhanced.toggleHeatmap();
    }
};

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const dashboard = DashboardEnhanced.init();
    dashboard.init();
});

// Make DashboardEnhanced globally available
window.DashboardEnhanced = DashboardEnhanced;
// Export for module usage
export default DashboardEnhanced;
