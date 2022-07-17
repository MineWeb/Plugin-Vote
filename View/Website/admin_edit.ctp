<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('VOTE__ADMIN_MANAGE_WEBSITES') ?></h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" data-ajax="true"
                          data-redirect-url="<?= $this->Html->url(['action' => 'index']) ?>">

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_NAME') ?></label>
                            <input name="name" class="form-control"
                                   value="<?= (isset($website)) ? $website['name'] : '' ?>" type="text">
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_TYPE') ?></label>
                            <select class="form-control" name="type">
                                <option value="default"><?= $Lang->get('VOTE__ADMIN_WEBSITE_TYPE_DEFAULT') ?></option>
                                <option data-inputs="server_id"
                                        value="YSERVEUR" <?= (isset($website) && $website['type'] == 'YSERVEUR') ? 'selected' : '' ?>>
                                    https://yserveur.fr/
                                </option>
                                <option data-inputs="server_id"
                                        value="SERVEUR-MINECRAFT-VOTE" <?= (isset($website) && $website['type'] == 'SERVEUR-MINECRAFT-VOTE') ? 'selected' : '' ?>>
                                    https://serveur-minecraft-vote.fr/
                                </option>
                                <option data-inputs="server_id"
                                        value="SERVEURS-MC" <?= (isset($website) && $website['type'] == 'SERVEURS-MC') ? 'selected' : '' ?>>
                                    https://serveurs-mc.net/
                                </option>
                                <option data-inputs="server_id"
                                        value="MINECRAFT-TOP-ORG" <?= (isset($website) && $website['type'] == 'MINECRAFT-TOP-ORG') ? 'selected' : '' ?>>
                                    https://minecraft-top.com/
                                </option>
                                <option data-inputs="server_id"
                                        value="SRV-MC-ORG" <?= (isset($website) && $website['type'] == 'SRV-MC-ORG') ? 'selected' : '' ?>>
                                    http://www.serveurs-minecraft.org
                                </option>
                                <option data-inputs="server_id"
                                        value="SRVMC-ORG" <?= (isset($website) && $website['type'] == 'SRVMC-ORG') ? 'selected' : '' ?>>
                                    https://www.serveursminecraft.org
                                </option>
                                <option data-inputs="server_id"
                                        value="SRV-MC-COM" <?= (isset($website) && $website['type'] == 'SRV-MC-COM') ? 'selected' : '' ?>>
                                    https://serveurs-minecraft.com
                                </option>
                                <option data-inputs="server_id"
                                        value="TOPG-ORG" <?= (isset($website) && $website['type'] == 'TOPG-ORG') ? 'selected' : '' ?>>
                                    http://topg.org
                                </option>
                                <option data-inputs="server_id"
                                        value="SRV-MINECRAFT-FR" <?= (isset($website) && $website['type'] == 'SRV-MINECRAFT-FR') ? 'selected' : '' ?>>
                                    https://serveur-minecraft.fr
                                </option>
                                <option data-inputs="server_token"
                                        value="TOP-SERVEUR-NET" <?= (isset($website) && $website['type'] == 'TOP-SERVEUR-NET') ? 'selected' : '' ?>>
                                    https://minecraft.top-serveurs.net
                                </option>
                                <option data-inputs="server_token"
                                        value="TOP-SERVEURS-NET" <?= (isset($website) && $website['type'] == 'TOP-SERVEURS-NET') ? 'selected' : '' ?>>
                                    https://minecraft.top-serveurs.net
                                </option>
                                <option data-inputs="server_id"
                                        value="LISTE-SRV-MC-FR" <?= (isset($website) && $website['type'] == 'LISTE-SRV-MC-FR') ? 'selected' : '' ?>>
                                    https://liste-serv-minecraft.fr
                                </option>
                                <option data-inputs="server_id"
                                        value="SRV-PRIV" <?= (isset($website) && $website['type'] == 'SRV-PRIV') ? 'selected' : '' ?>>
                                    https://serveur-prive.net
                                </option>
                                <option data-inputs="server_id"
                                        value="LIST-SRV-MC-ORG" <?= (isset($website) && $website['type'] == 'LIST-SRV-MC-ORG') ? 'selected' : '' ?>>
                                    https://www.liste-serveurs-minecraft.org
                                </option>
                                <option data-inputs="server_id"
                                        value="SRV-MULTIGAMES" <?= (isset($website) && $website['type'] == 'SRV-MULTIGAMES') ? 'selected' : '' ?>>
                                    https://serveur-multigames.net
                                </option>
                                <option data-inputs="server_id"
                                        value="MGS" <?= (isset($website) && $website['type'] == 'MGS') ? 'selected' : '' ?>>
                                    https://mygreatserver.fr
                                </option>
                                <option data-inputs="server_token"
                                        value="LISTE-SERVEUR-FR" <?= (isset($website) && $website['type'] == 'LISTE-SERVEUR-FR') ? 'selected' : '' ?>>
                                    https://www.liste-serveur.fr/
                                </option>
                                <option data-inputs="server_id"
                                        value="LISTE-SERVEURS-FR" <?= (isset($website) && $website['type'] == 'LISTE-SERVEURS-FR') ? 'selected' : '' ?>>
                                    https://www.liste-serveurs.fr
                                </option>
                                <option data-inputs="server_id"
                                        value="LISTE-MINECRAFT-SRV" <?= (isset($website) && $website['type'] == 'LISTE-MINECRAFT-SRV') ? 'selected' : '' ?>>
                                    https://www.liste-minecraft-serveurs.com/
                                </option>
                                <option data-inputs="server_id"
                                        value="SRV-MINECRAFT-COM" <?= (isset($website) && $website['type'] == 'SRV-MINECRAFT-COM') ? 'selected' : '' ?>>
                                    https://www.serveur-minecraft.com/
                                </option>
                                <option data-inputs="server_id"
                                        value="TOPSRVMINECRAFT-COM" <?= (isset($website) && $website['type'] == 'TOPSRVMINECRAFT-COM') ? 'selected' : '' ?>>
                                    https://topserveursminecraft.com/
                                </option>
                                <option data-inputs="server_id"
                                        value="SERVEUR-TOP-FR" <?= (isset($website) && $website['type'] == 'SERVEUR-TOP-FR') ? 'selected' : '' ?>>
                                    https://serveur-top.fr/
                                </option>
                            </select>
                        </div>

                        <div id="data">
                            <?php
                            if ($data = @json_decode($website['data'])) {
                                foreach ($data as $key => $value) {
                                    echo '<div class="form-group">';
                                    echo '<label>' . $key . '</label>';
                                    echo '<input name="datas[' . $key . ']" class="form-control" value="' . $value . '" type="text">';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_TIME') ?></label>
                            <div class="input-group">
                                <input name="time" class="form-control"
                                       value="<?= (isset($website)) ? $website['time'] : '' ?>" type="text">
                                <span class="input-group-addon"><?= $Lang->get('GLOBAL__DATE_R_MINUTES') ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_URL') ?></label>
                            <input name="url" class="form-control"
                                   value="<?= (isset($website)) ? $website['url'] : '' ?>" type="text">
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_SERVER_ID') ?></label>
                            <select class="form-control" name="server_id">
                                <?php
                                foreach ($servers as $id => $name) {
                                    echo '<option value="' . $id . '"';
                                    echo (isset($website) && $website['server_id'] == $id) ? ' selected' : '';
                                    echo '>' . $name . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_WEBSITE_REWARDS') ?></label>
                            <select class="form-control" name="rewards" multiple>
                                <?php
                                foreach ($rewards as $reward) {
                                    echo '<option value="' . $reward['Reward']['id'] . '"';
                                    echo (isset($website) && in_array($reward['Reward']['id'], json_decode($website['rewards']))) ? ' selected' : '';
                                    echo '>' . $reward['Reward']['name'] . '</option>';
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
<script type="text/javascript">
    $('select[name="type"]').on('change', function () {
        var value = $(this).val()
        var option = $('option[value="' + value + '"]')
        var inputs = option.attr('data-inputs')
        if (inputs !== undefined)
            inputs = inputs.split(',')
        else
            inputs = []

        var html = ''
        for (i = 0; i < inputs.length; i++) {
            html += '<div class="form-group">\n' +
                '<label>' + inputs[i] + '</label>\n' +
                '<input name="datas[' + inputs[i] + ']" class="form-control" type="text">\n' +
                '</div>'
        }
        $('#data').html(html)
    })
</script>
