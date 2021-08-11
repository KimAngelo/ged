<?php
require __DIR__.'/../../../vendor/autoload.php';

if (!strpos(url(), "localhost")) {

    /**
     * CSS
     */
    $minCSS = new MatthiasMullie\Minify\CSS();
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/vendors/base/vendors.bundle.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/demo/demo11/base/style.bundle.css");
    $minCSS->add(__DIR__ . "/../../../shared/assets/fontawesome/css/all.min.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/css/custom.css");

    //theme CSS
    /*$cssDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/css");
    foreach ($cssDir as $css) {
        $cssFile = __DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/css/{$css}";
        if (is_file($cssFile) && pathinfo($cssFile)['extension'] == "css") {
            $minCSS->add($cssFile);
        }
    }*/


    //Minify CSS
    $minCSS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/style.css");


    /**
     * JS
     */
    $minJS = new MatthiasMullie\Minify\JS();
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/js/webfonts.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/js/webfont_load.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/vendors/base/vendors.bundle.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/demo/demo11/base/scripts.bundle.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/app/js/dashboard.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/demo/demo11/custom/components/base/toastr.js");
    $minJS->add(__DIR__ . "/../../../shared/assets/js/jquery.mask.min.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/js/form.js");
    //theme CSS
    /*$jsDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/js");
    foreach ($jsDir as $js) {
        $jsFile = __DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/js/{$js}";
        if (is_file($jsFile) && pathinfo($jsFile)['extension'] == "js") {
            $minJS->add($jsFile);
        }
    }*/

    //Minify JS
    $minJS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/scripts.js");
}