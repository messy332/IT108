<h1 align="center">FarmTrack</h1>

<p align="center">
  <strong>A Smart Agricultural Management System for Filipino Farmers</strong>
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#installation">Installation</a> •
  <a href="#usage">Usage</a> •
  <a href="#tech-stack">Tech Stack</a>
</p>

---

## About FarmTrack

FarmTrack is a comprehensive web-based agricultural management system designed specifically for Filipino farmers. It provides an intuitive platform to monitor crops, track farm activities, visualize farm boundaries on interactive maps, and generate insightful analytics to improve farming productivity.

## Features

### 🗺️ Interactive Farm Mapping
- View farm location on an interactive map centered on the Philippines
- Draw and manage farm boundaries using polygon tools
- Color-coded crop markers showing status (Planted, Growing, Harvested, Failed)
- Click-to-view detailed information for farms and crops

### 🌾 Crop Management
- Track multiple crops with detailed information
- Monitor growth stages and progress
- Record planting and expected harvest dates
- Manage crop varieties and seasons

### 📊 Analytics & Reports
- **Farm Status Chart**: Track average growth stage over time
- **Activity Analysis**: View growth rates by activity type
- **Area Utilization**: Visualize planted vs available farm area

### 📍 Location Management
- Set farm location via GPS, manual coordinates, or map click
- Draw precise farm boundaries
- Auto-detect current location

### 📝 Activity Tracking
- Log farming activities with timestamps
- Track weather conditions during activities
- Monitor growth stages per activity

### 👨‍🌾 Farmer Dashboard
- Quick statistics overview (Total Crops, Active Crops, Total Area)
- Recent activity feed
- Direct access to crop details and management

## Installation

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/MariaDB

### Setup

1. Clone the repository
```bash
git clone https://github.com/messy332/IT108/tree/main/Cristoria_Cepeda_FinalProject/src_farmtrack
cd src_farmtrack
```

2. Install PHP dependencies
```bash
composer install
```

3. Install Node dependencies
```bash
npm install
```

4. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmtrack
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Build assets
```bash
npm run build
```

7. Start the development server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Usage

### For Farmers
1. **Register/Login** to your farmer account
2. **Dashboard** - View your farm overview with interactive map
3. **Update Location** - Set your farm boundaries using the drawing tools
4. **Manage Crops** - Add, edit, and track your crops
5. **View Analytics** - Monitor farm performance through charts and reports

### For Administrators
- Manage farmer accounts
- View system-wide analytics
- Configure system settings

## Tech Stack

| Category | Technology |
|----------|------------|
| Backend | Laravel (PHP) |
| Frontend | Blade Templates, Tailwind CSS |
| Database | MySQL |
| Maps | Leaflet.js + OpenStreetMap |
| Charts | Chart.js |
| Build Tool | Vite |

## License

This project is proprietary software developed for agricultural management in the Philippines.

---

<p align="center">
  Made with ❤️ for Filipino Farmers
</p>
