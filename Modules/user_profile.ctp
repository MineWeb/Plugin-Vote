<?php if (isset($rewards_waiting) && $rewards_waiting) { ?>
    <hr>
    <div class="alert alert-info">
        <?= str_replace('{NBR_REWARDS}', $rewards_waiting, $Lang->get('VOTE__USER_MSG_REWARDS_WAITING')) ?>
        &nbsp;&nbsp;<a href="<?= $this->Html->url(array('controller' => 'vote', 'action' => 'getNotCollectedReward', 'plugin' => 'vote')) ?>"
                class="btn btn-info">
            <?php if ($rewards_waiting > 1) echo str_replace('{VOTE_SERVER}', $server_vote, $Lang->get('VOTE__USER_GET_REWARD_PLURAL')); else echo str_replace('{VOTE_SERVER}', $server_vote, $Lang->get('VOTE__USER_GET_REWARD'));  ?>
        </a>
    </div>
<?php } ?>
