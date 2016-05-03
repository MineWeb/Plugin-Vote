<?php
// Ajout des tables pour la MAJ
$db = ConnectionManager::getDataSource('default');

// 0.3
$verif_03 = $db->query('SHOW COLUMNS FROM vote_configurations;');
$execute_03 = true;
foreach ($verif_03 as $k => $v) {
  	if($v['COLUMNS']['Field'] == 'servers') {
		$execute_03 = false;
		break;
	}
}
if($execute_03) {
	$db->query('ALTER TABLE `vote_configurations` ADD `servers` text DEFAULT NULL;');
}

// 0.4
$verif_04 = $db->query('SHOW COLUMNS FROM votes;');
$execute_04 = true;
foreach ($verif_04 as $k => $v) {
  	if($v['COLUMNS']['Field'] == 'website') {
		$execute_04 = false;
		break;
	}
}
if($execute_04) {
	$db->query('ALTER TABLE `votes` ADD `website` int(11) NOT NULL;');
	$db->query('ALTER TABLE `vote_configurations` ADD `websites` text NOT NULL;');
	$db->query('ALTER TABLE `vote_configurations` DROP `time_vote`, DROP `page_vote`, DROP `id_vote`;');
}

// 0.5
//rien

//0.5.1
//rien

// 0.6
$verif_06 = $db->query('SHOW COLUMNS FROM users;');
$execute_06 = true;
foreach ($verif_06 as $k => $v) {
  	if($v['COLUMNS']['Field'] == 'rewards_waited') {
		$execute_06 = false;
		break;
	}
}
if($execute_06) {
	$db->query('ALTER TABLE `users` ADD  `rewards_waited` int NULL DEFAULT NULL ;');
}

// 0.7.0
function switch_table_name($old_table, $new_table) {

  $db = ConnectionManager::getDataSource('default');

  $execute = true;

  try {
    $verif = $db->query('SHOW COLUMNS FROM '.$old_table.';');
  } catch(Exception $e) {
    $execute = false;
  }
  if(!isset($verif) || empty($verif)) {
    $execute = false;
  }

  if($execute) {

    @$db->query('RENAME TABLE `'.$old_table.'` TO `'.$new_table.'`;');

  }

}
function add_column($table, $name, $sql) {

  $db = ConnectionManager::getDataSource('default');

  $verif = $db->query('SHOW COLUMNS FROM '.$table.';');
  $execute = true;
  foreach ($verif as $k => $v) {
    if($v['COLUMNS']['Field'] == $name) {
      $execute = false;
      break;
    }
  }
  if($execute) {
    @$query = $db->query('ALTER TABLE `'.$table.'` ADD `'.$name.'` '.$sql.';');
  }
}
function remove_column($table, $name) {

  $db = ConnectionManager::getDataSource('default');

  $verif = $db->query('SHOW COLUMNS FROM '.$table.';');
  $execute = false;
  foreach ($verif as $k => $v) {
    if($v['COLUMNS']['Field'] == $name) {
      $execute = true;
      break;
    }
  }
  if($execute) {
    @$query = $db->query('ALTER TABLE `'.$table.'` DROP COLUMN `'.$name.'`;');
  }
}
$_SESSION['users'] = array();
function author_to_userid($table, $column = 'author') {

  $db = ConnectionManager::getDataSource('default');

  $verif = $db->query('SHOW COLUMNS FROM '.$table.';');
  $execute = false;
  foreach ($verif as $k => $v) {
    if($v['COLUMNS']['Field'] == $column) {
      $execute = true;
      break;
    }
  }
  if($execute) {

    $data = $db->query('SELECT * FROM '.$table);
    foreach ($data as $key => $value) {

      $table_author_id = $value[$table]['id'];
      $author_name = $value[$table][$column];

      if(isset($_SESSION['users'][$author_name])) {
        $author_id = $_SESSION['users'][$author_name];
      } else {
        // on le cherche
        $search_author = $db->query('SELECT id FROM users WHERE pseudo=\''.$author_name.'\'');
        if(!empty($search_author)) {
          $author_id = $_SESSION['users'][$author_name] = $search_author[0]['users']['id'];
        } else {
          $author_id = $_SESSION['users'][$author_name] = 0;
        }
      }

      // On leur met l'id
      $db->query('UPDATE '.$table.' SET user_id='.$author_id.' WHERE id='.$table_author_id);

      unset($table_author_id);
      unset($author_name);
      unset($author_id);
      unset($search_author);

    }
    unset($data);

    remove_column($table, $column);

  }
}

  //Votes
    switch_table_name('votes', 'vote__votes');
    add_column('vote__votes', 'user_id', 'int(20) NOT NULL');
    author_to_userid('vote__votes', 'username');
    @$db->query('ALTER TABLE `vote__votes` CHANGE `ip` `ip` varchar(16) NOT NULL');

  // Vote_configurations
    switch_table_name('vote_configurations', 'vote__configurations');
?>
