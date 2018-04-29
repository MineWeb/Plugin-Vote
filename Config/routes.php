<?php
Router::connect('/vote', array('controller' => 'vote', 'action' => 'index', 'plugin' => 'vote'));
Router::connect('/vote/step/user', array('controller' => 'vote', 'action' => 'setUser', 'plugin' => 'vote'));
Router::connect('/vote/step/website', array('controller' => 'vote', 'action' => 'setWebsite', 'plugin' => 'vote'));
Router::connect('/vote/step/check', array('controller' => 'vote', 'action' => 'checkVote', 'plugin' => 'vote'));
Router::connect('/vote/step/reward', array('controller' => 'vote', 'action' => 'getReward', 'plugin' => 'vote'));

Router::connect('/vote/collect', array('controller' => 'vote', 'action' => 'getNotCollectedReward', 'plugin' => 'vote'));

Router::connect('/admin/vote/configuration', array('controller' => 'vote', 'action' => 'configuration', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/rewards', array('controller' => 'reward', 'action' => 'index', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/rewards/add', array('controller' => 'reward', 'action' => 'edit', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/rewards/edit/:id', array('controller' => 'reward', 'action' => 'edit', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/rewards/delete/:id', array('controller' => 'reward', 'action' => 'delete', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites', array('controller' => 'website', 'action' => 'index', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites/add', array('controller' => 'website', 'action' => 'edit', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites/edit/:id', array('controller' => 'website', 'action' => 'edit', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites/delete/:id', array('controller' => 'website', 'action' => 'delete', 'plugin' => 'vote', 'admin' => true));

Router::connect('/api/vote/check/:username', array('controller' => 'vote', 'action' => 'apiCheck', 'plugin' => 'vote'));
Router::connect('/admin/vote/top', array('controller' => 'top', 'action' => 'index', 'plugin' => 'vote', 'admin' => true));
