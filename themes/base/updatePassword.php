<?php $v->layout('_theme'); ?>
<div class="ajax_response"></div>
<?= flash() ?>
<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">

                <h2 class="mb-10">Alterar minha senha</h2>
                <div class="ajax_response"></div>
                <?= flash() ?>
                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nova senha</label>
                                <input type="password" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Digite novamente</label>
                                <input type="password" class="form-control" placeholder="Digite aqui"/>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" title="Pesquisar" class="btn btn-theme btn-lg">Atualizar senha
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
