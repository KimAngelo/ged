<?php
require __DIR__ . '/../../../vendor/autoload.php';

if (strpos(url(), "localhost")) {

    /**
     * CSS
     */
    $minCSS = new MatthiasMullie\Minify\CSS();
    $minCSS->add(__DIR__ . "/../../../shared/assets/css/animate.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/plugins/global/plugins.bundle.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/plugins/custom/prismjs/prismjs.bundle.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/css/style.bundle.css");
    $minCSS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/css/custom.css");

    //theme CSS
    /*$cssDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/css");
    foreach ($cssDir as $css) {
        $cssFile = __DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/css/{$css}";
        if (is_file($cssFile) && pathinfo($cssFile)['extension'] == "css") {
            $minCSS->add($cssFile);
        }
    }*/


    //Minify CSS
    $minCSS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/style.css");


    /**
     * JS
     */
    $minJS = new MatthiasMullie\Minify\JS();
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/js/custom.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/plugins/global/plugins.bundle.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/plugins/custom/prismjs/prismjs.bundle.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/js/scripts.bundle.js");
    $minJS->add(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/js/form.js");
    //theme CSS
    /*$jsDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/js");
    foreach ($jsDir as $js) {
        $jsFile = __DIR__ . "/../../../themes/" . CONF_VIEW_APP . "/assets/js/{$js}";
        if (is_file($jsFile) && pathinfo($jsFile)['extension'] == "js") {
            $minJS->add($jsFile);
        }
    }*/

    //Minify JS
    $minJS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/assets/scripts.js");

}