document.addEventListener('DOMContentLoaded', function () {
  const birthdateInput = document.getElementById('birthdate');
  const ageInput = document.getElementById('age');
  const ageHint = document.getElementById('ageHint');
  const birthdateHint = document.getElementById('birthdateHint');
  const nameInput = document.getElementById('name');
  const phoneInput = document.getElementById('phone');
  const farmSizeInput = document.getElementById('farm_size');
  const form = document.querySelector('form');

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
    birthdateHint?.classList.add('hidden');
    if (birthdateHint) birthdateHint.textContent = '';
    birthdateInput?.classList.remove('border-red-500');
    ageIsValid = true;

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

  function formatName(v){
    v = v.replace(/[^A-Za-z ]+/g, '');
    v = v.replace(/\s+/g, ' ').trim();
    v = v.split(' ').filter(Boolean).map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
    return v;
  }
  nameInput?.addEventListener('input', () => {
    const pos = nameInput.selectionStart;
    const formatted = formatName(nameInput.value);
    nameInput.value = formatted;
    nameInput.setSelectionRange(pos, pos);
  });

  phoneInput?.addEventListener('input', () => {
    phoneInput.value = phoneInput.value.replace(/\D+/g, '').slice(0, 15);
  });

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
  farmSizeInput?.addEventListener('input', () => {
    const sanitized = sanitizeFarmSize(farmSizeInput.value);
    farmSizeInput.value = sanitized;
  });
});
