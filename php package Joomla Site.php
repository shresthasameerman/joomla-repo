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

<?php // Everything below here is the actual "template" part of the template. Where we put our HTML code for the layout and such. ?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

    <?php // Loads important metadata like the page title and viewport scaling ?>
	<jdoc:include type="metas" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <?php // Loads the site's CSS and JS files from web asset manager ?>
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
    <link rel="icon" href="images/assets/WhatsApp_Image_2024-11-06_at_3.30.01_PM-removebg-preview.png" type="image/x-icon"> <!-- If in template directory -->
        <!-- Custom CSS for the form -->
    <style>
        /* Mobile responsive styling */
        .navbar {
            background: rgba(255, 255, 255, 0.05) !important; /* Keep the existing background */
            transition: background 0.3s ease;
            width: 100%; /* Ensure the navbar takes the full width */
            margin: 0; /* Remove any margin */
            padding: 15px 20px; /* Add padding to increase the background area */
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

        @media (max-width: 6000px) {
            .navbar {
                background: rgba(255, 255, 255, 0.05) !important;
            }
    </style>  
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">



</head>

<?php // you can change data-bs-theme to dark for dark mode  // ?>
<body class="site <?php echo $pageclass; ?>" data-bs-theme="light">
<?php
$menu = JFactory::getApplication()->getMenu();
$isHomePage = $menu->getActive() == $menu->getDefault();
?>
<style>
    /* Default header height for large screens */
    header {
        position: relative;
        <?= $isHomePage ? 'height: 1000px; margin-top: -40px;' : 'margin: 0;' ?> 
        overflow: hidden;
    }

    /* Adjust background intensity and header size for small screens */
    @media (max-width: 768px) {
        header {
            height: auto !important;  /* Allow height to adjust automatically */
            margin-top: 0; /* Reset margin for mobile */
        }

        header img {
            height: auto;
            max-height: 400px; /* Adjust image height for smaller screens */
        }

        header .container {
            margin-left: 0 !important; /* Remove extra left margin */
            padding: 0px; /* Reduce padding */
            background: rgba(255, 255, 255, 0.001) !important; /* Reduce background opacity for small screens */
        }

        .navbar-brand img {
            height: 100px; /* Scale down logo size */
        }

        /* Reduce gap between header and other components */
        .row.align-items-center {
            margin-bottom: 10px !important; /* Reduce bottom margin */
        }

        /* Align header content to the left */
        .header-content {
            text-align: left; /* Align text to the left */
            margin-left: 10px; /* Add some left margin */
        }
    }

    /* Navbar menu item styles */
    .navbar-nav .nav-link {
        color: green !important; /* Set text color to green */
        font-size: 36px;
         margin-right: 20px;
        font-weight: bold;
    }

    .navbar-nav .nav-link:hover {
        color: darkgreen !important; /* Change color on hover for better visibility */
    }

    /* Add margin to the navbar */
    .navbar {
        margin-top: 20px; /* Adjust this value to increase/decrease the gap */
    }

    /* Add margin to the navbar items for horizontal spacing */
    .navbar-nav {
        margin-left: 50px; /* Adjust this value to increase/decrease the gap */
    }
</style>

<?php
$app = JFactory::getApplication();
$menu = $app->getMenu();
$isHomePage = $menu->getActive() == $menu->getDefault();
?>
<header class="site-header <?= $isHomePage ? 'home-header' : '' ?>">
    <?php if ($isHomePage): ?>
        <img class="background-image" 
             src="images/assets/suEDZJf2Rc7UnAmD2uLtXL1T6KwL0NmBhEKZKaTQ.jpg" 
             alt="Background Image" 
             onload="this.style.opacity='1'; this.style.transform='scale(1)';">
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

<style>
.site-header {
    position: relative;
    margin: 0;
    overflow: hidden;
}

.home-header {
    height: 1000px;
    margin-top: -40px;
}

.background-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transform: scale(1.1);
    transition: opacity 10s ease-out, transform 10s ease-out;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
}

.header-content {
    width: 100%;
    max-width: 1105px;
    background: rgba(255, 255, 255, 0.7);
    color: white;
    box-sizing: border-box;
    backdrop-filter: blur(5px);
    border-radius: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
}

.home-header .header-content {
    margin-left: 375px;
}

.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.8);
    padding: 10px;
}

.logo-container {
    flex: 0 0 auto;
}

.navbar-brand img {
    height: 200px;
    width: auto;
    margin-top: 5px;
}

.navbar-collapse {
    flex-grow: 1;
    display: flex;
    justify-content: flex-end;
}

/* Navbar menu items styling */
.navbar-collapse ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.navbar-collapse ul li {
    margin: 0 15px;
}

.navbar-collapse ul li a {
    color: #333;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

/* Bootstrap 5 navbar toggler button styling */
.navbar-toggler {
    padding: 0.25rem 0.75rem;
    font-size: 1.25rem;
    line-height: 1;
    background-color: transparent;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 0.25rem;
    display: none;
}

/* Responsive Breakpoints */
@media (max-width: 1200px) {
    .home-header .header-content {
        margin-left: auto;
        margin-right: auto;
    }
    
    .navbar-collapse ul li a {
        font-size: 1rem;
    }
}

@media (max-width: 991px) {
    .navbar-brand img {
        height: 150px;
    }
    
    .navbar-toggler {
        display: block;
    }
    
    .navbar-collapse {
        display: none;
        width: 100%;
        margin-top: 1rem;
    }
    
    .navbar-collapse.show {
        display: block;
    }
    
    .navbar {
        flex-wrap: wrap;
    }
    
    .navbar-collapse ul li {
        margin: 10px 0;
    }
    
    .navbar-collapse ul li a {
        font-size: 0.95rem;
    }
}

@media (max-width: 768px) {
    .header-content {
        padding: 10px;
    }
    
    .navbar-brand img {
        height: 120px;
    }
    
    .home-header {
        height: auto;
        min-height: 600px;
    }
    
    .navbar-collapse ul li a {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .navbar-brand img {
        height: 100px;
    }
    
    .home-header {
        min-height: 400px;
    }
    
    .navbar-collapse ul li a {
        font-size: 0.85rem;
    }
    
    .navbar-collapse ul li {
        margin: 8px 0;
    }
}

@media (max-width: 380px) {
    .navbar-collapse ul li a {
        font-size: 0.8rem;
    }
    
    .navbar-collapse ul li {
        margin: 6px 0;
    }
}
</style>

<!-- Add this script at the end of your body tag -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navbarToggler.addEventListener('click', function() {
        navbarCollapse.classList.toggle('show');
    });
});
</script>

<?php if ($this->countModules('breadcrumbs')): ?>
 <style>
     .mod-breadcrumbs {
         background-color: #198754; /* Green-500 background color */
         color: white; /* Ensure text color is white *//* Optional padding for better spacing */
         border-radius: 5px; /* Optional rounded corners */
     }
     .mod-breadcrumbs a, 
     .mod-breadcrumbs span {
         color: white; /* Ensure text color is white */
     }
     .mod-breadcrumbs .breadcrumb-item + .breadcrumb-item::before {
         color: white; /* Ensure separator color is white */
     }
 </style>
 <div class="mod-breadcrumbs">
     <div class="container">
         <jdoc:include type="modules" name="breadcrumbs" style="none"/>
     </div>
 </div>
<?php endif; ?>

<?php if ($this->countModules('hotel_module')): ?>
 <div class="container" style="margin-top: -100px;">
 <jdoc:include type="modules" name="hotel_module" style="none"/>
 </div>
<?php endif; ?>

 <div class="container" style="margin-bottom: 20px">
 <jdoc:include type="message"/>
 <jdoc:include type="component"/>
 </div>

        <?php
// Get the application object
$app = JFactory::getApplication();

// Check if the current page is the homepage
if ($app->getMenu()->getActive() == $app->getMenu()->getDefault()) {
    // HTML content for the homepage only
?>
<div style="display: flex; width: 90%; border-radius: 8px; overflow: hidden;" class="container">
    <!-- Left box with "Our Location" - reduced padding and adjusted margins -->
   <div style="background-color: #ECE2B1; padding: 15px 15px 15px 8px; flex: 1 1 40%; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-bottom: 50px; border-radius: 8px;"
        class="left-box">
        <h1 style="color: #2e8b57; font-family: 'Noto Serif', serif; margin: 0 0 8px; text-align: left;">Our Location</h1>
        <p style="margin: 0; font-family: Arial, sans-serif; line-height: 1.5;">
            White Leaf Resort Sukute<br>
            Araniko Highway, Kadambas 45314<br>
            PQ99+5R Kadambas<br><br>
            +977 9851342321<br>
            <a href="mailto:reservation@whiteleafresort.com" style="color: #2e8b57; text-decoration: none;">reservation@whiteleafresort.com</a>
        </p>
        <a href="https://www.google.com/maps/dir/?api=1&destination=White+Leaf+Resort+Sukute,+Araniko+Highway,+Kadambas+45314"
            target="_blank"
            style="margin-top: 15px; padding: 8px 12px; border: 2px solid #000; color: #000; text-decoration: none; border-radius: 5px; display: inline-block; text-align: center; font-family: 'Noto Serif', serif;">
            <h2 style="margin: 0; font-size: 16px; color: #000;">See Directions</h2>
        </a>
    </div>
    <!-- Right box with Google Map - adjusted width -->
    <div style="flex: 1 1 55%; position: relative; margin-bottom: 50px; overflow: hidden;">
        <div style="width: 100%; height: 0; padding-bottom: 75%; position: relative; border-radius: 8px; overflow: hidden;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.980251196604!2d85.767031911828!3d27.717896024925228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ebb15f15a00c05%3A0x768bca07f7b5b646!2sWhite%20Leaf%20Resort!5e0!3m2!1sen!2snp!4v1731065837861!5m2!1sen!2snp"
                width="100%"
                height="100%"
                style="border: 0; position: absolute; top: 0; left: 0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

<style>
/* Adjust the left box position on larger devices */
@media screen and (min-width: 992px) {
    .left-box {
        margin-left: 20px; /* Move the box to the right */
    }
}
</style>

<!-- Add media queries for responsiveness -->
<style>
    /* Flexbox setup for layout */
    div[style*="display: flex;"] {
        display: flex;
        width: 100%; /* Ensure the container takes full width */
    }

    div[style*="flex: 1 1 40%;"] {
        flex: 1 1 40%; /* Left box takes 40% */
    }

    div[style*="flex: 1 1 60%;"] {
        flex: 1 1 60%; /* Right box takes 60% */
    }

    /* Ensure both boxes behave well on smaller screens */
    @media only screen and (max-width: 768px) {
        div[style*="display: flex;"] {
            flex-direction: column !important;
            width: 100% !important; /* Full width on smaller screens */
            margin: 0 !important; /* Remove margins on smaller screens */
        }
        div[style*="flex: 1 1 40%;"], div[style*="flex: 1 1 60%;"] {
            width: 100% !i<div style="display: flex; width: 100%; border-radius: 8px; overflow: hidden;" class="container">
            
            
<!-- Container with left and right margin -->
<div class="container" style="display: flex; justify-content: space-between;  margin: 0 500px;">
    <!-- Left box with "Our Location" -->
    <div style="background-color: #ECE2B1; padding: 20px; flex: 1 1 40%; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-bottom: 50px; border-radius: 8px;">
        <h1 style="color: #2e8b57; font-family: 'Noto Serif', serif; margin: 0 0 10px; text-align: left;">Our Location</h1>
        <p style="margin: 0; font-family: Arial, sans-serif; line-height: 1.5;">
            White Leaf Resort Sukute<br>
            Araniko Highway, Kadambas 45314<br>
            PQ99+5R Kadambas<br><br>
            +977 9851342321<br>
            <a href="mailto:reservation@whiteleafresort.com" style="color: #2e8b57; text-decoration: none;">reservation@whiteleafresort.com</a>
        </p>
        <a href="https://www.google.com/maps/dir/?api=1&destination=White+Leaf+Resort+Sukute,+Araniko+Highway,+Kadambas+45314"
            target="_blank"
            style="margin-top: 20px; padding: 10px 15px; border: 2px solid #000; color: #000; text-decoration: none; border-radius: 5px; display: inline-block; text-align: center; font-family: 'Noto Serif', serif;">
            <h2 style="margin: 0; font-size: 18px; color: #000;">See Directions</h2>
        </a>
    </div>
    <!-- Right box with Google Map -->
    <div style="flex: 1 1 60%; position: relative; margin-bottom: 50px; overflow: hidden;">
        <div style="width: 100%; height: 0; padding-bottom: 75%; position: relative; border-radius: 8px; overflow: hidden;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.980251196604!2d85.767031911828!3d27.717896024925228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ebb15f15a00c05%3A0x768bca07f7b5b646!2sWhite%20Leaf%20Resort!5e0!3m2!1sen!2snp!4v1731065837861!5m2!1sen!2snp"
                width="100%"
                height="100%"
                style="border: 0; position: absolute; top: 0; left: 0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

<!-- Add styles for margins -->
<style>
    /* Add left and right margin to the container */
    .container {
        margin: 0 500px; /* Left and right margins */
    }

    /* Flexbox setup for layout */
    .container > div {
        display: flex;
        flex-direction: column;
    }

    /* Ensure both boxes behave well on smaller screens */
    @media only screen and (max-width: 768px) {
        .container {
            flex-direction: column; /* Stack boxes vertically */
            margin: 0 10px; /* Smaller margins on mobile */
        }
    }
</style>


<?php
}
?>
<footer class="py-5" style="background-color: black !important;">
    <div class="container">
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
                <p class="text-center m-0" style="color: white;">&copy; <?php echo date("Y"); ?> All Rights Reserved to Green Leaf Resort</p>
            </div>
        </div>
    </div>
</footer>

<?php // Include any debugging info ?>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
