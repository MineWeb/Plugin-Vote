<?php
class Website extends VoteAppModel
{
    // Valid if user has voted
    public function valid($user, $ip, $website)
    {
        // Data
        $website['data'] = json_decode($website['data'], true);
        // Check
        switch ($website['type']) {
            case 'RPG-PARADIZE':
                // Check OUT
                break;
            case 'SRV-MC-ORG':
                // Check with API
                $result = @file_get_contents("http://www.serveurs-minecraft.org/api/is_valid_vote.php?id={$website['data']['server_id']}&ip=$ip&duration=3");
                if ($result === false || $result > 0)
                    return true;
                break;
            case 'SRVMC-ORG':
                // Check with API
                $result = @file_get_contents("https://www.serveursminecraft.org/sm_api/peutVoter.php?id={$website['data']['server_id']}&ip=$ip");
                if ($result === false || $result != "true")
                    return true;
                break;
            case 'SRV-MC-COM':
                // Check with API
                $result = @file_get_contents("https://serveurs-minecraft.com/api.php?Classement={$website['data']['server_id']}&ip=$ip");
                if ($result === false)
                    return true;
                $result = @json_decode($result, true);
                if ($result === false)
                    return true;
                if (strtotime($result['reqVote']['date']) - strtotime($result['lastVote']['date']) < (3 * 60 * 60)) // 3 minutes between vote and check
                    return true;
                break;

            default:
                return true;
        }
        return false;
    }
}