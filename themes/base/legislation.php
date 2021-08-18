<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <h2 class="mb-10">Legislação</h2>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="">Data</label>
                                <div class="input-daterange input-group" id="kt_datepicker_5">
                                    <input type="text" class="form-control" placeholder="Inicio" name="date_start"
                                           data-mask="00/00/0000"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text">até</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Final" name="date_end"
                                           data-mask="00/00/0000"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ementa</label>
                                <input type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center w-100">
                            <button type="submit" title="Pesquisar" class="btn btn-theme btn-lg w-300px">PESQUISAR
                            </button>
                        </div>

                    </div>
                </form>


                <div class="mt-15">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Número</th>
                            <th>Ementa</th>
                            <th>Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Portaria</th>
                            <th>008/1994</th>
                            <th>NOMEIA COMISSÃO PERMANENTE DE LICITAÇÃO</th>
                            <th>
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-primary font-weight-bold btn-sm"><i
                                                class="fas fa-download"></i></button>

                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button"
                                                class="btn btn-dark font-weight-bold dropdown-toggle btn-sm"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" href="#">Visualizar PDF</a>
                                            <a data-toggle="modal" data-target="#information_" class="dropdown-item"
                                               href="#">Informações</a>
                                            <a data-toggle="modal" data-target="#send_email_" class="dropdown-item"
                                               href="#">Enviar por e-mail</a>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Portaria</th>
                            <th>008/1994</th>
                            <th>NOMEIA COMISSÃO PERMANENTE DE LICITAÇÃO</th>
                            <th>
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-primary font-weight-bold btn-sm"><i
                                                class="fas fa-download"></i></button>

                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button"
                                                class="btn btn-dark font-weight-bold dropdown-toggle btn-sm"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" href="#">Visualizar PDF</a>
                                            <a data-toggle="modal" data-target="#information_" class="dropdown-item"
                                               href="#">Informações</a>
                                            <a data-toggle="modal" data-target="#send_email_" class="dropdown-item"
                                               href="#">Enviar por e-mail</a>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Portaria</th>
                            <th>008/1994</th>
                            <th>NOMEIA COMISSÃO PERMANENTE DE LICITAÇÃO</th>
                            <th>
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-primary font-weight-bold btn-sm"><i
                                                class="fas fa-download"></i></button>

                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button"
                                                class="btn btn-dark font-weight-bold dropdown-toggle btn-sm"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" href="#">Visualizar PDF</a>
                                            <a data-toggle="modal" data-target="#information_" class="dropdown-item"
                                               href="#">Informações</a>
                                            <a data-toggle="modal" data-target="#send_email_" class="dropdown-item"
                                               href="#">Enviar por e-mail</a>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Portaria</th>
                            <th>008/1994</th>
                            <th>NOMEIA COMISSÃO PERMANENTE DE LICITAÇÃO</th>
                            <th>
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-primary font-weight-bold btn-sm"><i
                                                class="fas fa-download"></i></button>

                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button"
                                                class="btn btn-dark font-weight-bold dropdown-toggle btn-sm"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" href="#">Visualizar PDF</a>
                                            <a data-toggle="modal" data-target="#information_" class="dropdown-item"
                                               href="#">Informações</a>
                                            <a data-toggle="modal" data-target="#send_email_" class="dropdown-item"
                                               href="#">Enviar por e-mail</a>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="information_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="send_email_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar por e-mail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Informe o e-mail que deseja encaminhar o arquivo:</label>
                    <input type="email" class="form-control" placeholder="Digite aqui o e-mail"/>
                </div>
                <button class="btn btn-theme">Enviar</button>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>
<script>

    jQuery(document).ready(function () {
        KTBootstrapDatepicker.init();
    });
</script>
<?php $v->end() ?>
