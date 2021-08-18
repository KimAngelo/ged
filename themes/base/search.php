<?php $v->layout('_theme'); ?>
<div class="ajax_response"></div>
<?= flash() ?>
<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <!--begin::Nav Tabs-->
                <ul class="dashboard-tabs nav nav-pills nav-danger row row-paddingless m-0 p-0 flex-column flex-sm-row"
                    role="tablist">
                    <!--begin::Item-->
                    <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0">
                        <a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center"
                           href="<?= $router->route('app.expenses') ?>">
                            <span class="nav-icon py-2 w-auto">
                                <span class="svg-icon svg-icon-3x">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                         width="50.000000pt" height="50.000000pt" viewBox="0 0 50.000000 50.000000"
                                         preserveAspectRatio="xMidYMid meet">

                                        <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)"
                                           fill="#000000" stroke="none">
                                        <path d="M40 423 c0 -22 -6 -32 -20 -36 -20 -5 -20 -12 -18 -159 l3 -153 90
                                        -9 c114 -10 196 -10 310 0 l90 9 3 153 c2 147 2 154 -18 159 -14 4 -20 14 -20
                                        36 l0 30 -87 -9 c-49 -4 -104 -8 -123 -8 -19 0 -74 4 -122 8 l-88 9 0 -30z
                                        m183 -9 c15 -5 17 -21 17 -155 0 -139 -1 -150 -17 -145 -10 3 -51 8 -90 12
                                        l-73 7 0 150 0 150 73 -7 c39 -4 80 -9 90 -12z m217 -133 l0 -148 -72 -7 c-40
                                        -4 -81 -9 -90 -12 -17 -5 -18 7 -18 145 0 148 0 151 23 156 31 7 76 12 120 14
                                        l37 1 0 -149z m-400 -39 l0 -128 105 -11 c77 -8 133 -8 210 0 l105 11 0 128
                                        c0 79 4 128 10 128 6 0 10 -53 10 -139 l0 -138 -97 -8 c-122 -11 -144 -11
                                        -265 0 l-98 8 0 138 c0 86 4 139 10 139 6 0 10 -49 10 -128z"/>
                                        <path d="M323 353 c-35 -40 -29 -59 25 -87 21 -11 32 -23 30 -34 -4 -22 -46
                                        -28 -54 -8 -6 17 -24 22 -24 7 0 -4 11 -20 25 -35 l24 -26 27 25 c35 32 27 65
                                        -21 88 -25 12 -35 23 -33 35 4 22 46 28 54 8 6 -17 24 -22 24 -7 0 8 -46 62
                                        -52 60 -2 0 -13 -12 -25 -26z"/>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <span class="nav-text font-size-lg py-2 font-weight-bold text-center">Despesas</span>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0">
                        <a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center"
                           href="<?= $router->route('app.bidding') ?>">
                            <span class="nav-icon py-2 w-auto">
                                <span class="svg-icon svg-icon-3x">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                         width="50.000000pt" height="50.000000pt" viewBox="0 0 50.000000 50.000000"
                                         preserveAspectRatio="xMidYMid meet">
                                        <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)"
                                           fill="#000000" stroke="none">
                                        <path d="M225 400 c-41 -42 -46 -51 -35 -65 7 -8 15 -13 18 -10 3 3 16 -4 28
                                        -16 l22 -21 -104 -96 c-106 -98 -126 -133 -79 -140 14 -2 47 27 117 102 l96
                                        104 21 -22 c12 -12 19 -25 16 -28 -3 -3 2 -11 10 -18 14 -11 23 -6 65 35 56
                                        55 61 72 26 81 -22 5 -125 101 -126 117 0 4 0 12 0 17 0 22 -28 7 -75 -40z
                                        m29 -21 c-21 -21 -42 -39 -46 -39 -20 0 -4 31 34 66 54 49 64 27 12 -27z m55
                                        8 c6 -8 9 -17 6 -20 -3 -4 -2 -5 2 -2 10 8 53 -37 46 -48 -3 -6 -1 -7 4 -4 6
                                        4 16 0 23 -8 11 -13 8 -21 -18 -47 -26 -25 -35 -28 -47 -18 -8 7 -12 17 -8 24
                                        3 6 2 8 -2 3 -11 -10 -56 33 -48 46 3 6 1 7 -4 4 -6 -4 -16 0 -23 8 -11 13 -8
                                        21 15 45 32 34 38 36 54 17z m131 -102 c0 -12 -74 -85 -86 -85 -22 0 -17 11
                                        23 52 32 33 63 49 63 33z m-257 -121 c-54 -57 -101 -104 -105 -104 -4 0 -10 5
                                        -14 11 -7 12 196 210 209 202 4 -2 -37 -51 -90 -109z"/>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <span class="nav-text font-size-lg py-2 font-weight-bolder text-center">Licitação</span>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0">
                        <a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center"
                           href="<?= $router->route('app.contract') ?>">
                            <span class="nav-icon py-2 w-auto">
                                <span class="svg-icon svg-icon-3x">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Movie-Lane2.svg-->
                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                         width="50.000000pt" height="50.000000pt" viewBox="0 0 50.000000 50.000000"
                                         preserveAspectRatio="xMidYMid meet">

                                        <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)"
                                           fill="#000000" stroke="none">
                                        <path d="M70 250 l0 -230 180 0 180 0 0 168 0 168 -63 62 -63 62 -117 0 -117
                                        0 0 -230z m220 150 l0 -60 60 0 60 0 0 -150 0 -150 -160 0 -160 0 0 210 0 210
                                        100 0 100 0 0 -60z m65 0 l39 -40 -42 0 -42 0 0 40 c0 22 1 40 3 40 2 0 21
                                        -18 42 -40z"/>
                                        <path d="M140 390 c0 -6 28 -10 65 -10 37 0 65 4 65 10 0 6 -28 10 -65 10 -37
                                        0 -65 -4 -65 -10z"/>
                                        <path d="M140 350 c0 -6 28 -10 65 -10 37 0 65 4 65 10 0 6 -28 10 -65 10 -37
                                        0 -65 -4 -65 -10z"/>
                                        <path d="M140 310 c0 -6 45 -10 115 -10 70 0 115 4 115 10 0 6 -45 10 -115 10
                                        -70 0 -115 -4 -115 -10z"/>
                                        <path d="M140 270 c0 -6 45 -10 115 -10 70 0 115 4 115 10 0 6 -45 10 -115 10
                                        -70 0 -115 -4 -115 -10z"/>
                                        <path d="M140 230 c0 -6 45 -10 115 -10 70 0 115 4 115 10 0 6 -45 10 -115 10
                                        -70 0 -115 -4 -115 -10z"/>
                                        <path d="M176 181 c-3 -5 -5 -29 -5 -54 2 -41 4 -43 28 -39 14 3 34 11 45 18
                                        10 7 23 11 30 8 6 -2 22 5 35 15 l24 18 -41 0 c-25 0 -43 -5 -46 -13 -10 -26
                                        -24 -14 -22 19 1 26 -3 33 -21 35 -11 2 -24 -1 -27 -7z m34 -25 c0 -8 -4 -18
                                        -10 -21 -5 -3 -10 3 -10 14 0 12 5 21 10 21 6 0 10 -6 10 -14z"/>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <span class="nav-text font-size-lg py-2 font-weight-bolder text-center">Contrato</span>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0">
                        <a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center"
                           href="<?= $router->route('app.legislation') ?>">
                            <span class="nav-icon py-2 w-auto">
                                <span class="svg-icon svg-icon-3x">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                         width="50.000000pt" height="50.000000pt" viewBox="0 0 50.000000 50.000000"
                                         preserveAspectRatio="xMidYMid meet">
                                        <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)"
                                           fill="#000000" stroke="none">
                                        <path d="M237 474 c-4 -4 -7 -16 -7 -26 0 -17 -2 -18 -30 -3 -32 16 -115 20
                                        -124 6 -4 -5 -2 -11 4 -13 6 -2 -5 -41 -25 -92 -34 -83 -35 -90 -20 -112 29
                                        -44 101 -44 130 0 15 22 14 29 -20 112 -19 48 -35 89 -35 91 0 10 67 0 91 -14
                                        l29 -17 0 -163 c0 -156 1 -163 20 -163 19 0 20 7 20 160 l0 160 35 0 c34 0 85
                                        -18 85 -30 0 -3 -16 -46 -35 -94 -34 -83 -35 -90 -20 -112 29 -44 101 -44 130
                                        0 15 22 14 29 -19 110 -19 47 -30 86 -25 86 5 0 9 4 9 8 0 15 -90 52 -125 52
                                        -29 0 -34 4 -37 27 -3 26 -18 39 -31 27z m-105 -149 l26 -65 -58 0 -58 0 26
                                        65 c14 36 28 65 32 65 4 0 18 -29 32 -65z m300 -70 l26 -65 -58 0 -58 0 26 65
                                        c14 36 28 65 32 65 4 0 18 -29 32 -65z"/>
                                        <path d="M137 54 c-4 -4 -7 -13 -7 -21 0 -10 27 -13 121 -13 109 0 120 2 117
                                        18 -3 15 -18 17 -114 20 -60 1 -113 -1 -117 -4z"/>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <span class="nav-text font-size-lg py-2 font-weight-bolder text-center">Legislação</span>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0">
                        <a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center"
                           href="<?= $router->route('app.report') ?>">
                            <span class="nav-icon py-2 w-auto">
                                <span class="svg-icon svg-icon-3x">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/Shield-check.svg-->
                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                         width="50.000000pt" height="50.000000pt" viewBox="0 0 50.000000 50.000000"
                                         preserveAspectRatio="xMidYMid meet">

                                        <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)"
                                           fill="#000000" stroke="none">
                                        <path d="M90 250 l0 -200 160 0 160 0 0 149 0 148 -51 52 -52 51 -108 0 -109
                                        0 0 -200z m220 135 l0 -45 40 0 40 0 0 -135 0 -135 -140 0 -140 0 0 180 0 180
                                        100 0 100 0 0 -45z m45 5 l29 -30 -32 0 c-29 0 -32 3 -32 30 0 17 1 30 3 30 2
                                        0 16 -13 32 -30z"/>
                                        <path d="M312 232 l-32 -37 -24 27 -25 27 -36 -36 c-42 -42 -38 -59 5 -18 l30
                                        29 25 -24 25 -23 44 39 c24 21 45 42 45 47 2 18 -27 2 -57 -31z"/>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <span class="nav-text font-size-lg py-2 font-weight-bolder text-center">Relatório</span>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-0 mb-3 mb-lg-0">
                        <a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center"
                           href="<?= $router->route('app.convention') ?>">
                            <span class="nav-icon py-2 w-auto">
                                <span class="svg-icon svg-icon-3x">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                         width="50.000000pt" height="50.000000pt" viewBox="0 0 50.000000 50.000000"
                                         preserveAspectRatio="xMidYMid meet">

                                        <g transform="translate(0.000000,50.000000) scale(0.100000,-0.100000)"
                                           fill="#000000" stroke="none">
                                        <path d="M10 373 c0 -11 92 -23 171 -23 26 0 51 -5 55 -11 3 -6 -10 -28 -30
                                        -48 -35 -36 -36 -38 -18 -54 26 -24 56 -21 90 8 l29 24 48 -49 c47 -48 55 -80
                                        20 -80 -8 0 -15 -7 -15 -16 0 -9 -9 -18 -20 -21 -11 -3 -20 -11 -20 -18 0 -11
                                        -41 -45 -55 -45 -3 0 -5 7 -5 16 0 17 -6 25 -78 96 -49 50 -82 63 -143 55 -28
                                        -3 -26 -4 14 -5 45 -2 47 -3 41 -27 -5 -22 3 -35 59 -89 94 -89 106 -88 216
                                        24 47 48 95 90 106 93 15 4 14 5 -7 6 -16 0 -28 -4 -28 -9 0 -28 -31 -18 -68
                                        22 -30 32 -38 46 -30 55 21 26 -29 13 -67 -17 -42 -33 -57 -37 -74 -16 -10 11
                                        -2 23 36 60 41 39 54 46 89 46 74 0 164 13 164 23 0 5 -18 7 -45 3 -155 -24
                                        -235 -24 -389 -1 -28 5 -46 3 -46 -2z m140 -209 c0 -8 6 -14 14 -14 8 0 18 -9
                                        21 -20 3 -11 13 -20 21 -20 8 0 14 -7 14 -15 0 -7 8 -18 18 -24 13 -7 14 -13
                                        6 -21 -14 -14 -64 14 -64 36 0 8 -7 14 -16 14 -9 0 -18 9 -21 20 -3 11 -12 20
                                        -19 20 -20 0 -30 30 -14 40 17 11 40 1 40 -16z"/>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                            <span class="nav-text font-size-lg py-2 font-weight-bolder text-center">Convênio</span>
                        </a>
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Nav Tabs-->
                <!--begin::Nav Content-->
                <div class="tab-content m-0 p-0">
                    <div class="tab-pane active" id="forms_widget_tab_1" role="tabpanel"></div>
                    <div class="tab-pane" id="forms_widget_tab_2" role="tabpanel"></div>
                    <div class="tab-pane" id="forms_widget_tab_3" role="tabpanel"></div>
                    <div class="tab-pane" id="forms_widget_tab_4" role="tabpanel"></div>
                    <div class="tab-pane" id="forms_widget_tab_6" role="tabpanel"></div>
                </div>
                <!--end::Nav Content-->
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>

<?php $v->end() ?>
