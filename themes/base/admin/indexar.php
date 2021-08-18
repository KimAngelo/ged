<?php $v->layout('_theme'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <h2 class="mb-10">Indexação</h2>
                <div class="ajax_response"></div>
                <?= flash() ?>
                <table class="table table-hover table-responsive-sm">
                    <thead class="thead-theme">
                    <tr>
                        <th>Arquivos não indexados</th>
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>despesa_2013_090920_121051.xml</th>
                        <th>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <form action="">
                                    <button type="button" class="btn btn-theme mr-2">Indexar</button>
                                </form>
                                <form action="">
                                    <button type="button" class="btn btn-danger">Apagar</button>
                                </form>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>despesa_2013_090920_121051.xml</th>
                        <th>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <form action="">
                                    <button type="button" class="btn btn-theme mr-2">Indexar</button>
                                </form>
                                <form action="">
                                    <button type="button" class="btn btn-danger">Apagar</button>
                                </form>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>despesa_2013_090920_121051.xml</th>
                        <th>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <form action="">
                                    <button type="button" class="btn btn-theme mr-2">Indexar</button>
                                </form>
                                <form action="">
                                    <button type="button" class="btn btn-danger">Apagar</button>
                                </form>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>despesa_2013_090920_121051.xml</th>
                        <th>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <form action="">
                                    <button type="button" class="btn btn-theme mr-2">Indexar</button>
                                </form>
                                <form action="">
                                    <button type="button" class="btn btn-danger">Apagar</button>
                                </form>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>despesa_2013_090920_121051.xml</th>
                        <th>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <form action="">
                                    <button type="button" class="btn btn-theme mr-2">Indexar</button>
                                </form>
                                <form action="">
                                    <button type="button" class="btn btn-danger">Apagar</button>
                                </form>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>despesa_2013_090920_121051.xml</th>
                        <th>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <form action="">
                                    <button type="button" class="btn btn-theme mr-2">Indexar</button>
                                </form>
                                <form action="">
                                    <button type="button" class="btn btn-danger">Apagar</button>
                                </form>
                            </div>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $v->start('scripts'); ?>

<?php $v->end() ?>
