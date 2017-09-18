<?php
class Vote extends VoteAppModel
{
    // Check if user can vote
    public function can($user, $ip, $website)
    {
        return ($this->getNextVoteTime($user, $ip, $website) === false);
    }

    // Return a string time or false
    public function getNextVoteTime($user, $ip, $website)
    {
        $conditions = ['website_id' => $website['id'], 'OR' => []];
        if (isset($user['id']))
            $conditions['OR'][] = ['user_id' => $user['id']];
        else
            $conditions['OR'][] = ['username' => $user['username']];
        $conditions['OR'][] = ['ip' => $ip];
        $lastVote = $this->find('first', ['conditions' => $conditions]);
        if (empty($lastVote))
            return false;
        if (strtotime("+{$website['time']} minutes", strtotime($lastVote['Vote']['created'])) <= time())
            return false;

        // Generate string
        $waitTime = ($website['time'] - ((time() - strtotime($lastVote['Vote']['created'])) / 60)) * 60;
        return $waitTime;
    }
}