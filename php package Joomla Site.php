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
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 10px 15px;
            margin-right: auto;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            background: rgba(255, 255, 255, 0.2);
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
            min-height: 800px; /* Reduced from 600px */
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
    </style>
</head>

<body class="site <?php echo $pageclass; ?>" data-bs-theme="light">
<?php
$menu = JFactory::getApplication()->getMenu();
$isHomePage = $menu->getActive() == $menu->getDefault();
$currentUrl = JUri::getInstance()->toString();
$isHotelWhiteLeafPage = strpos($currentUrl, 'home/hotel-white-leaf-resort') !== false;
?>
<header class="site-header <?= $isHomePage ? 'home-header' : '' ?> <?= $isHotelWhiteLeafPage ? 'hotel-white-leaf-page' : '' ?>">
    <?php if ($isHomePage && !$isHotelWhiteLeafPage): ?>
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
    // Combined navbar functionality
    const navbar = document.querySelector('.navbar');
    let lastScroll = window.pageYOffset;

    // Throttled scroll handler
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                const currentScroll = window.pageYOffset;
                
                // Add/remove classes based on scroll
                if (currentScroll > 100) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                
                lastScroll = currentScroll;
                ticking = false;
            });
            ticking = true;
        }
    });

    // Dropdown handling
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        ['mouseenter', 'mouseleave'].forEach(event => {
            dropdown.addEventListener(event, function() {
                this.querySelector('.dropdown-menu').classList.toggle('show');
            });
        });
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
