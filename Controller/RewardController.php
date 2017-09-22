<?php
class RewardController extends VoteAppController {

    public function admin_index()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_MANAGE_REWARDS'))
            throw new ForbiddenException();
        $this->layout = 'admin';
        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_MANAGE_REWARDS'));

        $this->loadModel('Vote.Reward');
        $this->set('rewards', $this->Reward->find('all'));
    }

    public function admin_delete()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_MANAGE_REWARDS'))
            throw new ForbiddenException();
        $id = $this->request->params['id'];
        $this->loadModel('Vote.Reward');
        $this->Reward->delete($id);

        $this->Session->setFlash($this->Lang->get('VOTE__ADMIN_DELETE_REWARD_SUCCESS'), 'default.success');
        $this->redirect(['action' => 'index']);
    }

    public function admin_edit() // add & edit
    {
        if (!$this->Permissions->can('VOTE__ADMIN_MANAGE_REWARDS'))
            throw new ForbiddenException();
        if (isset($this->request->params['id'])) {
            $id = $this->request->params['id'];
            $reward = $this->Reward->find('first', ['conditions' => ['id' => $id]]);
            if (empty($reward))
                throw new NotFoundException();
            $this->set('reward', $reward['Reward']);
        }
        else
            $id = null;

        $this->layout = 'admin';
        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_MANAGE_REWARDS'));

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->type('json');
            if (empty($this->request->data['name']) || !isset($this->request->data['commands']) || !isset($this->request->data['amount']) || empty($this->request->data['probability']) || !isset($this->request->data['need_online']))
                return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('ERROR__FILL_ALL_FIELDS')]);

            $this->Reward->read(null, $id);
            $this->Reward->set([
                'name' => $this->request->data['name'],
                'commands' => (json_decode($this->request->data['commands']) !== false) ? $this->request->data['commands'] : '[]',
                'amount' => floatval($this->request->data['amount']),
                'probability' => intval($this->request->data['probability']),
                'need_online' => intval($this->request->data['need_online'])
            ]);
            $this->Reward->save();

            $this->History->set('ADD_EDIT_REWARD', 'vote');
            $this->Session->setFlash($this->Lang->get('VOTE__ADMIN_EDIT_REWARD_SUCCESS'), 'default.success');
            return $this->sendJSON(['statut' => true, 'msg' => $this->Lang->get('VOTE__ADMIN_EDIT_REWARD_SUCCESS')]);
        }
    }

}