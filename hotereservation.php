<?php
// Restrict direct access to the file
defined('_JEXEC') or die;

// Custom PHP logic (e.g., fetching data or processing form input)
// Example: Fetching reservations or module-specific data
$data = [
    ['label' => 'Check-in Date', 'type' => 'date', 'name' => 'checkin_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Check-out Date', 'type' => 'date', 'name' => 'checkout_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Guests', 'type' => 'number', 'name' => 'guests', 'min' => 1, 'max' => 10, 'icon' => 'fa-user', 'value' => 1],
    ['label' => 'Rooms', 'type' => 'number', 'name' => 'rooms', 'min' => 1, 'max' => 5, 'icon' => 'fa-bed'],
];

// Get current date in UTC
$currentDate = new DateTime('2025-01-05 06:55:09', new DateTimeZone('UTC'));
$tomorrow = clone $currentDate;
$tomorrow->modify('+1 day');

$currentDateFormatted = $currentDate->format('Y-m-d');
$tomorrowFormatted = $tomorrow->format('Y-m-d');
?>

<!-- Add Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="module-horizontal">
    <style>
    .module-horizontal {
        background: rgba(255, 255, 255, 0.01);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-container {
        display: flex;
        flex-wrap: nowrap; /* Changed from wrap to nowrap */
        gap: 10px; /* Increased gap from 10px to 20px */
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        position: relative;
        flex: 1;
        min-width: 150px; /* Adjusted min-width for better alignment */
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
        color: #2d572c;
    }

    .form-group input, .form-group select {
        padding: 10px;
        border: 1px solid #2d572c;
        border-radius: 5px;
        font-size: 16px;
        width: 100%;
        appearance: none;
        -moz-appearance: textfield;
        background-color: #ffffff;
    }

    .form-group input::-webkit-outer-spin-button,
    .form-group input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .number-input {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .number-input input {
        width: 60px;
        text-align: center;
    }

    .number-input button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-size: 16px;
        border-radius: 5px;
    }

    .number-input button:hover {
        background-color: #218838;
    }

    .btn-submit {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #28a745;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background-color: #218838;
    }

    .fa-calendar-alt {
        color: #2d572c;
    }

    /* Flatpickr customization */
    .flatpickr-calendar {
        background: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        width: 307.875px;
    }

    .flatpickr-day.selected, 
    .flatpickr-day.selected:hover {
        background: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
    }

    .flatpickr-day.today {
        border-color: #28a745;
    }

    .flatpickr-day:hover {
        background: #e8f5e9 !important;
    }

    .flatpickr-input {
        padding: 10px !important;
        border: 1px solid #2d572c !important;
        border-radius: 5px !important;
        font-size: 16px !important;
        width: 100% !important;
        background-color: #ffffff !important;
        cursor: pointer !important;
    }

    .flatpickr-current-month {
        color: #2d572c !important;
        font-weight: bold !important;
    }

    .flatpickr-monthDropdown-months {
        color: #2d572c !important;
    }

    .flatpickr-weekday {
        color: #28a745 !important;
        font-weight: bold !important;
    }

    .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-months .flatpickr-next-month {
        color: #2d572c !important;
        fill: #2d572c !important;
    }

    .children-age-group {
        width: 100%;
        display: flex;
        flex-direction: column; /* Changed to column for vertical stacking */
        gap: 15px;
    }

    .children-age-group .form-group {
        flex: 1 1 auto;
        min-width: 150px; /* Adjusted min-width */
    }

    /* Mobile optimization */
    @media (max-width: 1200px) {
        .form-container {
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1 1 auto;
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
    <h1 style="color: #2e8b57;"><strong>Book Now</strong></h1>
    <form class="form-container" action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&task=search'); ?>" method="post">
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
                        value="<?php echo $field['name'] === 'checkin_date' ? $currentDateFormatted : $tomorrowFormatted; ?>"
                    />
                <?php elseif ($field['name'] === 'guests' || $field['name'] === 'rooms'): ?>
                    <div class="number-input">
                        <button type="button" onclick="changeValue('<?php echo $field['name']; ?>', -1)">-</button>
                        <input
                            type="<?php echo $field['type']; ?>"
                            id="<?php echo $field['name']; ?>"
                            name="<?php echo $field['name']; ?>"
                            value="<?php echo isset($field['value']) ? $field['value'] : 1; ?>"
                            <?php echo isset($field['min']) ? 'min="' . $field['min'] . '"' : ''; ?>
                            <?php echo isset($field['max']) ? 'max="' . $field['max'] . '"' : ''; ?>
                            required
                        />
                        <button type="button" onclick="changeValue('<?php echo $field['name']; ?>', 1)">+</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <!-- Adults field -->
        <div class="form-group">
            <label for="adults"><i class="fas fa-user"></i> Adults:</label>
            <div class="number-input">
                <button type="button" onclick="changeValue('adults', -1)">-</button>
                <input type="number" id="adults" name="adults" min="1" max="10" value="1" required onchange="updateGuestCount()" />
                <button type="button" onclick="changeValue('adults', 1)">+</button>
            </div>
        </div>

        <!-- Children field -->
        <div class="form-group">
            <label for="children"><i class="fas fa-child"></i> Children:</label>
            <div class="number-input">
                <button type="button" onclick="changeValue('children', -1)">-</button>
                <input type="number" id="children" name="children" min="0" max="10" value="0" required onchange="updateGuestCount()" />
                <button type="button" onclick="changeValue('children', 1)">+</button>
            </div>
        </div>

        <!-- Container for children's age fields -->
        <div id="children-ages-container" class="children-age-group"></div>

        <div class="form-group">
            <button type="submit" class="btn-submit"><i class="fas fa-search"></i> Search</button>
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

        for (let age = 0; age <= 17; age++) {
            const option = document.createElement('option');
            option.value = age;
            option.innerText = age === 0 ? 'Under 1 year' : age + ' year' + (age === 1 ? '' : 's');
            select.appendChild(option);
        }

        ageGroup.appendChild(label);
        ageGroup.appendChild(select);
        container.appendChild(ageGroup);
    }
}

// Initialize Flatpickr date pickers with proper configuration
document.addEventListener('DOMContentLoaded', function() {
    const initialCheckinDate = new Date('<?php echo $currentDateFormatted; ?>');
    const initialCheckoutDate = new Date('<?php echo $tomorrowFormatted; ?>');
    
    const checkinPicker = flatpickr("#checkin_date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: initialCheckinDate,
        disableMobile: false,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates[0]) {
                // Calculate next day
                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 1);
                
                // Update checkout picker's minimum date
                checkoutPicker.set('minDate', nextDay);
                
                // Automatically set checkout date to next day if:
                // 1. No date is currently selected for checkout, or
                // 2. Current checkout date is before or equal to new checkin date
                if (!checkoutPicker.selectedDates[0] || 
                    checkoutPicker.selectedDates[0] <= selectedDates[0]) {
                    checkoutPicker.setDate(nextDay);
                }
            }
        }
    });

    const checkoutPicker = flatpickr("#checkout_date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: initialCheckoutDate,
        defaultDate: initialCheckoutDate,
        disableMobile: false,
    });
});
</script>
