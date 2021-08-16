<div id="kt_header" class="header flex-column header-fixed">
    <!--begin::Top-->
    <div class="header-top">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Left-->
            <div class="d-none d-lg-flex align-items-center mr-3">
                <!--begin::Logo-->
                <a href="<?= url() ?>" class="mr-20">
                    <img alt="Logo"
                         src="https://gedtec.rlvtecnologia.com.br/wp-content/themes/gedtec/img/logo.png"
                         class="max-h-35px"/>
                </a>
                <!--end::Logo-->
                <!--begin::Tab Navs(for desktop mode)-->
                <ul class="header-tabs nav align-self-end font-size-lg" role="tablist">
                    <!--begin::Item-->
                    <li class="nav-item">
                        <a href="#"
                           class="nav-link py-4 px-6
                           <?= menu_active($router->route('app.search')) ?>
                           <?= menu_active($router->route('app.expenses')) ?>
                           <?= menu_active($router->route('app.bidding')) ?>
                           <?= menu_active($router->route('app.contract')) ?>
                           <?= menu_active($router->route('app.legislation')) ?>
                           <?= menu_active($router->route('app.report')) ?>
                           <?= menu_active($router->route('app.convention')) ?>"
                           data-toggle="tab"
                           data-target="#kt_header_tab_search" role="tab">Pesquisa</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mr-3">
                        <a href="<?= $router->route('app.monitoring') ?>"

                           data-target="#kt_header_tab_monitoring" role="tab"
                           class="nav-link py-4 px-6 <?= menu_active($router->route('app.monitoring')) ?>">Monitoramento</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mr-3">
                        <a href="#" class="nav-link py-4 px-6
                        <?= menu_active($router->route('admin.indexar')) ?>
                        <?= menu_active($router->route('admin.companies')) ?>
                        <?= menu_active($router->route('admin.updateCompany')) ?>
                        <?= menu_active($router->route('admin.createCompany')) ?>
                        <?= menu_active($router->route('admin.deleteCompany')) ?>
                        <?= menu_active($router->route('admin.users')) ?>
                        <?= menu_active($router->route('admin.createUser')) ?>
                        <?= menu_active($router->route('admin.updateUser')) ?>
                        <?= menu_active($router->route('admin.deleteUser')) ?>"
                           data-toggle="tab"
                           data-target="#kt_header_tab_admin" role="tab">Administrativo</a>
                    </li>
                    <!--end::Item-->
                </ul>
                <!--begin::Tab Navs-->
            </div>
            <!--end::Left-->
            <!--begin::Topbar-->
            <div class="topbar bg-theme">
                <!--begin::User-->
                <div class="topbar-item">
                    <div class="btn btn-icon btn-hover-transparent-white w-sm-auto d-flex align-items-center btn-lg px-2"
                         id="kt_quick_user_toggle">
                        <div class="d-flex flex-column text-right pr-sm-3">
                            <span class="text-white font-weight-bolder font-size-sm d-none d-sm-inline">Kim</span>
                            <span class="text-white opacity-50 font-weight-bold font-size-sm d-none d-sm-inline">Prefeito</span>
                        </div>
                        <span class="symbol symbol-35">
                            <span class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30">K</span>
                        </span>
                    </div>
                </div>
                <!--end::User-->
            </div>
            <!--end::Topbar-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Top-->
    <!--begin::Bottom-->
    <div class="header-bottom">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Header Menu Wrapper-->
            <div class="header-navs header-navs-left" id="kt_header_navs">
                <!--begin::Tab Navs(for tablet and mobile modes)-->
                <ul class="header-tabs p-5 p-lg-0 d-flex d-lg-none nav nav-bold nav-tabs" role="tablist">
                    <!--begin::Item-->
                    <li class="nav-item mr-2">
                        <a href="<?= $router->route('app.search') ?>"
                           class="nav-link btn btn-clean
                           <?= menu_active($router->route('app.search')) ?>
                           <?= menu_active($router->route('app.expenses')) ?>
                           <?= menu_active($router->route('app.bidding')) ?>
                           <?= menu_active($router->route('app.contract')) ?>
                           <?= menu_active($router->route('app.legislation')) ?>
                           <?= menu_active($router->route('app.report')) ?>
                           <?= menu_active($router->route('app.convention')) ?>"
                           data-toggle="tab"
                           data-target="#kt_header_tab_search" role="tab">Pesquisa</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mr-2">
                        <a href="<?= $router->route('app.monitoring') ?>"
                           class="nav-link btn btn-clean <?= menu_active($router->route('app.monitoring')) ?>">Monitoramento</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mr-2">
                        <a href="#" class="nav-link btn btn-clean
                        <?= menu_active($router->route('admin.indexar')) ?>
                        <?= menu_active($router->route('admin.companies')) ?>
                        <?= menu_active($router->route('admin.updateCompany')) ?>
                        <?= menu_active($router->route('admin.createCompany')) ?>
                        <?= menu_active($router->route('admin.deleteCompany')) ?>
                        <?= menu_active($router->route('admin.users')) ?>
                        <?= menu_active($router->route('admin.createUser')) ?>
                        <?= menu_active($router->route('admin.updateUser')) ?>
                        <?= menu_active($router->route('admin.deleteUser')) ?>"
                           data-toggle="tab"
                           data-target="#kt_header_tab_admin" role="tab">Administrativo</a>
                    </li>
                    <!--end::Item-->
                </ul>
                <!--begin::Tab Navs-->
                <!--begin::Tab Content-->
                <div class="tab-content">
                    <div class="tab-pane py-5 p-lg-0
                           <?= menu_active($router->route('app.search')) ?>
                           <?= menu_active($router->route('app.expenses')) ?>
                           <?= menu_active($router->route('app.bidding')) ?>
                           <?= menu_active($router->route('app.contract')) ?>
                           <?= menu_active($router->route('app.legislation')) ?>
                           <?= menu_active($router->route('app.report')) ?>
                           <?= menu_active($router->route('app.convention')) ?>"
                         id="kt_header_tab_search">
                        <!--begin::Menu-->
                        <div id="kt_header_menu"
                             class="header-menu header-menu-mobile header-menu-layout-default">
                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item <?= menu_active($router->route('app.expenses')) ?>"
                                    aria-haspopup="true">
                                    <a href="<?= $router->route('app.expenses') ?>" class="menu-link">
                                        <span class="menu-text">Despesas</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= menu_active($router->route('app.bidding')) ?>"
                                    aria-haspopup="true">
                                    <a href="<?= $router->route('app.bidding') ?>" class="menu-link">
                                        <span class="menu-text">Licitação</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= menu_active($router->route('app.contract')) ?>"
                                    aria-haspopup="true">
                                    <a href="<?= $router->route('app.contract') ?>" class="menu-link">
                                        <span class="menu-text">Contrato</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= menu_active($router->route('app.legislation')) ?>"
                                    aria-haspopup="true">
                                    <a href="<?= $router->route('app.legislation') ?>" class="menu-link">
                                        <span class="menu-text">legislação</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= menu_active($router->route('app.report')) ?>"
                                    aria-haspopup="true">
                                    <a href="<?= $router->route('app.report') ?>" class="menu-link">
                                        <span class="menu-text">Relatório</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= menu_active($router->route('app.convention')) ?>"
                                    aria-haspopup="true">
                                    <a href="<?= $router->route('app.convention') ?>" class="menu-link">
                                        <span class="menu-text">Convênio</span>
                                    </a>
                                </li>

                            </ul>
                            <!--end::Nav-->
                        </div>
                        <!--end::Menu-->
                    </div>

                    <div class="tab-pane p-5 p-lg-0 justify-content-between <?= menu_active($router->route('app.monitoring')) ?>"
                         id="kt_header_tab_monitoring">
                    </div>

                    <div class="tab-pane p-5 p-lg-0 justify-content-between
                        <?= menu_active($router->route('admin.indexar')) ?>
                        <?= menu_active($router->route('admin.companies')) ?>
                        <?= menu_active($router->route('admin.updateCompany')) ?>
                        <?= menu_active($router->route('admin.createCompany')) ?>
                        <?= menu_active($router->route('admin.deleteCompany')) ?>
                        <?= menu_active($router->route('admin.users')) ?>
                        <?= menu_active($router->route('admin.createUser')) ?>
                        <?= menu_active($router->route('admin.updateUser')) ?>
                        <?= menu_active($router->route('admin.deleteUser')) ?>"
                         id="kt_header_tab_admin">
                        <div id="kt_header_menu"
                             class="header-menu header-menu-mobile header-menu-layout-default">
                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item <?= menu_active($router->route('admin.indexar')) ?>" aria-haspopup="true">
                                    <a href="<?= $router->route('admin.indexar') ?>" class="menu-link">
                                        <span class="menu-text">Indexação</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= menu_active($router->route('admin.companies')) ?>" aria-haspopup="true">
                                    <a href="<?= $router->route('admin.companies') ?>" class="menu-link">
                                        <span class="menu-text">Empresas</span>
                                    </a>
                                </li>
                                <li class="menu-item <?= menu_active($router->route('admin.users')) ?>" aria-haspopup="true">
                                    <a href="<?= $router->route('admin.users') ?>" class="menu-link">
                                        <span class="menu-text">Usuários</span>
                                    </a>
                                </li>
                            </ul>
                            <!--end::Nav-->
                        </div>
                    </div>

                </div>
                <!--end::Tab Content-->
            </div>
            <!--end::Header Menu Wrapper-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Bottom-->
</div>