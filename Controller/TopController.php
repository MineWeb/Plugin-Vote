<?php
class TopController extends VoteAppController
{
    public function admin_index()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_VIEW_TOP'))
            throw new ForbiddenException();
        $this->layout = 'admin';
        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_VIEW_TOP'));

    }

    function month()
    {
        $month = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
        return $month;
    }

}