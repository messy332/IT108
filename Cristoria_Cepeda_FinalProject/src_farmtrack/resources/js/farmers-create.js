document.addEventListener('DOMContentLoaded', function () {
  const birthdateInput = document.getElementById('birthdate');
  const ageInput = document.getElementById('age');
  const ageHint = document.getElementById('ageHint');
  const birthdateHint = document.getElementById('birthdateHint');
  const nameInput = document.getElementById('name');
  const addressInput = document.getElementById('address');
  const phoneInput = document.getElementById('phone');
  const farmSizeInput = document.getElementById('farm_size');
  const form = document.querySelector('form');

  // Helper function to create/update error message
  function showError(inputElement, message) {
    let errorEl = inputElement.parentElement.querySelector('.validation-error');
    if (!errorEl) {
      errorEl = document.createElement('p');
      errorEl.className = 'validation-error text-red-500 text-sm mt-1';
      inputElement.parentElement.appendChild(errorEl);
    }
    errorEl.textContent = message;
    inputElement.classList.add('border-red-500');
  }

  function clearError(inputElement) {
    const errorEl = inputElement.parentElement.querySelector('.validation-error');
    if (errorEl) {
      errorEl.remove();
    }
    inputElement.classList.remove('border-red-500');
  }

  function calcAge(dateStr) {
    if (!dateStr) return '';
    const dob = new Date(dateStr);
    if (isNaN(dob.getTime())) return '';
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
    return age;
  }

  let ageIsValid = true;

  function updateAge() {
    const a = calcAge(birthdateInput.value);
    if (a !== '' && a >= 0) ageInput.value = a;
    // Reset hints
    birthdateHint?.classList.add('hidden');
    if (birthdateHint) birthdateHint.textContent = '';
    birthdateInput?.classList.remove('border-red-500');
    ageIsValid = true;

    // Age range hints - show on birthdate field
    if (a !== '' && (a < 18 || a > 75)) {
      if (a < 18 && birthdateHint) birthdateHint.textContent = 'Must be at least 18 years old.';
      else if (a > 75 && birthdateHint) birthdateHint.textContent = 'Age cannot exceed 75 years old.';
      birthdateHint?.classList.remove('hidden');
      birthdateInput?.classList.add('border-red-500');
      ageIsValid = false;
    }
  }
  birthdateInput?.addEventListener('change', updateAge);
  birthdateInput?.addEventListener('input', updateAge);
  updateAge();

  // Prevent form submission if age is invalid
  form?.addEventListener('submit', function(e) {
    updateAge(); // Re-validate
    if (!ageIsValid) {
      e.preventDefault();
      birthdateInput?.focus();
      return false;
    }
  });

  // Name validation and formatter
  function formatName(v){
    v = v.replace(/[^A-Za-z ]+/g, '');
    v = v.replace(/\s+/g, ' ').trim();
    v = v.split(' ').filter(Boolean).map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
    return v;
  }
  
  function validateName() {
    const value = nameInput.value.trim();
    if (!value) {
      clearError(nameInput);
      return;
    }
    
    // Check if starts with capital letter
    if (!/^[A-Z]/.test(value)) {
      showError(nameInput, 'Name must start with a capital letter');
      return;
    }
    
    // Check for numbers
    if (/\d/.test(value)) {
      showError(nameInput, 'Numbers are not allowed in name');
      return;
    }
    
    // Check for special characters
    if (/[^A-Za-z ]/.test(value)) {
      showError(nameInput, 'Special characters are not allowed in name');
      return;
    }
    
    clearError(nameInput);
  }

  nameInput?.addEventListener('input', () => {
    const pos = nameInput.selectionStart;
    const original = nameInput.value;
    const formatted = formatName(original);
    nameInput.value = formatted;
    
    // Adjust cursor position if text was removed
    const diff = original.length - formatted.length;
    nameInput.setSelectionRange(pos - diff, pos - diff);
    
    validateName();
  });
  
  nameInput?.addEventListener('blur', validateName);

  // Address validation
  function formatAddress(v) {
    // Remove leading/trailing spaces and normalize internal spaces
    v = v.replace(/\s+/g, ' ').trim();
    // Capitalize first letter if there's text
    if (v.length > 0) {
      v = v.charAt(0).toUpperCase() + v.slice(1);
    }
    return v;
  }

  function validateAddress() {
    const value = addressInput.value.trim();
    if (!value) {
      clearError(addressInput);
      return;
    }
    
    // Check if starts with capital letter
    if (!/^[A-Z]/.test(value)) {
      showError(addressInput, 'Address must start with a capital letter');
      return;
    }
    
    // Check for numbers
    if (/\d/.test(value)) {
      showError(addressInput, 'Numbers are not allowed in address');
      return;
    }
    
    // Check for special characters (allow spaces, commas, periods, hyphens)
    if (/[^A-Za-z\s,.\-]/.test(value)) {
      showError(addressInput, 'Special characters (except ,.-) are not allowed');
      return;
    }
    
    clearError(addressInput);
  }

  addressInput?.addEventListener('input', () => {
    const pos = addressInput.selectionStart;
    const original = addressInput.value;
    
    // Remove special characters except allowed ones
    let cleaned = original.replace(/[^A-Za-z\s,.\-]/g, '');
    
    addressInput.value = cleaned;
    
    // Adjust cursor position
    const diff = original.length - cleaned.length;
    addressInput.setSelectionRange(pos - diff, pos - diff);
    
    validateAddress();
  });

  addressInput?.addEventListener('blur', () => {
    addressInput.value = formatAddress(addressInput.value);
    validateAddress();
  });

  // Phone validation - digits only
  function validatePhone() {
    const value = phoneInput.value;
    if (!value) {
      clearError(phoneInput);
      return;
    }
    
    // Check for letters
    if (/[a-zA-Z]/.test(value)) {
      showError(phoneInput, 'Letters are not allowed in phone number');
      return;
    }
    
    // Check for special characters
    if (/[^\d]/.test(value)) {
      showError(phoneInput, 'Special characters are not allowed in phone number');
      return;
    }
    
    // Check length
    if (value.length < 10) {
      showError(phoneInput, 'Phone number must be at least 10 digits');
      return;
    }
    
    clearError(phoneInput);
  }

  phoneInput?.addEventListener('input', () => {
    phoneInput.value = phoneInput.value.replace(/\D+/g, '').slice(0, 15);
    validatePhone();
  });
  
  phoneInput?.addEventListener('blur', validatePhone);

  // Farm size validation - numbers and decimal only
  function sanitizeFarmSize(v){
    // Remove everything except digits and decimal point
    v = v.replace(/[^\d.]/g, '');
    
    // Allow only one decimal point
    const parts = v.split('.');
    if (parts.length > 2) {
      v = parts[0] + '.' + parts.slice(1).join('');
    }
    
    // Limit to 6 digits before decimal and 2 after
    if (parts.length === 2) {
      v = parts[0].slice(0, 6) + '.' + parts[1].slice(0, 2);
    } else if (parts.length === 1) {
      v = parts[0].slice(0, 6);
    }
    
    return v;
  }
  
  function validateFarmSize() {
    const value = farmSizeInput.value;
    if (!value) {
      clearError(farmSizeInput);
      return;
    }
    
    // Check for letters
    if (/[a-zA-Z]/.test(value)) {
      showError(farmSizeInput, 'Letters are not allowed in farm size');
      return;
    }
    
    // Check for special characters (except decimal point)
    if (/[^\d.]/.test(value)) {
      showError(farmSizeInput, 'Special characters are not allowed in farm size');
      return;
    }
    
    // Check if it's a valid number
    const num = parseFloat(value);
    if (isNaN(num) || num <= 0) {
      showError(farmSizeInput, 'Farm size must be a positive number');
      return;
    }
    
    if (num > 10000) {
      showError(farmSizeInput, 'Farm size cannot exceed 10,000 hectares');
      return;
    }
    
    clearError(farmSizeInput);
  }

  farmSizeInput?.addEventListener('input', () => {
    const original = farmSizeInput.value;
    const sanitized = sanitizeFarmSize(original);
    farmSizeInput.value = sanitized;
    validateFarmSize();
  });
  
  farmSizeInput?.addEventListener('blur', validateFarmSize);
});
