<?php
require __DIR__ . '/../vendor/autoload.php';

/**
 * CSS
 */
$minCSS = new MatthiasMullie\Minify\CSS();
$minCSS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/vendors/base/vendors.bundle.css");
$minCSS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/demo/demo11/base/style.bundle.css");
$minCSS->add(__DIR__ . "/../shared/assets/fontawesome/css/all.min.css");
$minCSS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/css/custom.css");

//Minify CSS
$minCSS->minify(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/style.css");


/**
 * JS
 */
$minJS = new MatthiasMullie\Minify\JS();
$minJS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/js/webfonts.js");
$minJS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/js/webfont_load.js");
$minJS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/vendors/base/vendors.bundle.js");
$minJS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/demo/demo11/base/scripts.bundle.js");
$minJS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/app/js/dashboard.js");
$minJS->add(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/demo/demo11/custom/components/base/toastr.js");
$minJS->add(__DIR__ . "/../shared/assets/js/jquery.mask.min.js");

//Minify JS
$minJS->minify(__DIR__ . "/../themes/" . CONF_VIEW_PANEL . "/assets/scripts.js");