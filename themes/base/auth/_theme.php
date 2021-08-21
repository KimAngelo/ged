<!DOCTYPE html>

<html lang="pt-br">
<!--begin::Head-->
<head>
    <base href="<?= url() ?>">
    <meta charset="utf-8"/>
    <?= $head ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="<?= theme('assets/css/pages/login/classic/login-4.css') ?>" rel="stylesheet" type="text/css"/>
    <?= css_version_control('style', CONF_VIEW_APP) ?>
    <link href="<?= theme("assets/css/custom.css") ?>" rel="stylesheet" type="text/css"/>

    <link rel="shortcut icon" href="<?= url('storage/images/site/icon.ico') ?>"/>
    <script>var BASE_SITE = '<?= url(); ?>';</script>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
        <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat"
             style="background-image: url('<?= theme('assets/media/bg/bg-3.jpg') ?>');">
            <div class="login-form text-center p-7 position-relative overflow-hidden">
                <!--begin::Login Header-->
                <div class="d-flex flex-center mb-15">
                    <a href="#">
                        <img src="https://gedtec.rlvtecnologia.com.br/wp-content/themes/gedtec/img/logorlv.png"
                             class="max-h-75px" alt="<?= CONF_SITE_NAME ?>"/>
                    </a>
                </div>
                <?= $v->section('content') ?>
            </div>
        </div>
    </div>
    <!--end::Login-->
</div>

<?= js_version_control('scripts', CONF_VIEW_APP) ?>

</body>
<!--end::Body-->
</html>