<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <h2 class="mb-10">Convênio</h2>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="" method="get">
                    <input type="hidden" name="filter" value="s">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Número</label>
                                <input value="<?= $_GET['number'] ?? "" ?>" name="number" type="text"
                                       class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="type" class="form-control">
                                    <option value=""></option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "1" ? "selected" : "" ?>
                                            value="1">Convênio
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "2" ? "selected" : "" ?>
                                            value="2">Movimentação do Convênio
                                    </option>
                                    <option <?= isset($_GET['type']) && $_GET['type'] == "3" ? "selected" : "" ?>
                                            value="3">Prestação de Contas
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Objeto</label>
                                <input value="<?= $_GET['object'] ?? "" ?>" name="object" type="text"
                                       class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Concedente</label>
                                <input value="<?= $_GET['grantor'] ?? "" ?>" name="grantor" type="text"
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
                    <?php if ($conventions): ?>
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th>Número</th>
                                <th>Tipo</th>
                                <th>Objeto</th>
                                <?php if ($release_subscription): ?>
                                    <th class="d-flex border-bottom-0 justify-content-center align-items-center">
                                        <span class="label-to-sign">Assinar</span>
                                        <button title="Assinar documentos"
                                                data-url="<?= $router->route('app.convention') ?>"
                                                class="btn btn-xs btn-theme d-none button-to-sign"><i
                                                    class="fas fa-signature text-white"></i> Assinar
                                        </button>
                                    </th>
                                <?php endif; ?>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($conventions as $convention): ?>
                                <tr>
                                    <th><?= $convention->number ?></th>
                                    <th><?= type_convention($convention->type) ?></th>
                                    <th><?= str_limit_chars($convention->object, 50) ?></th>
                                    <?php if ($release_subscription): ?>
                                        <th class="">
                                            <?php if ($convention->signed !== 'true'): ?>
                                                <div class="form-group">
                                                    <div class="checkbox-list">
                                                        <label class="checkbox d-flex justify-content-center">
                                                            <input type="checkbox" value="<?= $convention->id ?>"
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
                                            <a target="_blank"
                                               href="#"
                                               data-toggle="modal"
                                               data-target="#view_<?= $convention->id ?>"
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
                                                    <a class="dropdown-item" target="_blank"
                                                       data-toggle="modal"
                                                       data-target="#view_<?= $convention->id ?>"
                                                       href="#">Visualizar
                                                        PDF</a>
                                                    <a data-toggle="modal"
                                                       data-target="#information_<?= $convention->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Informações</a>
                                                    <a data-toggle="modal"
                                                       data-target="#send_email_<?= $convention->id ?>"
                                                       class="dropdown-item"
                                                       href="#">Enviar por e-mail</a>
                                                </div>
                                            </div>

                                            <?php if ($delete_document): ?>
                                                <button type="button" data-toggle="modal" title="Excluir documento"
                                                        data-target="#delete_<?= $convention->id ?>"
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
                        <?= $render ?>
                    <?php else: ?>
                        <p>Nenhum convênio encontrado</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($conventions): foreach ($conventions as $convention): ?>
    <div class="modal fade" id="information_<?= $convention->id ?>" tabindex="-1" role="dialog"
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
                    <p><span class="font-weight-bolder">Número: </span><?= $convention->provider ?></p>
                    <p>
                        <span class="font-weight-bolder">Tipo: </span><?= type_convention($convention->type) ?>
                    </p>
                    <p><span class="font-weight-bolder">Valor: </span><?= str_price($convention->value) ?></p>
                    <p><span class="font-weight-bolder">Concedente: </span><?= $convention->grantor ?></p>
                    <p><span class="font-weight-bolder">Data: </span><?= date_fmt($convention->date, 'd/m/Y') ?>
                    </p>
                    <p><span class="font-weight-bolder">Objeto: </span><?= $convention->object ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send_email_<?= $convention->id ?>" tabindex="-1" role="dialog"
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
                        <input type="hidden" name="document" value="<?= $convention->id ?>">
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

    <div class="modal fade" id="view_<?= $convention->id ?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-1">
                    <iframe class="embed-responsive-item w-100" style="height: 100vh;"
                            src="<?= storage($convention->document_name, company()->id . "/" . CONF_UPLOAD_CONVENTION) ?>"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <?php if ($delete_document): ?>
        <div class="modal fade" id="delete_<?= $convention->id ?>" tabindex="-1" role="dialog"
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
                            <input type="hidden" name="document" value="<?= $convention->id ?>">
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
    checkbox_sign();
</script>
<?php $v->end() ?>
