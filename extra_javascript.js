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

// Initialize Flatpickr for Check-in (range mode, but show only check-in date in mm/dd/yyyy format)
flatpickr("#checkin", {
  altInput: true,
  altFormat: "m/d/Y", // Display date in mm/dd/yyyy format
  dateFormat: "Y-m-d", // Adjust as needed for your backend (backend will receive this format)
  minDate: "today", // Check-in must be today or later
  mode: "range", // Enable range selection (for check-in and check-out)
  onChange: function(selectedDates, dateStr, instance) {
    // Check if both check-in and check-out dates are selected
    if (selectedDates.length === 2) {
      // Set check-out date as the second date of the range (the end date)
      var checkinDate = selectedDates[0]; // First date selected (Check-in)
      var checkoutDate = selectedDates[1]; // Second date selected (Check-out)

      // Calculate the min checkout date, which should be 1 day after check-in
      var minCheckoutDate = new Date(checkinDate);
      minCheckoutDate.setDate(checkinDate.getDate() + 1); // Ensure checkout is at least 1 day after check-in

      // Update the checkout date to reflect the selected range
      updateCheckoutMinDate(minCheckoutDate);
      updateCheckoutDate(checkoutDate); // Update the checkout date to the end date of the range
    }
  }
});

// Initialize Flatpickr for Check-out with dynamic minDate (updated dynamically from Check-in)
flatpickr("#checkout", {
  altInput: true,
  altFormat: "m/d/Y", // Display date in mm/dd/yyyy format for checkout
  dateFormat: "Y-m-d", // Adjust as needed for your backend
  minDate: "today", // Initial minDate, will be updated dynamically
  onChange: function(selectedDates, dateStr, instance) {
    // If checkout date is manually changed, update the check-out date
    if (selectedDates.length > 0) {
      updateCheckoutDate(selectedDates[0]);
    }
  }
});

// Function to update the minDate for the Checkout Flatpickr instance
function updateCheckoutMinDate(newMinDate) {
  // Get the checkout instance and set its minDate
  var checkoutInstance = flatpickr("#checkout");
  checkoutInstance.set('minDate', newMinDate);
}

// Function to update the checkout date to ensure at least a 1-night stay
function updateCheckoutDate(newDate) {
  var checkoutInstance = flatpickr("#checkout");

  // Set the checkout date to reflect the selected checkout date
  var newCheckoutDate = new Date(newDate);

  // Ensure the checkout date is set correctly
  checkoutInstance.setDate(newCheckoutDate, false, 'user');
}
