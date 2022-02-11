<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex mb-10">
                    <h2 class="">Editar empresa - <small><?= $company->name ?></small></h2>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form class="form" method="post" action="">
                    <input type="hidden" name="action" value="update">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome do orgão</label>
                                <input name="name" value="<?= $company->name ?>" type="text" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" class="form-control" placeholder="00.000.000/0000-00"
                                       name="document" value="<?= $company->document ?>" data-mask="00.000.000/0000-00"
                                       data-mask-reverse="true"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gestor</label>
                                <input name="manager" value="<?= $company->manager ?>" type="text" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select required class="form-control" name="type">
                                    <option value=""></option>
                                    <option <?= $company->type == "Prefeitura" ? "selected" : "" ?> value="Prefeitura">
                                        Prefeitura
                                    </option>
                                    <option <?= $company->type == "Câmara Municipal" ? "selected" : "" ?>
                                            value="Câmara Municipal">Câmara Municipal
                                    </option>
                                    <option <?= $company->type == "Autarquia" ? "selected" : "" ?> value="Autarquia">
                                        Autarquia
                                    </option>
                                    <option <?= $company->type == "Previdência" ? "selected" : "" ?>
                                            value="Previdência">Previdência
                                    </option>
                                    <option <?= $company->type == "Conselho" ? "selected" : "" ?> value="Conselho">
                                        Conselho
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Endereço</label>
                                <input name="address" value="<?= $company->address ?>" type="text" class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="w-100">Certificado .pem
                                    <?php if (!empty($company->certificate_pem)): ?>
                                        <a title="Download do certificado"
                                           href="<?= url("/storage/certificados/{$company->certificate_pem}") ?>"
                                           download=""
                                           class="btn btn-icon btn-xs btn-light float-right ml-2"><i
                                                    class="fas fa-download"></i></a>
                                    <?php endif; ?>
                                    <a title="Gerar certificado .pem à partir do .pfx"
                                       href="https://www.leaderssl.com/tools/ssl_converter" target="_blank"
                                       class="btn btn-icon btn-xs btn-light float-right"><i
                                                class="fas fa-exclamation"></i></a>
                                </label>
                                <div></div>
                                <div class="custom-file">
                                    <input name="certificate_pem" type="file" class="custom-file-input"
                                           id="customFile"/>
                                    <label class="custom-file-label" for="customFile">Selecionar certificado</label>
                                </div>

                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label class="w-100">Certificado .key
                                    <?php /*if (!empty($company->certificate_key)): */ ?>
                                        <a title="Download do certificado"
                                           href="<? /*= url("/storage/certificados/{$company->certificate_key}") */ ?>"
                                           download=""
                                           class="btn btn-icon btn-xs btn-light float-right"><i
                                                    class="fas fa-download"></i></a>
                                    <?php /*endif; */ ?>
                                </label>
                                <div></div>
                                <div class="custom-file">
                                    <input name="certificate_key" type="file" class="custom-file-input"
                                           id="customFile"/>
                                    <label class="custom-file-label" for="customFile">Selecionar certificado</label>
                                </div>

                            </div>
                        </div>-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Senha do certificado </label>
                                <input name="certificate_password" value="<?= $company->certificate_password ?>"
                                       type="text"
                                       class="form-control"
                                       placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-theme" type="submit" title="Atualizar empresa">Salvar
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
