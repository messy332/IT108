document.addEventListener('DOMContentLoaded', function () {
  const cropSearch = document.getElementById('crop_search');
  const cropSel = document.getElementById('crop_id');
  const activitySel = document.getElementById('activity_type');
  const logDate = document.getElementById('log_date');
  const desc = document.getElementById('description');
  const cost = document.getElementById('cost');
  const stage = document.getElementById('growth_stage');
  const obs = document.getElementById('observations');

  const descHint = document.getElementById('descHint');
  const obsHint = document.getElementById('obsHint');
  const descCount = document.getElementById('descCount');
  const obsCount = document.getElementById('obsCount');
  const cropError = document.getElementById('cropError');
  const activityError = document.getElementById('activityError');
  const costError = document.getElementById('costError');
  const weatherError = document.getElementById('weatherError');
  const descError = document.getElementById('descError');
  const obsError = document.getElementById('obsError');
  // Add error element for date if it doesn't exist, or use a generic way to show error
  let dateError = document.querySelector('#log_date + p.text-red-500');
  if (!dateError) {
    dateError = document.createElement('p');
    dateError.className = 'text-red-500 text-sm mt-1 hidden';
    if (logDate && logDate.parentNode) {
      logDate.parentNode.appendChild(dateError);
    }
  }

  function hide(el) { if (el) { el.classList.add('hidden'); el.textContent = ''; } }
  function show(el, msg) { if (el) { el.textContent = msg; el.classList.remove('hidden'); } }

  // Parse existing logs from data attribute
  let existingLogs = {};
  if (cropSel && cropSel.getAttribute('data-existing-logs')) {
    try {
      existingLogs = JSON.parse(cropSel.getAttribute('data-existing-logs'));
    } catch (e) {
      existingLogs = {};
    }
  }

  // Crop search functionality
  if (cropSearch && cropSel) {
    cropSearch.addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase().trim();
      const options = cropSel.querySelectorAll('option');

      options.forEach(option => {
        if (option.value === '') {
          return;
        }

        const cropName = option.getAttribute('data-crop-name') || '';
        const variety = option.getAttribute('data-variety') || '';
        const farmer = option.getAttribute('data-farmer') || '';

        if (searchTerm === '' || cropName.includes(searchTerm) || variety.includes(searchTerm) || farmer.includes(searchTerm)) {
          option.style.display = '';
        } else {
          option.style.display = 'none';
        }
      });
    });

    // Update search input when selection changes
    cropSel.addEventListener('change', function () {
      if (this.value) {
        const selectedOption = this.options[this.selectedIndex];
        const cropName = selectedOption.getAttribute('data-crop-name') || '';
        const variety = selectedOption.getAttribute('data-variety') || '';
        cropSearch.value = cropName + ' (' + variety + ')';

        // Disable activity types that have already been logged for this crop
        updateActivityOptions();
      } else {
        cropSearch.value = '';
        // Enable all activity options if no crop is selected
        enableAllActivityOptions();
      }
    });
  }

  // Function to disable activity types that have been logged
  function updateActivityOptions() {
    if (!activitySel) return;

    const selectedCropId = cropSel?.value;
    const usedActivities = existingLogs[selectedCropId] || [];

    const options = activitySel.querySelectorAll('option');
    options.forEach(option => {
      if (option.value === '') {
        option.disabled = false;
        return;
      }

      if (usedActivities.includes(option.value)) {
        option.disabled = true;
        option.textContent = option.textContent.replace(' (Already logged)', '') + ' (Already logged)';
      } else {
        option.disabled = false;
        option.textContent = option.textContent.replace(' (Already logged)', '');
      }
    });
  }

  // Function to enable all activity options
  function enableAllActivityOptions() {
    if (!activitySel) return;

    const options = activitySel.querySelectorAll('option');
    options.forEach(option => {
      option.disabled = false;
      option.textContent = option.textContent.replace(' (Already logged)', '');
    });
  }

  // Activity type to growth stage mapping (1-10 scale)
  const activityToGrowthStage = {
    'land_preparation': 1,           // Land Preparation - Stage 1
    'seed_preparation_nursery': 2,   // Seed Preparation & Nursery - Stage 2
    'transplanting_seeding': 3,      // Transplanting/Seeding - Stage 3
    'watering_irrigation': 4,        // Watering/Irrigation - Stage 4 (vegetative)
    'fertilization': 5,              // Fertilization - Stage 5 (vegetative)
    'weeding': 6,                    // Weeding - Stage 6 (tillering)
    'pest_disease_management': 7,    // Pest & Disease - Stage 7 (reproductive)
    'panicle_flowering_care': 8,     // Panicle/Flowering - Stage 8 (flowering)
    'preharvest_drainage': 9,        // Pre-Harvest Drainage - Stage 9 (maturation)
    'harvesting': 10                 // Harvesting - Stage 10 (ready)
  };

  // Auto-calculate growth stage based on activity type
  function updateGrowthStage() {
    const selectedActivity = activitySel?.value;
    if (selectedActivity && activityToGrowthStage[selectedActivity]) {
      if (stage) {
        stage.value = activityToGrowthStage[selectedActivity];
      }
    }
  }

  // Validate description
  function validateDesc(checkRequired = false) {
    hide(descHint);
    hide(descError);
    if (!desc) return true;

    const value = desc.value || '';
    const len = value.length;
    if (descCount) descCount.textContent = len;

    // Check if required and empty
    if (checkRequired && len === 0) {
      show(descError, 'This input field is required');
      desc.classList.add('border-red-500');
      return false;
    }

    // If empty, that's okay for blur validation
    if (len === 0) {
      desc.classList.remove('border-red-500');
      return true;
    }

    // Ensure first character is uppercase
    if (value.length > 0 && value[0] !== value[0].toUpperCase()) {
      show(descHint, 'Description must start with a capital letter');
      desc.classList.add('border-red-500');
      return false;
    }

    if (len > 1000) {
      show(descHint, 'Description cannot exceed 1,000 characters.');
      desc.value = desc.value.slice(0, 1000);
      if (descCount) descCount.textContent = 1000;
    }

    desc.classList.remove('border-red-500');
    return true;
  }

  // Sanitize description input
  desc?.addEventListener('input', () => {
    validateDesc();
  });

  // Validate observations
  function validateObs(checkRequired = false) {
    hide(obsHint);
    hide(obsError);
    if (!obs) return true;

    const value = obs.value || '';
    const len = value.length;
    if (obsCount) obsCount.textContent = len;

    // Check if required and empty
    if (checkRequired && len === 0) {
      show(obsError, 'This input field is required');
      obs.classList.add('border-red-500');
      return false;
    }

    // If empty, that's okay for blur validation
    if (len === 0) {
      obs.classList.remove('border-red-500');
      return true;
    }

    // Ensure first character is uppercase
    if (value.length > 0 && value[0] !== value[0].toUpperCase()) {
      show(obsHint, 'Observations must start with a capital letter');
      obs.classList.add('border-red-500');
      return false;
    }

    if (len > 1000) {
      show(obsHint, 'Observations cannot exceed 1,000 characters.');
      obs.value = obs.value.slice(0, 1000);
      if (obsCount) obsCount.textContent = 1000;
    }

    obs.classList.remove('border-red-500');
    return true;
  }

  // Sanitize observations input
  obs?.addEventListener('input', () => {
    validateObs();
  });

  // Validate cost - numbers and decimal point only
  function sanitizeCost(v) {
    // Only allow digits and decimal point
    v = v.replace(/[^\d.]/g, '');

    // Ensure only one decimal point
    const parts = v.split('.');
    if (parts.length > 2) {
      v = parts[0] + '.' + parts.slice(1).join('');
    }

    // Limit to 8 digits before decimal and 2 digits after
    v = v.replace(/^(\d{0,8})(?:\.(\d{0,2})?)?.*$/, (_, a, b) => b !== undefined ? (a + '.' + b) : a);
    return v;
  }

  function validateCost() {
    if (!cost) return;

    if (!cost.value || cost.value.trim() === '') {
      show(costError, 'This input field is required');
      cost.classList.add('border-red-500');
      return;
    }

    const value = parseFloat(cost.value);
    if (isNaN(value) || value < 0) {
      show(costError, 'Please enter a valid positive number');
      cost.classList.add('border-red-500');
    } else {
      hide(costError);
      cost.classList.remove('border-red-500');
    }
  }

  cost?.addEventListener('input', () => {
    const pos = cost.selectionStart;
    const original = cost.value;
    const sanitized = sanitizeCost(original);
    cost.value = sanitized;

    // Adjust cursor position
    const diff = original.length - sanitized.length;
    cost.setSelectionRange(pos - diff, pos - diff);

    validateCost();
  });

  cost?.addEventListener('blur', validateCost);
  cost?.addEventListener('change', validateCost);

  // Update growth stage when activity type changes
  activitySel?.addEventListener('change', function () {
    updateGrowthStage();
    validateActivity();
  });

  function validateActivity() {
    if (!activitySel) return;

    if (!activitySel.value || activitySel.value.trim() === '') {
      show(activityError, 'This input field is required');
      activitySel.classList.add('border-red-500');
    } else {
      hide(activityError);
      activitySel.classList.remove('border-red-500');
    }
  }

  function validateCrop() {
    if (!cropSel) return;

    if (!cropSel.value || cropSel.value.trim() === '') {
      show(cropError, 'This input field is required');
      cropSel.classList.add('border-red-500');
    } else {
      hide(cropError);
      cropSel.classList.remove('border-red-500');
    }
  }

  function validateWeather() {
    if (!document.getElementById('weather_condition')) return;

    const weatherSel = document.getElementById('weather_condition');
    if (!weatherSel) return;

    if (!weatherSel.value || weatherSel.value.trim() === '') {
      show(weatherError, 'This input field is required');
      weatherSel.classList.add('border-red-500');
    } else {
      hide(weatherError);
      weatherSel.classList.remove('border-red-500');
    }
  }

  function validateDate() {
    if (!logDate) return;
    if (!logDate.value) {
      show(dateError, 'Activity date is required');
      logDate.classList.add('border-red-500');
    } else {
      hide(dateError);
      logDate.classList.remove('border-red-500');
    }
  }

  cropSel?.addEventListener('change', validateCrop);
  cropSel?.addEventListener('blur', validateCrop);
  activitySel?.addEventListener('blur', validateActivity);
  logDate?.addEventListener('change', validateDate);
  logDate?.addEventListener('blur', validateDate);
  document.getElementById('weather_condition')?.addEventListener('change', validateWeather);
  document.getElementById('weather_condition')?.addEventListener('blur', validateWeather);

  // Form submission validation
  const form = document.querySelector('form');
  form?.addEventListener('submit', function (e) {
    let isValid = true;

    // Validate crop
    if (!cropSel || !cropSel.value) {
      show(cropError, 'This input field is required');
      cropSel?.classList.add('border-red-500');
      isValid = false;
    }

    // Validate activity type
    if (!activitySel || !activitySel.value) {
      show(activityError, 'This input field is required');
      activitySel?.classList.add('border-red-500');
      isValid = false;
    }

    // Validate date
    if (!logDate || !logDate.value) {
      show(dateError, 'Activity date is required');
      logDate?.classList.add('border-red-500');
      isValid = false;
    }

    // Validate cost
    if (!cost || !cost.value || cost.value.trim() === '') {
      show(costError, 'This input field is required');
      cost?.classList.add('border-red-500');
      isValid = false;
    } else {
      // Also check if cost is valid number
      const val = parseFloat(cost.value);
      if (isNaN(val) || val < 0) {
        show(costError, 'Please enter a valid positive number');
        cost.classList.add('border-red-500');
        isValid = false;
      }
    }

    // Validate weather
    const weatherSel = document.getElementById('weather_condition');
    if (!weatherSel || !weatherSel.value) {
      show(weatherError, 'This input field is required');
      weatherSel?.classList.add('border-red-500');
      isValid = false;
    }

    // Validate description (required)
    if (!validateDesc(true)) {
      isValid = false;
    }

    // Validate observations (required)
    if (!validateObs(true)) {
      isValid = false;
    }

    // Check for any visible error messages in description or observations
    if (descHint && !descHint.classList.contains('hidden')) {
      isValid = false;
      desc.classList.add('border-red-500');
    }
    if (obsHint && !obsHint.classList.contains('hidden')) {
      isValid = false;
      obs.classList.add('border-red-500');
    }

    if (!isValid) {
      e.preventDefault();
      // Scroll to first error
      const firstError = document.querySelector('.border-red-500');
      if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  });

  // Initialize
  updateGrowthStage();
  validateDesc();
  validateObs();
});
