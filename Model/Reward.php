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
}