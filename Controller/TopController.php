<?php
class TopController extends VoteAppController
{
    public function admin_index()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_VIEW_TOP'))
            throw new ForbiddenException();
        $this->layout = 'admin';
        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_VIEW_TOP'));
        $this->loadModel('Vote.Vote');
        $thism = date('m');
        $months = array("janvier", "fevrier", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "decembre");
        $new_months = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        $date = date('Y-m-d h:i:s');
        for ($i = 0; $i < 13; $i++) {
            if ($i < 10) {
                $month = "0{$i}";
            } else {
                $month = $i;
            };
            $this_year[$i] = $this->Vote->find('all', [
                'fields' => ['username', 'COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') . '-' . $month . '-%', 'Vote.deleted_at' => null],
                'order' => 'count DESC',
                'group' => 'username'
            ]);
            $last_year[$i] = $this->Vote->find('all', [
                'fields' => ['username', 'COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') - 1 . '-' . $month . '-%', 'Vote.deleted_at' => null],
                'order' => 'count DESC',
                'group' => 'username'
            ]);

            $vote_this_year[$i] = $this->Vote->find('all', [
                'fields' => ['COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') . '-' . $month . '-%', 'Vote.deleted_at' => null],
                'order' => 'count DESC'
            ]);
            $vote_last_year[$i] = $this->Vote->find('all', [
                'fields' => ['COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') - 1 . '-' . $month . '-%', 'Vote.deleted_at' => null],
                'order' => 'count DESC'
            ]);
        }

        // GRAPH
        for ($i = 0; $i < 12; $i++) {
            $nbr_this_year[$i] = count($this_year[$i + 1]);
            $nbr_last_year[$i] = count($last_year[$i + 1]);

            $nbr_vote_this_year[$i] = $vote_this_year[$i + 1][0][0]['count'];
            $nbr_vote_last_year[$i] = $vote_last_year[$i + 1][0][0]['count'];
        }

        $maxvoteur_this_year = array_sum($nbr_this_year);
        $maxvoteur_last_year = array_sum($nbr_last_year);
        for ($i = 0; $i < 12; $i++) {
            $percent_this_year[$i] = $nbr_this_year[$i] / (($maxvoteur_last_year != 0) ? $maxvoteur_this_year : 1) * 100;
            $percent_last_year[$i] = $nbr_last_year[$i] / (($maxvoteur_last_year != 0) ? $maxvoteur_last_year : 1) * 100;

        }

        $this->set(compact("thism", "months", "new_months", "this_year", "last_year", "vote_this_year", "vote_last_year", "nbr_this_year", "nbr_last_year", "nbr_vote_this_year", "nbr_vote_last_year", "percent_this_year", "percent_last_year"));
    }
    
    public function admin_del_vote()
    {
 		if($this->isConnected AND $this->User->isAdmin())
		{
			if($this->request->is('ajax')) {
				$this->response->type('json');
				$this->autoRender = null;
				$this->loadModel('Vote.Vote');
				$this->Vote->updateAll(
          array('Vote.deleted_at' => "'" . date('Y-m-d h:i:s') . "'"),
          array('Vote.deleted_at' => null)
        );
				$this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('GLOBAL__SUCCESS'))));
			} else {
                $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('ERROR__BAD_REQUEST'))));
            }
			
		} else {
            $this->redirect('/');
        }
		
	}
}
