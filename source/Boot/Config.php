<?php


use Source\Core\Environment;

require __DIR__ . "/../../vendor/autoload.php";

//Inicia variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

/**
 * PROJECT URLs
 */
define("CONF_URL_TEST", $_ENV['URL_TEST']);
define("CONF_URL_BASE", $_ENV['URL_BASE']);

/**
 * DATABASE
 */

define("DATA_LAYER_CONFIG", [
    "driver" => $_ENV['CONF_DB_DRIVER'],
    "host" => $_ENV['CONF_DB_HOST'],
    "port" => $_ENV['CONF_DB_PORT'],
    "dbname" => $_ENV['CONF_DB_NAME'],
    "username" => $_ENV['CONF_DB_USER'],
    "passwd" => $_ENV['CONF_DB_PASS'],
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);


/**
 * SITE
 */
define("CONF_SITE_NAME", $_ENV['CONF_SITE_NAME']);
define("CONF_SITE_TITLE", $_ENV['CONF_SITE_TITLE']);
define("CONF_SITE_DESC", $_ENV['CONF_SITE_DESC']);
define("CONF_SITE_LANG", $_ENV['CONF_SITE_LANG']);
define("CONF_SITE_DOMAIN", $_ENV['CONF_SITE_DOMAIN']);
define("CONF_SITE_ADDR_STREET", "");
define("CONF_SITE_ADDR_NUMBER", "");
define("CONF_SITE_ADDR_COMPLEMENT", "");
define("CONF_SITE_ADDR_CITY", "");
define("CONF_SITE_ADDR_STATE", "");
define("CONF_SITE_ADDR_ZIPCODE", "");
define("CONF_SITE_PHONE", "");
define("CONF_SITE_EMAIL", "");

/**
 * IMAGES SITE
 */
define("CONF_IMAGE_LOGO", 'logo.png');//500x200
define("CONF_IMAGE_ICON", 'icon.png');//150x150
define("CONF_SITE_SHARE", 'share.png');//1280x620

/**
 * SOCIAL
 */
define("CONF_SOCIAL_TWITTER_CREATOR", "@kimangelo");
define("CONF_SOCIAL_TWITTER_PUBLISHER", "@kimangelo");
define("CONF_SOCIAL_FACEBOOK_APP", "626590460695980");
define("CONF_SOCIAL_FACEBOOK_PAGE", "zerocomissao");
define("CONF_SOCIAL_FACEBOOK_AUTHOR", "kim.angelo.dev");
define("CONF_SOCIAL_GOOGLE_PAGE", "107305124528362639842");
define("CONF_SOCIAL_GOOGLE_AUTHOR", "103958419096641225872");
define("CONF_SOCIAL_INSTAGRAM_PAGE", "zero.comissao");
define("CONF_SOCIAL_YOUTUBE_PAGE", "zero.comissao");


/**
 * DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");
define("CONF_DATE_CARD", "m-Y");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "web");
define("CONF_VIEW_APP", "app");
define("CONF_VIEW_PANEL", "panel");

/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_IMAGE_DIR_SITE", CONF_UPLOAD_IMAGE_DIR . "/site");
define("CONF_UPLOAD_IMAGE_DIR_PROFILE", CONF_UPLOAD_IMAGE_DIR . "/profile");
define("CONF_UPLOAD_IMAGE_DIR_ADVERT", CONF_UPLOAD_IMAGE_DIR . "/adverts");
define("CONF_UPLOAD_IMAGE_DIR_BLOG", CONF_UPLOAD_IMAGE_DIR . "/blog");
define("CONF_UPLOAD_IMAGE_DIR_RENT", CONF_UPLOAD_IMAGE_DIR . "/rent");

/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MAIL
 */
define("CONF_MAIL_HOST", $_ENV['CONF_MAIL_HOST']);
define("CONF_MAIL_PORT", $_ENV['CONF_MAIL_PORT']);
define("CONF_MAIL_USER", $_ENV['CONF_MAIL_USER']);
define("CONF_MAIL_PASS", $_ENV['CONF_MAIL_PASS']);
define("CONF_MAIL_SENDER", ["name" => $_ENV['CONF_MAIL_SENDER_NAME'], "address" => $_ENV['CONF_MAIL_SENDER_ADDRESS']]);
define("CONF_MAIL_SUPPORT", $_ENV['CONF_MAIL_SUPPORT']);
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");


