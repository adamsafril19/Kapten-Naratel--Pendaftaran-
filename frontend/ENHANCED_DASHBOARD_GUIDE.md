# Enhanced Dashboard Integration Guide

## Overview
This guide provides step-by-step instructions for integrating the enhanced dashboard into your existing application.

## Files Created
- `dashboard-enhanced.php` - Main enhanced dashboard page
- `assets/css/dashboard-enhanced.css` - Enhanced styling
- `dashboard-enhanced.js` - Modern JavaScript functionality

## Prerequisites
- Bootstrap 5.3+
- Chart.js 4.0+
- Leaflet.js (for maps)
- Existing API endpoints (already available)

## Installation Steps

### 1. Update Sidebar Navigation
Add the enhanced dashboard link to your sidebar:

```php
<!-- In sidebar.php, add after existing dashboard link -->
<li class="menu-item">
    <a href="dashboard-enhanced.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-trending-up"></i>
        <div data-i18n="Enhanced Dashboard">Enhanced Dashboard</div>
    </a>
</li>
```

### 2. Include Required Libraries
Add these CDN links to your template.php head section:

```html
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<!-- Leaflet.js for maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

### 3. Update Template.php
Add the enhanced CSS file to your template.php:

```php
<!-- Add after existing CSS includes -->
<link rel="stylesheet" href="assets/css/dashboard-enhanced.css">
```

### 4. Database Requirements
Ensure your database has the following fields for optimal functionality:
- `registrations.latitude` (DECIMAL)
- `registrations.longitude` (DECIMAL)
- `registrations.created_at` (TIMESTAMP)

### 5. API Endpoints Verification
Verify these API endpoints are working:
- `getTotalRegistrations()`
- `getDailyRegistrations(days)`
- `getWeeklyRegistrations()`
- `getMonthlyRegistrations()`
- `getStatusLokasi()`
- `getLayananDigunakan()`
- `getTahuLayanan()`

## Features Overview

### 1. Modern KPI Cards
- Animated counters
- Hover effects
- Responsive design
- Real-time updates

### 2. Interactive Charts
- **Trend Chart**: Line chart showing registration trends
- **Location Chart**: Doughnut chart for location distribution
- **Service Chart**: Bar chart for service usage
- **Source Chart**: Polar area chart for referral sources

### 3. Interactive Map
- Leaflet.js integration
- Marker clustering
- Popup information
- Auto-centering

### 4. Real-time Updates
- Auto-refresh every 30 seconds
- Manual refresh button
- Loading states
- Error handling

### 5. Responsive Design
- Mobile-first approach
- Bootstrap 5 grid system
- Collapsible sidebar
- Touch-friendly interactions

## Customization Options

### Colors
Edit CSS variables in `dashboard-enhanced.css`:
```css
:root {
    --primary-color: #696cff;
    --success-color: #71dd37;
    --info-color: #03c3ec;
    --warning-color: #ffab00;
    --danger-color: #ff3e1d;
}
```

### Chart Colors
Update chart colors in `dashboard-enhanced.js`:
```javascript
backgroundColor: [
    '#696cff', '#03c3ec', '#71dd37', 
    '#ffab00', '#ff3e1d', '#8592a3'
]
```

### Refresh Interval
Change auto-refresh timing:
```javascript
// In dashboard-enhanced.js
this.refreshInterval = setInterval(() => {
    if (!this.isLoading) {
        this.loadAllData();
    }
}, 30000); // Change 30000 to desired milliseconds
```

## Performance Optimizations

### 1. Lazy Loading
Charts and maps load only when needed
Skeleton screens for better perceived performance

### 2. Data Caching
Implement localStorage caching:
```javascript
// Add to dashboard-enhanced.js
const cacheKey = `dashboard-data-${days}`;
const cached = localStorage.getItem(cacheKey);
if (cached && Date.now() - JSON.parse(cached).timestamp < 300000) {
    return JSON.parse(cached).data;
}
```

### 3. Debounced Resize
Charts resize efficiently on window resize

## Troubleshooting

### Common Issues

#### Charts Not Loading
1. Check Chart.js CDN is loaded
2. Verify API endpoints return data
3. Check browser console for errors

#### Map Not Displaying
1. Verify Leaflet.js CDN is loaded
2. Check for latitude/longitude data
3. Ensure map container has height

#### Data Not Updating
1. Check API endpoints are accessible
2. Verify CORS settings
3. Check network tab for failed requests

### Debug Mode
Enable debug logging:
```javascript
// In dashboard-enhanced.js constructor
this.debug = true;
```

## Migration from Old Dashboard

### 1. Backup Current Files
```bash
cp dashboard.php dashboard-backup.php
```

### 2. Replace Dashboard
```bash
cp dashboard-enhanced.php dashboard.php
```

### 3. Update References
Update any hardcoded links to point to the enhanced dashboard.

## Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari 14+, Chrome Mobile 90+)

## Security Considerations
- All API calls use existing authentication
- XSS protection via textContent
- CSRF tokens if required
- Input validation on server side

## Future Enhancements
- Export functionality (PDF/Excel)
- Advanced filtering
- User preferences
- Dark mode toggle
- Real-time notifications
- Advanced analytics

## Support
For issues or questions:
1. Check browser console for errors
2. Verify all prerequisites are met
3. Test API endpoints independently
4. Review this guide for configuration issues