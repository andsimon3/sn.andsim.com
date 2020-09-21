<?php
include "../scripts/settings.php";
function MainBlockCreator($result){
	$block = '<div class="MainBlock'.$result[0].'">
			<div>
				Название 
				<input type="text" name="tittle" value="'.$result[1].'"/><br />
				Логотип disabled
				<input disabled type="file" name="logo" /><br />
			</div>
			Разрешения?
			Описание
			<input type="text" name="redirect_uri" value="'.$result[6].'"/><br /> 
			Страница перенаправления
			<input type="text" name="redirect_uri" value="'.$result[6].'"/><br /> 
			ID
			<input disabled type="text" name="code" value="'.$result[0].'"/>
			Secret
			<input disabled type="text" name="secret" value="'.$result[5].'/>
		</div>';
		return $block;
}
if(include '../scripts/auth.php'){
	$stmt = $mysqli->stmt_init();
	if(($stmt->prepare("SELECT apps FROM account WHERE id = ? and user_token = ?") === FALSE)
		// привязываем переменные к плейсхолдорам
		or ($stmt->bind_param('is', $_COOKIE['user_id'], $_COOKIE['access_token']) === FALSE)
		// отрправляем даные, которые на данный момент находятся в привязанных переменных
		or ($stmt->execute() === FALSE)
		or (($result = $stmt->get_result()->fetch_row()) === FALSE)
		or ($stmt->close() === FALSE)
	){
		die('Select Error (' . $stmt->errno . ') ' . $stmt->error);
	}else{
		if(empty($result[0])){
			echo 'You did not have any apps which use our API';
		}else{
			//Разделение строки и foreach

			$stmt = $mysqli->stmt_init();
			if(($stmt->prepare("SELECT id, tittle, description, icon, scopes, secret, redirect_uri FROM apps WHERE id = ?") === FALSE)
			// привязываем переменные к плейсхолдорам
			or ($stmt->bind_param('i', $result[0]) === FALSE)
			// отрправляем даные, которые на данный момент находятся в привязанных переменных
			or ($stmt->execute() === FALSE)
			or (($result = $stmt->get_result()->fetch_row()) === FALSE)
			or ($stmt->close() === FALSE)
			){
				die('Select Error');
			}else{
				echo ($result[1]);
				}
		}
	}
}else{
	header('Location: http://api.tittle.com/src/pages/login.php');
}
?>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $lang[13]; ?></title>
	<link rel="stylesheet" href='/src/styles/main.css' type='text/css' />
	<link rel="stylesheet" href='/src/styles/dev.css' type='text/css' />
</head>
<body>
	<form action="dev.php" method="POST">
		<input hidden name="csrf" value='notyet'>

		<button name='app' value='new'>Add new app +</button>
	
		<div id='MainBlock'>
			<div>
				Название 
				<input type="text" name="tittle"/><br />
				Логотип disabled
				<input disabled type='file' name="logo" /><br />
			</div>
			Страница перенаправления
			<input type="text" name="redirect_uri"/><br /> 
			ID
			<input disabled type="text" name="code"/>
			Secret
			<input disabled type="text" name="secret"/>
		</div>
	</form>
</body>
</html>