document.addEventListener('DOMContentLoaded', function () {
  const birthdateInput = document.getElementById('birthdate');
  const ageInput = document.getElementById('age');
  const nameInput = document.getElementById('name');
  const addressInput = document.getElementById('address');
  const phoneInput = document.getElementById('phone');
  const farmSizeInput = document.getElementById('farm_size');

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

  function updateAge() {
    const a = calcAge(birthdateInput.value);
    if (a !== '' && a >= 0) ageInput.value = a;

    // Future date validation
    if (birthdateInput.value) {
      const dob = new Date(birthdateInput.value);
      const today = new Date();
      if (dob > today) {
        showError(birthdateInput, 'Birth date must be in the past.');
        return;
      }
    }

    // Age range validation
    if (a !== '' && (a < 18 || a > 75)) {
      if (a < 18) {
        showError(birthdateInput, 'Age must be at least 18 years old.');
      } else if (a > 75) {
        showError(birthdateInput, 'Age cannot exceed 75 years.');
      }
      return;
    }

    clearError(birthdateInput);
  }

  birthdateInput?.addEventListener('change', updateAge);
  birthdateInput?.addEventListener('input', updateAge);
  updateAge();

  // Name validation and formatter
  function formatName(v) {
    v = v.replace(/[^A-Za-z ]+/g, '');
    v = v.replace(/\s+/g, ' ').trim();
    v = v.split(' ').filter(Boolean).map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
    return v;
  }

  function validateName() {
    const value = nameInput.value.trim();
    if (!value) {
      showError(nameInput, 'This input field is required');
      return;
    }

    if (!/^[A-Z]/.test(value)) {
      showError(nameInput, 'Name must start with a capital letter');
      return;
    }

    if (/\d/.test(value)) {
      showError(nameInput, 'Numbers are not allowed in name');
      return;
    }

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

    const diff = original.length - formatted.length;
    nameInput.setSelectionRange(pos - diff, pos - diff);

    validateName();
  });

  nameInput?.addEventListener('blur', validateName);

  // Address validation
  function formatAddress(v) {
    v = v.replace(/\s+/g, ' ').trim();
    if (v.length > 0) {
      v = v.charAt(0).toUpperCase() + v.slice(1);
    }
    return v;
  }

  function validateAddress() {
    const value = addressInput.value.trim();
    if (!value) {
      showError(addressInput, 'This input field is required');
      return;
    }

    if (!/^[A-Z]/.test(value)) {
      showError(addressInput, 'Address must start with a capital letter');
      return;
    }

    if (/\d/.test(value)) {
      showError(addressInput, 'Numbers are not allowed in address');
      return;
    }

    if (/[^A-Za-z\s,.\-]/.test(value)) {
      showError(addressInput, 'Special characters (except ,.-) are not allowed');
      return;
    }

    clearError(addressInput);
  }

  addressInput?.addEventListener('input', () => {
    const pos = addressInput.selectionStart;
    const original = addressInput.value;

    let cleaned = original.replace(/[^A-Za-z\s,.\-]/g, '');

    addressInput.value = cleaned;

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
      showError(phoneInput, 'This input field is required');
      return;
    }

    if (/[a-zA-Z]/.test(value)) {
      showError(phoneInput, 'Letters are not allowed in phone number');
      return;
    }

    if (/[^\d]/.test(value)) {
      showError(phoneInput, 'Special characters are not allowed in phone number');
      return;
    }

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
  function sanitizeFarmSize(v) {
    v = v.replace(/[^\d.]/g, '');

    const parts = v.split('.');
    if (parts.length > 2) {
      v = parts[0] + '.' + parts.slice(1).join('');
    }

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
      showError(farmSizeInput, 'This input field is required');
      return;
    }

    if (/[a-zA-Z]/.test(value)) {
      showError(farmSizeInput, 'Letters are not allowed in farm size');
      return;
    }

    if (/[^\d.]/.test(value)) {
      showError(farmSizeInput, 'Special characters are not allowed in farm size');
      return;
    }

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

  // Form submission validation
  const form = document.querySelector('form');
  const genderInput = document.getElementById('gender');
  const supportingDocInput = document.getElementById('supporting_document');

  // Real-time validation for other fields
  genderInput?.addEventListener('change', () => {
    if (!genderInput.value) {
      showError(genderInput, 'This input field is required');
    } else {
      clearError(genderInput);
    }
  });

  birthdateInput?.addEventListener('blur', () => {
    if (!birthdateInput.value) {
      showError(birthdateInput, 'This input field is required');
    }
  });

  supportingDocInput?.addEventListener('change', () => {
    if (!supportingDocInput.files || supportingDocInput.files.length === 0) {
      showError(supportingDocInput, 'This input field is required');
    } else {
      clearError(supportingDocInput);
    }
  });


  form?.addEventListener('submit', function (e) {
    let isValid = true;

    // Clear all previous validation errors first
    document.querySelectorAll('.validation-error').forEach(el => el.remove());
    document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

    // Check name
    if (!nameInput || !nameInput.value.trim()) {
      showError(nameInput, 'This input field is required');
      isValid = false;
    }

    // Check phone
    if (!phoneInput || !phoneInput.value.trim()) {
      showError(phoneInput, 'This input field is required');
      isValid = false;
    }

    // Check birthdate
    if (!birthdateInput || !birthdateInput.value) {
      showError(birthdateInput, 'This input field is required');
      isValid = false;
    }

    // Check gender
    if (!genderInput || !genderInput.value) {
      showError(genderInput, 'This input field is required');
      isValid = false;
    }

    // Check address
    if (!addressInput || !addressInput.value.trim()) {
      showError(addressInput, 'This input field is required');
      isValid = false;
    }

    // Check farm size
    if (!farmSizeInput || !farmSizeInput.value.trim()) {
      showError(farmSizeInput, 'This input field is required');
      isValid = false;
    }

    // Check supporting document
    if (!supportingDocInput || !supportingDocInput.files || supportingDocInput.files.length === 0) {
      showError(supportingDocInput, 'This input field is required');
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
      // Scroll to first error
      const firstError = document.querySelector('.validation-error');
      if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  });
});
