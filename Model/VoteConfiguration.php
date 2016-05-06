<?php
class VoteConfiguration extends VoteAppModel {
  public $useTable = 'configurations';

  private $defaultConfig = array(
    'id' => 1,
    'rewards_type' => 0,
    'websites' => array(
      array(
        'website_name' => 'Google',
        'time_vote' => '10',
        'page_vote' => 'https://google.fr',
        'website_type' => 'other',
        'rpg_id' => '0'
      )
    ),
    'rewards' => array(
      array(
        'type' => 'server',
        'name' => 'Une récompense',
        'command' => 'say {PLAYER} a voté',
        'proba' => '50',
        'need_connect_on_server' => 'false'
      )
    ),
    'servers' => array('1')
  );

  public function getConfig() {

    $config = $this->find('first');
    if(empty($config)) {
      $config = $this->defaultConfig;
    } else {
      $config = $config['VoteConfiguration'];
      $config['websites'] = unserialize($config['websites']);
      $config['rewards'] = unserialize($config['rewards']);
      $config['servers'] = unserialize($config['servers']);
    }

    return $config;

  }

}
