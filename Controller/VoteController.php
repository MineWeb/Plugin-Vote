<?php
class VoteController extends VoteAppController {

    public function index()
    {
        $this->set('title_for_layout', $this->Lang->get('VOTE__TITLE'));
        $this->set('config', $this->__getConfig());
    }

    public function setUser()
    {
        if (!$this->request->is('post'))
            throw new NotFoundException();
        if (empty($this->request->data) || empty($this->request->data['username']))
            throw new BadRequestException();
        $this->autoRender = false;
        $this->response->type('json');

        if ($this->User->isConnected()) { // If already logged
            $user = ['username' => $this->User->getKey('pseudo'), 'id' => $this->User->getKey('id')];
        } else if ($this->__getConfig()->need_register) { // If need register, check if username is valid
            $searchUser = $this->User->find('first', ['fields' => ['pseudo'], 'conditions' => ['pseudo' => $this->request->data['username']]]);
            if (empty($searchUser))
                return $this->sendJSON(['status' => false, 'error' => $this->Lang->get('VOTE__SET_USER_ERROR_USER_NOT_FOUND')]);
            $user = ['username' => $searchUser['User']['pseudo'], 'id' => $searchUser['User']['id']];
        } else {
            $user = ['username' => $this->request->data['username']];
        }

        // Store it
        $this->Session->write('vote.user', $user);
        $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__SET_USER_SUCCESS')]);
    }

    public function setWebsite()
    {
        if (!$this->request->is('post'))
            throw new NotFoundException();
        if (empty($this->request->data) || empty($this->request->data['website_id']))
            throw new BadRequestException();
        $this->autoRender = false;
        $this->response->type('json');

        // Check if user is stored
        if (!$this->Session->check('vote.user.username'))
            throw new ForbiddenException();

        // Find website
        $this->loadModel('Vote.Website.php');
        $website = $this->Website->find('first', ['conditions' => ['id' => $this->request->data['website_id']]]);
        if (empty($website))
            throw new NotFoundException();

        // Check if has already vote and if time isn't enough
        $this->loadModel('Vote.Vote');
        if (!$this->Vote->can($this->Session->read('vote.user'), $this->Util->getIP(), $website))
            return $this->sendJSON(['status' => false, 'error' => $this->Lang->getKey('VOTE__SET_WEBSITE_ERROR_NEED_WAIT', ['{TIME}' => $this->Util->generateStringFromTime($this->Vote->getNextVoteTime($this->Session->read('vote.user'), $this->Util->getIP(), $website))])]);

        // Store it
        $this->Session->write('vote.website.id', $this->request->data['website_id']);
        $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__SET_WEBSITE_SUCCESS'), 'data' => ['website' => ['url' => $website['Website.php']['url']]]]);
    }

    public function checkVote()
    {
        if (!$this->request->is('post'))
            throw new NotFoundException();
        $this->autoRender = false;
        $this->response->type('json');

        // Check if user is stored
        if (!$this->Session->check('vote.user.username'))
            throw new ForbiddenException();
        // Check if website is stored
        if (!$this->Session->check('vote.website.id'))
            throw new ForbiddenException();

        // Check if website type need verification
        $this->loadModel('Vote.Website.php');
        $website = $this->Website->find('first', ['conditions' => ['id' => $this->Session->read('vote.website.id')]]);
        if (empty($website))
            throw new NotFoundException();
        $this->loadModel('Vote.Vote');
        if (!$this->Website->valid($this->Session->read('vote.user'), $this->Util->getIP(), $website))
            return $this->sendJSON(['status' => false]);

        // Store it
        $this->Session->write('vote.check', true);
        $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__VOTE_SUCCESS')]);
    }

    public function getReward()
    {
        if (!$this->request->is('post'))
            throw new NotFoundException();
        if (empty($this->request->data) || empty($this->request->data['reward_time']) || !in_array($this->request->data['reward_time'], ['NOW', 'LATER']))
            throw new BadRequestException();
        if ($this->request->data['reward_time'] === 'LATER' && !$this->__getConfig()->need_register)
            throw new BadRequestException();
        $this->autoRender = false;
        $this->response->type('json');

        // Check if user is stored
        if (!$this->Session->check('vote.user.username'))
            throw new ForbiddenException();
        // Check if website is stored
        if (!$this->Session->check('vote.website.id'))
            throw new ForbiddenException();
        // Check if vote is stored
        if (!$this->Session->check('vote.check'))
            throw new ForbiddenException();

        // Get website
        $this->loadModel('Vote.Website.php');
        $website = $this->Website->find('first', ['conditions' => ['id' => $this->Session->read('vote.website.id')]]);
        if (empty($website))
            throw new NotFoundException();

        // Check if user can vote
        $this->loadModel('Vote.Vote');
        if (!$this->Vote->can($this->Session->read('vote.user'), $this->Util->getIP(), $website))
            return $this->sendJSON(['status' => false, 'error' => $this->Lang->getKey('VOTE__SET_WEBSITE_ERROR_NEED_WAIT', ['{TIME}' => $this->Util->generateStringFromTime($this->Vote->getNextVoteTime($this->Session->read('vote.user'), $this->Util->getIP(), $website))])]);

        // Generate random reward
        $this->loadModel('Vote.Reward');
        $reward = $this->Reward->getFromWebsite($website);

        // Store it
        $user = $this->Session->read('vote.user.username');
        $this->Vote->create();
        $this->Vote->set([
            'username' => $user['username'],
            'user_id' => ($this->__getConfig()->need_register && isset($user['id'])) ? $user['id'] : null,
            'reward_id' => $reward['id'],
            'collected' => 0,
            'website_id' => $website['id'],
            'ip' => $this->Util->getIP()
        ]);
        $this->Vote->save();

        // Destroy session
        $this->Session->delete('user');

        // If he want reward now, try to give it
        if ($this->request->data['reward_time'] === 'LATER')
            return $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__GET_REWARDS_LATER_SUCCESS')]);
        if (!$this->Reward->collect($reward, $user['username'], $this->Server, [$this->__getConfig()->global_command]))
            return $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__GET_REWARDS_NOW_ERROR')]);
        if ($reward['amount'] > 0 && isset($user['id']))
            $this->User->setToUser('money', (floatval($this->User->getFromUser('money', $user['id'])) + floatval($reward['amount'])), $user['id']);

        // Set as collected
        $this->Vote->read(null, $this->Vote->getLastInsertId());
        $this->Vote->set(['collected' => 1]);
        $this->Vote->save();

        // Success message
        $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__GET_REWARDS_NOW_SUCCESS')]);
    }

    public function getNotCollectedReward()
    {
        if (!$this->Permissions->can('VOTE__COLLECT_REWARD'))
            throw new ForbiddenException();
        $this->loadModel('Vote.Vote');
        $findVote = $this->Vote->find('first', ['conditions' => ['user_id' => $this->User->getKey('id'), 'collected' => 0], 'recursive' => 1]);
        if (empty($findVote))
            throw new NotFoundException();
        // Give it
        $this->loadModel('Vote.Reward');
        $reward = $findVote['Reward'];
        if (!$this->Reward->collect($reward, $this->User->getKey('User'), $this->Server, [$this->__getConfig()->global_command])) {
            $this->Session->setFlash($this->Lang->getKey('VOTE__COLLECT_REWARD_ERROR'), 'default.error');
            $this->redirect($this->referer());
            return;
        }
        // Add money
        if ($reward['amount'] > 0)
            $this->User->setKey('money', (floatval($this->User->getKey('money')) + floatval($reward['amount'])));
        // Set as collected
        $this->Vote->read(null, $findVote['Vote']['id']);
        $this->Vote->set(['collected' => 1]);
        $this->Vote->save();

        // Redirect
        $this->Session->setFlash($this->Lang->getKey('VOTE__COLLECT_REWARD_SUCCESS'), 'default.error');
        $this->redirect($this->referer());
    }

    private function __getConfig()
    {
        $this->loadModel('Vote.VoteConfiguration');
        return (object)$this->VoteConfiguration->getConfig();
    }

    public function admin_configuration()
    {
        if (!$this->Permissions->can('VOTE__ADMIN_MANAGE'))
            throw new ForbiddenException();
        $this->set('title_for_layout', $this->Lang->get('VOTE__ADMIN_VOTE_CONFIGURATION_TITLE'));
        $this->loadModel('Vote.VoteConfiguration');
        $this->set('configuration', $this->VoteConfiguration->getConfig());
        $this->layout = 'admin';

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->type('json');
            if (!isset($this->request->data['need_register']) || !isset($this->request->data['global_command']))
                return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('ERROR__FILL_ALL_FIELDS')]);
            $this->VoteConfiguration->read(null, 1);
            $this->VoteConfiguration->set([
                'need_register' => $this->request->data['need_register'],
                'global_command' => $this->request->data['global_command']
            ]);
            $this->VoteConfiguration->save();

            $this->History->set('EDIT_VOTE_CONFIGURATION', 'vote');
            return $this->sendJSON(['statut' => true, 'msg' => $this->Lang->get('VOTE__ADMIN_EDIT_CONFIG_SUCCESS')]);
        }
    }
}