<?php
// Restrict direct access to the file
defined('_JEXEC') or die;

// Custom PHP logic (e.g., fetching data or processing form input)
// Example: Fetching reservations or module-specific data
$data = [
    ['label' => 'Check-in Date', 'type' => 'date', 'name' => 'checkin_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Check-out Date', 'type' => 'date', 'name' => 'checkout_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Guests', 'type' => 'number', 'name' => 'guests', 'min' => 1, 'max' => 10, 'icon' => 'fa-user'],
    ['label' => 'Rooms', 'type' => 'number', 'name' => 'rooms', 'min' => 1, 'max' => 5, 'icon' => 'fa-bed'], // Corrected line
];
?>

<div class="module-horizontal">
    <style>
        .module-horizontal {
            background: rgba(255, 255, 255, 0.01); /* Semi-transparent white background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping to the next line */
            gap: 15px;
            align-items: center;
            justify-content: space-between; /* Adjust alignment */
        }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
            flex: 1 1 150px; /* Flex-grow, flex-shrink, and base width */
            min-width: 150px; /* Minimum width for form fields */
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #2d572c; /* Green color for labels */
        }

        .form-group input, .form-group select {
            padding: 10px;
            border: 1px solid #2d572c; /* Green border */
            border-radius: 5px;
            font-size: 16px;
            width: 100%; /* Ensure input fields take full width of container */
            appearance: none; /* Remove default arrows */
            -moz-appearance: textfield; /* Remove default arrows in Firefox */
        }

        .form-group input::-webkit-outer-spin-button,
        .form-group input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0; /* Remove default arrows in WebKit browsers */
        }

        /* Custom buttons for number input */
        .number-input {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 5px;
        }

        .number-input input {
            width: 60px;
            text-align: center;
        }

        .number-input button {
            background-color: #28a745; /* Green color */
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        .number-input button:hover {
            background-color: #218838; /* Darker green on hover */
        }

        .btn-submit {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #28a745; /* Green color */
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #218838; /* Darker green on hover */
        }

        /* Media query for smaller screens */
        @media (max-width: 768px) {
            .form-container {
                flex-direction: column; /* Stack elements vertically */
                align-items: stretch;
            }

            .number-input {
                justify-content: center; /* Center the number input buttons */
            }
        }
    </style>
    
    <!-- Heading -->
    <h1><strong>Book Now</strong></h1>
    
    <form class="form-container" action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&task=search'); ?>" method="post">
        <?php foreach ($data as $field): ?>
            <div class="form-group">
                <label for="<?php echo $field['name']; ?>"><i class="fas <?php echo $field['icon']; ?>"></i> <?php echo $field['label']; ?>:</label>
                <?php if ($field['name'] === 'guests' || $field['name'] === 'rooms'): ?>
                    <div class="number-input">
                        <button type="button" onclick="changeValue('<?php echo $field['name']; ?>', -1)">-</button>
                        <input
                            type="<?php echo $field['type']; ?>"
                            id="<?php echo $field['name']; ?>"
                            name="<?php echo $field['name']; ?>"
                            <?php echo isset($field['min']) ? 'min="' . $field['min'] . '"' : ''; ?>
                            <?php echo isset($field['max']) ? 'max="' . $field['max'] . '"' : ''; ?>
                            required
                        />
                        <button type="button" onclick="changeValue('<?php echo $field['name']; ?>', 1)">+</button>
                    </div>
                <?php else: ?>
                    <input
                        type="<?php echo $field['type']; ?>"
                        id="<?php echo $field['name']; ?>"
                        name="<?php echo $field['name']; ?>"
                        <?php echo isset($field['min']) ? 'min="' . $field['min'] . '"' : ''; ?>
                        <?php echo isset($field['max']) ? 'max="' . $field['max'] . '"' : ''; ?>
                        required
                    />
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <!-- Adults field -->
        <div class="form-group">
            <label for="adults"><i class="fas fa-user"></i> Adults:</label>
            <div class="number-input">
                <button type="button" onclick="changeValue('adults', -1)">-</button>
                <input type="number" id="adults" name="adults" min="1" max="10" value="2" required />
                <button type="button" onclick="changeValue('adults', 1)">+</button>
            </div>
        </div>

        <!-- Children field -->
        <div class="form-group">
            <label for="children"><i class="fas fa-child"></i> Children:</label>
            <div class="number-input">
                <button type="button" onclick="changeValue('children', -1)">-</button>
                <input type="number" id="children" name="children" min="0" max="10" value="0" required onchange="updateChildrenFields()" />
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
    if (id === 'children') updateChildrenFields();
}

function updateChildrenFields() {
    const childrenCount = document.getElementById('children').value;
    const container = document.getElementById('children-ages-container');
    container.innerHTML = ''; // Clear previous children age fields

    for (let i = 0; i < childrenCount; i++) {
        const ageGroup = document.createElement('div');
        ageGroup.className = 'form-group';

        const label = document.createElement('label');
        label.htmlFor = 'child_age_' + i;
        label.innerText = 'Child ' + (i + 1) + ' Age:';

        const select = document.createElement('select');
        select.id = 'child_age_' + i;
        select.name = 'child_age_' + i;
        select.required = true;
        select.style.padding = '10px';
        select.style.border = '1px solid #2d572c'; /* Green border */
        select.style.borderRadius = '5px';
        select.style.fontSize = '16px';

        // Create options for ages 1-17
        for (let age = 1; age <= 17; age++) {
            const option = document.createElement('option');
            option.value = age;
            option.innerText = age;
            select.appendChild(option);
        }

        ageGroup.appendChild(label);
        ageGroup.appendChild(select);
        container.appendChild(ageGroup);
    }
}
</script>
