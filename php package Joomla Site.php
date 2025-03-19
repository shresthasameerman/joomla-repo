<?php
/**
 * @package Joomla.Site
 * @subpackage Templates.WhiteLeafResort
 *
 * @copyright (C) 2024 Sameer man shrestha
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 * This is a heavily stripped down/modified version of the default Cassiopeia template, designed to build new templates off of.
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */
$app = Factory::getApplication();
$wa = $this->getWebAssetManager();

// custom JS script
$wa->addInlineScript('
    function adjustValue(fieldId, change) {
        const input = document.getElementById(fieldId);
        const currentValue = parseInt(input.value) || 0;
        const minValue = parseInt(input.min) || 0;
        const newValue = currentValue + change;
        
        if (newValue >= minValue) {
            input.value = newValue;
        }
    }
');

// Add Favicon from images folder
// Detecting Active Variables
$option = $app->input->getCmd('option', '');
$view = $app->input->getCmd('view', '');
$layout = $app->input->getCmd('layout', '');
$task = $app->input->getCmd('task', '');
$itemid = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Get params from template styling
$testparam = $this->params->get('testparam');

// Disable page heading globally
if (isset($menu)) {
    $menuParams = $menu->getParams();
    $menuParams->set('show_page_heading', 0);
}

// Get this template's path
$templatePath = 'templates/' . $this->template;

// Load required Bootstrap components
HTMLHelper::_('bootstrap.collapse');
HTMLHelper::_('bootstrap.dropdown');

// Register web assets
$wa->useStyle('template.joomstarter.mainstyles');
$wa->useStyle('template.joomstarter.user');
$wa->useScript('template.joomstarter.scripts');

// Set viewport meta tag
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

// Add CSS to hide page header if it still appears
$wa->addInlineStyle('
    .page-header, 
    .item-page > h1:first-child,
    .blog-featured h1,
    .blog h1 { 
        display: none !important; 
    }
');

// Check if current page is the booking page
$isBookingPage = ($option === 'com_content' && $view === 'hotel') || 
                 (strpos(Uri::getInstance()->toString(), 'book-now') !== false);

// Near the beginning of the file, update the home page check:
$app = Factory::getApplication();
$menu = $app->getMenu();
$isHomePage = ($menu->getActive() == $menu->getDefault()) && 
              empty($_GET['view']) && 
              empty($_GET['option']) && 
              empty($_GET['id']) &&
              empty($_GET['Itemid']);

?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    
    <!-- Single inclusion of external resources -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
    
    <!-- Favicon -->
    <link rel="icon" href="images/assets/WhatsApp_Image_2024-11-06_at_3.30.01_PM-removebg-preview.png" type="image/x-icon">

    <style>
        /* Essential fixes only - move rest to template.css */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.1) !important; /* Translucent white */
            backdrop-filter: blur(10px); /* Blur effect for background */
            transition: all 0.3s ease;
            padding: 15px 20px;
            height: 90px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Logo container styling */
        .navbar-brand {
            background: rgba(255, 255, 255, 0.005); /* Reduced from 0.1 to 0.05 */
            border-radius: 15px;
            padding: 10px 15px;
            margin-right: auto;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            background: rgba(255, 255, 255, 0.001); /* Reduced from 0.2 to 0.1 */
            transform: translateY(-2px);
        }

        /* Logo image styling */
        .navbar-brand img {
            height: 120px;
            width: auto;
            margin-top: 10px;
            transition: height 0.3s ease;
        }

        /* Navigation links */
        .navbar-nav {
            display: flex;
            flex-wrap: nowrap;
            justify-content: flex-end;
            margin-left: -50px;
        }

        .navbar-nav .nav-link {
            color: green !important;
            font-size: 18px;
            margin-right: 10px;
            white-space: nowrap;
            padding: 1px 1px;
            text-align: right;
        }

        /* Dropdown styling */
        .navbar-nav .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 30000;
        }

        .navbar-nav .dropdown-item {
            color: green !important; /* Match navbar color */
            padding: 10px 15px;
        }

        .navbar-nav .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.4);
            color: darkgreen !important;
        }

        /* Header background animation */
        @keyframes zoomInOut {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .home-header:not(.hotel-white-leaf-page) {
            min-height: 800px; /* increased from 600px */
            margin-top: 0;
            position: relative;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.02); /* Added translucent background */
        }

        .home-header:not(.hotel-white-leaf-page) .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            animation: zoomInOut 30s ease-in-out infinite;
        }

        /* Responsive styles */
        @media (max-width: 991px) {
            .navbar {
                height: 120px;
                background: rgba(255, 255, 255, 0.15) !important;
            }

            .navbar-brand {
                padding: 8px 12px;
            }

            .navbar-brand img {
                height: 90px;
                margin-top: 0;
            }

            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: rgba(255, 255, 255, 0.3);
                padding: 10px;
                border-radius: 0 0 10px 10px;
            }
        }

        @media (max-width: 768px) {
            .home-header:not(.hotel-white-leaf-page) {
                min-height: 350px;  /* Reduced from 400px */
            }
        }

        @media (max-width: 576px) {
            .home-header:not(.hotel-white-leaf-page) {
                min-height: 250px;  /* Reduced from 300px */
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                padding: 5px 10px;
            }

            .navbar-brand img {
                height: 80px;
            }

            .navbar {
                padding: 5px 15px;
            }
        }

        /* Update breadcrumbs styling */
        .mod-breadcrumbs {
            background-color: white;
            color: #198754;
            border-radius: 5px;
            width: 100%;
            clear: both;
            z-index: 1;
            position: relative;
            margin-top: 90px; /* Add top margin to shift down */
        }

        /* Update main container styling */
        .container {
            margin-top: 20px; /* Add spacing between breadcrumbs and content */
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .mod-breadcrumbs {
                margin-top: 120px; /* Increase margin for mobile navbar height */
            }
        }

        @media (max-width: 768px) {
            .mod-breadcrumbs {
                margin-top: 90px;
            }
            .container {
                margin-top: 15px;
            }
        }

        @media (max-width: 576px) {
            .mod-breadcrumbs {
                margin-top: 80px;
            }
            .container {
                margin-top: 10px;
            }
        }

        /* Navbar container layout */
        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1400px;
        }

        /* Logo positioning */
        .navbar-brand {
            margin-right: auto; /* Push logo to left */
        }

        /* Menu items positioning */
        .navbar-collapse {
            margin-left: auto; /* Push menu to right */
            flex-grow: 0; /* Prevent menu from expanding */
        }

        .navbar-nav {
            margin-left: 0; /* Remove existing margin */
            justify-content: flex-end; /* Align items to right */
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: rgba(255, 255, 255, 0.3);
                padding: 10px;
                border-radius: 0 0 10px 10px;
            }
        }

        /* Base header height */
        .home-header:not(.hotel-white-leaf-page) {
            min-height: 800px;
            margin-top: 0;
            position: relative;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* Tablet devices */
        @media (max-width: 991px) {
            .home-header:not(.hotel-white-leaf-page) {
                min-height: 600px;
            }
        }

        /* Large mobile devices */
        @media (max-width: 768px) {
            .home-header:not(.hotel-white-leaf-page) {
                min-height: 500px;
            }
        }

        /* Small mobile devices */
        @media (max-width: 576px) {
            .home-header:not(.hotel-white-leaf-page) {
                min-height: 400px;
            }
        }

        /* Dropdown styling for clickable behavior */
        .navbar-nav .dropdown-toggle {
            cursor: pointer;
        }

        .navbar-nav .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }

        .navbar-nav .dropdown-menu {
            display: none;
        }

        .navbar-nav .dropdown-menu.show {
            display: block;
        }

        /* Dropdown styling */
        .navbar-nav .dropdown-menu {
            display: none;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .dropdown-menu.show {
            display: block;
        }

        .navbar-nav .dropdown-toggle {
            cursor: pointer;
        }

        .dropdown-item:hover, 
        .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.6);
            color: darkgreen !important;
        }

        /* Dropdown hover behavior */
        .navbar-nav .dropdown:hover > .dropdown-menu {
            display: block;
        }

        .navbar-nav .dropdown-toggle {
            cursor: pointer;
        }

        /* Keep dropdown visible while hovering */
        .navbar-nav .dropdown-menu {
            margin-top: 0;
            display: none;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Parent menu items that are clickable */
        .navbar-nav .dropdown-toggle[href] {
            pointer-events: auto;
        }

        /* Dropdown items styling */
        .dropdown-item {
            color: green !important;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.6);
            color: darkgreen !important;
        }

        /* Mobile-first adjustments */
        @media (max-width: 720px) {
            /* Header adjustments */
            .navbar {
                height: auto;
                min-height: 70px;
                padding: 5px 10px;
            }

            .navbar-brand img {
                height: 60px;
                margin-top: 0;
            }

            /* Fix the gap between header and content */
            .home-header:not(.hotel-white-leaf-page) {
                min-height: 300px;
                margin-top: 0;
            }

            /* Adjust breadcrumbs positioning */
            .mod-breadcrumbs {
                margin-top: 70px;
                padding: 5px 0;
            }

            /* Container spacing */
            .container {
                padding: 0 15px;
                margin-top: 10px;
            }

            /* Hotel module adjustments */
            .hotel-module {
                margin-top: -30px;
                border-radius: 10px;
                padding: 15px;
            }

            /* Navbar collapse improvements */
            .navbar-collapse {
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                background-color: rgba(255, 255, 255, 0.95);
                padding: 10px;
                border-radius: 0 0 10px 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .navbar-nav {
                margin: 0;
                padding: 10px 0;
            }

            .navbar-nav .nav-link {
                padding: 8px 15px;
                text-align: left;
            }

            /* Dropdown adjustments */
            .navbar-nav .dropdown-menu {
                position: static;
                float: none;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.1);
                border: none;
                box-shadow: none;
                padding: 0;
            }

            .dropdown-item {
                padding: 8px 25px;
            }

            /* Map container adjustments */
            .map-class-container {
                flex-direction: column;
                padding: 10px;
            }

            .left-box, .right-box {
                width: 100%;
                margin-bottom: 15px;
            }

            .map-wrapper {
                padding-bottom: 56.25%; /* 16:9 aspect ratio */
            }

            /* Footer adjustments */
            footer .container {
                padding: 15px;
            }

            footer .row > div {
                margin-bottom: 20px;
            }
        }

        /* Extra small devices */
        @media (max-width: 576px) {
            .navbar-brand img {
                height: 50px;
            }

            .home-header:not(.hotel-white-leaf-page) {
                min-height: 250px;
            }

            .hotel-module {
                margin-top: -20px;
            }

            /* Adjust content spacing */
            .container {
                padding: 0 10px;
            }

            /* Scroll to top button adjustment */
            #scrollToTopBtn {
                bottom: 20px;
                right: 20px;
                padding: 12px;
                font-size: 16px;
            }
        }

        /* Fix for any remaining gaps */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .site {
            flex: 1;
        }

        /* Prevent horizontal scrolling */
        html, body {
            overflow-x: hidden;
            width: 100%;
        }

        /* Add this new style block in your existing <style> section */
        .hotel-white-leaf-page {
            margin-top: 90px; /* Default margin for desktop */
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .hotel-white-leaf-page {
                margin-top: 120px;
            }
        }

        @media (max-width: 768px) {
            .hotel-white-leaf-page {
                margin-top: 90px;
            }
        }

        @media (max-width: 576px) {
            .hotel-white-leaf-page {
                margin-top: 80px;
            }
        }

        /* New styles for booking page */
        body[class*="view-hotel"] .container,
        body[class*="layout-hotel"] .container,
        .booking-page .container {
            margin-top: 30px !important; /* Reduced from 50px to 30px */
            padding-top: 15px; /* Reduced from 20px to 15px */
        }

        .hotel-white-leaf-page {
            margin-top: 90px; /* Increased from 20px to 90px */
            padding-top: 30px;
        }

        /* Responsive adjustments for booking page */
        @media (max-width: 991px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 25px !important; /* Reduced from 45px to 25px */
            }
            
            .booking-page .navbar {
                margin-top: -12px; /* Reduced from -15px to -12px */
                height: 55px; /* Reduced from 60px to 55px */
            }
        }

        @media (max-width: 768px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 20px !important; /* Reduced from 40px to 20px */
            }
            
            .booking-page .navbar {
                margin-top: -10px; /* Kept at -10px */
                height: 50px; /* Reduced from 55px to 50px */
            }
        }

        @media (max-width: 576px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 15px !important; /* Reduced from 35px to 15px */
            }
            
            .booking-page .navbar {
                margin-top: -5px; /* Kept at -5px */
                height: 45px; /* Reduced from 50px to 45px */
            }
        }

        /* Content spacing fixes */
        .site {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1470px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Booking page specific styles */
        body[class*="view-hotel"] .container,
        body[class*="layout-hotel"] .container,
        .booking-page .container {
            margin-top: 30px !important; /* Reduced from 50px to 30px */
            padding-top: 15px; /* Reduced from 20px to 15px */
        }

        /* Adjust navbar for booking page */
        .booking-page .navbar {
            margin-top: -15px; /* Reduced from -20px to -15px */
            height: 65px; /* Reduced from 70px to 65px */
        }

        /* Responsive adjustments for booking page */
        @media (max-width: 991px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 25px !important; /* Reduced from 45px to 25px */
            }
            
            .booking-page .navbar {
                margin-top: -12px; /* Reduced from -15px to -12px */
                height: 55px; /* Reduced from 60px to 55px */
            }
        }

        @media (max-width: 768px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 20px !important; /* Reduced from 40px to 20px */
            }
            
            .booking-page .navbar {
                margin-top: -10px; /* Kept at -10px */
                height: 50px; /* Reduced from 55px to 50px */
            }
        }

        @media (max-width: 576px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 15px !important; /* Reduced from 35px to 15px */
            }
            
            .booking-page .navbar {
                margin-top: -5px; /* Kept at -5px */
                height: 45px; /* Reduced from 50px to 45px */
            }
        }

        /* Adjust navbar for booking page */
        .booking-page .navbar {
            margin-top: -20px; /* Adjusted from -30px to -20px */
            height: 70px; /* Reduced height */
        }

        /* Responsive adjustments for booking page */
        @media (max-width: 991px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 45px !important; /* Increased from 35px to 45px */
            }
            
            .booking-page .navbar {
                margin-top: -15px; /* Adjusted from -25px */
                height: 60px;
            }
        }

        @media (max-width: 768px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 40px !important; /* Increased from 30px to 40px */
            }
            
            .booking-page .navbar {
                margin-top: -10px; /* Adjusted from -20px */
                height: 55px;
            }
        }

        @media (max-width: 576px) {
            body[class*="view-hotel"] .container,
            body[class*="layout-hotel"] .container,
            .booking-page .container {
                margin-top: 35px !important; /* Increased from 25px to 35px */
            }
            
            .booking-page .navbar {
                margin-top: -5px; /* Adjusted from -15px */
                height: 50px;
            }
        }

        /* Booking page specific navbar styles */
        .booking-page .navbar {
            margin-top: -40px; /* Increased negative margin to move up */
            height: 120px;
            background: rgba(255, 255, 255, 0.25) !important;
            backdrop-filter: blur(20px);
        }

        .booking-page .navbar-brand img {
            margin-top: -10px; /* Added negative margin to move logo up */
            height: 100px; /* Adjusted height for better proportion */
        }

        /* Responsive adjustments for booking page navbar */
        @media (max-width: 991px) {
            .booking-page .navbar {
                margin-top: -35px;
                height: 100px;
            }
            
            .booking-page .navbar-brand img {
                margin-top: -8px;
                height: 90px;
            }
        }

        @media (max-width: 768px) {
            .booking-page .navbar {
                margin-top: -30px;
                height: 90px;
            }
            
            .booking-page .navbar-brand img {
                margin-top: -5px;
                height: 80px;
            }
        }

        @media (max-width: 576px) {
            .booking-page .navbar {
                margin-top: -25px;
                height: 80px;
            }
            
            .booking-page .navbar-brand img {
                margin-top: -3px;
                height: 70px;
            }
        }

        /* Update breadcrumbs styling for booking page */
        .booking-page .mod-breadcrumbs {
            margin-top: 40px; /* Reduced from 90px to 40px */
        }

        /* Responsive adjustments for booking page breadcrumbs */
        @media (max-width: 991px) {
            .booking-page .mod-breadcrumbs {
                margin-top: 70px; /* Reduced from 120px to 70px */
            }
        }

        @media (max-width: 768px) {
            .booking-page .mod-breadcrumbs {
                margin-top: 40px; /* Reduced from 90px to 40px */
            }
        }

        @media (max-width: 576px) {
            .booking-page .mod-breadcrumbs {
                margin-top: 30px; /* Reduced from 80px to 30px */
            }
        }

        .site-header {
            position: relative;
            width: 100%;
        }

        .home-header {
            height: 100vh;
            min-height: 800px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .home-header .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            animation: zoomInOut 30s ease-in-out infinite;
        }

        @keyframes zoomInOut {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @media (max-width: 768px) {
            .home-header {
                min-height: 600px;
            }
        }

        @media (max-width: 576px) {
            .home-header {
                min-height: 400px;
            }
        }
    </style>
</head>

<body class="site <?php echo $pageclass; ?> <?php echo $isBookingPage ? 'booking-page' : ''; ?>" data-bs-theme="light">
    <?php
    $menu = JFactory::getApplication()->getMenu();
    $isHomePage = $menu->getActive() == $menu->getDefault();
    $currentUrl = JUri::getInstance()->toString();
    $isHotelWhiteLeafPage = strpos($currentUrl, 'home/hotel-white-leaf-resort') !== false;
    ?>
    
    <header class="site-header <?= $isHomePage && strpos($currentUrl, 'east-coast-holiday-hotel') === false ? 'home-header' : '' ?> <?= $isHotelWhiteLeafPage ? 'hotel-white-leaf-page' : '' ?>">
        <?php if ($isHomePage && strpos($currentUrl, 'east-coast-holiday-hotel') === false): ?>
            <img class="background-image" 
                 src="images/assets/suEDZJf2Rc7UnAmD2uLtXL1T6KwL0NmBhEKZKaTQ.jpg" 
                 alt="Background Image">
        <?php endif; ?>
        
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="images/assets/Untitled_design-removebg-preview.png" alt="Site Logo" height="80">
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <jdoc:include type="modules" name="menu" style="none" />
                </div>
            </div>
        </nav>
    </header>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbarItems = document.querySelectorAll('.navbar-nav .nav-item');

        navbarItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const dropdownMenu = item.querySelector('.dropdown-menu');

           // Show dropdown on hover
item.addEventListener('mouseenter', function() {
    if (dropdownMenu) {
        dropdownMenu.classList.add('show');
    }
});

item.addEventListener('mouseleave', function() {
    if (dropdownMenu) {
        dropdownMenu.classList.remove('show');
    }
});

if (dropdownMenu) {
    link.addEventListener('click', function(event) {
        // Prevent the default action if the dropdown is shown
        if (dropdownMenu.classList.contains('show')) {
            event.preventDefault();
            // Optionally, you can toggle the dropdown here if needed
        } else {
            // Redirect to the page if the dropdown is not shown
            window.location.href = link.href;
        }
    });
}
});

const navbar = document.querySelector('.navbar');
const body = document.body;
let lastScroll = 0;

const navbarToggler = document.querySelector('.navbar-toggler');
const navbarCollapse = document.querySelector('.navbar-collapse');

navbarToggler.addEventListener('click', function() {
    navbarCollapse.classList.toggle('show');
});

window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;

    if (currentScroll > 100) {
        navbar.classList.add('sticky-navbar');
        body.classList.add('has-sticky-navbar');
    } else {
        navbar.classList.remove('sticky-navbar');
        body.classList.remove('has-sticky-navbar');
    }

    lastScroll = currentScroll;
});
});
</script>

<?php if ($this->countModules('breadcrumbs')): ?>
<style>
    .mod-breadcrumbs {
        background-color: white;
        color: #198754;
        border-radius: 5px;
        width: 100%;
        clear: both;
        z-index: 1; /* Decreased z-index */
        position: relative; /* Ensure it is positioned relative to its parent */
    }

    .mod-breadcrumbs .container {
        
        max-width: 1400px;
        margin: 0 auto;
    }

    .mod-breadcrumbs a, 
    .mod-breadcrumbs span {
        color: #198754;
    }

    .mod-breadcrumbs .breadcrumb-item + .breadcrumb-item::before {
        color: #198754;
    }

    .mod-breadcrumbs .breadcrumb {
        margin: 0;
    }

    @media (max-width: 991px) {
        .mod-breadcrumbs .container {
            padding: 0 15px;
        }
    }

    @media (max-width: 768px) {
        .mod-breadcrumbs {
            margin-top: 0;
        }
    }
</style>
<div class="mod-breadcrumbs">
    <div class="container">
        <jdoc:include type="modules" name="breadcrumbs" style="none"/>
    </div>
</div>
<?php endif; ?>

<?php if ($this->countModules('hotel_module') && strpos($currentUrl, 'east-coast-holiday-hotel') === false): ?>
 <div class="hotel-module">
     <jdoc:include type="modules" name="hotel_module" style="none"/>
 </div>
 <style>
     .hotel-module {
         width: 100%;
         max-width: 1400px;
         color: white;
         box-sizing: border-box;
         backdrop-filter: blur(5px);
         border-radius: 20px;
         margin: 0 auto;
         margin-top: -75px; /* Default margin for larger screens */
         z-index: 1002;
         position: relative;
     }

     @media (max-width: 768px) {
         .hotel-module {
             margin-top: -40px; /* Adjust margin-top for small screens */
         }
     }
 </style>
<?php endif; ?>

<div class="container" style="
    margin-bottom: 20px;
    width: 100%;
    max-width: 1470px;
    margin: 0 auto;">
    <jdoc:include type="message"/>
    <jdoc:include type="component"/>
    <style>
@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-100%);
  }
}
</style>
  <script>
    window.addEventListener('resize', function () {
      const logo = document.querySelector('img[alt="White Leaf Resort Logo"]');
      const logoMobile = document.querySelectorAll('img[alt="White Leaf Resort Logo"]')[1];
      if (window.innerWidth <= 600) {
        logo.style.display = 'none';
        logoMobile.style.display = 'block';
      } else {
        logo.style.display = 'block';
        logoMobile.style.display = 'none';
      }
    });
    window.addEventListener('load', function () {
      const logo = document.querySelector('img[alt="White Leaf Resort Logo"]');
      const logoMobile = document.querySelectorAll('img[alt="White Leaf Resort Logo"]')[1];
      if (window.innerWidth <= 600) {
        logo.style.display = 'none';
        logoMobile.style.display = 'block';
      } else {
        logo.style.display = 'block';
        logoMobile.style.display = 'none';
      }
    });
  </script>
</div>
<?php
// Get the application object and check for specific conditions
$app = JFactory::getApplication();
$currentUrl = JUri::getInstance()->toString();

// Check if it's homepage and not the excluded URL
if ($isHomePage && empty($_GET) && strpos($currentUrl, 'east-coast-holiday-hotel') === false) {
    // HTML content for the location section
?>
<div class="map-class-container">
    <!-- Your existing location section content stays the same -->
</div>
<?php
}
?>

<footer class="py-5" style="background-color: black !important;">
    <div class="container" style="max-width: 1400px; margin: 0 auto;">
        <div class="row gy-4"> <!-- Added gy-4 for vertical spacing between rows when they stack -->
            <!-- Footer1 Section -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="px-3">
                    <jdoc:include type="modules" name="footer1" style="raw" />
                </div>
            </div>
            
            <!-- Footer2 Section -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="px-3">
                    <jdoc:include type="modules" name="footer2" style="raw" />
                </div>
            </div>
            
            <!-- Footer3 Section -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="px-3">
                    <jdoc:include type="modules" name="footer3" style="raw" />
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="col-12">
                <hr class="mt-4 mb-4" style="border-color: rgba(255, 255, 255, 0.1);">
                <p class="text-center m-0" style="color: white; font-family: 'Noto Serif', serif;">&copy; <?php echo date("Y"); ?> All Rights Reserved to Green Leaf Resort</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" title="Go to top" style="display: none;">
    <i class="fas fa-chevron-up"></i>
</button>

<style>
#scrollToTopBtn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    border: none;
    outline: none;
    background-color: #28a745; /* Green color */
    color: white;
    cursor: pointer;
    padding: 15px;
    border-radius: 50%; /* Make it round */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-size: 20px; /* Font size for the icon */
    transition: background-color 0.3s, transform 0.3s;
}

#scrollToTopBtn:hover {
    background-color: #218838; /* Darker green shade on hover */
    transform: scale(1.1); /* Slightly enlarge on hover */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    // Show or hide the button based on scroll position
    window.onscroll = function() {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    };

    // Scroll to top when the button is clicked
    scrollToTopBtn.onclick = function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Smooth scroll
        });
    };
});
</script>

<?php // Include any debugging info ?>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
