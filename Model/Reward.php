<?php
class Reward extends VoteAppModel
{
    public function getFromWebsite($website)
    {
        $rewardsList = json_decode($website['rewards'], true);
        $searchRewards = $this->find('all', ['conditions' => ['id' => $rewardsList]]);
        $rewardsList = $rewards = [];
        $totalProbability = 0;

        foreach ($searchRewards as $reward) {
            $rewards[$reward['Reward']['id']] = $reward['Reward'];
            $rewardsList[$reward['Reward']['id']] = $reward['Reward']['probability'];
            $totalProbability += $reward['Reward']['probability'];
        }

        App::import('Component','Util');
        $util = new UtilComponent();
        $rewardId = $util->random($rewardsList, $totalProbability);

        $reward = $rewards[$rewardId];
        $reward['commands'] = json_decode($reward['commands'], true);
        return $reward;
    }

    public function collect($reward, $server_id, $username, ServerComponent $server, $additionalsCommands = [])
    {
        // Check if logged
        if ($reward['need_online'] && !$server->userIsConnected($username, $server_id))
            return false;
        // Replace vars
        $commands = [];
        if (!is_array($reward['commands']))
            $reward['commands'] = json_decode($reward['commands'], true);
        foreach (array_merge($reward['commands'], $additionalsCommands) as $command) {
            $commands[] = str_replace('{PLAYER}', $username, str_replace('{REWARD_NAME}', $reward['name'], $command));
        }

        // Send commands
        $server->commands($commands, $server_id);

        return true;
    }
}