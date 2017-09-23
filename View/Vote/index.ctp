<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="row form-group">
                <div class="col-xs-12">
                    <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                        <li class="active">
                            <a href="#step-1">
                                <h4 class="list-group-item-heading"><?= $Lang->get('VOTE__STEP_TITLE') ?> 1</h4>
                                <p class="list-group-item-text"><?= $Lang->get('VOTE__STEP_SET_USER') ?></p>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="#step-2">
                                <h4 class="list-group-item-heading"><?= $Lang->get('VOTE__STEP_TITLE') ?> 2</h4>
                                <p class="list-group-item-text"><?= $Lang->get('VOTE__STEP_SET_WEBSITE') ?></p>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="#step-3">
                                <h4 class="list-group-item-heading"><?= $Lang->get('VOTE__STEP_TITLE') ?> 3</h4>
                                <p class="list-group-item-text"><?= $Lang->get('VOTE__STEP_GET_REWARDS') ?></p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row step active" id="step-1">
                <div class="col-xs-12">
                    <div class="col-md-12 well setup-content text-center">

                        <h3>Entrez votre pseudo</h3>
                        <form data-ajax="true" action="<?= $this->Html->url(['action' => 'setUser']) ?>" method="post" data-callback-function="afterSetUser">
                            <div style="width: 350px;display: inline-block;">
                                <div class="ajax-msg"></div>
                                <input type="text" class="form-control" name="username" placeholder="Eywek" <?= ($user) ? 'value="' . $user['pseudo'] . '" disabled' : '' ?>>
                            </div>
                            <br><br>
                            <button class="btn btn-success" type="submit">Passer a l'étape suivante</button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="row step" id="step-2">
                <div class="col-xs-12">
                    <div class="col-md-12 well setup-content">
                        <div class="loader">
                            <p>
                                <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
                            </p>
                        </div>

                        <div id="website-error"></div>
                        <div class="row">
                            <?php
                            $i = 0;
                            foreach ($websitesByServers as $serverName => $websites) {
                                if ($i % 3 === 0)
                                    echo '</div><div class="row">';
                                $i++;
                                echo '<div class="col-md-4">';
                                    echo '<div class="panel panel-default">';
                                        echo '<div class="panel-heading text-center">';
                                            echo '<h3 class="panel-title">' . $serverName . '</h3>';
                                        echo '</div>';
                                        echo '<div class="panel-body">';
                                            foreach ($websites as $website) {
                                                echo '<a data-website-id="' . $website['Website']['id'] . '" href="' . $website['Website']['url'] . '" target="_blank" class="btn btn-block btn-success website">' . $website['Website']['name'] . '</a>';
                                            }
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row step" id="step-3">
                <div class="col-xs-12">
                    <div class="col-md-12 well setup-content">

                        <div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <div id="reward-msg"></div>
                                <button class="btn btn-success btn-block get-reward" data-reward="now"><?= $Lang->get('VOTE__GET_REWARD_NOW') ?></button>
                                <button class="btn btn-success btn-block get-reward" data-reward="later"><?= $Lang->get('VOTE__GET_REWARD_LATER') ?></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">

            <hr>

            <div class="well">

                <h2 class="text-center">
                    <i class="fa fa-table"></i>
                    Classement
                </h2>
                
                <div class="table-responsive">
                    <table class="table text-muted">
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($users as $user) {
                                ++$i;
                                echo '<tr>';
                                    echo "<td>#$i";
                                        if ($i === 1)
                                            echo '&nbsp;<i style="color:rgb(255, 215, 0);" class="fa fa-trophy"></i>';
                                        else if ($i === 2)
                                            echo '&nbsp;<i style="color:rgb(192, 192, 192);" class="fa fa-trophy"></i>';
                                        else if ($i === 3)
                                            echo '&nbsp;<i style="color:rgb(176, 0, 14);" class="fa fa-trophy"></i>';
                                    echo "</td>";
                                    echo "<td><img src='{$this->Html->url(['controller' => 'API', 'action' => 'get_head_skin', 'plugin' => false, $user['username'], 25])}' class='img-rounded' alt=''> &nbsp;{$user['username']}</td>";
                                    echo "<td>{$user['count']} " . strtolower($Lang->get('VOTE__TITLE_ACTION')) . "</td>";
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>
<style>
    ul.nav {
        padding-bottom: 5px;
        padding-top: 5px;
        margin-bottom: 0;
    }

    .well {
        background: #fff;
    }

    .step:not(.active) {
        display: none;
    }

    .loader {
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(156, 156, 156, 0.55);
        z-index: 9;
        cursor: wait;
    }
    .loader p {
        position: absolute;
        top: 45%;
        left: 47.8%;
        color: #fff;
    }
</style>
<script type="text/javascript">
    function afterSetUser(req, res)
    {
        next(2)
    }

    function next(step)
    {
        $('#step-' + (step - 1).toString()).removeClass('active')
        $('#step-' + step.toString()).addClass('active')
        $('a[href="#step-' + (step - 1).toString() + '"]').parent().removeClass('active')
        $('a[href="#step-' + step.toString() + '"]').parent().addClass('active').removeClass('disabled')
    }

    $('.website').on('click', function (e) {
        e.preventDefault()
        website = $(this)
        $('.loader').css('display', 'block')
        $.post('<?= $this->Html->url(['action' => 'setWebsite']) ?>', {'data[_Token][key]': '<?= $csrfToken ?>', 'website_id': $(this).attr('data-website-id')}, function (data) {
            if (data.status) {
                if (!window.open(data.data.website.url, '_blank')) {
                    $('#voteBtn').attr('href', data.data.website.url)
                    $('#redirectModal').modal({backdrop: 'static', keyboard: false})
                } else {
                    startTimerCheckVote()
                }
            } else {
                $('#website-error').html('<div class="alert alert-danger"><b><?= $Lang->get('GLOBAL__ERROR') ?>:</b> ' + data.error + '</div>')
                $('.loader').css('display', 'none')
            }
        }).fail(function () {
            $('#website-error').html('<div class="alert alert-danger"><b><?= $Lang->get('GLOBAL__ERROR') ?>:</b> <?= $Lang->get('VOTE__ERROR_WEBSITE') ?></div>')
            $('.loader').css('display', 'none')
        })
    })

    function startTimerCheckVote()
    {
        setTimeout(checkVote, 10000);
    }
    function checkVote()
    {
        $.get('<?= $this->Html->url(['action' => 'checkVote']) ?>', function (data) {
            if (data.status) {
                if (!data.reward_later)
                    $('.get-reward[data-reward="later"]').remove()
                next(3)
            } else
                setTimeout(checkVote, 2500);
        }).fail(function () {
            setTimeout(checkVote, 2500);
        })
    }

    $('.get-reward').on('click', function (e) {
        var btn = $(this)
        var type = btn.attr('data-reward')
        $('.get-reward').addClass('disabled')
        $.post('<?= $this->Html->url(['action' => 'getReward']) ?>', {'data[_Token][key]': '<?= $csrfToken ?>', 'reward_time': type.toUpperCase()}, function (data) {
            if (data.status) {
                $('#reward-msg').html('<div class="alert alert-success"><b><?= $Lang->get('GLOBAL__SUCCESS') ?>:</b> ' + data.success + '</div>')
            } else if (!data.status) {
                $('.get-reward').removeClass('disabled')
                $('#reward-msg').html('<div class="alert alert-danger"><b><?= $Lang->get('GLOBAL__ERROR') ?>:</b> ' + data.error + '</div>')
            } else {
                $('.get-reward').removeClass('disabled')
                $('#reward-msg').html('<div class="alert alert-danger"><b><?= $Lang->get('GLOBAL__ERROR') ?>:</b> ' + data.msg + '</div>')
            }
        }).fail(function () {
            $('.get-reward').removeClass('disabled')
            $('#reward-msg').html('<div class="alert alert-danger"><b><?= $Lang->get('GLOBAL__ERROR') ?>:</b> <?= $Lang->get('VOTE__ERROR_REWARD') ?></div>')
        })
    })
</script>
<div class="modal fade" id="redirectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?= $Lang->get('VOTE__TITLE') ?></h4>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-info"><?= $Lang->get('VOTE__MODAL_DESC') ?></div>
                <a href="#" id="voteBtn" target="_blank" onclick="$('#redirectModal').modal('hide');startTimerCheckVote()" class="btn btn-info btn-block"><?= $Lang->get('VOTE__MODAL_BTN') ?></a>
            </div>
        </div>
    </div>
</div>