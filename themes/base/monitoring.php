<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <h2 class="mb-10">Monitoramento</h2>
                <div class="ajax_response"></div>
                <?= flash() ?>

                <table class="table table-hover table-responsive-sm">
                    <thead class="thead-theme">
                    <tr>
                        <th>Módulo</th>
                        <th>Documentos</th>
                        <th>Páginas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th class="font-weight-bolder">Despesa</th>
                        <th>43.062</th>
                        <th>247.082</th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Licitação</th>
                        <th>181</th>
                        <th>43.514</th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Contrato</th>
                        <th>196</th>
                        <th>1.718</th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Legislação</th>
                        <th>2.036</th>
                        <th>3.552</th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Relatório</th>
                        <th>198</th>
                        <th>40.306</th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Total</th>
                        <th>45.673</th>
                        <th>336.172</th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>

<?php $v->end() ?>
