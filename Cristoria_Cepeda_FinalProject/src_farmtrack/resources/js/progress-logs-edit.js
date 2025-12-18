document.addEventListener('DOMContentLoaded', function(){
  const cropSel = document.getElementById('crop_id');
  const dateInp = document.getElementById('log_date');
  const activitySel = document.getElementById('activity_type');
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

  // Activity type to growth stage mapping (must match create.js)
  const activityToGrowthStage = {
    'land_preparation': 1,
    'seed_preparation_nursery': 2,
    'transplanting_seeding': 3,
    'watering_irrigation': 4,
    'fertilization': 5,
    'weeding': 6,
    'pest_disease_management': 7,
    'panicle_flowering_care': 8,
    'preharvest_drainage': 9,
    'harvesting': 10,
  };

  function hide(el) { if (el) { el.classList.add('hidden'); el.textContent = ''; } }
  function show(el, msg) { if (el) { el.textContent = msg; el.classList.remove('hidden'); } }

  const dateHint = document.getElementById('dateHint');

  function setDateHints(){
    hide(dateHint);
    const opt = cropSel?.selectedOptions?.[0];
    if (!opt) return;
    const planting = opt.getAttribute('data-planting');
    const actual = opt.getAttribute('data-actual');
    // No min/max set to avoid blocking calendar selection
    dateInp?.removeAttribute('min');
    dateInp?.removeAttribute('max');
    if (dateInp?.value){
      if (planting && dateInp.value < planting){
        show(dateHint, 'Activity date cannot be before the crop planting date.');
      } else if (actual && dateInp.value > actual){
        show(dateHint, 'Activity date cannot be after the crop harvest date.');
      }
    }
  }

  // Validate description - letters only, no numbers or special characters
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

    // Skip validation if empty (for blur validation)
    if (len === 0) {
      desc.classList.remove('border-red-500');
      return true;
    }

    // Check for numbers
    if (/\d/.test(value)) {
      show(descHint, 'Numbers are not allowed in description');
      desc.classList.add('border-red-500');
      return false;
    }

    // Check for special characters (allow letters, spaces, commas, periods, hyphens)
    if (/[^A-Za-z\s,.\-]/.test(value)) {
      show(descHint, 'Special characters (except ,.-) are not allowed in description');
      desc.classList.add('border-red-500');
      return false;
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
    const pos = desc.selectionStart;
    const original = desc.value;

    // Remove numbers
    let cleaned = original.replace(/\d/g, '');
    // Remove special characters except allowed ones
    cleaned = cleaned.replace(/[^A-Za-z\s,.\-]/g, '');

    desc.value = cleaned;

    // Adjust cursor position
    const diff = original.length - cleaned.length;
    desc.setSelectionRange(pos - diff, pos - diff);

    validateDesc();
  });

  desc?.addEventListener('blur', validateDesc);

  // Validate observations - letters only, no numbers or special characters
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

    // Skip validation if empty (for blur validation)
    if (len === 0) {
      obs.classList.remove('border-red-500');
      return true;
    }

    // Check for numbers
    if (/\d/.test(value)) {
      show(obsHint, 'Numbers are not allowed in observations');
      obs.classList.add('border-red-500');
      return false;
    }

    // Check for special characters (allow letters, spaces, commas, periods, hyphens)
    if (/[^A-Za-z\s,.\-]/.test(value)) {
      show(obsHint, 'Special characters (except ,.-) are not allowed in observations');
      obs.classList.add('border-red-500');
      return false;
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
    const pos = obs.selectionStart;
    const original = obs.value;

    // Remove numbers
    let cleaned = original.replace(/\d/g, '');
    // Remove special characters except allowed ones
    cleaned = cleaned.replace(/[^A-Za-z\s,.\-]/g, '');

    obs.value = cleaned;

    // Adjust cursor position
    const diff = original.length - cleaned.length;
    obs.setSelectionRange(pos - diff, pos - diff);

    validateObs();
  });

  obs?.addEventListener('blur', validateObs);

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
      hide(costError);
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

  // Update growth stage when activity type changes
  function updateGrowthStage() {
    const selectedActivity = activitySel?.value;
    if (selectedActivity && activityToGrowthStage[selectedActivity]) {
      if (stage) {
        stage.value = activityToGrowthStage[selectedActivity];
      }
    }
  }

  // Initialize growth stage on page load
  updateGrowthStage();

  cropSel?.addEventListener('change', validateCrop);
  cropSel?.addEventListener('blur', validateCrop);
  activitySel?.addEventListener('blur', validateActivity);
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

    // Validate cost
    if (!cost || !cost.value || cost.value.trim() === '') {
      show(costError, 'This input field is required');
      cost?.classList.add('border-red-500');
      isValid = false;
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

    if (!isValid) {
      e.preventDefault();
      // Scroll to first error
      const firstError = document.querySelector('.border-red-500');
      if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  });

  // init
  setDateHints();
  validateDesc();
  validateObs();
});
