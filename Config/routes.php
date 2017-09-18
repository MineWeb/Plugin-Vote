<?php
Router::connect('/vote', array('controller' => 'vote', 'action' => 'index', 'plugin' => 'vote'));
Router::connect('/admin/vote/rewards', array('controller' => 'reward', 'action' => 'index', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/rewards/add', array('controller' => 'reward', 'action' => 'add', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/rewards/edit/:id', array('controller' => 'reward', 'action' => 'edit', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/rewards/delete/:id', array('controller' => 'reward', 'action' => 'delete', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites', array('controller' => 'website', 'action' => 'index', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites/add', array('controller' => 'website', 'action' => 'add', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites/edit/:id', array('controller' => 'website', 'action' => 'edit', 'plugin' => 'vote', 'admin' => true));
Router::connect('/admin/vote/websites/delete/:id', array('controller' => 'website', 'action' => 'delete', 'plugin' => 'vote', 'admin' => true));