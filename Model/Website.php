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
                // TODO: Check OUT
                break;
            case 'SRV-MC-ORG':
                // Check with API
                $result = @file_get_contents("http://www.serveurs-minecraft.org/api/is_valid_vote.php?id={$website['data']['server_id']}&ip=$ip&duration=3");
                if ($result === false || intval($result) > 0)
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
                if (time() - strtotime($result['lastVote']['date']) < (3 * 60 * 60)) // 3 minutes between vote and check
                    return true;
                break;
            case 'TOPG-ORG':
                // Check with API
                $result = @file_get_contents("http://topg.org/check_ip.php?siteid={$website['data']['server_id']}&userip=$ip");
                if ($result === false || $result == "1")
                    return true;
                break;
            case 'TOP-SERVEUR-NET':
                // Check with API
                $result = @file_get_contents("https://api.top-serveurs.net/v1/votes/last?server_token={$website['data']['server_token']}");
                if ($result && ($result = json_decode($result, true))) {
                    if (in_array($ip, array_map(function ($vote) {
                        return $vote['ip'];
                    }, $result['votes'])))
                        return true;
                }
                break;
            case 'LISTE-SRV-MC-FR':
                // Check with API
                $result = @file_get_contents("https://liste-serv-minecraft.fr/api/check?server={$website['data']['server_id']}&ip=$ip");
                if ($result === false || ($result = json_decode($result, true)) === false || $result['id_vote'])
                    return true;
                break;
			case 'SRV-PRIV':
                // Check with API
                $result = @file_get_contents("https://serveur-prive.net/api/vote/{$website['data']['server_id']}/$ip");
                if ($result === false || intval($result) > 0)
                    return true;
                break;
	        case 'LIST-SRV-MC-ORG':
                // Check with API
                $result = @file_get_contents("http://www.liste-serveurs-minecraft.org/get_ip.php/{$website['data']['server_id']}/$ip");
                if ($result === false || intval($result) > 0)
                    return true;
                break;
            case 'SRV-MULTIGAMES':
                // Check with API
                $result = @file_get_contents("https://serveur-multigames.net/api/{$website['data']['server_id']}/?ip=$ip");
                if ($result || intval($result) > 0)
                    return true;
                break;
            default:
                return true;
        }
        return false;
    }
}
