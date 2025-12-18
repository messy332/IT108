# Farmer Dashboard Features

## Overview
The enhanced farmer dashboard provides an interactive map-based interface for farmers to view and manage their farm information, with a focus on the Philippines region.

## Key Features

### 1. **Interactive Farm Map**
- **Location**: Displays farm location on an interactive Leaflet map
- **Default Center**: Philippines (Manila) - Latitude: 14.5995, Longitude: 120.9842
- **Farm Boundaries**: Shows farm boundaries as green polygons on the map
- **Crop Markers**: Displays individual crops as colored markers within the farm
  - Blue: Planted crops
  - Green: Growing crops
  - Grey: Harvested crops
  - Red: Failed crops

### 2. **Farm Information Display**
- Farmer personal details (name, email, phone, age, gender)
- Farm details (size, type, status)
- Total and active crop counts
- Farm address and coordinates

### 3. **Interactive Popups**
- **Farm Polygon Popup**: Click on the green farm boundary to see:
  - Farm size
  - Farm type
  - Address
  - Total crops
  - Active crops
  
- **Crop Marker Popup**: Click on crop markers to see:
  - Crop name and variety
  - Status
  - Area planted
  - Season
  - Planting date
  - Expected harvest date
  - Growth stage
  - Link to view full crop details

### 4. **Location Update Feature**
- Modal dialog for updating farm location
- Options to:
  - Enter address manually
  - Input latitude/longitude coordinates
  - Use current GPS location
  - Draw farm boundaries using polygon tool
  - Click on map to set center point
- Interactive map with drawing tools for defining farm boundaries

### 5. **Statistics Dashboard**
- **Quick Stats Cards**:
  - Total Crops
  - Active Crops
  - Total Area (hectares)
  - Average Growth Stage

### 6. **Crop Information Panel**
- Grid view of all crops
- Click on crop card to highlight it on the map
- Shows crop status, area, season, and progress
- Direct links to view/edit crop details

### 7. **Recent Activity Feed**
- Timeline of recent farming activities
- Shows activity type, date, growth stage, and weather conditions
- Link to view all activities

### 8. **Analytics & Reports**
All original graphs are preserved:

#### a. **Farm Status Chart** (Line Chart)
- Shows average growth stage over the last 6 months
- Tracks farm progress over time

#### b. **Activity Types by Growth Rate** (Bar Chart)
- Displays average growth rate for each activity type
- Helps identify which activities contribute most to crop growth

#### c. **Farm Area Utilization** (Doughnut Chart)
- Visual representation of planted vs available area
- Shows how much of the farm is currently in use

## Technical Implementation

### Database Changes
- Added `latitude` (decimal 10,8) field to farmers table
- Added `longitude` (decimal 11,8) field to farmers table
- Added `farm_boundaries` (JSON) field to store polygon coordinates

### Libraries Used
- **Leaflet.js**: Free, open-source map library (no API key required)
- **Leaflet Draw**: Plugin for drawing farm boundaries
- **Chart.js**: For analytics graphs
- **OpenStreetMap**: Free map tiles

### Security
- Farmers can only view and edit their own farm data
- Location updates require authentication
- Form validation for coordinates (latitude: -90 to 90, longitude: -180 to 180)

## Usage Instructions

### For Farmers:

1. **View Your Farm**:
   - Login to your account
   - Navigate to Dashboard
   - See your farm location on the map

2. **Update Farm Location**:
   - Click "Update Location" button
   - Enter your farm address
   - Either:
     - Click "Use My Current Location" to auto-detect
     - Enter coordinates manually
     - Click on the map to set location
   - Use the polygon drawing tool to mark your farm boundaries
   - Click "Save Location"

3. **View Crop Information**:
   - Click on colored markers on the map to see crop details
   - Click on crop cards below the map to highlight them
   - Click "View Details" to see full crop information

4. **Monitor Farm Progress**:
   - Check the statistics cards for quick overview
   - Review the graphs for detailed analytics
   - View recent activities in the activity feed

## Future Enhancements
- Weather integration for farm location
- Soil type mapping
- Crop rotation planning
- Satellite imagery overlay
- Multi-farm support for larger operations
- Export farm data and reports
