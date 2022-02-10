<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <h2>Despesas</h2>
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
                                <label>N° da despesa</label>
                                <input value="<?= isset($_GET['number_expense']) && !empty($_GET['number_expense']) ? $_GET['number_expense'] : "" ?>"
                                       name="number_expense" type="text" class="form-control"
                                       placeholder="Digite aqui"/>
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
                                <label>Credor</label>
                                <input value="<?= isset($_GET['favored']) && !empty($_GET['favored']) ? $_GET['favored'] : "" ?>"
                                       name="favored" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fonte</label>
                                <input value="<?= isset($_GET['source']) && !empty($_GET['source']) ? $_GET['source'] : "" ?>"
                                       name="source" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="">Valor</label>
                                <div class="input-group">
                                    <input placeholder="R$" type="text" class="form-control" name="value_start"
                                           value="<?= isset($_GET['value_start']) && !empty($_GET['value_start']) ? $_GET['value_start'] : "" ?>"
                                           data-mask="#.##0,00" data-mask-reverse="true"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text">até</span>
                                    </div>
                                    <input placeholder="R$" type="text" class="form-control" name="value_end"
                                           value="<?= isset($_GET['value_end']) && !empty($_GET['value_end']) ? $_GET['value_end'] : "" ?>"
                                           data-mask="#.##0,00" data-mask-reverse="true"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Histórico</label>
                                <input value="<?= isset($_GET['historical']) && !empty($_GET['historical']) ? $_GET['historical'] : "" ?>"
                                       name="historical" type="text" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center w-100">
                            <button type="submit" title="Pesquisar" class="btn btn-theme btn-lg w-300px">PESQUISAR
                            </button>
                        </div>

                    </div>
                </form>


                <div class="mt-15">
                    <?php if (!empty($expenses)): ?>
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th>Número</th>
                                <th>Credor</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <?php if ($release_subscription): ?>
                                    <th class="d-flex border-bottom-0 justify-content-center align-items-center">
                                        <span class="label-to-sign">Assinar</span>
                                        <button title="Assinar documentos"
                                                data-url="<?= $router->route('app.expenses') ?>"
                                                class="btn btn-xs btn-theme d-none button-to-sign"><i
                                                    class="fas fa-signature text-white"></i> Assinar
                                        </button>
                                    </th>
                                <?php endif; ?>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <?php foreach ($expenses as $expense): ?>
                                <tr>
                                    <th><?= $expense->number_expense ?></th>
                                    <th><?= $expense->favored ?></th>
                                    <th>R$ <?= str_price($expense->value) ?></th>
                                    <th><?= date_fmt($expense->date, 'd/m/Y') ?></th>
                                    <?php if ($release_subscription): ?>
                                        <th class="">
                                            <?php if ($expense->signed !== 'true'): ?>
                                                <div class="form-group">
                                                    <div class="checkbox-list">
                                                        <label class="checkbox d-flex justify-content-center">
                                                            <input type="checkbox" value="<?= $expense->id ?>"
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
                                            <a data-toggle="modal"
                                               data-target="#view_<?= $expense->id ?>"
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
                                                       href="<?= storage($expense->document_name, company()->id . "/" . CONF_UPLOAD_EXPENSE) ?>">Visualizar
                                                        PDF</a>
                                                    <a data-toggle="modal"
                                                       data-target="#information_<?= $expense->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Informações</a>
                                                    <a data-toggle="modal" data-target="#send_email_<?= $expense->id ?>"
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
                        <?= $render; ?>
                    <?php else: ?>
                        <p>Não encontramos despesas cadastradas</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($expenses)):foreach ($expenses as $expense): ?>

    <div class="modal fade" id="information_<?= $expense->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">INFORMAÇÕES</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p><span class="font-weight-bolder">Credor: </span><?= $expense->favored ?></p>
                    <p><span class="font-weight-bolder">Fonte: </span><?= $expense->source ?></p>
                    <p><span class="font-weight-bolder">Tipo: </span><?= type_expense($expense->type) ?></p>
                    <p><span class="font-weight-bolder">Valor: </span>R$<?= str_price($expense->value) ?></p>
                    <p><span class="font-weight-bolder">Histórico: </span><?= $expense->historical ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send_email_<?= $expense->id ?>" tabindex="-1" role="dialog"
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
                        <input type="hidden" name="document" value="<?= $expense->id ?>">
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

    <div class="modal fade" id="view_<?= $expense->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-1">
                    <iframe class="embed-responsive-item w-100" style="height: 100vh;"
                            src="<?= storage($expense->document_name, company()->id . "/" . CONF_UPLOAD_EXPENSE) ?>"
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
