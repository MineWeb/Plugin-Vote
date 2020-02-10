<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('VOTE__ADMIN_VOTE_CONFIGURATION_TITLE') ?></h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" data-ajax="true">

                        <div class="form-group">
                            <div class="checkbox">
                                <input name="need_register"
                                       type="checkbox" <?= ($configuration['need_register']) ? 'checked' : '' ?>>
                                <label><?= $Lang->get('VOTE__ADMIN_VOTE_CONFIGURATION_NEED_REGISTER') ?></label>
                            </div>
                        </div>


                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_VOTE_CONFIGURATION_GLOBAL_COMMAND') ?></label>
                            <input name="global_command" class="form-control" type="text"
                                   value="<?= htmlspecialchars($configuration['global_command']) ?>"><br>
                            <small>
                                <b>{PLAYER}</b> = Pseudo du voteur <br>
                                <b>{REWARD_NAME}</b> = Nom de la récompense (Quand il n'y a qu'une récompense) <br>
                                <b><?= $Lang->get('GLOBAL__EXAMPLE') ?>:</b> <i>say {PLAYER} vient de voter et de
                                    recevoir {REWARD_NAME} !</i>
                            </small>
                        </div>
                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_VOTE_CONFIGURATION_GLOBAL_COMMAND_PLURAL') ?></label>
                            <input name="global_command_plural" class="form-control" type="text"
                                   value="<?= htmlspecialchars($configuration['global_command_plural']) ?>"><br>
                            <small>
                                <b>{PLAYER}</b> = Pseudo du voteur <br>
                                <b>{REWARD_NUMBER}</b> = Nombre de récompenses en attentes <br>
                                <b><?= $Lang->get('GLOBAL__EXAMPLE') ?>:</b> <i>say {PLAYER} vient de récupèrer {REWARD_NUMBER} votes !</i>
                            </small>
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
