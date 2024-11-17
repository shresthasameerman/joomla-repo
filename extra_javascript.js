document.addEventListener('DOMContentLoaded', function() {
    // Decrement and Increment logic for Adults and Children
    document.querySelectorAll('button[type="button"]').forEach(button => {
        button.addEventListener('click', function() {
            let inputField = this.closest('div').querySelector('input[type="number"]');
            let currentValue = parseInt(inputField.value) || 0;

            // Increment logic for Adults and Children without adding extra +1 or -1
            if (this.textContent === '+') {
                inputField.value = currentValue ; // Update value by 1
                if (inputField.id === 'childrenCount') {
                    updateChildrenCount(1); // Only update children count if necessary
                }
            } else if (this.textContent === '-' && currentValue > 0) {
                inputField.value = currentValue ; // Decrease value by 1
                if (inputField.id === 'childrenCount') {
                    updateChildrenCount(-1); // Only update children count if necessary
                }
            }
        });
    });

    // Initialize Flatpickr for Check-in and Check-out
    flatpickr("#checkin", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        mode: "range",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                var checkinDate = selectedDates[0];
                var checkoutDate = selectedDates[1];
                var minCheckoutDate = new Date(checkinDate);
                minCheckoutDate.setDate(checkinDate.getDate() + 1);
                updateCheckoutMinDate(minCheckoutDate);
                updateCheckoutDate(checkoutDate);
            }
        }
    });

    flatpickr("#checkout", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                updateCheckoutDate(selectedDates[0]);
            }
        }
    });

    function updateCheckoutMinDate(newMinDate) {
        var checkoutInstance = flatpickr("#checkout");
        checkoutInstance.set('minDate', newMinDate);
    }

    function updateCheckoutDate(newDate) {
        var checkoutInstance = flatpickr("#checkout");
        var newCheckoutDate = new Date(newDate);
        checkoutInstance.setDate(newCheckoutDate, false, 'user');
    }

    // Function to update the children count and add/remove age dropdowns
    function updateChildrenCount(delta) {
        const countInput = document.getElementById('childrenCount');
        let count = parseInt(countInput.value) + delta;

        if (count < 0) return; // Prevent negative values
        countInput.value = count;

        const dropdownContainer = document.getElementById('childrenAgeDropdowns');

        // Add dropdowns for children if the count increased
        if (delta > 0) {
            const childIndex = count; // Track child index based on count
            const dropdown = document.createElement('select');
            dropdown.name = `childAge${childIndex}`; // Set the name dynamically
            dropdown.style.padding = '5px';
            dropdown.innerHTML = `
                <option value="" disabled selected>Age</option>
                <option value="0">0 years</option>
                <option value="1">1 year</option>
                <option value="2">2 years</option>
                <option value="3">3 years</option>
                <option value="4">4 years</option>
                <option value="5">5 years</option>
                <option value="6">6 years</option>
                <option value="7">7 years</option>
                <option value="8">8 years</option>
                <option value="9">9 years</option>
                <option value="10">10 years</option>
                <option value="11">11 years</option>
                <option value="12">12 years</option>
                <option value="13">13 years</option>
                <option value="14">14 years</option>
                <option value="15">15 years</option>
                <option value="16">16 years</option>
                <option value="17">17 years</option>
            `;
            dropdownContainer.appendChild(dropdown);
        }

        // Remove dropdowns for children if the count decreased
        if (delta < 0 && dropdownContainer.lastChild) {
            dropdownContainer.removeChild(dropdownContainer.lastChild);
        }
    }
});

