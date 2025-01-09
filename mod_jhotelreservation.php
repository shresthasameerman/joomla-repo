<?php
// Restrict direct access to the file
defined('_JEXEC') or die;

// Custom PHP logic (e.g., fetching data or processing form input)
$data = [
    ['label' => 'Check-in Date', 'type' => 'date', 'name' => 'checkin_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Check-out Date', 'type' => 'date', 'name' => 'checkout_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Guests', 'type' => 'number', 'name' => 'guests', 'min' => 1, 'max' => 10, 'icon' => 'fa-user', 'value' => 1],
];
?>

<!-- Add Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="module-horizontal">
    <style>
    :root {
        --primary-green: #2d572c;
        --secondary-green: #4a8748;
        --light-green: #e8f5e8;
        --accent-green: #367c36;
        --hover-green: #1e3e1d;
        --white: #ffffff;
        --border-color: #4a8748;
        --shadow-color: rgba(45, 87, 44, 0.2);
    }

    .module-horizontal {
        background: rgba(232, 245, 232, 0.2); /* Semi-transparent light green */
        backdrop-filter: blur(12px); /* Adds the blur effect */
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px var(--shadow-color);
    }

    .form-container {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px; /* Reduced gap between items */
        align-items: flex-end;
        justify-content: space-between;
        width: 100%;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        position: relative;
        flex: 1;
        min-width: 140px;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--primary-green);
        font-size: 14px;
    }

    .form-group input,
    .form-group select {
        padding: 12px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        width: 100%;
        appearance: none;
        -moz-appearance: textfield;
        background-color: var(--white);
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(74, 135, 72, 0.2);
    }

    .number-input {
        display: flex;
        align-items: center;
        position: relative;
        width: 100%;
    }

    .number-input input {
        text-align: center;
        width: 60px; /* Set fixed width for adults and children inputs */
    }

    .btn-container {
        display: flex;
        flex-direction: column;
        margin-left: 8px;
        gap: 4px;
    }

    .btn-container button {
        width: 28px;
        height: 28px;
        background-color: var(--secondary-green);
        color: var(--white);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-container button:hover {
        background-color: var(--accent-green);
        transform: translateY(-1px);
    }

    .btn-submit-container {
        flex: 0 0 auto;
        margin-left: 15px;
        margin-top: -25px;
    }

    .btn-submit {
        padding: 42px 50px;
        border: none;
        border-radius: 8px;
 background: linear-gradient(145deg, var(--secondary-green), var(--primary-green)); /* Changed back to previous color */
        color: var(--white);
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 64px;
        white-space: nowrap;
        box-shadow: 0 4px 12px var(--shadow-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        background: linear-gradient(145deg, var(--primary-green), var(--hover-green));
        transform: translateY(-2px);
        box-shadow: 0 6px 15px var(--shadow-color);
    }

    .btn-submit:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px var(--shadow-color);
    }

    .btn-submit i {
        margin-right: 8px;
        font-size: 18px;
    }

    /* Flatpickr customization */
    .flatpickr-calendar {
        background: var(--white);
        box-shadow: 0 4px 12px var(--shadow-color);
        border-radius: 12px;
    }

    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
        background: var(--primary-green) !important;
        border-color: var(--primary-green) !important;
        color: var(--white) !important;
    }

    .flatpickr-day.today {
        border-color: var(--secondary-green) !important;
    }

    .flatpickr-day:hover {
        background: var(--light-green) !important;
    }

    .flatpickr-input {
        background: var(--white) !important;
        border: 2px solid var(--border-color) !important;
        border-radius: 8px !important;
        padding: 12px !important;
        font-size: 14px !important;
        cursor: pointer !important;
    }

    .flatpickr-current-month {
        color: var(--primary-green) !important;
        font-weight: 600 !important;
    }

    .flatpickr-monthDropdown-months {
        color: var(--primary-green) !important;
    }

    .flatpickr-weekday {
        color: var(--secondary-green) !important;
        font-weight: 600 !important;
    }

    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        color: var(--primary-green) !important;
        fill: var(--primary-green) !important;
    }

    .children-age-group {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-height: 200px;
        overflow-y: auto;
        border-radius: 5px;
        padding: 10px;
    }

    .children-age-group .form-group {
        flex: 1 1 auto;
        min-width: 150px;
    }

    /* Mobile optimization */
    @media (max-width: 1200px) {
        .form-container {
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1 1 calc(50% - 10px);
            min-width: 160px;
        }
    }

    @media (max-width: 768px) {
        .form-container {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-group {
            flex: 1 1 calc(50% - 10px);
            min-width: 140px;
        }
    }

    @media (max-width: 480px) {
        .form-group {
            flex: 1 1 100%;
        }
    }
    </style>
    
    <!-- Heading -->
    <h2 style="color: #fff;"><strong>Book Now</strong></h2>
    <form class="form-container" action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&task=search'); ?>" method="post" onsubmit="return validateForm();">
    <?php foreach ($data as $field): ?>
        <div class="form-group">
            <label for="<?php echo $field['name']; ?>">
                <i class="fas <?php echo $field['icon']; ?>"></i> <?php echo $field['label']; ?>:
            </label>
            <?php if ($field['type'] === 'date'): ?>
                <input
                    type="text"
 id="<?php echo $field['name']; ?>"
                    name="<?php echo $field['name']; ?>"
                    placeholder="Select date"
                    class="flatpickr-input"
                    readonly="readonly"
                    required
                />
            <?php elseif ($field['name'] === 'guests'): ?>
                <input
                    type="<?php echo $field['type']; ?>"
                    id="<?php echo $field['name']; ?>"
                    name="<?php echo $field['name']; ?>"
                    value="<?php echo isset($field['value']) ? $field['value'] : 1; ?>"
                    <?php echo isset($field['min']) ? 'min="' . $field['min'] . '"' : ''; ?>
                    <?php echo isset($field['max']) ? 'max="' . $field['max'] . '"' : ''; ?>
                    readonly
                />
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <!-- Rooms field -->
    <div class="form-group">
        <label for="rooms"><i class="fas fa-bed"></i> Rooms:</label>
        <div class="number-input">
            <input
                type="number"
                id="rooms"
                name="rooms"
                value="<?php echo isset($field['value']) ? $field['value'] : 1; ?>"
                <?php echo isset($field['min']) ? 'min="' . $field['min'] . '"' : ''; ?>
                <?php echo isset($field['max']) ? 'max="' . $field['max'] . '"' : ''; ?>
                required readonly
            />
            <div class="btn-container">
                <button type="button" onclick="changeValue('rooms', 1)">+</button>
                <button type="button" onclick="changeValue('rooms', -1)">-</button>
            </div>
        </div>
    </div>
    <!-- Adults field -->
    <div class="form-group">
        <label for="adults"><i class="fas fa-user"></i> Adults:</label>
        <div class="number-input">
            <input type="number" id="adults" name="adults" min="1" max="10" value="1" required readonly />
            <div class="btn-container">
                <button type="button" onclick="changeValue('adults', 1)">+</button>
                <button type="button" onclick="changeValue('adults', -1)">-</button>
            </div>
        </div>
    </div>

    <!-- Children field -->
    <div class="form-group">
        <label for="children"><i class="fas fa-child"></i> Children:</label>
        <div class="number-input">
            <input type="number" id="children" name="children" min="0" max="10" value="0" required readonly />
            <div class="btn-container">
                <button type="button" onclick="changeValue('children', 1)">+</button>
                <button type="button" onclick="changeValue('children', -1)">-</button>
            </div>
        </div>
    </div>

    <!-- Container for children's age fields -->
    <div id="children-ages-container" class="children-age-group"></div>

    <div class="form-group">
        <button type="submit" class="btn-submit"><i class="fas fa-search"></i> Book Now</button>
    </div>
</form>
</div>

<script>
function changeValue(id, delta) {
    const input = document.getElementById(id);
    let value = parseInt(input.value);
    value = isNaN(value) ? 0 : value + delta;
    if (value < parseInt(input.min)) value = parseInt(input.min);
    if (value > parseInt(input.max)) value = parseInt(input.max);
    input.value = value;
    updateGuestCount();
    if (id === 'children') updateChildrenFields();
}

function updateGuestCount() {
    const adultsCount = parseInt(document.getElementById('adults').value) || 0;
    const childrenCount = parseInt(document.getElementById('children').value) || 0;
    const totalGuests = adultsCount + childrenCount;
    document.getElementById('guests').value = totalGuests;
}

function updateChildrenFields() {
    const childrenCount = document.getElementById('children').value;
    const container = document.getElementById('children-ages-container');
    container.innerHTML = '';

    for (let i = 0; i < childrenCount; i++) {
        const ageGroup = document.createElement('div');
        ageGroup.className = 'form-group';

        const label = document.createElement('label');
        label.htmlFor = 'child_age_' + i;
        label.innerHTML = '<i class="fas fa-baby"></i> Child ' + (i + 1) + ' Age:';

        const select = document.createElement('select');
        select.id = 'child_age_' + i;
        select.name = 'child_age_' + i;
        select.required = true;
        select.style.padding = '10px';
        select.style.border = '1px solid #2d572c';
        select.style.borderRadius = '5px';
        select.style.fontSize = '16px';
        select.style.width = '100%';

        const ages = ['Under 1 year', '1 year', '2 years', '3 years', '4 years', '5 years', '6 years', '7 years', '8 years', '9 years', '10 years', '11 years', '12 years', '13 years', '14 years', '15 years', '16 years', '17 years'];
        ages.forEach((age, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.innerText = age;
            select.appendChild(option);
        });

        ageGroup.appendChild(label);
        ageGroup.appendChild(select);
        container.appendChild(ageGroup);
    }
}

function validateForm() {
    const checkinDate = document.getElementById('checkin_date').value;
    const checkoutDate = document.getElementById('checkout_date').value;

    if (!checkinDate || !checkoutDate) {
        alert("Please fill in both check-in and check-out dates.");
        return false; // Prevent form submission
    }

    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);

    if (checkout <= checkin) {
        alert("Check-out date must be after check-in date.");
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}

document.addEventListener('DOMContentLoaded', function() {
    // Common configuration for both date pickers
    const commonConfig = {
        enableTime: false,
        dateFormat: "Y-m-d",
        disableMobile: false,
        allowInput: true,
        minDate: "today"
    };

    // Check-in date picker configuration
    const checkinPicker = flatpickr("#checkin_date", {
        ...commonConfig,
        mode: "range",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                // When a range is selected, update both inputs
                const [checkinDate, checkoutDate] = selectedDates;
                checkoutPicker.setDate(checkoutDate);
                // Reset the check-in picker to show only the check-in date
                setTimeout(() => {
                    this.setDate(checkinDate);
                }, 100);
            }
        },
        onOpen: function() {
            // When opening check-in picker, set initial range if checkout date exists
            const checkoutDate = checkoutPicker.selectedDates[0];
            if (checkoutDate && this.selectedDates[0]) {
                this.setDate([this.selectedDates[0], checkoutDate]);
            }
        }
    });

    // Check-out date picker configuration
    const checkoutPicker = flatpickr("#checkout_date", {
        ...commonConfig,
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                const checkinDate = checkinPicker.selectedDates[0];
                
                // If check-in date exists and new checkout date is before it
                if (checkinDate && selectedDates[0] <= checkinDate) {
                    // Reset to the day after check-in
                    const nextDay = new Date(checkinDate);
                    nextDay.setDate(checkinDate.getDate() + 1);
                    this.setDate(nextDay);
                }
            }
        }
    });

    // Update min dates when check-in changes
    checkinPicker.config.onChange.push(function(selectedDates) {
        if (selectedDates.length > 0) {
            const nextDay = new Date(selectedDates[0]);
            nextDay.setDate(nextDay.getDate() + 1);
            checkoutPicker.set('minDate', nextDay);

            // If checkout date exists and is before new check-in date
            const currentCheckout = checkoutPicker.selectedDates[0];
            if (currentCheckout && currentCheckout <= selectedDates[0]) {
                checkoutPicker.setDate(nextDay);
            }
        }
    });

    // Store references to pickers globally if needed
    window.checkinPicker = checkinPicker;
    window.checkoutPicker = checkoutPicker;
});
</script>
