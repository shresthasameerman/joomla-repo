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
// custom JS script here:
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
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

    <?php // Loads important metadata like the page title and viewport scaling ?>
	<jdoc:include type="metas" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <?php // Loads the site's CSS and JS files from web asset manager ?>
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
    <link rel="icon" href="images/assets/WhatsApp_Image_2024-11-06_at_3.30.01_PM-removebg-preview.png" type="image/x-icon"> <!-- If in template directory -->
    
    <style>
        /* Mobile responsive styling */
        .navbar {
            background: rgba(255, 255, 255, 0.05) !important; /* Keep the existing background */
            transition: background 0.3s ease;
            width: 100%; /* Ensure the navbar takes the full width */
            margin: 0; /* Remove any margin */
            padding: 15px 20px; /* Add padding to increase the background area */
            z-index: 10000;
        }

        .navbar-nav {
            display: flex; /* Use flexbox to arrange items in a single row */
            flex-wrap: nowrap; /* Prevent wrapping to the next line */
            justify-content: flex-end; /* Align items to the right (optional) */
            margin-left: -50px;
        }

        .navbar-nav .nav-link {
            white-space: nowrap; /* Prevent text wrapping within each link */
            margin-left: 0; /* Adjust left margin if necessary */
            margin-right: 5px; /* Increase this value to create a larger gap */
            padding: 1px 1px; /* Keep padding as needed */
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.55%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-nav .nav-link:hover {
            color: #555555 !important;
        }

        .container-fluid {
            padding: 0 10px;
        }

        /* Dropdown Menu Styles */
        .navbar-nav .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.95); /* Background color for dropdown */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Shadow for dropdown */
            z-index: 30000; /* Increased z-index */
        }

        .navbar-nav .dropdown-item {
            color: #198754; /* Text color for dropdown items */
            padding: 10px 15px; /* Padding for dropdown items */
        }

        .navbar-nav .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.1); /* Background color on hover */
            color: #2e8b57; /* Text color on hover */
        }

        @media (max-width: 6000px) {
            .navbar {
                background: rgba(255, 255, 255, 0.05) !important;
            }
        }
    </style>  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="site <?php echo $pageclass; ?>" data-bs-theme="light">
<?php
$menu = JFactory::getApplication()->getMenu();
$isHomePage = $menu->getActive() == $menu->getDefault();
$currentUrl = JUri::getInstance()->toString();
$isHotelWhiteLeafPage = strpos($currentUrl, 'home/hotel-white-leaf-resort') !== false;
?>
<style>
/* Base Styles */
body {
    font-family: 'Noto Serif', serif;
}

/* Header Styles */
.site-header {
    position: relative;
    margin: 0;
    overflow: hidden;
    height: auto;
    min-height: auto;
}

/* Home Page Specific Styles */
.home-header:not(.hotel-white-leaf-page) {
    min-height: 800px;
    margin-top: 0;
}

.home-header:not(.hotel-white-leaf-page) .header-content {
    width: 100%;
    max-width: 1400px;
    background: rgba(255, 255, 255, 0.3);
    color: white;
    box-sizing: border-box;
    backdrop-filter: blur(5px);
    border-radius: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    height: auto;
    padding: 0;
}

/* Non-Home Page Styles */
.site-header:not(.home-header),
.hotel-white-leaf-page .site-header {
    background-color: transparent !important;
    min-height: auto;
    height: auto;
    margin-bottom: 0;
}

.site-header:not(.home-header) .header-content,
.hotel-white-leaf-page .header-content {
    width: 100%;
    max-width: 1400px;
    background: rgba(255, 255, 255, 0.3);
    color: inherit;
    box-sizing: border-box;
    backdrop-filter: blur(5px);
    border-radius: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    height: auto;
    padding: 0;
}

/* Animation for background image */
@keyframes zoomInOut {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Background Image - Only visible on home page */
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

/* Navbar Styles - Consistent Transparent Background */
.navbar {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.01) !important;
    padding: 15px 5px;
    margin-top: 0;
    transition: all 0.3s ease;
    width: 100%;
    height: 90px;
    position: sticky;
    top: 0;
    z-index: 1000; /* Navbar z-index */
}

.logo-container {
    flex: 0 0 auto;
}

.navbar-brand img {
    height: 120px;
    width: auto;
    margin-top: 10px;
    transition: height 0.3s ease;
}

.navbar-collapse {
    flex-grow: 1;
    display: flex;
    justify-content: center;
}

.navbar-nav {
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    margin-left: auto;
}

.navbar-nav .nav-link {
    color: green !important;
    font-size: 18px;
    margin-right: 10px;
    font-weight: normal;
    white-space: nowrap;
    padding: 1px 1px;
}

.navbar-nav .nav-link:hover {
    color: darkgreen !important;
}

/* Dropdown Menu Styles */
.navbar-nav .dropdown-menu {
    background-color: rgba(255, 255, 255, 0.95); /* Background color for dropdown */
    border-radius: 5px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Shadow for dropdown */
    z-index: 30000; /* Increased z-index */
}

.navbar-nav .dropdown-item {
    color: #198754; /* Text color for dropdown items */
    padding: 10px 15px; /* Padding for dropdown items */
}

.navbar-nav .dropdown-item:hover {
    background-color: rgba(0, 0, 0, 0.1); /* Background color on hover */
    color: #2e8b57; /* Text color on hover */
}



/* Responsive Styles */
@media (max-width: 1200px) {
    .navbar-collapse ul li a {
        font-size: 1rem;
    }

    .navbar-brand img {
        height: 100px;
    }

    .navbar {
        height: 80px;
    }
}

@media (max-width: 991px) {
    .navbar-brand img {
        height: 90px;
    }

    .navbar {
        height: 70px;
    }

    .navbar-toggler {
        display: block;
    }

    .navbar-collapse {
        display: none;
        width: 100%;
        margin-top: 1rem;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        position: absolute;
        top: 5px;
    }

    .navbar-collapse.show {
        display: block;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        position: absolute;
        top: 70px;
        text-align: center;
    }

    .navbar {
        flex-wrap: wrap;
    }

    .navbar-collapse ul li {
        margin: 8px 0;
    }

    .navbar-collapse ul li a {
        font-size: 0.95rem;
    }
}

@media (max-width: 768px) {
    .home-header:not(.hotel-white-leaf-page) {
        min-height: 250px;
    }

    .navbar-brand img {
        height: 80px;
    }

    .navbar {
        height: 60px;
    }

    .navbar-collapse ul li a {
        font-size: 0.9rem;
    }

    .mod-breadcrumbs {
        margin-top: 0;
    }
}

@media (max-width: 576px) {
    .navbar-brand img {
        height: 70px;
    }

    .navbar {
        height: 50px;
    }

    .navbar-collapse ul li a {
        font-size: 0.85rem;
    }
}

@media (max-width: 380px) {
    .navbar-collapse ul li a {
        font-size: 0.8rem;
    }

    .navbar {
        height: 45px;
    }

    .navbar-brand img {
        height: 60px;
    }
}
</style>
<header class="site-header <?= $isHomePage ? 'home-header' : '' ?> <?= $isHotelWhiteLeafPage ? 'hotel-white-leaf-page' : '' ?>">
    <?php if ($isHomePage && !$isHotelWhiteLeafPage): ?>
        <img class="background-image" 
             src="images/assets/suEDZJf2Rc7UnAmD2uLtXL1T6KwL0NmBhEKZKaTQ.jpg" 
             alt="Background Image">
    <?php endif; ?>
    
    <div class="header-content">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="logo-container">
                    <a href="" class="navbar-brand">
                        <img src="images/assets/Untitled_design-removebg-preview.png" alt="Site Logo">
                    </a>
                </div>
            
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            
                <div class="collapse navbar-collapse" id="navbarNav">
                    <jdoc:include type="modules" name="menu" style="none" />
                </div>
            </nav>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
        z-index: -100; /* Decreased z-index */
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

<?php if ($this->countModules('hotel_module')): ?>
 <div class="hotel-module">
     <jdoc:include type="modules" name="hotel_module" style="none"/>
 </div>
 <style>
     .hotel-module {
         width: 100%;
         max-width: 1400px;
         background: rgba(255, 255, 255, 0.2);
         color: white;
         box-sizing: border-box;
         backdrop-filter: blur(5px);
         border-radius: 20px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
         margin: 0 auto;
         margin-top: -75px; /* Default margin for larger screens */
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
// Get the application object
$app = JFactory::getApplication();

// Check if the current page is the homepage
if ($app->getMenu()->getActive() == $app->getMenu()->getDefault()) {
    // HTML content for the homepage only
?>
<div class="map-class-container">
    <!-- Left box with "Our Location" -->
    <div class="left-box">
        <h2>Our Location</h2>
        <p>
            White Leaf Resort Sukute<br>
            Araniko Highway, Kadambas 45314<br>
            PQ99+5R Kadambas<br><br>
            +977 9851342321<br>
            <a href="mailto:reservation@whiteleafresort.com">reservation@whiteleafresort.com</a>
        </p>
        <a href="https://www.google.com/maps/dir/?api=1&destination=White+Leaf+Resort+Sukute,+Araniko+Highway,+Kadambas+45314"
            target="_blank" class="directions-button">
            <h2>See Directions</h2>
        </a>
    </div>
    <!-- Right box with Google Map -->
    <div class="right-box">
        <div class="map-wrapper">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.980251196604!2d85.767031911828!3d27.717896024925228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ebb15f15a00c05%3A0x768bca07f7b5b646!2sWhite%20Leaf%20Resort!5e0!3m2!1sen!2snp!4v1731065837861!5m2!1sen!2snp"
                width="100%"
                height="100%"
                style="border: 0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

<!-- CSS for layout adjustments -->
<style>
/* Container adjustments */
.map-class-container {
    display: flex;
    justify-content: center; /* Center horizontally */
    width: 100%;
    max-width: 1400px; /* Maximum width */
    margin: 0 auto; /* Center the container */
}

/* Left box styling */
.left-box {
    background-color: #ECE2B1;
    padding: 15px;
    flex: 0 0 40%; /* Set the left box to 40% */
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    border-radius: 8px;
}

.left-box h2 {
    color: #2e8b57;
    font-family: 'Noto Serif', serif;
    margin: 0 0 8px;
}

.left-box p {
    margin: 0;
    font-family: Arial, sans-serif;
    line-height: 1.5;
}

.left-box a {
    color: #2e8b57;
    text-decoration: none;
}

.directions-button {
    margin-top: 15px;
    padding: 8px 12px;
    border: 2px solid #000;
    color: #000;
    text-decoration: none;
    border-radius: 5px;
    display: inline-block;
    text-align: center;
    font-family: 'Noto Serif', serif;
}

.directions-button h2 {
    margin: 0;
    font-size: 16px;
    color: #000;
}

/* Right box styling */
.right-box {
    flex: 0 0 60%; /* Set the right box to 60% */
    position: relative;
    border-radius: 8px;
    overflow: hidden;
}

.map-wrapper {
    width: 100%;
    padding-bottom: 75%; /* Aspect ratio */
    position: relative;
    border-radius: 8px;
    overflow: hidden;
}

.map-wrapper iframe {
    width: 100%;
    height: 100%;
    border: 0;
    position: absolute;
    top: 0;
    left: 0;
}

/* Responsive layout */
@media only screen and (max-width: 768px) {
    .map-class-container {
        flex-direction: column; /* Stack vertically */
        padding: 0 10px; /* Smaller padding */
    }

    .left-box, .right-box {
        width: 100%; /* Full width on mobile */
        margin-bottom: 20px; /* Space between boxes */
    }
}
</style>
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
