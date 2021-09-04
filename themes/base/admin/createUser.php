<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex mb-10">
                    <h2 class="">Cadastrar Usuário</h2>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="" class="form" method="post">
                    <input type="hidden" name="action" value="create">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome *</label>
                                <input name="first_name" required type="text" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sobrenome *</label>
                                <input name="last_name" required type="text" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail *</label>
                                <input name="email" required type="email" class="form-control"
                                       placeholder="Digite aqui..." autocomplete="off"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Senha *</label>
                                <input name="password" required minlength="<?= CONF_PASSWD_MIN_LEN ?>"
                                       maxlength="<?= CONF_PASSWD_MAX_LEN ?>" type="password" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular</label>
                                <input name="phone" type="text" class="form-control" placeholder="(00) 00000-0000"
                                       data-mask="(00) 00000-0000"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Função *</label>
                                <input name="occupation" required type="text" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status *</label>
                                <select required class="form-control" name="status">
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Administrador *</label>
                                <select name="admin" required class="form-control">
                                    <option value="false">Não</option>
                                    <option value="true">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Módulos de acesso *</label>
                                <div class="checkbox-inline">
                                    <label class="checkbox">
                                        <input type="checkbox" value="1" name="roles[]"/>
                                        <span></span>
                                        Despesas
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" value="2" name="roles[]"/>
                                        <span></span>
                                        Licitação
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" value="3" name="roles[]"/>
                                        <span></span>
                                        Contrato
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" value="4" name="roles[]"/>
                                        <span></span>
                                        Legislação
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" value="5" name="roles[]"/>
                                        <span></span>
                                        Relatório
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" value="6" name="roles[]"/>
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
                                            <input type="checkbox" value="<?= $company->id ?>" name="companies[]"/>
                                            <span></span>
                                            <?= $company->name ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-theme" type="submit" title="Cadastrar nova empresa">Cadastrar
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
