<?php
/**
 * @package Joomla.Site
 * @subpackage Templates.WhiteLeafResort
 *
 * @copyright (C) YEAR Your Name
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

// Add Favicon from images folder
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'icon', 'rel', ['type' => 'image/x-icon']);

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

    <?php /** You can put links to CSS/JS just like any regular HTML page here too, and remove the jdoc:include script/style lines above if you want.
     * Do not delete the metas line though
     * 
     * For example, if you want to manually link to a custom stylesheet or script, you can do it like this:
     * <link rel="stylesheet" href="https://mysite.com/templates/mytemplate/mycss.css" type="text/css" />
     * <script src="https://mysite.com/templates/mytemplate/myscript.js"></script>
     * */ 
    ?>
    
</head>

<?php // you can change data-bs-theme to dark for dark mode  // ?>
<body class="site <?php echo $pageclass; ?>" data-bs-theme="light">
	<header >
        <div class="container">
            <a href="" class="navbar-brand" style="z-index: 10; position: relative;">
                <img src="images/logo/WhatsApp_Image_2024-11-06_at_3.30.01_PM-removebg-preview.png" alt="Site Logo" style="height: 160px; width: auto; margin-top: 25px;">
            </a>
        </div>
    </header>
            <div class="container">
                <nav class="navbar navbar-expand-lg" style="position: relative; margin-top: -40px;"> <!-- Set position relative here -->
                    <div class="container">

                        <!-- Right-aligned hamburger menu button -->
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Toggle navigation" style="margin-top: -50px;">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <?php if ($this->countModules('menu')): ?>
                            <!-- Dropdown menu with absolute positioning -->
                            <div class="collapse navbar-collapse" id="mainmenu" style="position: absolute; right: 0; left: auto; top: 100%; margin-top: -25px;"> <!-- Added top: 100% -->
                                <div class="navbar-nav" style="font-size: 25px; text-align: right;">
                                    <jdoc:include type="modules" name="menu" style="none"/>
                                </div>
                            </div> 
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
    <br>
    <?php if ($this->countModules('breadcrumbs')): ?>
        <div class="bg-primary text-white link-white">
            <div class="container">
                <jdoc:include type="modules" name="breadcrumbs" style="none"/>
            </div>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <jdoc:inlcude type="message"/>
                <jdoc:include type="component"/>
            </div>
        </div>
    </div>
    
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

