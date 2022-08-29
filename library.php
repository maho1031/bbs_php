<?php
// htmlspcialcharsを短くする
function sanitize($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}
// DBへの接続
function dbconnect() {
    $db = new mysqli('localhost', 'root', 'root', 'min_bbs');

	if (!$db) {
		die($db->error);
	}
	
    return $db;
}
?>