<?php
if (!strpos(url(), "localhost")) {

    /**
     * CSS
     */
    $minCSS = new MatthiasMullie\Minify\CSS();
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/font-awesome.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/themify.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/icofont.css");

    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/animation.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/bootstrap.min.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/select2.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/slick.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/slick-theme.css");
    /*$minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/font-awesome.css");*/
    /*$minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/icofont.css");*/
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/light-box.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/magnific-popup.css");
    /*$minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/line-icon.css");*/
    /*$minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/plugins/themify.css");*/
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/styles.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/colors.css");


    //theme CSS
    /*$cssDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css");
    foreach ($cssDir as $css) {
        $cssFile = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/{$css}";
        if (is_file($cssFile) && pathinfo($cssFile)['extension'] == "css") {
            $minCSS->add($cssFile);
        }
    }*/
    //$minCSS->add(__DIR__ . "/../../../shared/assets/css/animate.css");

    //Minify CSS
    $minCSS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/style.css");


    /**
     * JS
     */
    $minJS = new MatthiasMullie\Minify\JS();
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/jquery.min.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/popper.min.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/bootstrap.min.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/select2.min.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/slick.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/lightbox.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/imagesloaded.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/jquery.magnific-popup.min.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/slider-bg.js");
    $minJS->add(__DIR__ . "/../../../shared/assets/js/jquery.mask.min.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/custom.js");

    //theme CSS
    /*$jsDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js");
    foreach ($jsDir as $js) {
        $jsFile = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/{$js}";
        if (is_file($jsFile) && pathinfo($jsFile)['extension'] == "js") {
            $minJS->add($jsFile);
        }
    }*/

    //Minify JS
    $minJS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/scripts.js");
}