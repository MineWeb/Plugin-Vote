<?php
class Vote extends VoteAppModel
{
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Reward' => array(
            'className' => 'Vote.Reward',
            'foreignKey' => 'reward_id'
        ),
        'Website' => array(
            'className' => 'Vote.Website',
            'foreignKey' => 'website_id'
        )
    );

    // Check if user can vote
    public function can($user, $ip, $website)
    {
        return ($this->getNextVoteTime($user, $ip, $website) === false);
    }

    // Return a string time or false
    public function getNextVoteTime($user, $ip, $website)
    {
        $conditions = ['website_id' => $website['id'], 'OR' => [['ip' => $ip]]];
        if (isset($user['id']))
            $conditions['OR'][] = ['user_id' => $user['id']];
        else
            $conditions['OR'][] = ['username' => $user['username']];
        $conditions['OR'][] = ['ip' => $ip];
        $lastVote = $this->find('first', ['conditions' => $conditions, 'order' => 'id desc']);
        if (empty($lastVote))
            return false;
        if (strtotime("+{$website['time']} minutes", strtotime($lastVote['Vote']['created'])) <= time())
            return false;

        // Generate string
        $waitTime = ($website['time'] - ((time() - strtotime($lastVote['Vote']['created'])) / 60)) * 60;
        return $waitTime;
    }

    // Check if user can vote in a website
    public function canInAll($user, $ip, $websites)
    {
        foreach ($websites as $website) {
            if ($this->getNextVoteTime($user, $ip, $website['Website']) === false)
                return true;
        }
        return false;
    }
}