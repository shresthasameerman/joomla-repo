document.addEventListener('DOMContentLoaded', function() {
    // Decrement and Increment logic for Adults
    document.querySelectorAll('button[type="button"]').forEach(button => {
        button.addEventListener('click', function() {
            let inputField = this.closest('div').querySelector('input[type="number"]');
            let currentValue = parseInt(inputField.value) || 0;

            if (this.textContent === '+') {
                inputField.value = currentValue + 1;
            } else if (this.textContent === '-' && currentValue > 0) {
                inputField.value = currentValue - 1;
            }
        });
    });
});

 // Get today's date in YYYY-MM-DD format
  const today = new Date().toISOString().split('T')[0];
  
  // Set the minimum date for check-in to today
  document.getElementById('checkin').setAttribute('min', today);
  
  // Update the check-out date when check-in is selected
  document.getElementById('checkin').addEventListener('change', function() {
    const checkInDate = this.value;
    document.getElementById('checkout').setAttribute('min', checkInDate);
  });
