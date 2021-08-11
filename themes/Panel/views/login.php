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
    <link href="<?= theme("assets/css/pages/login/login-3.css", CONF_VIEW_PANEL) ?>" rel="stylesheet" type="text/css"/>
    <?= css_version_control('style', CONF_VIEW_PANEL) ?>
    <link rel="icon" type="imagem/png"
          href="<?= url(CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/" . CONF_IMAGE_ICON) ?>"/>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body"
      class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-3 wizard d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="login-aside d-flex flex-column flex-row-auto">
            <!--begin::Aside Top-->
            <div class="d-flex flex-column-auto flex-column pt-lg-1 pt-15">
                <!--begin::Aside header-->
                <a href="#" class="login-logo text-center pt-lg-10 pb-10">
                    <img src="<?= url("/storage/images/site/" . CONF_IMAGE_LOGO); ?>" class="max-h-70px"
                         alt="<?= CONF_SITE_NAME ?>"/>
                </a>
                <!--end::Aside header-->
            </div>
            <!--end::Aside Top-->
            <!--begin::Aside Bottom-->
            <div class="d-none d-lg-block">
                <div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-x-center"
                     style="background-position-y: calc(100% + 5rem); background-image: url(<?= theme('assets/media/svg/illustrations/login-visual-5.svg', CONF_VIEW_PANEL) ?>)"></div>
            </div>

            <!--end::Aside Bottom-->
        </div>
        <!--begin::Aside-->
        <!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column p-10">
            <!--begin::Wrapper-->
            <div class="d-flex flex-row-fluid flex-center">
                <!--begin::Signin-->
                <div class="login-form">
                    <!--begin::Form-->
                    <form class="form" action="<?= url("/panel/login") ?>" method="post" autocomplete="on">
                        <!--begin::Title-->
                        <div class="pb-5 pb-lg-15">
                            <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Login</h3>
                            <div class="text-muted font-weight-bold font-size-h4">
                                Acessar painel administrativo
                            </div>
                        </div>
                        <!--begin::Title-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">E-mail</label>
                            <input class="form-control h-auto py-7 px-6 rounded-lg border-0" type="text" name="email"/>
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div class="d-flex justify-content-between mt-n5">
                                <label class="font-size-h6 font-weight-bolder text-dark pt-5">Senha</label>
                                <!--<a href="#"
                                   class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5">Forgot
                                    Password ?</a>-->
                            </div>
                            <input class="form-control h-auto py-7 px-6 rounded-lg border-0" type="password"
                                   name="password"/>
                        </div>
                        <!--end::Form group-->
                        <!--begin::Action-->
                        <div class="pb-lg-0 pb-5">
                            <button type="submit"
                                    class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Entrar
                            </button>

                        </div>
                        <!--end::Action-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Signin-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
<?= js_version_control('scripts', CONF_VIEW_PANEL) ?>
</body>
<!--end::Body-->
</html>