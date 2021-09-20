<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <h2 class="mb-10">Indexação</h2>
                <div class="ajax_response"></div>
                <?= flash(); ?>
                <table class="table table-hover table-responsive-md">
                    <thead class="thead-theme">
                    <tr>
                        <th>Arquivos não indexados</th>
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($list)): ?>
                        <?php foreach ($list as $item): ?>
                            <tr>
                                <th><?= $item['name'] ?></th>
                                <th>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <form action="" method="post" class="form">
                                            <input type="hidden" name="action" value="indexar">
                                            <input type="hidden" name="file_name" value="<?= $item['name'] ?>">
                                            <input type="hidden" name="module" value="<?= $item['module'] ?>">
                                            <button type="submit" class="btn btn-theme mr-2">Indexar</button>
                                        </form>
                                        <form action="" method="post" class="form">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="file_name" value="<?= $item['name'] ?>">
                                            <input type="hidden" name="module" value="<?= $item['module'] ?>">
                                            <button type="submit" class="btn btn-danger">Apagar</button>
                                        </form>
                                    </div>
                                </th>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>

<?php $v->end() ?>
