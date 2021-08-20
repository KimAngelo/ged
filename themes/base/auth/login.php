<?php $v->layout('auth/_theme'); ?>

<div class="login-signin">
    <div class="">
        <h3>Acessar <?= CONF_SITE_NAME ?></h3>
        <div class="text-muted font-weight-bold mb-10">Entre com os seus dados:</div>
    </div>
    <?= flash() ?>
    <div class="ajax_response"></div>
    <form class="form" method="post">
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="text"
                   placeholder="E-mail" name="email"/>
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="password"
                   placeholder="Senha" name="password"/>
        </div>
        <div class="form-group d-flex flex-wrap justify-content-end align-items-center">
            <a href="<?= $router->route('auth.forget') ?>" class="text-muted color-theme-hover">Esqueceu
                sua senha?</a>
        </div>
        <button id="kt_login_signin_submit"
                class="btn btn-theme font-weight-bold px-9 py-4 my-3 mx-4">Acessar
        </button>
    </form>
</div>
