<?php
header("Cache-Control: no-store");
#TODO:CSRF Def!!!!!
session_start();
include('../scripts/settings.php');
if(include '../scripts/auth.php'){
header('Location: https://tittle.vercel.app');
}else{
#Redirect if cookies exist
if($_POST['email']!=null&$_POST['pass']!=null){
	$email = $_POST['email'];
	$password = $_POST['pass'];

	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = @$_SERVER['REMOTE_ADDR'];

	if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
	elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
	else $ip = $remote;	

	$device = $_SERVER['HTTP_USER_AGENT'].' '.$ip;

	if ($mysqli->connect_error) {
    	echo('Connect Error');
	}else{
		#Login
		$stmt = $mysqli->stmt_init();
		if(
			// подготовливаем запрос, там куда будут вствлятся данные отмечаем символом ? (плейсхолдоры)
			($stmt->prepare("SELECT confirm, devices, pass, user_token, id FROM account WHERE email = ?") === FALSE)
			// привязываем переменные к плейсхолдорам
			or ($stmt->bind_param('s', $email) === FALSE)
			// отрправляем даные, которые на данный момент находятся в привязанных переменных
			or ($stmt->execute() === FALSE)
			or (($result = $stmt->get_result()) === FALSE)
			or ($stmt->close() === FALSE)
		) {
			echo('Connect Error');
		}else{
		$result = $result->fetch_row();
		//$device_exist = 0;
		//DEVICES CHECK Разобрать на части, чтобы уменьшить размер строки  2Factor
		/*
				setcookie('access_token', $result['user_token'], time()+60*60*24*365*10);
				setcookie('user_id', $result['id'], time()+60*60*24*365*10);
				header('Location: /src/pages/profile.php');
		*/
		if(password_verify($password, $result[2])){
			if($result[0]){
				setcookie('access_token', $result[3], time()+60*60*24*365*10);
				setcookie('user_id', $result[4], time()+60*60*24*365*10);
				header('Location: https://tittle.vercel.app');
			}else{echo 'Email not confirmed';}
		}else{echo 'Wrong password or e-mail';}
	}
		
	}
}
$userlang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
$lang = '../../src/lang/login/'.$userlang.'.txt';
if(file_exists($lang)){
	$lang = file($lang);
}else{
	$lang = file('../../src/lang/login/en.txt');
}
}
#Перевод страниц(массив слов-язык и echo элементов массива ) UPD App
#echo <head> UPD App
#ИКОНКИ САЙТА
?>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $lang[13]; ?></title>
	<link rel="stylesheet" href='/src/styles/main.css' type='text/css' />
	<link rel="stylesheet" href='/src/styles/login.css' type='text/css' />
</head>
<body>
	<div id='LoginInBlock'>
		<div id='LoginDiv' class='loginBlock'>
			<?php echo $lang[0]; ?><hr>

			<form method="POST" action="login.php">
				<label><b><?php echo $lang[8]; ?></b>
				<input type="text" name="email" 
							placeholder="<?php echo $lang[8]; ?>" 
						 /></label>

				<label><b><?php echo $lang[9]; ?></b>
				<input type="password" name="pass"
							placeholder="<?php echo $lang[9]; ?>" 
						/></label>

				<button class='FormButton' name="submit" value='login' ><?php echo $lang[11]; ?></button>
			</form>
			Нет аккаунта?<a href='/src/pages/reg.php'>Регистрация</a><!-- Мультиязык Цвет ссылок и как-то что-то не то...-->
		</div>
	</div>
</body>
</html>