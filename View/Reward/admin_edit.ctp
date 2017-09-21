<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('VOTE__ADMIN_MANAGE_REWARDS') ?></h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" data-ajax="true" data-custom-function="parseData" data-redirect-url="<?= $this->Html->url(['action' => 'index']) ?>">

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_REWARD_NAME') ?></label>
                            <input name="name" class="form-control" value="<?= (isset($reward)) ? $reward['name'] : '' ?>" type="text">
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('GLOBAL__SERVER_COMMANDS') ?></label>
                            <div class="input-group">
                                <?php
                                if (isset($reward))
                                    $reward['commands'] = (isset($reward['commands'])) ? json_decode($reward['commands'], true) : []
                                ?>
                                <input name="commands" class="form-control" value="<?= isset($reward) ? htmlentities($reward['commands'][0]) : '' ?>" type="text">
                                <div class="input-group-btn">
                                    <button data-i="<?= isset($reward) ? count($reward['commands']) : '0' ?>" type="button" id="addCommand" class="btn btn-success"><?= $Lang->get('VOTE__ADMIN_REWARD_ADD_COMMAND') ?></button>
                                </div>
                            </div>
                            <div class="addCommand">
                                <?php
                                $i = 0;
                                if (isset($reward)) {
                                    unset($reward['commands'][0]);
                                    foreach ($reward['commands'] as $key => $value) {
                                        $i++;
                                        echo '<div class="input-group" style="margin-top:5px;" id="' . $i . '">';
                                        echo '<input name="commands" class="form-control" value="' . htmlentities($value) . '" type="text">';
                                        echo '<span class="input-group-btn">';
                                        echo '<button class="btn btn-danger delete-cmd" data-id="' . $i . '" type="button"><span class="fa fa-close"></span></button>';
                                        echo '</span>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <small><b>{PLAYER}</b> = Pseudo <br><b><?= $Lang->get('GLOBAL__EXAMPLE') ?>:</b> <i>give {PLAYER} 1 1</i></small>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_REWARD_AMOUNT', ['{MONEY_NAME}' => $Configuration->getMoneyName()]) ?></label>
                            <div class="input-group">
                                <input name="amount" class="form-control" value="<?= (isset($reward)) ? $reward['amount'] : '' ?>" type="text">
                                <span class="input-group-addon"><?= $Configuration->getMoneyName() ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= $Lang->get('VOTE__ADMIN_REWARD_PROBABILITY') ?></label>
                            <div class="input-group">
                                <input name="probability" class="form-control" step="0.01" value="<?= (isset($reward)) ? $reward['probability'] : '' ?>" type="number">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <input name="need_online" type="checkbox" <?= (isset($reward) && $reward['need_online']) ? 'checked' : '' ?>>
                                <label><?= $Lang->get('VOTE__ADMIN_REWARD_NEED_ONLINE') ?></label>
                            </div>
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
<script>
    function parseData(form)
    {
        var commands = []
        var inputs = form.find('input[name="commands"]');
        for (i = 0; i < inputs.length; i++) {
            commands.push($(inputs[i]).val())
        }

        return {
            name: form.find('input[name="name"]').val(),
            commands: JSON.stringify(commands),
            amount: form.find('input[name="amount"]').val(),
            probability: form.find('input[name="probability"]').val(),
            need_online: form.find('input[name="need_online"]:checked').length
        }
    }

    $('#addCommand').on('click', function(e) {

        e.preventDefault();

        var i = parseInt($(this).attr('data-i'));

        var input = '';
        input += '<div style="margin-top:5px;" class="input-group" id="'+i+'">';
        input += '<input name="commands" class="form-control" type="text">';
        input += '<span class="input-group-btn">';
        input += '<button class="btn btn-danger delete-cmd" data-id="'+i+'" type="button"><span class="fa fa-close"></span></button>';
        input += '</span>';
        input + '</div>';

        i++;

        $(this).attr('data-i', i);

        $('.addCommand').append(input);

        $('.delete-cmd').unbind('click');
        $('.delete-cmd').on('click', function(e) {

            var id = $(this).attr('data-id');

            $('#'+id).slideUp(150, function() {
                $('#'+id).remove();
            });
        });

    });

    $('.delete-cmd').on('click', function(e) {

        var id = $(this).attr('data-id');

        $('#'+id).slideUp(150, function() {
            $('#'+id).remove();
        });
    });
</script>