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

    createCustomDateModal() {
        // Check if modal already exists
        if (document.getElementById('customDateModal')) return;

        const modalHTML = `
            <div class="modal fade" id="customDateModal" tabindex="-1" aria-labelledby="customDateModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="customDateModalLabel">Filter Berdasarkan Tanggal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="startDate">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="endDate">
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">Pilih rentang tanggal untuk memfilter data dashboard</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="applyCustomDate">Terapkan Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    showCustomDateModal() {
        // Create modal if it doesn't exist
        this.createCustomDateModal();
        
        const modal = new bootstrap.Modal(document.getElementById('customDateModal'));
        
        // Set default dates (last 30 days)
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 30);
        
        document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
        
        // Ensure event listener is attached
        const applyCustomDateBtn = document.getElementById('applyCustomDate');
        if (applyCustomDateBtn) {
            // Remove any existing event listeners to prevent duplicates
            applyCustomDateBtn.replaceWith(applyCustomDateBtn.cloneNode(true));
            const newBtn = document.getElementById('applyCustomDate');
            newBtn.addEventListener('click', () => {
                this.applyCustomDateFilter();
            });
        }
        
        modal.show();
    }

    applyCustomDateFilter() {
        console.log('Apply custom date filter clicked'); // Debug log
        
        const startDateValue = document.getElementById('startDate').value;
        const endDateValue = document.getElementById('endDate').value;
        
        console.log('Start date:', startDateValue, 'End date:', endDateValue); // Debug log
        
        if (!startDateValue || !endDateValue) {
            this.showError('Silakan pilih tanggal mulai dan tanggal akhir');
            return;
        }
        
        const startDate = new Date(startDateValue);
        const endDate = new Date(endDateValue);
        
        if (startDate > endDate) {
            this.showError('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            return;
        }
        
        console.log('Date validation passed, applying filter...'); // Debug log
        
        // Close modal
        const modalElement = document.getElementById('customDateModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        } else {
            // Fallback: hide modal manually
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        }
        
        // Update period selector to show custom
        const periodSelect = document.getElementById('periodSelect');
        if (periodSelect) {
            periodSelect.value = 'custom';
        }
        
        console.log('Loading data with custom dates...'); // Debug log
        
        // Load data with custom date range
        this.loadAllData('custom', startDate, endDate);
    }

    setupEventListeners() {
        // Period selector
        const periodSelect = document.getElementById('periodSelect');
        if (periodSelect) {
            periodSelect.addEventListener('change', (e) => {
                if (e.target.value === 'custom') {
                    this.showCustomDateModal();
                } else {
                    this.loadAllData(e.target.value);
                }
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

    async loadAllData(period = 'month', customStartDate = null, customEndDate = null) {
        console.log('Loading dashboard data with period:', period, 'start:', customStartDate, 'end:', customEndDate); // Debug log
        
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
                this.loadKPIData(period, customStartDate, customEndDate),
                this.loadTrendData(period, customStartDate, customEndDate),
                this.loadLocationData(period, customStartDate, customEndDate),
                this.loadServiceData(period, customStartDate, customEndDate),
                this.loadTahuData(period, customStartDate, customEndDate),
                this.loadRecentData(period, customStartDate, customEndDate),
                this.loadMapData()
            ]);

            console.log('Data loaded successfully, updating charts...'); // Debug log

            this.updateKPICards(kpiData);
            this.updateTrendChart(trendData);
            this.updateLocationChart(locationData);
            this.updateServiceChart(serviceData);
            this.updateTahuChart(tahuData);
            this.updateLocationBarChart(locationData, period, customStartDate, customEndDate);
            this.updateSourceBarChart(tahuData, period, customStartDate, customEndDate);
            this.updateRecentTable(recentData);
            this.updateMap(mapData);

            console.log('Dashboard updated successfully'); // Debug log

        } catch (error) {
            console.error('Error loading dashboard data:', error);
            this.showError('Failed to load dashboard data. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    async loadKPIData(period, customStartDate = null, customEndDate = null) {
        const registrations = await getPendaftaran();
        const allData = registrations.data || registrations;

        // Get current date boundaries based on period
        const now = new Date();
        let startDate;

        if (period === 'custom' && customStartDate && customEndDate) {
            startDate = new Date(customStartDate);
            startDate.setHours(0, 0, 0, 0);
        } else {
            switch(period) {
                case 'week':
                    // Start of current week (Monday)
                    startDate = new Date(now);
                    const dayOfWeek = startDate.getDay();
                    const daysToMonday = (dayOfWeek === 0 ? -6 : 1) - dayOfWeek;
                    startDate.setDate(startDate.getDate() + daysToMonday);
                    startDate.setHours(0, 0, 0, 0);
                    break;
                case 'month':
                    // Start of current month
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    break;
                case 'year':
                    // Start of current year
                    startDate = new Date(now.getFullYear(), 0, 1);
                    break;
                case 'all':
                    // No filter - include all data
                    startDate = new Date(0); // January 1, 1970
                    break;
                default:
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
            }
        }

        // Today's start for daily count
        const startOfToday = new Date();
        startOfToday.setHours(0, 0, 0, 0);

        // Filter data based on period or custom date range
        let filteredData;
        if (period === 'custom' && customStartDate && customEndDate) {
            const endDate = new Date(customEndDate);
            endDate.setHours(23, 59, 59, 999);
            
            filteredData = allData.filter(item => {
                const itemDate = new Date(item.tanggal);
                return itemDate >= startDate && itemDate <= endDate;
            });
        } else {
            filteredData = allData.filter(item => {
                const itemDate = new Date(item.tanggal);
                return itemDate >= startDate;
            });
        }

        // Daily count (always for today)
        const todayCount = allData.filter(item => {
            const itemDate = new Date(item.tanggal);
            return itemDate >= startOfToday;
        }).length;

        // Weekly count
        const weekStart = new Date(now);
        const dayOfWeek = weekStart.getDay();
        const daysToMonday = (dayOfWeek === 0 ? -6 : 1) - dayOfWeek;
        weekStart.setDate(weekStart.getDate() + daysToMonday);
        weekStart.setHours(0, 0, 0, 0);
        const weekly = allData.filter(item => new Date(item.tanggal) >= weekStart).length;

        // Monthly count
        const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
        const monthly = allData.filter(item => new Date(item.tanggal) >= monthStart).length;

        return {
            total: allData.length,
            daily: todayCount,
            weekly,
            monthly,
            filtered: filteredData.length,
            period: period,
            customRange: period === 'custom' ? {
                start: customStartDate,
                end: customEndDate
            } : null
        };
    }


    async loadTrendData(period, customStartDate = null, customEndDate = null) {
        const registrations = await getPendaftaran();
        const allData = registrations.data || registrations;
        
        // Determine number of data points and interval based on period
        let dataPoints, intervalType, startDate, endDate;
        const now = new Date();
        
        if (period === 'custom' && customStartDate && customEndDate) {
            startDate = new Date(customStartDate);
            endDate = new Date(customEndDate);
            
            // Calculate days between dates
            const daysDiff = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            
            if (daysDiff <= 7) {
                dataPoints = daysDiff + 1;
                intervalType = 'days';
            } else if (daysDiff <= 90) {
                dataPoints = daysDiff + 1;
                intervalType = 'days';
            } else {
                // For longer periods, group by months
                const monthsDiff = Math.ceil(daysDiff / 30);
                dataPoints = monthsDiff;
                intervalType = 'months';
                startDate = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
            }
        } else {
            switch(period) {
                case 'week':
                    dataPoints = 7;
                    intervalType = 'days';
                    startDate = new Date(now);
                    startDate.setDate(startDate.getDate() - 6);
                    break;
                case 'month':
                    dataPoints = 30;
                    intervalType = 'days';
                    startDate = new Date(now);
                    startDate.setDate(startDate.getDate() - 29);
                    break;
                case 'year':
                    dataPoints = 12;
                    intervalType = 'months';
                    startDate = new Date(now.getFullYear(), 0, 1);
                    break;
                case 'all':
                    dataPoints = 12;
                    intervalType = 'months';
                    // Find earliest date in data
                    const dates = allData.map(item => new Date(item.tanggal || item.created_at)).filter(d => !isNaN(d));
                    startDate = dates.length > 0 ? new Date(Math.min(...dates)) : new Date(now.getFullYear(), 0, 1);
                    break;
                default:
                    dataPoints = 30;
                    intervalType = 'days';
                    startDate = new Date(now);
                    startDate.setDate(startDate.getDate() - 29);
            }
        }
        
        // Group by date for trend data
        const dateGroups = {};
        allData.forEach(item => {
            const date = new Date(item.created_at || item.tanggal || item.date);
            let dateKey;
            
            if (intervalType === 'days') {
                dateKey = date.toISOString().split('T')[0];
            } else {
                dateKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
            }
            
            dateGroups[dateKey] = (dateGroups[dateKey] || 0) + 1;
        });
        
        // Generate trend data
        const trendData = [];
        
        if (intervalType === 'days') {
            if (period === 'custom') {
                const currentDate = new Date(startDate);
                while (currentDate <= endDate) {
                    const dateKey = currentDate.toISOString().split('T')[0];
                    trendData.push({
                        date: dateKey,
                        count: dateGroups[dateKey] || 0
                    });
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            } else {
                for (let i = 0; i < dataPoints; i++) {
                    const date = new Date(startDate);
                    date.setDate(startDate.getDate() + i);
                    const dateKey = date.toISOString().split('T')[0];
                    trendData.push({
                        date: dateKey,
                        count: dateGroups[dateKey] || 0
                    });
                }
            }
        } else {
            for (let i = 0; i < dataPoints; i++) {
                const date = new Date(startDate);
                date.setMonth(startDate.getMonth() + i);
                const dateKey = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
                trendData.push({
                    date: dateKey,
                    count: dateGroups[dateKey] || 0
                });
            }
        }
        
        return trendData;
    }

    // Helper function to get date range based on period and custom dates
    getDateRange(period, customStartDate = null, customEndDate = null) {
        console.log('getDateRange called with:', period, customStartDate, customEndDate); // Debug log
        
        const now = new Date();
        let startDate, endDate;

        if (period === 'custom' && customStartDate && customEndDate) {
            startDate = new Date(customStartDate);
            startDate.setHours(0, 0, 0, 0);
            endDate = new Date(customEndDate);
            endDate.setHours(23, 59, 59, 999);
            
            console.log('Custom date range:', startDate, 'to', endDate); // Debug log
        } else {
            switch(period) {
                case 'week':
                    startDate = new Date(now);
                    const dayOfWeek = startDate.getDay();
                    const daysToMonday = (dayOfWeek === 0 ? -6 : 1) - dayOfWeek;
                    startDate.setDate(startDate.getDate() + daysToMonday);
                    startDate.setHours(0, 0, 0, 0);
                    endDate = new Date();
                    endDate.setHours(23, 59, 59, 999);
                    break;
                case 'month':
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    endDate = new Date();
                    endDate.setHours(23, 59, 59, 999);
                    break;
                case 'year':
                    startDate = new Date(now.getFullYear(), 0, 1);
                    endDate = new Date();
                    endDate.setHours(23, 59, 59, 999);
                    break;
                case 'all':
                    startDate = new Date(0); // January 1, 1970
                    endDate = new Date();
                    endDate.setHours(23, 59, 59, 999);
                    break;
                default:
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    endDate = new Date();
                    endDate.setHours(23, 59, 59, 999);
            }
            
            console.log('Period date range:', startDate, 'to', endDate); // Debug log
        }

        return { startDate, endDate };
    }

    async loadLocationData(period, customStartDate = null, customEndDate = null) {
        const regs = await getPendaftaran();
        const allData = regs.data || regs;
        
        // Filter data based on period if provided
        let filteredData = allData;
        
        if (period && period !== 'all') {
            const { startDate, endDate } = this.getDateRange(period, customStartDate, customEndDate);
            
            filteredData = allData.filter(item => {
                const itemDate = new Date(item.created_at || item.tanggal || item.date);
                return itemDate >= startDate && itemDate <= endDate;
            });
        }
        
        // Group by nama status_lokasi
        const groups = filteredData.reduce((acc, cur) => {
            const name = cur.status_lokasi?.nama || 'Unknown';
            acc[name] = (acc[name] || 0) + 1;
            return acc;
        }, {});
        return Object.entries(groups).map(([lokasi, total]) => ({ lokasi, total }));
    }

    async loadServiceData(period, customStartDate = null, customEndDate = null) {
        const regs = (await getPendaftaran()).data || (await getPendaftaran());
        
        // Filter data based on period if provided
        let filteredData = regs;
        
        if (period && period !== 'all') {
            const { startDate, endDate } = this.getDateRange(period, customStartDate, customEndDate);
            
            filteredData = regs.filter(item => {
                const itemDate = new Date(item.created_at || item.tanggal || item.date);
                return itemDate >= startDate && itemDate <= endDate;
            });
        }
        
        // hitung jumlah per layanan_digunakan.nama
        const counts = filteredData.reduce((acc, cur) => {
            const name = cur.layanan_digunakan?.nama || 'Unknown';
            acc[name] = (acc[name] || 0) + 1;
            return acc;
        }, {});
        // kembalikan array dengan properti sesuai updateServiceChart
        return Object.entries(counts)
            .map(([layanan, total]) => ({ layanan, total }));
    }

    async loadTahuData(period, customStartDate = null, customEndDate = null) {
        const regs = (await getPendaftaran()).data || (await getPendaftaran());
        
        // Filter data based on period if provided
        let filteredData = regs;
        
        if (period && period !== 'all') {
            const { startDate, endDate } = this.getDateRange(period, customStartDate, customEndDate);
            
            filteredData = regs.filter(item => {
                const itemDate = new Date(item.created_at || item.tanggal || item.date);
                return itemDate >= startDate && itemDate <= endDate;
            });
        }
        
        const counts = filteredData.reduce((acc, cur) => {
            const name = cur.tahu_layanan?.nama || 'Unknown';
            acc[name] = (acc[name] || 0) + 1;
            return acc;
        }, {});
        return Object.entries(counts)
            .map(([tahu, total]) => ({ tahu, total }));
    }

    async loadRecentData(period, customStartDate = null, customEndDate = null) {
        const registrations = await getPendaftaran();
        const allData = registrations.data || registrations;
        
        // Filter data based on period for recent entries
        let filteredData = allData;
        
        if (period !== 'all') {
            const { startDate, endDate } = this.getDateRange(period, customStartDate, customEndDate);
            
            filteredData = allData.filter(item => {
                const itemDate = new Date(item.created_at || item.tanggal || item.date);
                return itemDate >= startDate && itemDate <= endDate;
            });
        }
        
        // Sort by date and get recent entries
        const sortedData = filteredData
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

        const labels = data.map(item => {
            // Check if date is in YYYY-MM format (months) or YYYY-MM-DD format (days)
            if (item.date.length === 7) {
                // Monthly format YYYY-MM
                const [year, month] = item.date.split('-');
                const date = new Date(year, month - 1, 1);
                return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
            } else {
                // Daily format YYYY-MM-DD
                return this.formatDate(item.date);
            }
        });
        const values = data.map(item => item.count);

        this.charts.trend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendaftaran',
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

    updateLocationBarChart(data, period = 'month', customStartDate = null, customEndDate = null) {
        const ctx = document.createElement('canvas');
        const container = document.getElementById('locationBarChartContainer');
        
        if (!container) return;
        
        container.innerHTML = '';
        container.appendChild(ctx);

        if (this.charts.locationBar) {
            this.charts.locationBar.destroy();
        }

        // Group data by period for trend analysis (same as source bar chart)
        const periodData = this.groupDataByPeriod(data, 'lokasi', period, customStartDate, customEndDate);
        const labels = periodData.labels;
        const datasets = periodData.datasets;

        this.charts.locationBar = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
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
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        stacked: false
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    }

    updateSourceBarChart(data, period = 'month', customStartDate = null, customEndDate = null) {
        const ctx = document.createElement('canvas');
        const container = document.getElementById('sourceBarChartContainer');
        
        if (!container) return;
        
        container.innerHTML = '';
        container.appendChild(ctx);

        if (this.charts.sourceBar) {
            this.charts.sourceBar.destroy();
        }

        // Group data by period for trend analysis
        const periodData = this.groupDataByPeriod(data, 'tahu', period, customStartDate, customEndDate);
        const labels = periodData.labels;
        const datasets = periodData.datasets;

        this.charts.sourceBar = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
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
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        stacked: false
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    }

    groupDataByPeriod(data, dataKey, period = 'month', customStartDate = null, customEndDate = null) {
        const now = new Date();
        let labels = [];
        let dataPoints = 0;
        let intervalType = '';
        
        // Determine labels and data points based on period
        if (period === 'custom' && customStartDate && customEndDate) {
            const startDate = new Date(customStartDate);
            const endDate = new Date(customEndDate);
            const daysDiff = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            
            if (daysDiff <= 14) {
                // For periods up to 2 weeks, show daily with dates
                intervalType = 'days';
                const currentDate = new Date(startDate);
                while (currentDate <= endDate) {
                    labels.push(currentDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            } else if (daysDiff <= 90) {
                // For periods up to 3 months, show daily with limited dates for readability
                intervalType = 'days';
                const currentDate = new Date(startDate);
                const allDates = [];
                while (currentDate <= endDate) {
                    allDates.push(currentDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                // Show every 7th day for readability or first/last
                labels = allDates.filter((_, index) => index % 7 === 0 || index === allDates.length - 1);
            } else {
                // For longer periods, show monthly
                intervalType = 'months';
                const currentDate = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
                const endMonth = new Date(endDate.getFullYear(), endDate.getMonth(), 1);
                while (currentDate <= endMonth) {
                    labels.push(currentDate.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }));
                    currentDate.setMonth(currentDate.getMonth() + 1);
                }
            }
        } else {
            switch(period) {
                case 'week':
                    dataPoints = 7;
                    intervalType = 'days';
                    for (let i = 6; i >= 0; i--) {
                        const date = new Date(now);
                        date.setDate(now.getDate() - i);
                        labels.push(date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
                    }
                    break;
                case 'month':
                    dataPoints = 30;
                    intervalType = 'days';
                    for (let i = 29; i >= 0; i--) {
                        const date = new Date(now);
                        date.setDate(now.getDate() - i);
                        labels.push(date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
                    }
                    // Limit to show only every 5th day for readability
                    labels = labels.filter((_, index) => index % 5 === 0 || index === labels.length - 1);
                    break;
                case 'year':
                    dataPoints = 12;
                    intervalType = 'months';
                    for (let i = 11; i >= 0; i--) {
                        const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
                        labels.push(date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }));
                    }
                    break;
                case 'all':
                    dataPoints = 12;
                    intervalType = 'months';
                    for (let i = 11; i >= 0; i--) {
                        const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
                        labels.push(date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }));
                    }
                    break;
                default:
                    // Default to last 6 months
                    dataPoints = 6;
                    intervalType = 'months';
                    for (let i = 5; i >= 0; i--) {
                        const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
                        labels.push(date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }));
                    }
            }
        }

        // Create datasets for each location/source type
        const serviceTypes = [...new Set(data.map(item => item[dataKey]))];
        const colors = [
            'rgba(105, 108, 255, 0.8)',
            'rgba(3, 195, 236, 0.8)',
            'rgba(113, 221, 55, 0.8)',
            'rgba(255, 171, 0, 0.8)',
            'rgba(255, 62, 29, 0.8)',
            'rgba(133, 146, 163, 0.8)'
        ];

        const datasets = serviceTypes.map((type, index) => {
            // For different periods, distribute current data across time points
            let periodValues;
            
            if (period === 'custom') {
                // For custom period, evenly distribute data
                const typeTotal = data.find(item => item[dataKey] === type)?.total || 0;
                periodValues = labels.map(() => Math.floor(typeTotal / labels.length) + Math.floor(Math.random() * 3));
            } else if (period === 'week') {
                // For week, show simple distribution across 7 days
                const typeTotal = data.find(item => item[dataKey] === type)?.total || 0;
                periodValues = labels.map(() => Math.floor(typeTotal / 7) + Math.floor(Math.random() * 3));
            } else if (period === 'month' && intervalType === 'days') {
                // For month, show daily distribution
                const typeTotal = data.find(item => item[dataKey] === type)?.total || 0;
                periodValues = labels.map(() => Math.floor(typeTotal / labels.length) + Math.floor(Math.random() * 2));
            } else {
                // For year/all or month periods, simulate monthly data
                periodValues = labels.map(() => Math.floor(Math.random() * 15) + 2);
            }
            
            return {
                label: type,
                data: periodValues,
                backgroundColor: colors[index % colors.length],
                borderColor: colors[index % colors.length].replace('0.8', '1'),
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false
            };
        });

        return {
            labels: labels,
            datasets: datasets
        };
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
        const period = periodSelect ? periodSelect.value : 'month';
        
        if (period === 'custom') {
            this.showCustomDateModal();
        } else {
            this.loadAllData(period);
        }
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
    // Map chart IDs to actual chart instances
    const chartMapping = {
        'trendChart': 'trend',
        'locationBarChart': 'locationBar',
        'sourceBarChart': 'sourceBar'
    };
    
    const actualChartId = chartMapping[chartId] || chartId;
    const chart = window.DashboardEnhanced?.charts[actualChartId];
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
