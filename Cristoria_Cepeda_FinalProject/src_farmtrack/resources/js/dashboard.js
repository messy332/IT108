// Dashboard Search Functionality
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('dashboardSearch');
  const searchResults = document.getElementById('searchResults');

  if (searchInput) {
    let searchTimeout;

    searchInput.addEventListener('input', function () {
      clearTimeout(searchTimeout);
      const query = this.value.trim();

      if (query.length === 0) {
        searchResults.classList.add('hidden');
        searchResults.innerHTML = '';
        return;
      }

      if (query.length < 2) {
        return;
      }

      // Debounce search
      searchTimeout = setTimeout(() => {
        performSearch(query);
      }, 300);
    });

    // Close search results when clicking outside
    document.addEventListener('click', function (e) {
      if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
      }
    });
  }

  function performSearch(query) {
    const lowerQuery = query.toLowerCase();
    const results = [];

    // Search through visible card content
    const cards = document.querySelectorAll('.bg-white.rounded-lg');
    cards.forEach(card => {
      const text = card.textContent.toLowerCase();
      if (text.includes(lowerQuery)) {
        const title = card.querySelector('h2, .font-semibold, .font-medium');
        if (title) {
          results.push({
            type: 'card',
            text: title.textContent.trim(),
            element: card
          });
        }
      }
    });

    displaySearchResults(results, query);
  }

  function displaySearchResults(results, query) {
    if (results.length === 0) {
      searchResults.innerHTML = `
        <div class="p-4 text-center text-gray-500">
          <p class="text-sm">No results found for "${query}"</p>
        </div>
      `;
      searchResults.classList.remove('hidden');
      return;
    }

    const uniqueResults = results.slice(0, 10); // Limit to 10 results
    const html = uniqueResults.map(result => `
      <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0" 
           onclick="document.getElementById('dashboardSearch').value=''; document.getElementById('searchResults').classList.add('hidden'); this.scrollIntoView({behavior: 'smooth', block: 'center'});">
        <p class="text-sm font-medium text-gray-900">${highlightText(result.text, query)}</p>
        <p class="text-xs text-gray-500">${result.type}</p>
      </div>
    `).join('');

    searchResults.innerHTML = html;
    searchResults.classList.remove('hidden');
  }

  function highlightText(text, query) {
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
  }
});

(async function () {
  async function loadScript(src) {
    return new Promise((resolve, reject) => {
      const s = document.createElement('script');
      s.src = src; s.async = true;
      s.onload = resolve; s.onerror = reject;
      document.head.appendChild(s);
    });
  }
  if (typeof window.Chart === 'undefined') {
    try {
      await loadScript('https://cdn.jsdelivr.net/npm/chart.js');
    } catch (e) {
      console.error('Failed to load Chart.js', e);
      return;
    }
  }
  const statsEl = document.getElementById('dashboard-stats-json');
  // Fallback: read globals injected via a script tag if present
  let stats = window.__DASHBOARD_STATS__ || {};
  let recentMonths = window.__DASHBOARD_RECENT_MONTHS__ || [];
  let genderStats = window.__GENDER_STATS__ || { female: 0, male: 0 };
  let activityStats = window.__ACTIVITY_STATS__ || {};
  let cropHectares = window.__CROP_HECTARES__ || [];
  let topRiceVarieties = window.__TOP_RICE_VARIETIES__ || [];

  if (statsEl) {
    try {
      const payload = JSON.parse(statsEl.textContent || '{}');
      stats = payload.stats || stats;
      recentMonths = payload.recentMonths || recentMonths;
      genderStats = payload.genderStats || genderStats;
      activityStats = payload.activityStats || activityStats;
      cropHectares = payload.cropHectares || cropHectares;
      topRiceVarieties = payload.topRiceVarieties || topRiceVarieties;
    } catch (_) { }
  }

  function lastNMonthLabels(n) {
    const labels = [];
    const d = new Date();
    d.setUTCDate(1);
    for (let i = n - 1; i >= 0; i--) {
      const temp = new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth() - i, 1));
      labels.push(`${temp.getUTCFullYear()}-${String(temp.getUTCMonth() + 1).padStart(2, '0')}`);
    }
    return labels;
  }

  // ORIGINAL CHARTS
  // Area: count recent logs per month (last 6 months)
  const areaLabels = lastNMonthLabels(6);
  let areaData = areaLabels.map(m => recentMonths.filter(x => x === m).length);
  if (!areaData.some(v => v > 0)) areaData = [2, 3, 1, 4, 5, 2];

  const barLabels = ['Farmers', 'Active Crops', 'Total Logs', 'Farm Area (ha)'];
  let barData = [
    Number(stats.total_farmers || 0),
    Number(stats.active_crops || 0),
    Number(stats.total_logs || 0),
    Number(stats.total_farm_area || 0)
  ];
  if (!barData.some(v => v > 0)) barData = [24, 56, 142, 38.5];

  const pieLabels = ['Farmers', 'Crops', 'Logs'];
  let pieData = [
    Number(stats.total_farmers || 0),
    Number(stats.active_crops || 0),
    Number(stats.total_logs || 0)
  ];
  if (!pieData.some(v => v > 0)) pieData = [24, 56, 142];

  const areaCtx = document.getElementById('areaChart');
  if (areaCtx) {
    new Chart(areaCtx, {
      type: 'line',
      data: {
        labels: areaLabels,
        datasets: [{
          label: 'Logs',
          data: areaData,
          fill: true,
          borderColor: 'rgb(34,197,94)',
          backgroundColor: 'rgba(34,197,94,0.15)',
          tension: 0.3,
        }]
      },
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
  }

  const barCtx = document.getElementById('barChart');
  if (barCtx) {
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: barLabels,
        datasets: [{ label: 'Count', data: barData, backgroundColor: ['#22c55e', '#06b6d4', '#a855f7', '#f59e0b'] }]
      },
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
  }

  const pieCtx = document.getElementById('pieChart');
  if (pieCtx) {
    new Chart(pieCtx, {
      type: 'pie',
      data: { labels: pieLabels, datasets: [{ data: pieData, backgroundColor: ['#22c55e', '#06b6d4', '#a855f7'] }] },
      options: { plugins: { legend: { position: 'bottom' } } }
    });
  }

  // NEW CHARTS
  // Gender Distribution Donut Chart
  const genderPieCtx = document.getElementById('genderPieChart');
  if (genderPieCtx) {
    const femaleCount = Number(genderStats.female || 0);
    const maleCount = Number(genderStats.male || 0);

    // Use sample data if no real data
    let genderPieData = [femaleCount, maleCount];
    if (femaleCount === 0 && maleCount === 0) {
      genderPieData = [12, 18]; // Sample data
    }

    new Chart(genderPieCtx, {
      type: 'doughnut',
      data: {
        labels: ['Female', 'Male'],
        datasets: [{
          data: genderPieData,
          backgroundColor: ['#ec4899', '#3b82f6'], // Pink for female, blue for male
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 15,
              font: {
                size: 12
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                const label = context.label || '';
                const value = context.parsed || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                return `${label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  }

  // Activity Type Distribution Pie Chart
  const activityPieCtx = document.getElementById('activityPieChart');
  if (activityPieCtx) {
    let activityLabels = [];
    let activityData = [];
    
    if (activityStats && Object.keys(activityStats).length > 0) {
      // Extract labels and data from activityStats
      Object.values(activityStats).forEach(item => {
        activityLabels.push(item.label);
        activityData.push(Number(item.count));
      });
    } else {
      // Sample data
      activityLabels = ['Land Preparation', 'Watering', 'Fertilization', 'Weeding', 'Harvesting'];
      activityData = [150, 280, 220, 180, 120];
    }

    // Color palette for activities
    const activityColors = [
      '#10b981', // Emerald - Land Preparation
      '#06b6d4', // Cyan - Seed Preparation
      '#3b82f6', // Blue - Transplanting
      '#8b5cf6', // Violet - Watering
      '#f59e0b', // Amber - Fertilization
      '#ef4444', // Red - Weeding
      '#ec4899', // Pink - Pest Management
      '#14b8a6', // Teal - Flowering Care
      '#f97316', // Orange - Pre-Harvest
      '#22c55e', // Green - Harvesting
    ];

    new Chart(activityPieCtx, {
      type: 'doughnut',
      data: {
        labels: activityLabels,
        datasets: [{
          data: activityData,
          backgroundColor: activityColors.slice(0, activityLabels.length),
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 10,
              font: {
                size: 10
              },
              boxWidth: 12
            }
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                const label = context.label || '';
                const value = context.parsed || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                return `${label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  }

  // Crop Hectares Bar Chart
  const cropBarCtx = document.getElementById('cropHectaresBarChart');
  if (cropBarCtx) {
    let cropLabels = [];
    let cropData = [];

    if (cropHectares && cropHectares.length > 0) {
      // Limit to top 10 varieties
      const topCrops = cropHectares.slice(0, 10);
      cropLabels = topCrops.map(item => item.variety);
      cropData = topCrops.map(item => Number(item.hectares));
    } else {
      // Sample data
      cropLabels = ['Sinandomeng', 'Dinorado', 'Jasmine', 'Regular Rice', 'Malagkit White'];
      cropData = [15.5, 12.3, 8.7, 6.2, 4.1];
    }

    // Generate different colors for each variety
    const varietyColors = [
      '#10b981', // Green
      '#3b82f6', // Blue
      '#f59e0b', // Amber
      '#ef4444', // Red
      '#8b5cf6', // Violet
      '#ec4899', // Pink
      '#14b8a6', // Teal
      '#f97316', // Orange
      '#06b6d4', // Cyan
      '#a855f7', // Purple
    ];

    // Create color array matching the number of varieties
    const backgroundColors = cropLabels.map((_, index) => varietyColors[index % varietyColors.length]);
    const borderColors = backgroundColors.map(color => {
      // Darken the color slightly for border
      return color.replace(/^#/, '#').replace(/../g, m => ('0' + Math.max(0, parseInt(m, 16) - 20).toString(16)).slice(-2));
    });

    new Chart(cropBarCtx, {
      type: 'bar',
      data: {
        labels: cropLabels,
        datasets: [{
          label: 'Hectares',
          data: cropData,
          backgroundColor: backgroundColors,
          borderColor: borderColors,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return `${context.parsed.y.toFixed(2)} hectares`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 1,
              callback: function (value) {
                return value + ' ha';
              }
            },
            title: {
              display: true,
              text: 'Hectares (ha)'
            }
          },
          x: {
            ticks: {
              font: {
                size: 10
              },
              maxRotation: 45,
              minRotation: 45
            }
          }
        }
      }
    });
  }

  // Top 10 Most Planted Rice Varieties Bar Chart
  const topRiceBarCtx = document.getElementById('topRiceVarietiesBarChart');
  if (topRiceBarCtx) {
    let riceLabels = [];
    let riceData = [];

    if (topRiceVarieties && topRiceVarieties.length > 0) {
      riceLabels = topRiceVarieties.map(item => item.variety);
      riceData = topRiceVarieties.map(item => Number(item.count));
    } else {
      // Sample data
      riceLabels = ['Sinandomeng', 'Dinorado', 'Jasmine', 'Regular Rice', 'Malagkit White', 'Princess Bea', 'Harvester', 'Angelica', 'Tinawon White', 'Unoy'];
      riceData = [450, 380, 320, 290, 250, 210, 180, 150, 120, 100];
    }

    // Generate gradient colors from green to blue
    const riceColors = [
      '#059669', // Emerald 600
      '#10b981', // Emerald 500
      '#34d399', // Emerald 400
      '#0891b2', // Cyan 600
      '#06b6d4', // Cyan 500
      '#22d3ee', // Cyan 400
      '#0284c7', // Sky 600
      '#0ea5e9', // Sky 500
      '#38bdf8', // Sky 400
      '#3b82f6', // Blue 500
    ];

    const backgroundColors = riceLabels.map((_, index) => riceColors[index % riceColors.length]);

    new Chart(topRiceBarCtx, {
      type: 'bar',
      data: {
        labels: riceLabels,
        datasets: [{
          label: 'Number of Plantings',
          data: riceData,
          backgroundColor: backgroundColors,
          borderColor: backgroundColors.map(color => color),
          borderWidth: 1,
          borderRadius: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                const variety = topRiceVarieties[context.dataIndex];
                if (variety && variety.hectares) {
                  return [
                    `Plantings: ${context.parsed.y}`,
                    `Total Area: ${variety.hectares} ha`
                  ];
                }
                return `Plantings: ${context.parsed.y}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0,
              callback: function (value) {
                return value;
              }
            },
            title: {
              display: true,
              text: 'Number of Plantings',
              font: {
                size: 12,
                weight: 'bold'
              }
            }
          },
          x: {
            ticks: {
              font: {
                size: 10
              },
              maxRotation: 45,
              minRotation: 45
            },
            title: {
              display: true,
              text: 'Rice Variety',
              font: {
                size: 12,
                weight: 'bold'
              }
            }
          }
        }
      }
    });
  }
})();
