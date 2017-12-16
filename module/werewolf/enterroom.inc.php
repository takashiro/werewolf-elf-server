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
	exit('Bad request. Empty input.');
}

$input = json_decode($input, true);
if (!$input || empty($input['id'])) {
	exit('id is a required parameter.');
}

$room_id = intval($input['id']);
if ($room_id <= 0) {
	exit('id is invalid.');
}

$table = $db->select_table('werewolfroom');
$room = $table->fetch_first('*', 'id='.$room_id.' AND expiry>'.TIMESTAMP);
if (!$room) {
	exit('{"id" : 0}');
}

$table = $db->select_table('werewolfrole');
$room_roles = $table->fetch_all('*', 'room_id='.$room_id);

$room['roles'] = array();
foreach($room_roles as $row) {
	$room['roles'][] = $row['role_id'];
}

echo json_encode($room);
