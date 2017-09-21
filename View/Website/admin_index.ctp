<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('VOTE__ADMIN_MANAGE_WEBSITES') ?></h3>
                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><?= $Lang->get('VOTE__ADMIN_WEBSITE_NAME') ?></th>
                                <th><?= $Lang->get('VOTE__ADMIN_WEBSITE_URL') ?></th>
                                <th><?= $Lang->get('VOTE__ADMIN_WEBSITE_CREATED') ?></th>
                                <th><?= $Lang->get('GLOBAL__ACTIONS') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($websites as $website) {
                                echo '<tr>';
                                echo '<td>' . $website['Website']['name'] . '</td>';
                                echo '<td><a href="' . $website['Website']['url'] . '">' . $website['Website']['url'] . '</a></td>';
                                echo '<td>' . $Lang->date($website['Website']['created']) . '</td>';
                                echo '<td>';
                                echo '<a class="btn btn-info" href="' . $this->Html->url(['action' => 'edit', 'id' => $website['Website']['id']]) . '">' . $Lang->get('GLOBAL__EDIT') . '</a>';
                                echo '<a class="btn btn-danger" href="' . $this->Html->url(['action' => 'delete', 'id' => $website['Website']['id']]) . '">' . $Lang->get('GLOBAL__DELETE') . '</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <a href="<?= $this->Html->url(['action' => 'edit']) ?>" class="btn btn-success"><?= $Lang->get('GLOBAL__ADD') ?></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
