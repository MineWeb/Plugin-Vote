<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('VOTE__ADMIN_MANAGE_REWARDS') ?></h3>
                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?= $Lang->get('VOTE__ADMIN_REWARD_NAME') ?></th>
                                    <th><?= $Lang->get('VOTE__ADMIN_REWARD_CREATED') ?></th>
                                    <th><?= $Lang->get('GLOBAL__ACTIONS') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($rewards as $reward) {
                                    echo '<tr>';
                                        echo '<td>' . $reward['Reward']['name'] . '</td>';
                                        echo '<td>' . $Lang->date($reward['Reward']['created']) . '</td>';
                                        echo '<td>';
                                            echo '<a class="btn btn-info" href="' . $this->Html->url(['action' => 'edit', 'id' => $reward['Reward']['id']]) . '">' . $Lang->get('GLOBAL__EDIT') . '</a>';
                                            echo '<a class="btn btn-danger" href="' . $this->Html->url(['action' => 'delete', 'id' => $reward['Reward']['id']]) . '">' . $Lang->get('GLOBAL__DELETE') . '</a>';
                                        echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                <tr>
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
