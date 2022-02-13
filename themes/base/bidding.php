<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <h2>Licitação</h2>
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
                                <label>Processo / N° Modalidade</label>
                                <input value="<?= $_GET['number_process'] ?? "" ?>" name="number_process" type="text"
                                       class="form-control"
                                       placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="">Data</label>
                                <div class="input-daterange input-group" id="kt_datepicker_5">
                                    <input type="text" class="form-control" placeholder="Inicio" name="date_start"
                                           value="<?= $_GET['date_start'] ?? "" ?>" data-mask="00/00/0000"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text">até</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Final" name="date_end"
                                           value="<?= $_GET['date_end'] ?? "" ?>" data-mask="00/00/0000"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Modalidade</label>
                                <select name="modality" class="form-control">
                                    <option value=""></option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "8" ? "selected" : "" ?>
                                            value="8">Adesão à Registro de Preços
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "3" ? "selected" : "" ?>
                                            value="3">Carta Convite
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "5" ? "selected" : "" ?>
                                            value="5">Concorrência Pública
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "7" ? "selected" : "" ?>
                                            value="7">Concurso
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "10" ? "selected" : "" ?>
                                            value="10">Dispensa
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "9" ? "selected" : "" ?>
                                            value="9">Inexigibilidade
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "6" ? "selected" : "" ?>
                                            value="6">Leilão
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "2" ? "selected" : "" ?>
                                            value="2">Pregão Eletrônico
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "1" ? "selected" : "" ?>
                                            value="1">Pregão Presencial
                                    </option>
                                    <option <?= isset($_GET['modality']) && $_GET['modality'] == "4" ? "selected" : "" ?>
                                            value="4">Tomada de Preço
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Objeto</label>
                                <input value="<?= $_GET['object'] ?? "" ?>" name="object" type="text"
                                       class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center w-100">
                            <button type="submit" title="Pesquisar" class="btn btn-theme btn-lg w-300px">PESQUISAR
                            </button>
                        </div>

                    </div>
                </form>

                <div class="mt-15">
                    <?php if ($biddings): ?>
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th>N° Processo</th>
                                <th>Modalidade</th>
                                <th>N° Modalidade</th>
                                <th>Data</th>
                                <?php if ($release_subscription): ?>
                                    <th class="d-flex border-bottom-0 justify-content-center align-items-center">
                                        <span class="label-to-sign">Assinar</span>
                                        <button title="Assinar documentos"
                                                data-url="<?= $router->route('app.bidding') ?>"
                                                class="btn btn-xs btn-theme d-none button-to-sign"><i
                                                    class="fas fa-signature text-white"></i> Assinar
                                        </button>
                                    </th>
                                <?php endif; ?>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($biddings as $bidding): ?>
                                <tr>
                                    <th><?= $bidding->number_process ?></th>
                                    <th><?= modality_bidding($bidding->modality) ?></th>
                                    <th><?= $bidding->number_modality ?></th>
                                    <th><?= date_fmt($bidding->date, 'd/m/Y') ?></th>
                                    <?php if ($release_subscription): ?>
                                        <th class="">
                                            <?php if ($bidding->signed !== 'true'): ?>
                                                <div class="form-group">
                                                    <div class="checkbox-list">
                                                        <label class="checkbox d-flex justify-content-center">
                                                            <input type="checkbox" value="<?= $bidding->id ?>"
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
                                               data-target="#view_<?= $bidding->id ?>"
                                               title="Baixar documento"
                                               class="btn btn-primary font-weight-bold btn-sm"><i
                                                        class="fas fa-download"></i></a>

                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                        class="btn btn-dark font-weight-bold dropdown-toggle btn-sm"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a target="_blank" class="dropdown-item"
                                                       data-toggle="modal"
                                                       data-target="#view_<?= $bidding->id ?>"
                                                       href="#">Visualizar
                                                        PDF</a>
                                                    <a data-toggle="modal"
                                                       data-target="#information_<?= $bidding->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Informações</a>
                                                    <a data-toggle="modal" data-target="#send_email_<?= $bidding->id ?>"
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
                    <?php else: ?>
                        <p>Nenhuma licitação encontrada</p>
                    <?php endif; ?>
                </div>
                <?= $render ?>
            </div>
        </div>
    </div>
</div>
<?php if ($biddings): foreach ($biddings as $bidding): ?>
    <div class="modal fade" id="information_<?= $bidding->id ?>" tabindex="-1" role="dialog"
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
                    <p><span class="font-weight-bolder">Número do processo: </span><?= $bidding->number_process ?></p>
                    <p><span class="font-weight-bolder">Modalidade: </span><?= modality_bidding($bidding->modality) ?>
                    </p>
                    <p>
                        <span class="font-weight-bolder">Data do processo: </span><?= date_fmt($bidding->date, 'd/m/Y') ?>
                    </p>
                    <p><span class="font-weight-bolder">Objeto: </span><?= $bidding->object ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send_email_<?= $bidding->id ?>" tabindex="-1" role="dialog"
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
                        <input type="hidden" name="document" value="<?= $bidding->id ?>">
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

    <div class="modal fade" id="view_<?= $bidding->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-1">
                    <iframe class="embed-responsive-item w-100" style="height: 100vh;"
                            src="<?= storage($bidding->document_name, company()->id . "/" . CONF_UPLOAD_BIDDING) ?>"
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
