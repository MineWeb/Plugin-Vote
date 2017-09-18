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
        } else if ($this->__getConfig()->needRegister) { // If need register, check if username is valid
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
        $this->loadModel('Vote.Website');
        $website = $this->Website->find('first', ['conditions' => ['id' => $this->request->data['website_id']]]);
        if (empty($website))
            throw new NotFoundException();

        // Check if has already vote and if time isn't enough
        $this->loadModel('Vote.Vote');
        if (!$this->Vote->can($this->Session->read('vote.user.username'), $website))
            return $this->sendJSON(['status' => false, 'error' => $this->Lang->getKey('VOTE__SET_WEBSITE_ERROR_NEED_WAIT', ['{TIME}' => $this->Vote->getNextVoteTime($this->Session->read('vote.user.username'), $website)])]);

        // Store it
        $this->Session->write('vote.website.id', $this->request->data['website_id']);
        $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__SET_WEBSITE_SUCCESS'), 'data' => ['website' => ['url' => $website['Website']['url']]]]);
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
        $this->loadModel('Vote.Website');
        $website = $this->Website->find('first', ['conditions' => ['id' => $this->Session->read('vote.website.id']]);
        if (empty($website))
            throw new NotFoundException();
        if (!$this->Website->valid($website, $this->Session->read('vote.user.username')))
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
        if ($this->request->data['reward_time'] === 'LATER' && !$this->__getConfig()->needRegister)
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
        $this->loadModel('Vote.Website');
        $website = $this->Website->find('first', ['conditions' => ['id' => $this->Session->read('vote.website.id']]);
        if (empty($website))
            throw new NotFoundException();

        // Check if user can vote
        $this->loadModel('Vote.Vote');
        if (!$this->Vote->can($this->Session->read('vote.user.username'), $website))
            return $this->sendJSON(['status' => false, 'error' => $this->Lang->getKey('VOTE__SET_WEBSITE_ERROR_NEED_WAIT', ['{TIME}' => $this->Vote->getNextVoteTime($this->Session->read('vote.user.username'), $website)])]);

        // Generate random reward
        $this->loadModel('Vote.Reward');
        $reward = $this->Reward->getFromWebsite($website);

        // Store it
        $user = $this->Session->read('vote.user.username');
        $this->Vote->create();
        $this->Vote->set([
            'username' => $user['username'],
            'user_id' => ($this->__getConfig()->needRegister && isset($user['id'])) ? $user['id'] : null,
            'reward_id' => $reward['id'],
            'collected' => 0,
            'website_id' => $website['id']
        ]);
        $this->Vote->save();

        // Destroy session
        $this->Session->delete('user');

        // If he want reward now, try to give it
        if ($this->request->data['reward_time'] === 'LATER')
            return $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__GET_REWARDS_LATER_SUCCESS')]);
        // Check if logged
        // Replace vars
        $commands = [];
        foreach ($reward['commands'] as $command) {
            $commands[] = str_replace('{PLAYER}', $user['username'], $command);
        }
        // Add global command
        $commands[] = str_replace('{PLAYER}', $user['username'], str_replace('{REWARD_NAME}', $reward['name'], $this->__getConfig()->global_command));

        // Send commands
        $this->Server->commands($commands, $reward['server_id']);

        // Set as collected
        $this->Vote->read(null, $this->Vote->getLastInsertId());
        $this->Vote->set(['collected' => 1]);
        $this->Vote->save();

        // Success message
        $this->sendJSON(['status' => true, 'success' => $this->Lang->get('VOTE__GET_REWARDS_NOW_SUCCESS')]);
    }

    private function __getConfig()
    {
        $this->loadModel('Vote.VoteConfiguration');
        return (object)$this->VoteConfiguration->getConfig();
    }

}