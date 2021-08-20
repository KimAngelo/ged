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
    <?= css_version_control('style', CONF_VIEW_APP) ?>
    <link href="<?= theme("assets/css/custom.css") ?>" rel="stylesheet" type="text/css"/>
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="<?= theme('assets/media/logos/favicon.ico') ?>"/>
    <script>var BASE_SITE = '<?= url(); ?>';</script>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Error-->
    <div class="d-flex flex-row-fluid flex-column bgi-size-cover bgi-position-center bgi-no-repeat p-10 p-sm-30"
         style="background-image: url(<?= theme('assets/media/error/bg1.jpg') ?>);">
        <!--begin::Content-->
        <h1 class="font-weight-boldest text-dark-75 mt-15" style="font-size: 10rem"><?= $errcode ?></h1>
        <p class="font-size-h3 text-muted font-weight-normal">OOPS! Página não encontrada</p>
        <!--end::Content-->
    </div>
    <!--end::Error-->
</div>

<?= js_version_control('scripts', CONF_VIEW_APP) ?>
</body>
<!--end::Body-->
</html>