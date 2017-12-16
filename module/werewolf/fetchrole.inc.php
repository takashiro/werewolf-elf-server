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
if (!$input) {
	exit('Bad request. Input is empty.');
}

$input = json_decode($input, true);
if (!$input) {
	exit('Bad request. Input format must be JSON.');
}

if (empty($input['id'])) {
	exit('Bad request. id is required.');
}

$room_id = intval($input['id']);
if ($room_id <= 0) {
	exit('Bad request. id must be positive.');
}

$table = $db->select_table('werewolfroom');
$room = $table->fetch_first('*', 'id='.$room_id.' AND expiry>'.TIMESTAMP);
if (!$room) {
	exit('{}');
}

$table = $db->select_table('werewolfrole');
$room_roles = $table->fetch_all('*', array('room_id' => $room_id, 'used' => 0));

for (;;) {
	$chosen = $room_roles[array_rand($room_roles)];
	$db->query("UPDATE {$tpre}werewolfrole SET used=1 WHERE id={$chosen['id']}");
	if ($db->affected_rows > 0) {
		break;
	}

	unset($room_roles[$chosen]);
	if (!$room_roles) {
		$chosen = null;
		break;
	}
}

if (!$chosen) {
	exit('{"role": 0}');
}

$result = array(
	'role' => intval($chosen['role_id']),
);
echo json_encode($result);
