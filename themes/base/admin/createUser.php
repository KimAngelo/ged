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

                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome *</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sobrenome *</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail *</label>
                                <input type="email" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Senha *</label>
                                <input required minlength="<?= CONF_PASSWD_MIN_LEN ?>"
                                       maxlength="<?= CONF_PASSWD_MAX_LEN ?>" type="password" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" class="form-control" placeholder="(00) 00000-0000"
                                       data-mask="(00) 00000-0000"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Função *</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status *</label>
                                <select required class="form-control">
                                    <option>Ativo</option>
                                    <option>Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Administrador *</label>
                                <select required class="form-control">
                                    <option>Não</option>
                                    <option>Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Módulos de acesso *</label>
                                <div class="checkbox-inline">
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes2"/>
                                        <span></span>
                                        Despesa
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes2"/>
                                        <span></span>
                                        Licitação
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes2"/>
                                        <span></span>
                                        Contrato
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes2"/>
                                        <span></span>
                                        Legislação
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes2"/>
                                        <span></span>
                                        Relatórios
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Empresas vinculadas *</label>
                                <div class="checkbox-list">
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes1"/>
                                        <span></span>
                                        Empresa 1
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes1"/>
                                        <span></span>
                                        Empresa 2
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="Checkboxes1"/>
                                        <span></span>
                                        Empresa 3
                                    </label>
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
