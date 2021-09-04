<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex mb-10">
                    <h2 class="">Editar Usuário</h2>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="" class="form" method="post">
                    <input type="hidden" name="action" value="update">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome *</label>
                                <input name="first_name" value="<?= $user->first_name ?>" type="text"
                                       class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sobrenome *</label>
                                <input name="last_name" value="<?= $user->last_name ?>" type="text"
                                       class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail *</label>
                                <input name="email" value="<?= $user->email ?>" type="email" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control" placeholder="(00) 00000-0000"
                                       name="phone" value="<?= $user->phone ?>" data-mask="(00) 00000-0000"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Função *</label>
                                <input name="occupation" value="<?= $user->occupation ?>" type="text"
                                       class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status *</label>
                                <select name="status" required class="form-control">
                                    <option <?= $user->status == 1 ? "selected" : "" ?> value="1">Ativo</option>
                                    <option <?= $user->status == 0 ? "selected" : "" ?> value="0">Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Administrador *</label>
                                <select required class="form-control" name="admin">
                                    <option <?= $user->admin == "false" ? "selected" : "" ?> value="false">Não</option>
                                    <option <?= $user->admin == "true" ? "selected" : "" ?> value="true">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Módulos de acesso *</label>
                                <div class="checkbox-inline">
                                    <label class="checkbox">
                                        <input <?= in_array('1', $roles) ? "checked" : "" ?> type="checkbox" value="1"
                                                                                             name="roles[]"/>
                                        <span></span>
                                        Despesa
                                    </label>
                                    <label class="checkbox">
                                        <input <?= in_array('2', $roles) ? "checked" : "" ?> type="checkbox" value="2" name="roles[]"/>
                                        <span></span>
                                        Licitação
                                    </label>
                                    <label class="checkbox">
                                        <input <?= in_array('3', $roles) ? "checked" : "" ?> type="checkbox" value="3" name="roles[]"/>
                                        <span></span>
                                        Contrato
                                    </label>
                                    <label class="checkbox">
                                        <input <?= in_array('4', $roles) ? "checked" : "" ?> type="checkbox" value="4" name="roles[]"/>
                                        <span></span>
                                        Legislação
                                    </label>
                                    <label class="checkbox">
                                        <input <?= in_array('5', $roles) ? "checked" : "" ?> type="checkbox" value="5" name="roles[]"/>
                                        <span></span>
                                        Relatórios
                                    </label>
                                    <label class="checkbox">
                                        <input <?= in_array('6', $roles) ? "checked" : "" ?> type="checkbox" value="6" name="roles[]"/>
                                        <span></span>
                                        Convênio
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Empresas vinculadas *</label>
                                <div class="checkbox-list">
                                    <?php foreach ($companies as $company): ?>
                                        <label class="checkbox">
                                            <input <?= in_array($company->id, $companies_user) ? "checked" : "" ?> type="checkbox" value="<?= $company->id ?>" name="companies[]"/>
                                            <span></span>
                                            <?= $company->name ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-theme" type="submit" title="Cadastrar nova empresa">Salvar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex mb-10">
                    <h2 class="">Alterar senha</h2>
                </div>

                <form action="" class="form" method="post">
                    <input type="hidden" name="action" value="update_password">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nova senha *</label>
                                <input name="password" type="password" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Confirme a senha *</label>
                                <input name="password_re" type="password" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-theme" type="submit" title="Salvar nova senha">Salvar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $v->start('scripts'); ?>

<?php $v->end() ?>
