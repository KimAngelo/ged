<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <h2>Contrato</h2>
                    <button title="Limpar campos" onclick="clearForm('form')"
                            class="btn btn-light font-weight-bold btn-pill btn-lg">Limpar campos
                    </button>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° Contrato/Ata</label>
                                <input value="<?= $_GET['number_contract'] ?? "" ?>" name="number_contract" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="type" class="form-control">
                                    <option value=""></option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "2" ? "selected" : "" ?> value="2">Contrato</option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "3" ? "selected" : "" ?> value="3">Aditivo</option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "4" ? "selected" : "" ?> value="4">Rescisão</option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "5" ? "selected" : "" ?> value="5">Ata de RP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Objeto</label>
                                <input value="<?= $_GET['object'] ?? "" ?>" name="object" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center w-100">
                            <button type="submit" title="Pesquisar" class="btn btn-theme btn-lg w-300px">PESQUISAR
                            </button>
                        </div>

                    </div>
                </form>


                <div class="mt-15">
                    <?php if ($contracts): ?>
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>N° Contrato/Ata</th>
                                <th>Fornecedor</th>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($contracts as $contract): ?>
                                <tr>
                                    <th><?= type_contract($contract->type) ?></th>
                                    <th><?= $contract->number_contract ?></th>
                                    <th><?= $contract->provider ?></th>
                                    <th><?= date_fmt($contract->date, 'd/m/Y') ?></th>
                                    <th>R$ <?= str_price($contract->value) ?></th>
                                    <th>
                                        <div class="btn-group" role="group"
                                             aria-label="Button group with nested dropdown">
                                            <a target="_blank"
                                               href="<?= storage($contract->document_name, company()->id . "/" . CONF_UPLOAD_CONTRACT) ?>"
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
                                                    <a class="dropdown-item"
                                                       href="<?= storage($contract->document_name, company()->id . "/" . CONF_UPLOAD_CONTRACT) ?>">Visualizar
                                                        PDF</a>
                                                    <a data-toggle="modal"
                                                       data-target="#information_<?= $contract->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Informações</a>
                                                    <a data-toggle="modal"
                                                       data-target="#send_email_<?= $contract->id ?>"
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
                    <p>Nenhum contrato encontrado</p>
                    <?php endif; ?>
                    <?= $render ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($contracts): foreach ($contracts as $contract): ?>
    <div class="modal fade" id="information_<?= $contract->id ?>" tabindex="-1" role="dialog"
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
                    <p><span class="font-weight-bolder">Fornecedor: </span><?= $contract->provider ?></p>
                    <p>
                        <span class="font-weight-bolder"><?= type_contract($contract->type) ?>: </span><?= $contract->number_contract ?>
                    </p>
                    <p><span class="font-weight-bolder">Valor: </span><?= str_price($contract->value) ?></p>
                    <p><span class="font-weight-bolder">N° Processo: </span><?= $contract->number_process ?></p>
                    <p><span class="font-weight-bolder">Modalidade: </span><?= modality_bidding($contract->modality) ?>
                    </p>
                    <p><span class="font-weight-bolder">Objeto: </span><?= $contract->object ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send_email_<?= $contract->id ?>" tabindex="-1" role="dialog"
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
                        <input type="hidden" name="document" value="<?= $contract->id ?>">
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
<?php endforeach; endif; ?>
<?php $v->start('scripts'); ?>
<script>

    jQuery(document).ready(function () {
        KTBootstrapDatepicker.init();
    });
</script>
<?php $v->end() ?>
