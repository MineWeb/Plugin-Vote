<?php
class TopController extends VoteAppController
{
    public function admin_index($month)
    {
        if (!$this->Permissions->can('VOTE__ADMIN_VIEW_TOP'))
            throw new ForbiddenException();

        $this->loadModel('Vote.Vote');
        $this->layout = 'admin';

        $this->set('thism', date('m'));
        $month = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
        $this->set('months', $month);

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
        $this->set(compact("this_year"));
        $this->set(compact("last_year"));
    }
}