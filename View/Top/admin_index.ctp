<?php
$act_month = date(m);

?>
<div class="container-fluid">
    <h3 style="text-align: center">Sur cet année (<?= date(Y) ?>)</h3>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php
            $i = 0;
            foreach(TopController::month() as $var) {
                $i++;
                echo '<li class="';
                if($i == $act_month){ echo "active"; };
                echo '"><a href="#'.$var.'" data-toggle="tab" style="text-transform:capitalize;" aria-expanded="';
                if($i == $act_month){ echo "true"; } else { echo "false"; };
                echo '">'.$var.'</a></li>';
            }
            ?>
        </ul>
        <div class="tab-content">
            <?php
            $i = 00;
            foreach(TopController::month() as $var) {
                $i++;
                ?>
                <div class="tab-pane <?php if($i == $act_month): echo "active"; endif; ?>" id="<?= $var ?>">
                    <?php
                    if($i < 10){ $iz = "0{$i}"; } else { $iz = $i;};
                    $top = ClassRegistry::init('Vote.Vote')->find('all', [
                        'fields' => ['username', 'COUNT(id) AS count'],
                        'conditions' => ['created LIKE' => date('Y') . '-' . $iz . '-%'],
                        'order' => 'count DESC',
                        'group' => 'username',
                        'limit' => 16
                    ]);
                    ?>
                    <?php if(empty($top)) { ?>
                        <?php if($iz > date(m)) { ?>
                            <h3 style="text-align: center; padding: 30px">Nous ne sommes pas encore en <?= $var ?> <?= date(Y) ?>.</h3>
                        <?php } else { ?>
                            <h3 style="text-align: center; padding: 30px">Il n'y a pas eu de voteur en <?= $var ?> <?= date(Y) ?>.</h3>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th class="col-sm-1 col-xs-2">ID</th>
                                    <th class="col-md-2 col-sm-3 col-xs-6">Pseudo</th>
                                    <th class="col-md-2 col-sm-3 col-xs-4">Nombre de votes</th>
                                    <th class="col-md-7 col-sm-4"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i_top = 0;
                                foreach($top as $value) { $i_top++; ?>
                                    <tr>
                                        <td>#<?= $i_top ?></td>
                                        <td><?= $value['Vote']['username'] ?></td>
                                        <td><?= $value[0]['count'] ?> vote<?php if($value[0]['count'] < 2){ } else { echo 's';} ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <br>
    <h3 style="text-align: center">Sur l'année dernière (<?= date(Y) - 1 ?>)</h3>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php
            $i = 0;
            foreach(TopController::month() as $var) {
                $i++;
                echo '<li class="';
                if($i == "1"){ echo "active"; };
                echo '"><a href="#last-'.$var.'" data-toggle="tab" style="text-transform:capitalize;" aria-expanded="';
                if($i == "1"){ echo "true"; } else { echo "false"; };
                echo '">'.$var.'</a></li>';
            }
            ?>
        </ul>
        <div class="tab-content">
            <?php
            $i = 00;
            foreach(TopController::month() as $var) {
                $i++;
                ?>
                <div class="tab-pane <?php if($i == "1"): echo "active"; endif; ?>" id="last-<?= $var ?>">
                    <?php
                    if($i < 10){ $iz = "0{$i}"; } else { $iz = $i;};
                    $top = ClassRegistry::init('Vote.Vote')->find('all', [
                        'fields' => ['username', 'COUNT(id) AS count'],
                        'conditions' => ['created LIKE' => date('Y') - 1 . '-' . $iz . '-%'],
                        'order' => 'count DESC',
                        'group' => 'username',
                        'limit' => 16
                    ]);
                    ?>

                    <?php if(empty($top)) { ?>
                        <h3 style="text-align: center; padding: 30px">Il n'y a pas eu de voteur en <?= $var ?> <?= date(Y) - 1 ?>.</h3>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th class="col-sm-1 col-xs-2">ID</th>
                                    <th class="col-md-2 col-sm-3 col-xs-6">Pseudo</th>
                                    <th class="col-md-2 col-sm-3 col-xs-4">Nombre de votes</th>
                                    <th class="col-md-7 col-sm-4"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i_top = 0;
                                foreach($top as $value) { $i_top++; ?>
                                    <tr>
                                        <td>#<?= $i_top ?></td>
                                        <td><?= $value['Vote']['username'] ?></td>
                                        <td><?= $value[0]['count'] ?> vote<?php if($value[0]['count'] < 2){ } else { echo 's';} ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>