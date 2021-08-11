<?php $v->layout("_theme", ["title" => "E-mail de suporte"]); ?>

<h2>O <?= $first_name; ?> precisa de suporte</h2>
<p>Assunto:</p>
<p><?= $subject; ?></p><br>
<p>Mensagem:</p>
<p><?= $message; ?></p>
<hr>
<p>E-mail de cadastro é: <?= $email; ?></p>
<p>Código identificador: <?= $cod ?></p>