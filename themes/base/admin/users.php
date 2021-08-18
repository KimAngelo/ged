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

                <form action="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="email" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Função</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empresa</label>
                                <select class="form-control">
                                    <option></option>
                                    <option>Empresa 1</option>
                                    <option>Empresa 2</option>
                                    <option>Empresa 3</option>
                                    <option>Empresa 4</option>
                                    <option>Empresa 5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control">
                                    <option></option>
                                    <option>Ativo</option>
                                    <option>Bloqueado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Administrador</label>
                                <select class="form-control">
                                    <option></option>
                                    <option>Sim</option>
                                    <option>Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-lg btn-theme" type="submit">Buscar usuário</button>
                        </div>
                    </div>
                </form>

                <div class="mt-15">
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
                        <tr>
                            <th>30</th>
                            <th>Kim Angelo</th>
                            <th>kim@kimangelo.me</th>
                            <th>Prefeito</th>
                            <th>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a title="Editar usuário"
                                       href="<?= $router->route('admin.updateUser', ['id' => 50]) ?>"
                                       class="btn btn-primary font-weight-bold btn-sm"><i
                                                class="far fa-edit"></i></a>
                                    <button title="Excluir usuário" type="button" data-toggle="modal"
                                            data-target="#modal_delete_"
                                            class="btn btn-danger font-weight-bold btn-sm"><i
                                                class="fas fa-times"></i></button>
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

<!-- Modal-->
<div class="modal fade" id="modal_delete_" data-backdrop="static" tabindex="-1" role="dialog"
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
                <p>Tem certeza que deseja exlcuir o usuário <strong>XXXX</strong> ?</p>
                <p>Todos os seus dados serão apagados e não poderão mais ser acessados!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar
                </button>
                <button type="button" class="btn btn-danger font-weight-bold">Sim, excluir!</button>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>

<?php $v->end() ?>
