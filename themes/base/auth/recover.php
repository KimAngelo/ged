<?php $v->layout('auth/_theme'); ?>

<div class="login-signin">
    <div class="">
        <h3>Nova senha</h3>
        <div class="text-muted font-weight-bold mb-10">Digite e repita sua senha para recuperar seu acesso:</div>
    </div>
    <?= flash() ?>
    <div class="ajax_response"></div>
    <form class="form" method="post">
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="password"
                   placeholder="Nova senha" name="password"/>
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="password"
                   placeholder="Repita sua senha" name="password"/>
        </div>
        <button id="kt_login_signin_submit"
                class="btn btn-theme font-weight-bold px-9 py-4 my-3 mx-4">Restaurar acesso
        </button>
    </form>
</div>
