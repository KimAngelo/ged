<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <h2 class="">Empresas</h2>
                    <a href="<?= $router->route('admin.createCompany') ?>" title="Cadastrar uma nova empresa"
                       class="btn btn-secondary font-weight-bold btn-pill btn-lg">Cadastrar Nova Empresa</a>
                </div>
                <div class="ajax_response"></div>
                <?= flash() ?>
                <?php if (!empty($companies)): ?>
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Orgão</th>
                            <th>Tipo</th>
                            <th>Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <th><?= $company->id ?></th>
                                <th><?= $company->name ?></th>
                                <th><?= $company->type ?></th>
                                <th>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="<?= $router->route('admin.updateCompany', ['id' => $company->id]) ?>"
                                           class="btn btn-primary btn-sm">Editar</a>
                                    </div>
                                </th>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>

<?php $v->end() ?>
