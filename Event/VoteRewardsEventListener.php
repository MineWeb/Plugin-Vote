<?php
App::uses('CakeEventListener', 'Event');

class VoteRewardsEventListener implements CakeEventListener {

    public function implementedEvents() {
        return array(
            'onLoadPage' => 'checkRewardsWaiting'
        );
    }

    public function checkRewardsWaiting($event) {
        if ($event->subject()->request->params['controller'] != "user" || $event->subject()->request->params['action'] != "profile")
            return;

        // Add vote

        $user = $event->subject()->viewVars['user'];
        $user['votes_count'] = ClassRegistry::init('Vote.Vote')->find('count', [
            'conditions' => ['created LIKE' => date('Y') . '-' . date('m') . '-%', 'user_id' => $user['id']]
        ]);
        $user['votes_not_collected_count'] = ClassRegistry::init('Vote.Vote')->find('count', ['conditions' => ['user_id' => $user['id'], 'collected' => 0]]);
        $event->subject()->viewVars['user'] = $user;

        $server_vote = ClassRegistry::init('Vote.Vote')->find('first', ['conditions' => ['user_id' => $user['id'], 'collected' => 0], 'recursive' => 1]);
        $server_vote =  $server_vote['Website']['server_id'];
        $server_vote = ClassRegistry::init('Server')->find('first', ['conditions' => ['id' => $server_vote]]);

        ModuleComponent::$vars['server_vote'] = $server_vote['Server']['name'];
        ModuleComponent::$vars['rewards_waiting'] = $user['votes_not_collected_count'];
    }
}
