# Farmer Location Setup Guide

## How It Works (Simplified)

### For Farmers - No Manual Coordinates Needed! 

Farmers **DO NOT** need to manually enter latitude and longitude. The system handles this automatically in three easy ways:

## Option 1: Use Current GPS Location (Easiest)
1. Click "Set Farm Location" button
2. Click "📍 Use My Current Location" button
3. Allow browser to access your location
4. Your farm location is automatically set!
5. Click "Save Location"

## Option 2: Click on Map
1. Click "Set Farm Location" button
2. Simply click anywhere on the map where your farm is located
3. A green marker will appear at that spot
4. The coordinates are automatically captured
5. Click "Save Location"

## Option 3: Draw Farm Boundaries (Most Accurate)
1. Click "Set Farm Location" button
2. Find your farm area on the map (zoom in/out as needed)
3. Click the polygon tool (⬟) in the toolbar
4. Click multiple points around your farm to draw the boundary
5. Click the first point again to close the polygon
6. The system automatically calculates the center coordinates
7. Click "Save Location"

## What Farmers See

### Main Dashboard:
- **Map showing their farm location** (if set)
- **Green polygon showing farm boundaries** (if drawn)
- **Colored markers for each crop** on the farm
- **Statistics cards** (Total Crops, Active Crops, etc.)
- **All original graphs and reports**

### Location Modal (When Setting Location):
- **Address field** - Enter farm address (optional)
- **"Use My Current Location" button** - Auto-detect via GPS
- **Interactive map** - Click to set location
- **Drawing tools** - Draw farm boundaries
- **NO manual coordinate entry required!**

## Behind the Scenes (Technical)

The system automatically:
1. Captures latitude/longitude when farmer clicks map or uses GPS
2. Stores coordinates in hidden form fields
3. Saves to database when form is submitted
4. Displays farm location on dashboard map
5. Shows crop markers relative to farm location

## Benefits

✅ **No technical knowledge needed** - Just click on map
✅ **GPS auto-detection** - One click to set location
✅ **Visual boundary drawing** - Draw your actual farm shape
✅ **Philippines-focused** - Map defaults to Philippines
✅ **Mobile-friendly** - Works on phones with GPS
✅ **Edit anytime** - Update location whenever needed

## For Developers

### Hidden Fields (Automatically Populated):
```html
<input type="hidden" name="latitude" id="latitudeInput">
<input type="hidden" name="longitude" id="longitudeInput">
<input type="hidden" name="farm_boundaries" id="farmBoundariesInput">
```

### JavaScript Handles:
- Map click events → captures lat/lng
- GPS button → uses navigator.geolocation
- Polygon drawing → captures boundary coordinates
- All coordinates stored in hidden fields
- Form submission sends to controller

### Controller Receives:
- `latitude` (decimal)
- `longitude` (decimal)
- `farm_boundaries` (JSON array of coordinates)
- `address` (text)

## Default Behavior

### If No Location Set:
- Map shows Philippines overview (zoom level 6)
- Center: Manila (14.5995, 120.9842)
- Message: "Click 'Set Farm Location' to mark your farm"

### If Location Set (No Boundaries):
- Map shows green marker at farm location
- Zoom level 15 (close-up view)
- Crop markers appear around farm marker

### If Boundaries Drawn:
- Map shows green polygon of farm area
- Automatically zooms to fit boundaries
- Crop markers appear inside farm area
- Click polygon to see farm details

## Security

- Farmers can only view/edit their own location
- Coordinates validated server-side
- Latitude: -90 to 90
- Longitude: -180 to 180
- Authentication required for updates

## Mobile Support

Works perfectly on mobile devices:
- Touch to click map
- GPS auto-detection
- Pinch to zoom
- Touch to draw boundaries
- Responsive layout

## Summary

**Farmers never need to know what latitude/longitude means!** They just:
1. Click a button to use GPS, OR
2. Click on the map where their farm is, OR
3. Draw their farm boundaries

The system handles all the technical coordinate stuff automatically. 🎉
