<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('VOTE__ADMIN_MANAGE_WEBSITES') ?></h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" data-ajax="true" data-redirect-url="<?= $this->Html->url(['action' => 'index']) ?>">

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_NAME') ?></label>
                            <input name="name" class="form-control" value="<?= (isset($website)) ? $website['name'] : '' ?>" type="text">
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_TYPE') ?></label>
                            <select class="form-control" name="type">
                                <option value="default"><?= $Lang->get('VOTE__ADMIN_WEBSITE_TYPE_DEFAULT') ?></option>
                                <option value="RPG-PARADIZE">RPG-Paradize (OUT)</option>
                                <option value="SRV-MC-ORG">http://www.serveurs-minecraft.org</option>
                                <option value="RVMC-ORG">https://www.serveursminecraft.org</option>
                                <option value="SRV-MC-COM">https://serveurs-minecraft.com</option>
                                <option value="TOPG-ORG">http://topg.org</option>
                                <option value="TOP-SERVEUR-NET">https://minecraft.top-serveurs.net</option>
                                <option value="LISTE-SRV-MC-FR">https://liste-serv-minecraft.fr</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_TIME') ?></label>
                            <div class="input-group">
                                <input name="time" class="form-control" value="<?= (isset($website)) ? $website['time'] : '' ?>" type="text">
                                <span class="input-group-addon"><?= $Lang->get('GLOBAL__DATE_R_MINUTES') ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_URL') ?></label>
                            <input name="url" class="form-control" value="<?= (isset($website)) ? $website['url'] : '' ?>" type="text">
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_SERVER_ID') ?></label>
                            <select class="form-control" name="server_id">
                                <?php
                                foreach ($servers as $id => $name) {
                                    echo '<option value="'.$id.'"';
                                    echo (isset($website) && $website['server_id'] === $id) ? ' selected' : '';
                                    echo '>'.$name.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_REWARDS') ?></label>
                            <select class="form-control" name="rewards" multiple>
                                <?php
                                foreach ($rewards as $reward) {
                                    echo '<option value="'.$reward['Reward']['id'].'"';
                                    echo (isset($website) && in_array($reward['Reward']['id'], json_decode($website['rewards']))) ? ' selected' : '';
                                    echo '>'.$reward['Reward']['name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="pull-right">
                            <button class="btn btn-primary" type="submit"><?= $Lang->get('GLOBAL__SUBMIT') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>