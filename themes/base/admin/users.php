<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <h2 class="">Usuários</h2>
                    <a href="<?= $router->route('admin.createUser') ?>" title="Cadastrar um novo usuário"
                       class="btn btn-secondary font-weight-bold btn-pill btn-lg">Cadastrar Novo Usuário</a>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="<?= $router->route('admin.users') ?>" method="get">
                    <input type="hidden" name="filter" value="s">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome</label>
                                <input value="<?= isset($_GET['name']) ? $_GET['name'] : "" ?>" name="name" type="text"
                                       class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>E-mail</label>
                                <input value="<?= isset($_GET['email']) ? $_GET['email'] : "" ?>" name="email"
                                       type="email" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Função</label>
                                <input value="<?= isset($_GET['occupation']) ? $_GET['occupation'] : "" ?>"
                                       name="occupation" type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empresa</label>
                                <select name="company" class="form-control">
                                    <option></option>
                                    <?php foreach ($companies as $company): ?>
                                        <option <?= isset($_GET['company']) && $_GET['company'] == $company->id ? "selected" : "" ?>
                                                value="<?= $company->id ?>"><?= $company->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option></option>
                                    <option <?= isset($_GET['status']) && $_GET['status'] == "1" ? "selected" : "" ?>
                                            value="1">Ativo
                                    </option>
                                    <option <?= isset($_GET['status']) && $_GET['status'] == "2" ? "selected" : "" ?>
                                            value="2">Bloqueado
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Administrador</label>
                                <select name="admin" class="form-control">
                                    <option></option>
                                    <option <?= isset($_GET['admin']) && $_GET['admin'] == "true" ? "selected" : "" ?>
                                            value="true">Sim
                                    </option>
                                    <option <?= isset($_GET['admin']) && $_GET['admin'] == "false" ? "selected" : "" ?>
                                            value="false">Não
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-lg btn-theme" type="submit">Buscar usuário</button>
                            <button type="button" onclick="clearForm('form')" class="btn btn-lg btn-light">Limpar</button>
                        </div>
                    </div>
                </form>

                <div class="mt-15">
                    <?php if (!empty($users)): ?>
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Função</th>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <th><?= $user->id ?></th>
                                    <th><?= $user->first_name . " " . $user->last_name ?></th>
                                    <th><?= $user->email ?></th>
                                    <th><?= $user->occupation ?></th>
                                    <th>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a title="Editar usuário"
                                               href="<?= $router->route('admin.updateUser', ['id' => $user->id]) ?>"
                                               class="btn btn-primary font-weight-bold btn-sm"><i
                                                        class="far fa-edit"></i></a>
                                            <button title="Excluir usuário" type="button" data-toggle="modal"
                                                    data-target="#modal_delete_<?= $user->id ?>"
                                                    class="btn btn-danger font-weight-bold btn-sm"><i
                                                        class="fas fa-times"></i></button>
                                        </div>

                                    </th>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum usuário encontrado</p>
                    <?php endif; ?>
                    <?= $render; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal-->
<?php if (!empty($users)): ?>
    <?php foreach ($users as $user): ?>
        <div class="modal fade" id="modal_delete_<?= $user->id ?>" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Excluir usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja exlcuir o usuário <strong><?= $user->first_name ?></strong> ?</p>
                        <p>Todos os seus dados serão apagados e não poderão mais ser acessados!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger font-weight-bold">Sim, excluir!</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php $v->start('scripts'); ?>

<?php $v->end() ?>
