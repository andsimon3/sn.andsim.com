<?php
header("Cache-Control: no-store");
#TODO:CSRF Def!!!!!
session_start();
include('../../src/pages/settings.php');
#Redirect if cookies exist
#Переделать под подготовленные запросы SQL!!!!!!!!!!!!!!!!
if($_POST['email']!=null&$_POST['pass']!=null){
	$email = $_POST['email'];
	$password = $_POST['pass'];
	$passwordhash = password_hash($password, PASSWORD_ARGON2ID);

	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = @$_SERVER['REMOTE_ADDR'];

	if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
	elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
	else $ip = $remote;	

	if ($mysqli->connect_error) {
    	die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}else{
		#Login
		$stmt = $mysqli->stmt_init();
		if(
			// подготовливаем запрос, там куда будут вствлятся данные отмечаем символом ? (плейсхолдоры)
			($stmt->prepare("SELECT confirm, devices FROM auth WHERE email = ? and pass = ?") ===FALSE)
			// привязываем переменные к плейсхолдорам
			or ($stmt->bind_param('ss', $email, $passwordhash) === FALSE)
			// отрправляем даные, которые на данный момент находятся в привязанных переменных
			or ($stmt->execute() === FALSE)
			or (($result = $stmt->get_result()) === FALSE)
			or ($stmt->close() === FALSE)
		) {
			die('Select Error (' . $stmt->errno . ') ' . $stmt->error);
		}
		$row = $result->fetch_row();
		echo $row[0].' '.$row[1];
		//$sqlresponse = "SELECT * FROM auth WHERE email='".$email."'";
		//$result = $mysqli->query($sqlresponse, MYSQLI_USE_RESULT);#MYSQL_STORE_RESULT?
		//$result = mysqli_fetch_assoc($result);
		/*if( password_verify($password, $result['pass'])){
			if($result['confirm']){
				#TODO:2factor auth 
				setcookie('access_token', $result['user_token'], time()+60*60*24*365*10);
				setcookie('user_id', $result['id'], time()+60*60*24*365*10);
				header('Location: /src/pages/profile.php');

			}else{
				echo 'E-mail not confirmed';
			}
		}else{
			echo 'Email or password is wrong';
		}
		#TODO:Redirect to previous page */
			
		
	}
}
$userlang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
$lang = '../../src/lang/login/'.$userlang.'.txt';
if(file_exists($lang)){
	$lang = file($lang);
}else{
	$lang = file('../../src/lang/login/en.txt');
}
#Перевод страниц(массив слов-язык и echo элементов массива ) UPD App
#echo <head> UPD App
#ИКОНКИ САЙТА
?>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $lang[13]; ?></title>
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