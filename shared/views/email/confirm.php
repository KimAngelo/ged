<?php $v->layout("_theme", ["title" => "Confirme e ative sua conta na " . CONF_SITE_NAME]); ?>

<h2>Seja bem-vindo(a) a <?= CONF_SITE_NAME ?> <?= $name; ?>. Vamos confirmar seu cadastro?</h2>
<p>É importante confirmar seu cadastro para ativar as notificações. Assim podemos enviar a você avisos e
    muito mais.</p>
<p><a title='Confirmar Cadastro' href='<?= $confirm_link; ?>'>CLIQUE AQUI PARA CONFIRMAR</a></p>