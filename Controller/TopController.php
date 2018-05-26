<?php
class TopController extends VoteAppController
{
    public function admin_index()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_VIEW_TOP'))
            throw new ForbiddenException();

        $this->loadModel('Vote.Vote');
        $this->layout = 'admin';

        $this->set('thism', date('m'));
        $month = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
        $this->set('months', $month);

        $new_month = array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
        $this->set('new_months', $new_month);

        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_VIEW_TOP'));
        for($i = 0; $i < 12; $i++){
            if($i < 10){
                $month = "0{$i}"; 
            } else { 
                $month = $i;
            };
            $this_year[$i] = $this->Vote->find('all', [
                'fields' => ['username', 'COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') . '-' . $month . '-%'],
                'order' => 'count DESC',
                'group' => 'username'
            ]);
            $last_year[$i] = $this->Vote->find('all', [
                'fields' => ['username', 'COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') - 1 . '-' . $month . '-%'],
                'order' => 'count DESC',
                'group' => 'username'
            ]);
        }
        for($i = 0; $i < 13; $i++){
            if($i < 10){
                $month = "0{$i}"; 
            } else { 
                $month = $i;
            };
            $vote_this_year[$i] = $this->Vote->find('all', [
                'fields' => ['COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') . '-' . $month . '-%'],
                'order' => 'count DESC'
            ]);
            $vote_last_year[$i] = $this->Vote->find('all', [
                'fields' => ['COUNT(id) AS count'],
                'conditions' => ['created LIKE' => date('Y') - 1 . '-' . $month . '-%'],
                'order' => 'count DESC'
            ]);
        }

        $this->set(compact("this_year"));
        $this->set(compact("last_year"));
        $this->set(compact("vote_this_year"));
        $this->set(compact("vote_last_year"));
        // GRAPH
        for($i = 0; $i < 12; $i++)
        {
            $nbr_this_year[$i] = 0;
            $nbr_last_year[$i] = 0;
            $iv = 0;
            foreach($this_year[$i] as $count){
                $nbr_this_year[$i-1]++;
            }
            foreach($last_year[$i] as $count){
                $nbr_last_year[$i-1]++;
            }
        }
        $this->set("nbr_this_year", $nbr_this_year);
        $this->set("nbr_last_year", $nbr_last_year);
        
        for($i = 0; $i < 12; $i++){
            $nbr_vote_this_year[$i] = $vote_this_year[$i+1][0][0]['count'];
            $nbr_vote_last_year[$i] = $vote_last_year[$i+1][0][0]['count'];
        }

        $this->set("nbr_vote_this_year", $nbr_vote_this_year);
        $this->set("nbr_vote_last_year", $nbr_vote_last_year);

        $maxvoteur_this_year = array_sum($nbr_this_year);
        $maxvoteur_last_year = array_sum($nbr_last_year);
        for($i = 0; $i < 12; $i++)
        {
			if($maxvoteur_last_year != 0){		
				$percent_this_year[$i] = $nbr_this_year[$i]/$maxvoteur_this_year * 100;
				$percent_last_year[$i] = $nbr_last_year[$i]/$maxvoteur_last_year * 100;
			}
			else {
				$percent_this_year[$i] = $nbr_this_year[$i]/1 * 100;
				$percent_last_year[$i] = $nbr_last_year[$i]/1 * 100;
			}
        }
        
        $this->set("percent_this_year", $percent_this_year);
        $this->set("percent_last_year", $percent_last_year);
    }
}
