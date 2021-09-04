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
                        <th><?= $reports->expense_total_documents ?></th>
                        <th><?= $reports->expense_total_pages ?></th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Licitação</th>
                        <th><?= $reports->bidding_total_documents ?></th>
                        <th><?= $reports->bidding_total_pages ?></th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Contrato</th>
                        <th><?= $reports->contract_total_documents ?></th>
                        <th><?= $reports->contract_total_pages ?></th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Legislação</th>
                        <th><?= $reports->legislation_total_documents ?></th>
                        <th><?= $reports->legislation_total_pages ?></th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Relatório</th>
                        <th><?= $reports->report_total_documents ?></th>
                        <th><?= $reports->report_total_pages ?></th>
                    </tr>
                    <tr>
                        <th class="font-weight-bolder">Total</th>
                        <th><?= $reports->total_documents ?></th>
                        <th><?= $reports->total_pages ?></th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>

<?php $v->end() ?>
