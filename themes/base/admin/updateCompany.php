<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex mb-10">
                    <h2 class="">Editar empresa - <small>XXXXX</small></h2>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome do orgão</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" class="form-control" placeholder="00.000.000/0000-00"
                                       data-mask="00.000.000/0000-00" data-mask-reverse="true"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gestor</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select required class="form-control">
                                    <option value=""></option>
                                    <option>Prefeitura</option>
                                    <option>Câmara Municipal</option>
                                    <option>Autarquia</option>
                                    <option>Previdência</option>
                                    <option>Conselho</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Endereço</label>
                                <input type="text" class="form-control" placeholder="Digite aqui..."/>
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
