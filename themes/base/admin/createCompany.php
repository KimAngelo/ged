<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex mb-10">
                    <h2 class="">Cadastrar empresa</h2>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form class="form" action="" method="post">
                    <input type="hidden" name="action" value="create">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome do orgão *</label>
                                <input name="name" type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" class="form-control" placeholder="00.000.000/0000-00"
                                       name="document" data-mask="00.000.000/0000-00" data-mask-reverse="true"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gestor</label>
                                <input name="manager" type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo *</label>
                                <select name="type" required class="form-control">
                                    <option value=""></option>
                                    <option value="Prefeitura">Prefeitura</option>
                                    <option value="Câmara Municipal">Câmara Municipal</option>
                                    <option value="Autarquia">Autarquia</option>
                                    <option value="Previdência">Previdência</option>
                                    <option value="Conselho">Conselho</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Endereço</label>
                                <input name="address" type="text" class="form-control" placeholder="Digite aqui..."/>
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
