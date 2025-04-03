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
            height: 40px; /* Reduced from 50px */
            width: auto;
            transition: height 0.3s ease;
            margin-top: 0;
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
                height: 35px; /* Reduced from 45px */
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

            .navbar-brand img {
                height: 30px; /* Reduced from 40px */
                margin-top: 0;
            }
        }

        @media (max-width: 576px) {
            .home-header:not(.hotel-white-leaf-page) {
                min-height: 250px;  /* Reduced from 300px */
            }

            .navbar-brand {
                padding: 5px 10px;
            }

            .navbar-brand img {
                height: 25px; /* Reduced from 35px */
                margin-top: 0;
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
            height: 40px; /* Reduced from 50px */
            margin-top: 0;
        }

        @media (max-width: 991px) {
            .booking-page .navbar-brand img {
                height: 35px; /* Reduced from 45px */
                margin-top: 0;
            }
        }

        @media (max-width: 768px) {
            .booking-page .navbar-brand img {
                height: 30px; /* Reduced from 40px */
                margin-top: 0;
            }
        }

        @media (max-width: 576px) {
            .booking-page .navbar-brand img {
                height: 25px; /* Reduced from 35px */
                margin-top: 0;
            }
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

        /* Navbar styling */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            background: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            padding: 15px 20px;
            height: 90px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Logo container styling */
        .navbar-brand {
            background: rgba(255, 255, 255, 0.005);
            border-radius: 15px;
            padding: 10px 15px;
            margin-right: auto;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            background: rgba(255, 255, 255, 0.001);
            transform: translateY(-2px);
        }

        /* Logo image styling - MODIFIED */
        .navbar-brand img {
            height: 40px; /* Reduced from 50px */
            width: auto;
            transition: height 0.3s ease;
            margin-top: 0;
        }

        /* Navigation links */
        .navbar-nav {
            display: flex;
            flex-wrap: nowrap;
            justify-content: flex-end;
            margin-left: -50px;
        }

        .navbar-nav .nav-link {
            color: #32cd32 !important;
            font-size: 18px;
            margin-right: 10px;
            white-space: nowrap;
            padding: 1px 1px;
            text-align: right;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #228b22 !important;
        }

        /* Responsive styles */
        @media (max-width: 991px) {
            .navbar {
                height: 80px;
                background: rgba(0, 0, 0, 0.55) !important;
            }

            .navbar-brand {
                padding: 8px 12px;
            }

            .navbar-brand img {
                height: 35px; /* Reduced from 45px */
                margin-top: 0;
            }

            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: rgba(0, 0, 0, 0.7);
                padding: 10px;
                border-radius: 0 0 10px 10px;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand img {
                height: 30px; /* Reduced from 40px */
                margin-top: 0;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                padding: 5px 10px;
            }

            .navbar-brand img {
                height: 25px; /* Reduced from 35px */
                margin-top: 0;
            }

            .navbar {
                padding: 5px 15px;
            }
        }

        /* Update the brand container and text styles */
        .brand-container {
            display: flex;
            align-items: center;
            gap: 0; /* Reduced from 10px to 0 */
            margin-left: -45px; /* Added negative margin to shift content left */
        }

        .brand-text {
            color: #198754;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
            margin-left: -105px; /* Added negative margin to move text closer to logo */
            margin-top: 50px; /* Adjusted margin to align with logo */
        }

        /* Update responsive styles */
        @media (max-width: 768px) {
            .brand-text {
                font-size: 20px;
                margin-left: -10px; /* Slightly less negative margin for smaller screens */
            }
        }

        @media (max-width: 576px) {
            .brand-text {
                font-size: 18px;
                margin-left: -5px; /* Even less negative margin for mobile */
            }
        }

        /* Update brand text styles */
        .navbar .brand-container .brand-text {
            color: #198754;
            font-size: 15px !important; /* Fixed at 10px */
            font-weight: bold;
            white-space: nowrap;
            margin-left: -115px;
        }

        /* Remove font-size changes from media queries */
        @media (max-width: 768px) {
            .navbar .brand-container .brand-text {
                margin-left: -10px;
            }
        }

        @media (max-width: 576px) {
            .navbar .brand-container .brand-text {
                margin-left: -5px;
            }
        }
       /* Mobile-first adjustments */
@media (max-width: 768px) {
    /* Header height reduction */
    .home-header:not(.hotel-white-leaf-page) {
        min-height: 60vh !important;
        max-height: 500px;
    }
    
    /* Navbar container */
    .navbar {
        height: 70px;
        padding: 5px 15px;
        background: rgba(50, 50, 50, 0.95) !important;
    }
    
    /* Brand container */
    .brand-container {
        flex-direction: row;
        align-items: center;
        margin-left: 0;
        gap: 8px;
    }
    
    /* Logo sizing */
    .navbar-brand img {
        height: 50px;
        width: auto;
        margin-left: 0;
    }
    
    /* Brand text */
    .brand-text {
        font-size: 16px;
        margin-left: 0;
        margin-top: 0;
        color: white !important;
    }
    
    /* Menu items */
    .navbar-nav .nav-link {
        padding: 12px 15px !important;
        font-size: 16px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    /* Dropdown menus */
    .dropdown-menu {
        position: static !important;
        float: none;
        width: 100%;
        background: rgba(70,70,70,0.9) !important;
        border: none;
        box-shadow: none;
    }
    
    .dropdown-item {
        padding-left: 25px !important;
    }
    
    /* Toggler button */
    .navbar-toggler {
        padding: 8px 10px;
        border-color: rgba(255,255,255,0.5);
    }
    
    /* Collapsed menu */
    .navbar-collapse {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(50,50,50,0.95);
        padding: 0 15px;
        max-height: 70vh;
        overflow-y: auto;
    }
}

/* Extra small devices (phones) */
@media (max-width: 576px) {
    .home-header:not(.hotel-white-leaf-page) {
        min-height: 50vh !important;
        max-height: 400px;
    }
    
    .navbar-brand img {
        height: 40px;
    }
    
    .brand-text {
        font-size: 14px;
    }
    
    .navbar-nav .nav-link {
        font-size: 15px;
        padding: 10px 15px !important;
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
            <div class="background-slider">
                <img class="background-image active" 
                     src="images/assets/suEDZJf2Rc7UnAmD2uLtXL1T6KwL0NmBhEKZKaTQ.jpg" 
                     alt="Background Image 1">
                <img class="background-image" 
                     src="images/assets/rafting%20.jpg" 
                     alt="Background Image 2">
                <img class="background-image" 
                     src="images/assets/swimming%20pool.jpeg" 
                     alt="Background Image 3">
            </div>
        <?php endif; ?>
        
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <div class="brand-container">
                        <img src="images/assets/White%20Leaf%20Resort_Leafonly1.png" alt="Site Logo" style="height: 100px; width: auto; transform: scale(0.5); margin-left: -25px;">
                        <span class="brand-text">White Leaf Resort</span>
                    </div>
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

    <style>
    .background-slider {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 1.5s ease-in-out;
        z-index: 0;
    }

    .background-image.active {
        opacity: 1;

        z-index: 1;
    }

    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
        background: rgba(0, 0, 0, 0.5) !important; /* Semi-transparent black background */
        backdrop-filter: blur(10px); /* Blur effect for background */
        transition: all 0.3s ease;
        padding: 15px 20px;
        height: 90px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
.navbar-nav .nav-link {
    color: #ffffff !important; /* White text color */
    font-size: 18px;
    margin-right: 10px;
    white-space: nowrap;
    padding: 1px 1px;
    text-align: right;
    transition: color 0.3s ease;
}
.navbar-nav .nav-link:hover {
    color: #e0e0e0 !important; /* Light gray on hover for subtle effect */
}
    .navbar-brand img {
        height: 85px;
        width: auto;
    }

    .brand-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .brand-text {
        color: #198754;
        font-size: 18px;
        font-weight: bold;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .brand-text {
            font-size: 20px;
        }
    }

    @media (max-width: 576px) {
        .brand-text {
            font-size: 18px;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.background-slider .background-image');
        let currentIndex = 0;

        setInterval(() => {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }, 5000); // Change image every 5 seconds

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

    // Add this to your existing script
document.addEventListener('DOMContentLoaded', function() {
    // Better dropdown handling for mobile
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                menu.classList.toggle('show');
                
                // Close other open dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                    if (otherMenu !== menu && otherMenu.classList.contains('show')) {
                        otherMenu.classList.remove('show');
                    }
                });
            }
        });
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && !e.target.closest('.navbar-collapse') && !e.target.closest('.navbar-toggler')) {
            document.querySelector('.navbar-collapse').classList.remove('show');
        }
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
        margin-top: -200px; /* Increased negative margin to move further upward */
        z-index: 1002;
        position: relative;
    }

    @media (max-width: 768px) {
        .hotel-module {
           margin-top: -100px; /* Adjusted margin-top for small screens */
        }
    }
</style>
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
        .white-leaf-facilities {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        max-width: 1450px;
        margin: 0 auto;
        padding: 20px;
    }

    @media (max-width: 1200px) {
        .white-leaf-facilities {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 900px) {
        .white-leaf-facilities {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .white-leaf-facilities {
            grid-template-columns: 1fr;
        }
    }

    .facility-card {
        background-color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        cursor: pointer;
        height: 350px; /* Fixed height to maintain grid consistency */
    }

    .facility-card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .facility-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .facility-card:hover .facility-popup {
        display: flex;
        opacity: 1;
    }

    .facility-popup-content {
        background-color: white;
        border-radius: 15px;
        padding: 30px;
        max-width: 800px;
        width: 90%;
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    .facility-popup:hover .facility-popup-content {
        transform: scale(1);
    }

    .facility-popup-close {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        cursor: pointer;
        color: #2e8b57;
    }

    @media (max-width: 768px) {
        .facility-popup-content {
            flex-direction: column;
            text-align: center;
        }
        .facility-popup-content img {
            width: 100% !important;
            margin-bottom: 20px;
        }
    }
    </style>
    <style>
    div[style*="background-color: #ffffff"]:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>
<style>
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>
    <!-- Add the hover effect CSS with fixed button visibility -->
<style>
    .hotel-classs{
        background-color: var(--bg-green-50);
    }
    .service-card {
        position: relative;
        margin-bottom: 80px; /* Even more space for description and button */
        cursor: pointer;
    }
    
    .service-card:hover .service-description,
    .service-card:hover .read-more {
        opacity: 1;
        visibility: visible;
    }
    
    .service-card:hover img {
        transform: scale(1.05);
    }
    
    .service-description {
        position: absolute;
        bottom: -40px;
        left: 0;
        right: 0;
        z-index: 10;
        background-color: rgba(255, 255, 255, 0.98);
        color: #333;
        padding: 12px;
        font-size: 14px;
        line-height: 1.4;
        text-align: left;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 0 0 8px 8px;
        border-top: 2px solid #4caf50;
    }
    
    .read-more {
        position: absolute;
        bottom: -70px; /* Adjusted position */
        right: 10px;
        z-index: 11;
        font-size: 14px;
        font-weight: bold;
        color: white !important;
        text-decoration: none;
        background-color: #4caf50;
        padding: 6px 12px;
        border-radius: 4px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease, background-color 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        display: inline-block; /* Ensure it's displayed as a block */
    }
    
    .read-more:hover {
        background-color: #3d8b3d;
    }
    
    /* Debug styling to help see the button */
    .read-more::after {
        content: " →"; /* Add an arrow to make it more visible */
    }

    .hotel-class {
        background-color: var(--bg-green-50);
    }
</style>

  <script>
        document.querySelectorAll('.facility-popup-close').forEach(closeBtn => {
        closeBtn.addEventListener('click', (e) => {
            e.closest('.facility-popup').style.display = 'none';
        });
    });
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
    document.querySelectorAll('a').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.hotel-description').style.display = 'block';
        });
        card.addEventListener('mouseleave', function() {
            this.querySelector('.hotel-description').style.display = 'none';
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
  // For Our Hotels section
  const hotelBoxes = document.querySelectorAll('.hotel-description').forEach(function(desc) {
    const parentDiv = desc.parentElement;
    parentDiv.addEventListener('mouseover', function() {
      desc.style.display = 'block';
    });
    parentDiv.addEventListener('mouseout', function() {
      desc.style.display = 'none';
    });
  });
});
        document.addEventListener('DOMContentLoaded', function() {
            // Handle hover effects for service cards
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(card => {
                const title = card.querySelector('.service-title');
                const description = card.querySelector('.service-description');
                const readMore = card.querySelector('.read-more');
                const img = card.querySelector('img');
                
                card.addEventListener('mouseenter', function() {
                    title.style.bottom = '50px';
                    description.style.bottom = '0';
                    readMore.style.opacity = '1';
                    img.style.transform = 'scale(1.05)';
                    card.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    title.style.bottom = '0';
                    description.style.bottom = '-100%';
            messages.forEach(message => {
                message.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
                });
                
                message.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = 'none';
                });
            });

            // Handle hover effects for components
            const components = document.querySelectorAll('.component-content');
            components.forEach(component => {
                component.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'all 0.3s ease';
                });
                
                component.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</div>
<?php if ($isHomePage): ?>
<div class="green-location-container">
    <div class="location-content">
        <div class="location-text">
            <h2>Ready to Visit Our Location?</h2>
            <p>Discover the perfect escape at White Leaf Resort. Nestled in the heart of nature, we offer an unforgettable experience.</p>
            <div class="location-details">
                <div class="detail-item">
                    <strong>Address:</strong>
                    White Leaf Resort Sukute<br>
                    Araniko Highway, Sindhupalchowk<br>
                    PQ99+5R Kadambas
                </div>
                <div class="detail-item">
                    <strong>Contact:</strong>
                    +977 9851342321<br>
                    reservation@whiteleafresort.com
                </div>
            </div>
            <a href="https://www.google.com/maps/dir/?api=1&destination=White+Leaf+Resort+Sukute,+Araniko+Highway,+Kadambas+45314" 
               target="_blank" 
               class="get-directions-btn">Get Directions</a>
        </div>
        <div class="location-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.980251196604!2d85.767031911828!3d27.717896024925228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ebb15f15a00c05%3A0x768bca07f7b5b646!2sWhite%20Leaf%20Resort!5e0!3m2!1sen!2snp!4v1731065837861!5m2!1sen!2snp"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

<style>
.green-location-container {
    background-color: #2ecc71;
    padding: 60px 0;
    color: white;
    font-family: 'Arial', sans-serif;
}

.location-content {
    display: flex;
    flex-wrap: wrap;
    max-width: 1400px;
    margin: 0 auto;
    align-items: center;
    gap: 40px;
}

.location-text {
    flex: 1;
    padding-right: 20px;
}

.location-text h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    font-weight: bold;
}

.location-text p {
    font-size: 1.1rem;
    margin-bottom: 20px;
    line-height: 1.6;
}

.location-details {
    margin-bottom: 30px;
}

.detail-item {
    margin-bottom: 15px;
}

.detail-item strong {
    display: block;
    margin-bottom: 5px;
    font-size: 1rem;
}

.get-directions-btn {
    display: inline-block;
    background-color: white;
    color: #2ecc71;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.3s;
}

.get-directions-btn:hover {
    background-color: #f0f0f0;
    transform: translateY(-3px);
}

.location-map {
    flex: 1;
    height: 400px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .location-content {
        flex-direction: column;
        text-align: center;
        padding: 0 20px;
    }

    .location-text {
        padding-right: 0;
        margin-bottom: 30px;
    }

    .location-map {
        width: 100%;
        height: 300px;
    }
}
</style>
<?php endif; ?>
<footer class="py-5" style="background-color: #14532d !important; color: #bbf7d0;">
    <div class="container" style="max-width: 1450px; margin: 0 auto;">
        <div class="row gy-4">
            <!-- Footer1 Section: GreenStay -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="px-3">
                    <jdoc:include type="modules" name="footer1" style="raw" />
                </div>
            </div>
            
            <!-- Footer2 Section: Our Hotels -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="px-3">
                    <jdoc:include type="modules" name="footer2" style="raw" />
                </div>
            </div>
            
            <!-- Footer3 Section: Information -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="px-3">
                    <jdoc:include type="modules" name="footer3" style="raw" />
                </div>
            </div>
            
            <!-- Footer4 Section: Contact -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="px-3">
                    <jdoc:include type="modules" name="footer4" style="raw;" />
                </div>
            </div>
            
            <!-- Copyright and Legal Links -->
            <div class="col-12">
                <hr class="mt-4 mb-3" style="border-color: rgba(255, 255, 255, 0.1);">
                <div class="text-center">
                    <p class="m-0 mb-2" style="color: rgba(255,255,255,0.7);">
                        &copy; <?php echo date("Y"); ?> White Leaf Resort. All Rights Reserved.
                    </p>
                    <div class="footer-links">
                        <a href="#" class="me-3" style="color: rgba(255,255,255,0.7); text-decoration: none;">Privacy Policy</a>
                        <a href="#" class="me-3" style="color: rgba(255,255,255,0.7); text-decoration: none;">Terms of Service</a>
                        <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none;">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    footer {
        font-family: Arial, sans-serif;
        color: #bbf7d0;
    }
    
    footer h5 {
        color: white;
        font-size: 18px;
    }
    
    footer ul {
        list-style: none;
        padding: 0;
    }
    
    footer ul li {
        margin-bottom: 10px;
    }
    
    footer ul li a {
        color: #bbf7d0;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    footer ul li a:hover {
        color: white;
        text-decoration: underline;
    }
    
    .footer-social a {
        font-size: 20px;
        transition: color 0.3s ease;
    }
    
    .footer-social a:hover {
        color: #bbf7d0;
    }
    
    .footer-links a:hover {
        color: white !important;
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        footer .text-center {
            text-align: center !important;
        }
        
        .footer-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .footer-links a {
            margin: 5px 0 !important;
        }
    }
    a:hover {
        background-color: rgba(204, 204, 204, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add dynamic year update
        const yearElement = document.querySelector('footer .m-0');
        if (yearElement) {
            yearElement.innerHTML = `&copy; ${new Date().getFullYear()} White Leaf Resort. All Rights Reserved.`;
        }
    });
</script>

<?php // Include any debugging info ?>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
