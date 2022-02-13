<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <h2>Legislação</h2>
                    <button title="Limpar campos" onclick="clearForm('form')"
                            class="btn btn-light font-weight-bold btn-pill btn-lg">Limpar campos
                    </button>
                </div>

                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="" method="get">
                    <input type="hidden" name="filter" value="s">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input value="<?= isset($_GET['number']) && !empty($_GET['number']) ? $_GET['number'] : "" ?>"
                                       name="number" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="">Data</label>
                                <div class="input-daterange input-group" id="kt_datepicker_5">
                                    <input type="text" class="form-control" placeholder="Inicio" name="date_start"
                                           value="<?= isset($_GET['date_start']) && !empty($_GET['date_start']) ? $_GET['date_start'] : "" ?>"
                                           data-mask="00/00/0000"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text">até</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Final" name="date_end"
                                           value="<?= isset($_GET['date_end']) && !empty($_GET['date_end']) ? $_GET['date_end'] : "" ?>"
                                           data-mask="00/00/0000"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="type" class="form-control">
                                    <option value=""></option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "1" ? "selected" : "" ?>
                                            value="1">Leis
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "2" ? "selected" : "" ?>
                                            value="2">Decretos
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "3" ? "selected" : "" ?>
                                            value="3">Portarias
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "4" ? "selected" : "" ?>
                                            value="4">Indicação
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "5" ? "selected" : "" ?>
                                            value="5">Moção
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ementa</label>
                                <input value="<?= isset($_GET['ementa']) && !empty($_GET['ementa']) ? $_GET['ementa'] : "" ?>"
                                       name="ementa" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center w-100">
                            <button type="submit" title="Pesquisar" class="btn btn-theme btn-lg w-300px">PESQUISAR
                            </button>
                        </div>

                    </div>
                </form>


                <div class="mt-15">
                    <?php if (!empty($legislations)): ?>
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Número</th>
                                <th>Ementa</th>
                                <?php if ($release_subscription): ?>
                                    <th class="d-flex border-bottom-0 justify-content-center align-items-center">
                                        <span class="label-to-sign">Assinar</span>
                                        <button title="Assinar documentos"
                                                data-url="<?= $router->route('app.legislation') ?>"
                                                class="btn btn-xs btn-theme d-none button-to-sign"><i
                                                    class="fas fa-signature text-white"></i> Assinar
                                        </button>
                                    </th>
                                <?php endif; ?>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($legislations as $legislation): ?>
                                <tr>
                                    <th><?= type_legislation($legislation->type) ?></th>
                                    <th><?= $legislation->number ?></th>
                                    <th><?= str_limit_words($legislation->ementa, 10) ?></th>
                                    <?php if ($release_subscription): ?>
                                        <th class="">
                                            <?php if ($legislation->signed !== 'true'): ?>
                                                <div class="form-group">
                                                    <div class="checkbox-list">
                                                        <label class="checkbox d-flex justify-content-center">
                                                            <input type="checkbox" value="<?= $legislation->id ?>"
                                                                   name="document_signed"/>
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </th>
                                    <?php endif; ?>
                                    <th>
                                        <div class="btn-group" role="group"
                                             aria-label="Button group with nested dropdown">
                                            <a href="#"
                                               data-toggle="modal"
                                               data-target="#view_<?= $legislation->id ?>"
                                               target="_blank" class="btn btn-primary font-weight-bold btn-sm"><i
                                                        class="fas fa-download"></i></a>

                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                        class="btn btn-dark font-weight-bold dropdown-toggle btn-sm"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a class="dropdown-item"
                                                       href="#" data-toggle="modal"
                                                       data-target="#view_<?= $legislation->id ?>">Visualizar
                                                        PDF</a>
                                                    <a data-toggle="modal"
                                                       data-target="#information_<?= $legislation->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Informações</a>
                                                    <a data-toggle="modal"
                                                       data-target="#send_email_<?= $legislation->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Enviar por e-mail</a>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($legislations)): foreach ($legislations as $legislation): ?>
    <div class="modal fade" id="information_<?= $legislation->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
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
                    <p><span class="font-weight-bolder">Número: </span><?= $legislation->number ?></p>
                    <p><span class="font-weight-bolder">Tipo: </span><?= type_legislation($legislation->type) ?></p>
                    <p><span class="font-weight-bolder">Data: </span><?= date_fmt($legislation->date, 'd/m/Y') ?></p>
                    <p><span class="font-weight-bolder">Ementa: </span><?= $legislation->ementa ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send_email_<?= $legislation->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
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
                    <form action="" method="post" class="form">
                        <input type="hidden" name="action" value="send_document_email">
                        <input type="hidden" name="document" value="<?= $legislation->id ?>">
                        <div class="form-group">
                            <label>Informe o e-mail que deseja encaminhar o arquivo:</label>
                            <input name="email" required type="email" class="form-control"
                                   placeholder="Digite aqui o e-mail"/>
                        </div>
                        <button type="submit" class="btn btn-theme">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view_<?= $legislation->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-1">
                    <iframe class="embed-responsive-item w-100" style="height: 100vh;"
                            src="<?= storage($legislation->document_name, company()->id . "/" . CONF_UPLOAD_LEGISLATION) ?>"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; endif; ?>
<?php $v->start('scripts'); ?>
<script>

    jQuery(document).ready(function () {
        KTBootstrapDatepicker.init();
    });
    checkbox_sign();
</script>
<?php $v->end() ?>
