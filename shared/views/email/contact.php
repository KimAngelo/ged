<?php $v->layout("_theme", ["title" => "Contato"]); ?>

<h2><?= $name; ?> Enviou uma mesagem no formulário fale conosco.</h2>
<p>E-mail: <?= $email; ?></p>
<p>Telefone: <?= $phone ?></p>
<p>Mensagem:</p>
<p><?= $message; ?></p>