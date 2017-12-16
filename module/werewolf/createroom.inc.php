<?php

/***********************************************************************
Werewolf PHP Server
Copyright (C) 2017  Kazuichi Takashiro

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

takashiro@qq.com
************************************************************************/

if (!defined('S_ROOT')) exit('access denied');

$input = file_get_contents('php://input');
if (empty($input)) {
	exit('Bad request. Invalid format.');
}

$input = json_decode($input, true);
if (!$input || !$input['roles']) {
	exit('Bad request. roles is a required parameter.');
}

$roles = array();
foreach ($input['roles'] as $role) {
	$role = intval($role);
	if ($role > 0) {
		$roles[] = $role;
	}
}
if (!$roles) {
	exit('bad request');
}

shuffle($roles);

$salt = rand(0, 0xFFFFFFFF);

$table = $db->select_table('werewolfroom');
$table->insert(array(
	'salt' => $salt,
	'expiry' => TIMESTAMP + 3600,
));

$room = array(
	'id' => $table->insert_id(),
	'salt' => $salt,
	'roles' => array(),
);

$table = $db->select_table('werewolfrole');
foreach($roles as $role) {
	$table->insert(array(
		'room_id' => $room['id'],
		'role_id' => $role,
	));

	if ($db->affected_rows > 0) {
		$room['roles'][] = $role;
	}
}

echo json_encode($room);
