<?php

class VoteConfiguration extends VoteAppModel
{
    public $useTable = 'configurations';

    private $defaultConfig = [
        'needRegister' => true,
        'global_command' => '{PLAYER} vient de voter et de recevoir {REWARD_NAME} !'
    ];

    public function getConfig()
    {
        $config = $this->find('first');
        if (empty($config))
            return $this->defaultConfig;
        return $config['VoteConfiguration'];
    }

}
