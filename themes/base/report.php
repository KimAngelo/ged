<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <h2 class="mb-10">Relatório</h2>
                    <button title="Limpar campos" onclick="clearForm('form')"
                            class="btn btn-light font-weight-bold btn-pill btn-lg">Limpar campos
                    </button>
                </div>

                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="" method="get">
                    <input type="hidden" name="filter" value="s">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome</label>
                                <input value="<?= isset($_GET['name']) && !empty($_GET['name']) ? $_GET['name'] : "" ?>"
                                       name="name" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input minlength="4"
                                       value="<?= isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : "" ?>"
                                       name="year" type="number" maxlength="4" class="form-control"
                                       placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="type" class="form-control">
                                    <option value=""></option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "1" ? "selected" : "" ?>
                                            value="1">Contábil
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "2" ? "selected" : "" ?>
                                            value="2">Diário de Obra
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "3" ? "selected" : "" ?>
                                            value="3">Recursos Humanos
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "4" ? "selected" : "" ?>
                                            value="4">Licitação
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center w-100">
                            <button type="submit" title="Pesquisar" class="btn btn-theme btn-lg w-300px">PESQUISAR
                            </button>
                        </div>

                    </div>
                </form>

                <div class="mt-15">
                    <?php if (!empty($reports)): ?>
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Ano</th>
                                <th>Tipo</th>
                                <?php if ($release_subscription): ?>
                                    <th class="d-flex border-bottom-0 justify-content-center align-items-center">
                                        <span class="label-to-sign">Assinar</span>
                                        <button title="Assinar documentos"
                                                data-url="<?= $router->route('app.report') ?>"
                                                class="btn btn-xs btn-theme d-none button-to-sign"><i
                                                    class="fas fa-signature text-white"></i> Assinar
                                        </button>
                                    </th>
                                <?php endif; ?>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <th><?= $report->name ?></th>
                                    <th><?= $report->year ?></th>
                                    <th><?= type_report($report->type) ?></th>
                                    <?php if ($release_subscription): ?>
                                        <th class="">
                                            <?php if ($report->signed !== 'true'): ?>
                                                <div class="form-group">
                                                    <div class="checkbox-list">
                                                        <label class="checkbox d-flex justify-content-center">
                                                            <input type="checkbox" value="<?= $report->id ?>"
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
                                            <a href="#" data-toggle="modal"
                                               data-target="#view_<?= $report->id ?>"
                                               target="_blank" class="btn btn-primary font-weight-bold btn-sm"><i
                                                        class="fas fa-download"></i>
                                            </a>

                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                        class="btn btn-dark font-weight-bold dropdown-toggle btn-sm"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a target="_blank" class="dropdown-item"
                                                       href="#" data-toggle="modal"
                                                       data-target="#view_<?= $report->id ?>">Visualizar
                                                        PDF</a>
                                                    <a data-toggle="modal" data-target="#send_email_<?= $report->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Enviar por e-mail</a>
                                                </div>
                                            </div>

                                            <?php if ($delete_document): ?>
                                                <button type="button" data-toggle="modal" title="Excluir documento"
                                                        data-target="#delete_<?= $report->id ?>"
                                                        class="btn btn-danger font-weight-bold btn-sm">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </th>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Não encontramos relatórios cadastrados.</p>
                    <?php endif; ?>
                </div>
                <?= $render ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($reports)): foreach ($reports as $report): ?>
    <div class="modal fade" id="send_email_<?= $report->id ?>" tabindex="-1" role="dialog"
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
                        <input type="hidden" name="document" value="<?= $report->id ?>">
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

    <div class="modal fade" id="view_<?= $report->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-1">
                    <iframe class="embed-responsive-item w-100" style="height: 100vh;"
                            src="<?= storage($report->document_name, company()->id . "/" . CONF_UPLOAD_REPORT) ?>"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <?php if ($delete_document): ?>
        <div class="modal fade" id="delete_<?= $report->id ?>" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir documento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir definitivamente este documento?</p>
                        <form action="" method="post" class="form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="document" value="<?= $report->id ?>">
                            <button type="submit" class="btn btn-danger">Sim, excluir!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; endif; ?>
<?php $v->start('scripts'); ?>
<script>

    jQuery(document).ready(function () {
        KTBootstrapDatepicker.init();
    });
    checkbox_sign();
</script>
<?php $v->end() ?>
