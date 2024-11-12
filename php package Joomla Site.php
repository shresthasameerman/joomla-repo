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

    <?php // Loads the site's CSS and JS files from web asset manager ?>
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
    <link rel="icon" href="<?php echo $this->baseurl; ?>/templates/joomstarter/favicon.ico" type="image/x-icon">
        <!-- Custom CSS for the form -->
    <style>
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }
        form > label {
            flex: 0 0 80px;
            min-width: 80px;
            text-align: right;
            margin-right: 5px;
            color: #004933;
        }
        form > input, form > div, form > button {
            flex: 1 1 100px;
            min-width: 100px;
            padding: 5px;
        }
        button {
            background-color: #004933;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 20px;
        }
        /* Mobile responsive styling */
        @media (max-width: 600px) {
            form {
                flex-direction: column;
                align-items: flex-start;
            }
            form > label {
                text-align: left;
                flex: 1 1 auto;
                min-width: unset;
            }
            form > div, form > input, form > button {
                flex: 1 1 auto;
                width: 100%;
                max-width: none;
            }
            button {
                margin-top: 15px;
            }
        }
        .navbar {
            background: rgba(255, 255, 255, 0.05) !important;
            transition: background 0.3s ease;
        }

        .navbar-nav .nav-link {
            color: #2E8B57 !important;
            font-family: 'Noto Serif', serif;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.55%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-nav .nav-link:hover {
            color: #555555 !important;
        }

        .container-fluid {
            padding: 0 15px;
        }

        @media (max-width: 991.98px) {
            .navbar {
                background: rgba(255, 255, 255, 0.05) !important;
            }
    </style>  
      
</head>

<?php // you can change data-bs-theme to dark for dark mode  // ?>
<body class="site <?php echo $pageclass; ?>" data-bs-theme="light">
<header>
 <div class="container">
 <div class="row align-items-center" style="margin-bottom: -40px">
 <div class="col-lg-3 col-md-4 col-sm-6">
 <a href="" class="navbar-brand" style="z-index: 10; position: relative;">
 <img src="images/logo/Untitled_design-removebg-preview.png" alt="Site Logo" style="height: 200px; width: auto; margin-top: 5px;">
 </a>
 </div>
 <div class="col-lg-9 col-md-8 col-sm-6">
 <nav class="navbar navbar-expand-lg navbar-light" style="position: relative;margin-bottom:20px; background: rgba(255, 255, 255, 0.05);">
 <div class="container-fluid">
 <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Toggle navigation" style="margin-top:-55px">
 <span class="navbar-toggler-icon"></span>
 </button>

<?php if ($this->countModules('menu')): ?>
 <div class="collapse navbar-collapse" id="mainmenu">
 <div class="navbar-nav ms-auto" style="font-size: 24px;">
 <!-- Override module chrome to prevent nested hamburger -->
 <jdoc:include type="modules" name="menu" style="raw"/>
 </div>
 </div>
<?php endif; ?>
 </div>
 </nav>
 </div>
 </div>
 </div>
</header>
    <?php if ($this->countModules('breadcrumbs')): ?>
        <div class="bg-primary text-white link-white">
            <div class="container">
                <jdoc:include type="modules" name="breadcrumbs" style="none"/>
            </div>
        </div>
    <?php endif; ?>
        <div class="row">
                <jdoc:inlcude type="message"/>
                <jdoc:include type="component"/>
        </div>

        <?php
// Get the application object
$app = JFactory::getApplication();

// Check if the current page is the homepage
if ($app->getMenu()->getActive() == $app->getMenu()->getDefault()) {
    // HTML content for the homepage only
?>
    <div style="display: flex; border-radius: 8px; overflow: hidden; margin-left: 185px; margin-right: 185px; ">
    <!-- Left box with "Our Location" -->
    <div style="background-color: #ECE2B1; padding: 20px; width: 40%; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-bottom: 50px; border-radius: 8px;">
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
    <div style="width: 60%; position: relative;">
            <div style="width: 100%; height: 0; padding-bottom: 75%; position: relative; border-radius: 8px; overflow: hidden; margin-bottom: 50px;">
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


    <!-- Add media queries for responsiveness -->
    <style>
        @media only screen and (max-width: 768px) {
            .container {
                flex-direction: column !important;
            }
            .container > div {
                width: 100% !important;
                margin-bottom: 30px;
            }
        }
    </style>
<?php
}
?>


    
        
        

    
    <div class="bg-black text-white">
        <div class="container">
            <footer class="row">
                <!-- Footer1 Section -->
                <div class="col-12 col-lg-4">
                    <jdoc:include type="modules" name="footer1" style="none" />
                </div>

                <!-- Footer2 Section -->
                <div class="col-12 col-lg-4">
                    <jdoc:include type="modules" name="footer2" style="none" />
                </div>

                <!-- Footer3 Section -->
                <div class="col-12 col-lg-3">
                    <jdoc:include type="modules" name="footer3" style="none" />
                </div>

                <!-- Copyright -->
                <div class="col-12 col-lg-3 text-center">
                    <span>&copy; <?php echo date("Y"); ?> Green Leaf Resort</span>
                </div>

            </footer>
        </div>
    </div>


    <?php // Include any debugging info ?>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>

