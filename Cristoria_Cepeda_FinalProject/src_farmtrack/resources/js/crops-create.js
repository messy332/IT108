document.addEventListener('DOMContentLoaded', function () {
  const planting = document.getElementById('planting_date');
  const harvest = document.getElementById('expected_harvest_date');
  const plantingHint = document.getElementById('plantingHint');
  const variety = document.getElementById('variety');
  const cropName = document.getElementById('crop_name');
  const areaPlanted = document.getElementById('area_planted');
  const expectedYield = document.getElementById('expected_yield');
  const farmerSearch = document.getElementById('farmer_search');
  const farmerSelect = document.getElementById('farmer_id');
  const customVarietyContainer = document.getElementById('custom_variety_container');
  const customVariety = document.getElementById('custom_variety');

  // Farmer search functionality
  if (farmerSearch && farmerSelect) {
    farmerSearch.addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase().trim();
      const options = farmerSelect.querySelectorAll('option');
      
      options.forEach(option => {
        if (option.value === '') {
          return;
        }
        
        const farmerName = option.getAttribute('data-name') || '';
        const farmerEmail = option.getAttribute('data-email') || '';
        
        if (searchTerm === '' || farmerName.includes(searchTerm) || farmerEmail.includes(searchTerm)) {
          option.style.display = '';
        } else {
          option.style.display = 'none';
        }
      });
    });

    // Update search input when selection changes
    farmerSelect.addEventListener('change', function () {
      if (this.value) {
        const selectedOption = this.options[this.selectedIndex];
        const farmerName = selectedOption.text.split('(')[0].trim();
        farmerSearch.value = farmerName;
      } else {
        farmerSearch.value = '';
      }
    });
  }

  // Custom variety input toggle and validation
  if (variety) {
    variety.addEventListener('change', function () {
      if (this.value === 'others') {
        customVarietyContainer.classList.remove('hidden');
        customVariety.required = true;
      } else {
        customVarietyContainer.classList.add('hidden');
        customVariety.required = false;
        customVariety.value = '';
      }
    });

    // Check on page load if "others" is already selected
    if (variety.value === 'others') {
      customVarietyContainer.classList.remove('hidden');
      customVariety.required = true;
    }
  }

  // Custom variety validation - capital letter start, letters and spaces only, no double spaces
  if (customVariety) {
    customVariety.addEventListener('input', function () {
      let value = this.value;
      
      // Remove numbers and special characters, keep only letters and spaces
      value = value.replace(/[^a-zA-Z\s]/g, '');
      
      // Replace multiple spaces with single space
      value = value.replace(/\s+/g, ' ');
      
      // Ensure first character is capital
      if (value.length > 0) {
        value = value.charAt(0).toUpperCase() + value.slice(1);
      }
      
      this.value = value;
    });
  }

  // Use UTC-only dates to avoid timezone off-by-one issues
  const now = new Date();
  const todayUTC = new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate()));
  const todayStr = todayUTC.toISOString().slice(0, 10);

  // Rice variety harvest periods (days to maturity from transplanting)
  const riceVarietyPeriods = {
    // A. Premium / Aromatic Market Varieties (110-135 days)
    'Princess Bea': 122,
    'Dinorado': 125,
    'Super Dinorado': 128,
    'Angelica': 120,
    'Super Angelica': 123,
    'Milagrosa': 118,
    'Harvester': 115,
    'Sinandomeng': 112,
    'Sinandomeng Special': 115,
    'Hasmine': 120,
    'Jasmine': 125,

    // B. Regular Milled / Well-Milled Categories (105-130 days)
    'Regular Rice': 115,
    'Well-Milled Rice': 118,
    'Premium Rice': 120,
    'Blue Label': 118,
    'Red Label': 120,
    'Gold Label': 122,
    'Diamond Rice': 125,

    // C. Glutinous / Sticky Rice (Malagkit) (120-135 days)
    'Malagkit White': 127,
    'Malagkit Black': 130,
    'Ominio': 128,
    'Diket': 132,

    // D. Heirloom / Native Varieties (130-160 days)
    'Tinawon White': 145,
    'Tinawon Red': 145,
    'Tinawon Pink': 145,
    'Unoy': 140,
    'Unoy Red Rice': 142,
    'Balatinaw': 138,
    'Kintoman': 135
  };

  // Rice variety expected yield per hectare (kg/ha)
  const riceVarietyYields = {
    // A. Premium / Aromatic Market Varieties (medium yield, premium quality)
    'Princess Bea': 4500,
    'Dinorado': 4200,
    'Super Dinorado': 4300,
    'Angelica': 4400,
    'Super Angelica': 4500,
    'Milagrosa': 4600,
    'Harvester': 5000,
    'Sinandomeng': 4800,
    'Sinandomeng Special': 5000,
    'Hasmine': 4500,
    'Jasmine': 4300,

    // B. Regular Milled / Well-Milled Categories (higher yield)
    'Regular Rice': 5500,
    'Well-Milled Rice': 5800,
    'Premium Rice': 5600,
    'Blue Label': 5700,
    'Red Label': 5800,
    'Gold Label': 6000,
    'Diamond Rice': 6200,

    // C. Glutinous / Sticky Rice (Malagkit) (medium yield)
    'Malagkit White': 4000,
    'Malagkit Black': 3800,
    'Ominio': 3900,
    'Diket': 3500,

    // D. Heirloom / Native Varieties (lower yield, heirloom quality)
    'Tinawon White': 2500,
    'Tinawon Red': 2500,
    'Tinawon Pink': 2500,
    'Unoy': 2800,
    'Unoy Red Rice': 2700,
    'Balatinaw': 2600,
    'Kintoman': 3000
  };

  function parseDate(v) {
    if (!v) return null;
    const [y, m, d] = v.split('-').map(Number);
    const dt = new Date(Date.UTC(y, (m || 1) - 1, d || 1));
    return isNaN(dt.getTime()) ? null : dt;
  }

  function addDays(date, days) {
    const d = new Date(date.getTime());
    d.setUTCDate(d.getUTCDate() + days);
    return d;
  }

  function fmt(d) { return d.toISOString().slice(0, 10); }

  // Area planted validation - numbers and decimal only
  function sanitizeArea(v) {
    v = v.replace(/[^\d.]/g, '');
    const parts = v.split('.');
    if (parts.length > 2) {
      v = parts[0] + '.' + parts.slice(1).join('');
    }
    v = v.replace(/^(\d{0,6})(?:\.(\d{0,2})?)?.*$/, (m, a, b) => b !== undefined ? (a + '.' + b) : a);
    return v;
  }

  areaPlanted?.addEventListener('input', () => {
    const pos = areaPlanted.selectionStart;
    const original = areaPlanted.value;
    const sanitized = sanitizeArea(original);
    areaPlanted.value = sanitized;
    
    // Adjust cursor position
    const diff = original.length - sanitized.length;
    areaPlanted.setSelectionRange(pos - diff, pos - diff);
    
    calculateYield();
  });

  // Calculate expected harvest date based on planting date and variety
  function calculateHarvestDate() {
    if (plantingHint) { plantingHint.classList.add('hidden'); plantingHint.textContent = ''; }

    const p = parseDate(planting?.value);
    const selectedVariety = variety?.value;

    // Calculate harvest date if we have both planting date and variety
    if (p && selectedVariety && riceVarietyPeriods[selectedVariety]) {
      const daysToHarvest = riceVarietyPeriods[selectedVariety];
      const harvestDate = addDays(p, daysToHarvest);
      if (harvest) {
        harvest.value = fmt(harvestDate);
      }
    } else if (harvest) {
      harvest.value = '';
    }
  }

  // Calculate expected yield based on area planted and variety
  function calculateYield() {
    const area = parseFloat(areaPlanted?.value);
    const selectedVariety = variety?.value;

    if (area && area > 0 && selectedVariety && riceVarietyYields[selectedVariety]) {
      const yieldPerHectare = riceVarietyYields[selectedVariety];
      const totalYield = area * yieldPerHectare;
      if (expectedYield) {
        expectedYield.value = totalYield.toFixed(2);
      }
    } else if (expectedYield) {
      expectedYield.value = '';
    }
  }

  // Update calculations when inputs change
  planting?.addEventListener('change', calculateHarvestDate);
  planting?.addEventListener('input', calculateHarvestDate);

  variety?.addEventListener('change', function () {
    calculateHarvestDate();
    calculateYield();
  });

  areaPlanted?.addEventListener('blur', calculateYield);

  // Initial calculation on page load
  calculateHarvestDate();
  calculateYield();
});
