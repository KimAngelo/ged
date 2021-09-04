<?php $v->layout('auth/_theme'); ?>

<div class="login-signin">
    <div class="mb-10">
        <h3>Selecione uma empresa</h3>
    </div>
    <?= flash() ?>
    <div class="ajax_response"></div>
    <form class="form" method="post">
        <div class="form-group mb-5">
            <select class="form-control h-auto form-control-solid py-4 px-8" name="company">
                <?php foreach ($companies as $company): ?>
                    <option value="<?= $company->id_company ?>"><?= $company->company()->name ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <button type="submit" class="btn btn-theme font-weight-bold px-9 py-4 my-3 mx-4">Pronto!
        </button>
    </form>
</div>
