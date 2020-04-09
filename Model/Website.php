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
			case 'MINECRAFT-TOP-ORG':
                // Check with API
                $result = @file_get_contents("https://api.minecraft-top.com/v1/vote/$ip/{$website['data']['server_id']}");
                if($result['vote'])
                    return true;
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
	        case 'SRV-MINECRAFT-FR':
                // Check with API
                $result = @file_get_contents("https://serveur-minecraft.fr/api-{$website['data']['server_id']}_$ip.json");
                if ($result && ($result = json_decode($result, true))) {
                    if ($result["status"] == "Success")
                        return true;
                }
                break;
            case 'TOPG-ORG':
                // Check with API
                $result = @file_get_contents("http://topg.org/check_ip.php?siteid={$website['data']['server_id']}&userip=$ip");
                if ($result === false || $result == "1")
                    return true;
                break;
            case 'TOP-SERVEUR-NET':
                // Check with API
                $result = @file_get_contents("https://api.top-serveurs.net/v1/votes/check-ip?server_token={$website['data']['server_token']}&ip=$ip");
                if ($result && ($result = json_decode($result, true)))
                    return true;
                break;
            case 'LISTE-SRV-MC-FR':
                // Check with API
                $result = @file_get_contents("https://liste-serv-minecraft.fr/api/check?server={$website['data']['server_id']}&ip=$ip");
                if ($result === false || ($result = json_decode($result, true)) === false || $result['id_vote'])
                    return true;
                break;
            case 'SRV-PRIV':
                // Check with API
                $result = @file_get_contents("https://serveur-prive.net/api/vote/json/{$website['data']['server_id']}/$ip");
                if ($result && ($result = json_decode($result, true))) {
			        if ($result === false || intval($result['status']) == 1)
                        return true;
		        }
                break;
            case 'LIST-SRV-MC-ORG':
                // Check with API
                $result = @file_get_contents("https://api.liste-serveurs-minecraft.org/vote/vote_verification.php?server_id={$website['data']['server_id']}&ip=$ip&duration=180");
                if ($result === false || intval($result) == 1)
                    return true;
                break;
            case 'SRV-MULTIGAMES':
                // Check with API
                $result = @file_get_contents("https://serveur-multigames.net/api/{$website['data']['server_id']}/?ip=$ip");
                if ($result || intval($result) > 0)
                    return true;
                break;
            case 'MGS':
                // Check with API
                $result = json_decode(@file_get_contents("https://mygreatserver.fr/api/checkvote/{$website['data']['server_id']}/$ip"));
                if ($result->success && $result->data->vote)
                    return true;
                break;
            case 'LISTE-SERVEUR-FR':
                // Check with API
                $result = json_decode(@file_get_contents("https://www.liste-serveur.fr/api/hasVoted/{$website['data']['server_token']}/$ip"));
                if (isset($result->hasVoted) && $result->hasVoted === true)
                    return true;
                break;
            case 'LISTE-SERVEURS-FR':
                // Check with API
                $result = json_decode(@file_get_contents("https://www.liste-serveurs.fr/api/checkVote/{$website['data']['server_id']}/$ip"));
                if ($result->success === true)
                    return true;
                break;
            case 'LISTE-MINECRAFT-SRV':
                // Check with API
		$result = json_decode(file_get_contents("https://www.liste-minecraft-serveurs.com/Api/Worker/id_server/{$website['data']['server_id']}/ip/$ip"));
		if($result->result == 202)
		    return true;
                break;	
            default:
                return true;
        }
        return false;
    }
}
