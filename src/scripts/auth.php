<?php
	$stmt = $mysqli->stmt_init();
	if(
		// подготовливаем запрос, там куда будут вствлятся данные отмечаем символом ? (плейсхолдоры)
		(empty($_COOKIE['user_id']))
		or ($stmt->prepare("SELECT devices, user_token FROM account WHERE id = ?") === FALSE)
		// привязываем переменные к плейсхолдорам
		or ($stmt->bind_param('i', $_COOKIE['user_id']) === FALSE)
		// отрправляем даные, которые на данный момент находятся в привязанных переменных
		or ($stmt->execute() === FALSE)
		or (($result = $stmt->get_result()->fetch_row()) === FALSE)
		or ($stmt->close() === FALSE)
		or (empty($_COOKIE['access_token']))
		or ($_COOKIE['access_token']!=$result[1])//device check
	) {
		$is_auth = false;
	}else{
		$is_auth = true;
	}
	return $is_auth;
?>