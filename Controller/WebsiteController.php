<?php
class WebsiteController extends VoteAppController {

    public function admin_index()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_MANAGE_WEBSITES'))
            throw new ForbiddenException();
        $this->layout = 'admin';
        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_MANAGE_WEBSITES'));

        $this->loadModel('Vote.Website');
        $this->set('websites', $this->Website->find('all'));
    }

    public function admin_delete()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_MANAGE_WEBSITES'))
            throw new ForbiddenException();
        $id = $this->request->params['id'];
        $this->loadModel('Vote.Website');
        $this->Website->delete($id);

        $this->Session->setFlash($this->Lang->get('VOTE__ADMIN_DELETE_WEBSITE_SUCCESS'), 'default.success');
        $this->redirect(['action' => 'index']);
    }

    public function admin_edit() // add & edit
    {
        if (!$this->Permissions->can('VOTE__ADMIN_MANAGE_WEBSITES'))
            throw new ForbiddenException();
        if (isset($this->request->params['id'])) {
            $id = $this->request->params['id'];
            $website = $this->Website->find('first', ['conditions' => ['id' => $id]]);
            if (empty($website))
                throw new NotFoundException();
            $this->set('website', $website['Website']);
        }
        else
            $id = null;

        $this->layout = 'admin';
        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_MANAGE_WEBSITES'));

        $this->loadModel('Vote.Reward');
        $this->set('rewards', $this->Reward->find('all'));
        $this->loadModel('Server');
        $this->set('servers', $this->Server->findSelectableServers());

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->type('json');
            if (empty($this->request->data['name']) || !isset($this->request->data['time']) || empty($this->request->data['url']) || empty($this->request->data['server_id']) || empty($this->request->data['type']) || empty($this->request->data['rewards']))
                return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('ERROR__FILL_ALL_FIELDS')]);

            $this->Website->read(null, $id);
            $this->Website->set([
                'name' => $this->request->data['name'],
                'time' => intval($this->request->data['time']),
                'url' => $this->request->data['url'],
                'server_id' => intval($this->request->data['server_id']),
                'type' => $this->request->data['type'],
                'rewards' => json_encode($this->request->data['rewards']),
                'data' => (isset($this->request->data['datas']) && is_array($this->request->data['datas'])) ? json_encode($this->request->data['datas']) : NULL
            ]);
            $this->Website->save();

            $this->History->set('ADD_EDIT_WEBSITE', 'vote');
            $this->Session->setFlash($this->Lang->get('VOTE__ADMIN_EDIT_WEBSITE_SUCCESS'), 'default.success');
            return $this->sendJSON(['statut' => true, 'msg' => $this->Lang->get('VOTE__ADMIN_EDIT_WEBSITE_SUCCESS')]);
        }
    }

}