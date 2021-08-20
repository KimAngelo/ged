<?php $v->layout('auth/_theme'); ?>

<div class="login-signin">
    <div class="mb-10">
        <h3>Selecione uma empresa</h3>
    </div>
    <?= flash() ?>
    <div class="ajax_response"></div>
    <form class="form" method="post">
        <div class="form-group mb-5">
            <select class="form-control h-auto form-control-solid py-4 px-8" name="" id="">
                <option value="">Empresa 1</option>
                <option value="">Empresa 2</option>
                <option value="">Empresa 3</option>
                <option value="">Empresa 4</option>
                <option value="">Empresa 5</option>
                <option value="">Empresa 6</option>
            </select>

        </div>
        <button id="kt_login_signin_submit"
                class="btn btn-theme font-weight-bold px-9 py-4 my-3 mx-4">Pronto!
        </button>
    </form>
</div>
