<?php $v->layout('auth/_theme'); ?>

<div class="login-signin">
    <div class="">
        <h3>Restaurar senha</h3>
        <div class="text-muted font-weight-bold mb-10">Informe seu e-mail para restaurar sua senha:</div>
    </div>
    <?= flash() ?>
    <div class="ajax_response"></div>
    <form class="form" method="post">
        <?= csrf_input() ?>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="text"
                   placeholder="E-mail" name="email"/>
        </div>
        <div class="form-group d-flex flex-wrap justify-content-end align-items-center">
            <a href="<?= $router->route('auth.login') ?>"
               class="text-muted color-theme-hover">Lembrei minha senha :)</a>
        </div>
        <button id="kt_login_signin_submit"
                class="btn btn-theme font-weight-bold px-9 py-4 my-3 mx-4">Restaurar senha
        </button>
    </form>
</div>
