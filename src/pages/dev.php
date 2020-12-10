<?php
include "../scripts/settings.php";
function ChooseBlockCreator($result){
	return '<a href="dev.php?app='.$result.'">
	<div>
		<img src="'.$result[3].'"" />
		'.$result[1].'
	</div>
	</a>';
}
if(include '../scripts/auth.php' != 0){
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
			$idlist = preg_split('"/[\s,]+/"', $result[0]);
		}
		if($_GET['app']=='new'){
			//$result = array('','','','','','','');
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
	<div id='MainBlock'>
		<form action="dev.php" method="POST">
			<div id="MainDiv">
				<div id='LogoDiv'>
					<span style="margin:auto 0;">Логотип</span>
				<input type='file' name="logo" id="LogoInput" />
				</div>
				<div id="MainTextDiv">
					<label id="IDDiv">
						ID			
						<select>
							<option>
								Выберите приложение
							</option>
							<?php

			foreach($idlist as $id){
				$stmt = $mysqli->stmt_init();
				if(($stmt->prepare("SELECT id, tittle, description, icon, scopes, secret, redirect_uri FROM apps WHERE id = ?") === FALSE)
				// привязываем переменные к плейсхолдорам
				or ($stmt->bind_param('i', $id) === FALSE)
				// отрправляем даные, которые на данный момент находятся в привязанных переменных
				or ($stmt->execute() === FALSE)
				or (($result = $stmt->get_result()->fetch_row()) === FALSE)
				or ($stmt->close() === FALSE)
				){
					die('Select Error');
				}else{
					echo '<option>';
					echo ($id);
					echo '</option>';
				}
			}
							?>
							<option>
								Создать новое приложение
							</option>
						</select>
					</label>
					<label id="IDDiv">
						Название
						<input type="text" name="code"/>
					</label>
				</div>
			</div><br/>
			Режим работы	
			<select>
				<option>
					Отключено
				</option>
				<option>
					Режим разработки
				</option>
				<option>
					Открытое приложение
				</option>
			</select>
			Разрешения
			<input type="text" name="redirect_uri"/><br /> 
			Тип приложения
			<select>
				<option>
					Android
				</option>
				<option>
					IOS
				</option>
				<option>
					Web
				</option>
				<option>
					Server
				</option>
				<option>
					Встраиваемое?
				</option>
			</select><br /> 
			<input type="checkbox" name="redirect_uri"/><b>Включить</b><br /> 
			Страница перенаправления
			<input type="text" name="redirect_uri"/>
		</form>
	</div>
</body>
</html>