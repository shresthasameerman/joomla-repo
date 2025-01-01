# White Leaf Resort

![image](https://github.com/user-attachments/assets/7339e53a-5909-42cd-8782-7fe36ed9a1e5)

Welcome to the White Leaf Resort project! This repository contains the source code and assets for the White Leaf Resort website, showcasing our luxurious accommodations, dining options, event hosting, and more.

## Table of Contents

- [About Us](#about-us)
- [Features](#features)
- [Getting Started](#getting-started)
- [Services Offered](#services-offered)
- [Programming Languages Used](#programming-languages-used)
- [Contact Us](#contact-us)
- [License](#license)
- [Booking Form Module](#booking-form-module)

## About Us

White Leaf Resort is a premier destination located in Sukute, Sindhupalchowk, Nepal. We offer luxurious services and unique hospitality, aimed at providing the best experience for both domestic and international tourists. Our resort promotes the beauty of Sukute Beach and the surrounding hillside.

## Features

- **Luxurious Accommodations**: Comfortable and stylish suites designed for relaxation.
- **Dining Experience**: Organic, locally-sourced cuisine that is both healthy and delicious.
- **Event Hosting**: Perfect venue for various events, ensuring memorable moments for all guests.
- **Natural Environment**: A serene setting ideal for unwinding and connecting with nature.

## Getting Started

To get started with the White Leaf Resort website, follow these steps:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-username/white-leaf-resort.git

    Navigate to the Project Directory:

    cd white-leaf-resort

    Open the Project: Use your preferred code editor to open the project.

    Run the Project: Follow the instructions for your development environment to run the project locally.

Prerequisites

    A web server (e.g., Apache, Nginx) to host the website.
    PHP and MySQL for backend functionality (if applicable).

Services Offered

    Dining: Open daily from 7:30 AM to 11:00 PM with a seating capacity of 150.
    Events: Various event spaces available for hosting gatherings.
    Amenities: Swimming pool, parking space, and 24/7 light services.

Programming Languages Used

    HTML: Used for structuring the content of the website.
    CSS: Used for styling and layout of the website.
    JavaScript: Used for interactive features and functionality.
    PHP: Used for server-side scripting and backend functionality (if applicable).

Contact Us

For bookings or inquiries, please reach out to us:

    Phone: +977 9851342321
    Email: reservation@whiteleafresort.com

You can also find directions to our resort here.
License

This project is licensed under the MIT License. See the LICENSE file for more details.

Thank you for your interest in the White Leaf Resort project! We hope you enjoy exploring our website and learning about our services.
Booking Form Module

This module provides a horizontal booking form with custom fields for reservations. The form includes fields for check-in date, check-out date, number of guests, rooms, adults, and children. It also dynamically adds age fields for each child.
Features

    Responsive design with a semi-transparent background
    Customizable form fields for reservations
    Number input fields with increment and decrement buttons
    Dynamically generated age fields for children
    Styled using CSS for a modern look and feel
    Built-in JavaScript for handling dynamic form elements

Installation

    Download the module files.
    Place the files in the appropriate directory of your Joomla installation.
    Include the module in your desired location within your Joomla template.

Usage

To use the booking form module, simply include the following code snippet in your Joomla template where you want the form to appear:
PHP

<?php
// Ensure direct access is not allowed
defined('_JEXEC') or die;

// Include the module file
include_once 'path/to/your/module/file.php';
?>

Customization

You can customize the form fields by modifying the $data array in the PHP file. Each array element represents a form field with the following properties:

    label: The label text for the form field
    type: The input type (e.g., date, number)
    name: The name attribute for the input field
    icon: The FontAwesome icon class for the label
    min: The minimum value for number inputs (optional)
    max: The maximum value for number inputs (optional)

Example

Here's an example of how to customize the form fields:
PHP

$data = [
    ['label' => 'Check-in Date', 'type' => 'date', 'name' => 'checkin_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Check-out Date', 'type' => 'date', 'name' => 'checkout_date', 'icon' => 'fa-calendar-alt'],
    ['label' => 'Guests', 'type' => 'number', 'name' => 'guests', 'min' => 1, 'max' => 10, 'icon' => 'fa-user'],
    ['label' => 'Rooms', 'type' => 'number', 'name' => 'rooms', 'min' => 1, 'max' => 5, 'icon' => 'fa-bed'],
];

Styling

The module is styled using CSS to ensure a modern and responsive design. You can customize the styles by modifying the CSS within the <style> tags in the module's HTML file.
Media Query

A media query is included to ensure the form is responsive on smaller screens:
CSS

@media (max-width: 768px) {
    .form-container {
        flex-direction: column; /* Stack elements vertically */
        align-items: stretch;
    }

    .number-input {
        justify-content: center; /* Center the number input buttons */
    }
}

JavaScript

The module includes JavaScript functions to handle dynamic form elements, such as incrementing/decrementing number inputs and generating age fields for children.
Example Functions

    changeValue(id, delta): Adjusts the value of a number input by a specified delta.
    updateChildrenFields(): Dynamically generates age fields for each child based on the number of children selected.

License

This module is open-source and available under the MIT License. Feel free to use, modify, and distribute it as needed.

If you have any questions or need further assistance, please contact the module developer.
