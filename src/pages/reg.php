<?php
header("Cache-Control: no-store");
#TODO:CSRF Def
session_start();
include('../scripts/settings.php');
#Redirect if cookies exist
#Переделать под подготовленные запросы SQL!!!!!!!!!!!!!!!!
if(include '../scripts/auth.php' != 0){
header('Location: https://tittle.vercel.app');
}else{
if($_POST['email']!=null&$_POST['pass']!=null&$_POST['submit']!=null){
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

			#Registration
			$tokennexist = true;
			if(
				// подготовливаем запрос, там куда будут вствлятся данные отмечаем символом ? (плейсхолдоры)
				($stmt->prepare("SELECT COUNT(*) FROM account WHERE email=?") ===FALSE)
				// привязываем переменные к плейсхолдорам
				or ($stmt->bind_param('s', $email) === FALSE)
				// отрправляем даные, которые на данный момент находятся в привязанных переменных
				or ($stmt->execute() === FALSE)
				or (($result = $stmt->get_result()) === FALSE)
				or ($stmt->close() === FALSE)
			) {
				die('Select Error (' . $stmt->errno . ') ' . $stmt->error);
			}
			$result = $result->fetch_row();
			if($result[0] == 0){
				while($tokennexist){
					$usertoken = bin2hex(random_bytes(32));
					$stmt = $mysqli->stmt_init();
					if(
						// подготовливаем запрос, там куда будут вствлятся данные отмечаем символом ? (плейсхолдоры)
						($stmt->prepare("SELECT COUNT(*) FROM account WHERE user_token=?") ===FALSE)
						// привязываем переменные к плейсхолдорам
						or ($stmt->bind_param('s', $usertoken) === FALSE)
						// отрправляем даные, которые на данный момент находятся в привязанных переменных
						or ($stmt->execute() === FALSE)
						or (($result = $stmt->get_result()) === FALSE)
						or ($stmt->close() === FALSE)
					) {
						die('Select Error (' . $stmt->errno . ') ' . $stmt->error);
					}
					$result = $result->fetch_row();
					if($result[0]==0){$tokennexist=false;};
				}
				$device = $_SERVER['HTTP_USER_AGENT'].' '.$ip;
				$stmt = $mysqli->stmt_init();
					if(
						// подготовливаем запрос, там куда будут вствлятся данные отмечаем символом ? (плейсхолдоры)
						($stmt->prepare("INSERT INTO account(email, pass, user_token, regdate, devices) 
				VALUES(?,?,?,?,?)") ===FALSE)
						// привязываем переменные к плейсхолдорам
						or ($stmt->bind_param('sssss', $email, $passwordhash, $usertoken, date("Y-m-d H:i:s"), $device) === FALSE)
						// отрправляем даные, которые на данный момент находятся в привязанных переменных
						or ($stmt->execute() === FALSE)
						or ($stmt->close() === FALSE)
					) {
						die('Select Error (' . $stmt->errno . ') ' . $stmt->error);
					}
				#Confirm mail
					$to      = $email;
					$subject = 'the subject';
					$message = 'hello';
					$headers = 'From: qwe';
				mail($to, $subject, $message, $headers);
			}else{
				echo "EMAIL ALREADY REGISTERED";
				#TODO:Email already registered
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
#Перевод страниц(массив слов-язык и echo элементов массива )
#echo <head>
#ИКОНКИ САЙТА
?>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $lang[13]; ?></title>
	<link rel="stylesheet" href='/src/styles/reg.css' type='text/css' />
</head>
<body>
	<!-- Список официальных клиентов
	<button><?php echo $lang[2]; ?></button>
	<button><?php echo $lang[3]; ?></button>
	Вернуть поле входа?
-->
	<div id='LoginInBlock'>
		<div id='RegDiv' class='loginBlock'>
			<?php echo $lang[1]; ?><hr>
			<form method="POST" action="reg.php">
				<label><b><?php echo $lang[8]; ?>:</b>
					<input type="text" name="email"  
							placeholder="<?php echo $lang[8]; ?>" 
						/>
				</label>

				<div  class='twoInputDiv'>
					<label><b><?php echo $lang[9]; ?>:</b>
						<input class='twoInput' type="password" name="pass" 
							placeholder="<?php echo $lang[9]; ?>" 
						/></label>
				</div>
				
				<div  class='twoInputDiv'>
					<label><b><?php echo $lang[10]; ?>:</b>
						<input class='twoInput' type="password" name="pass2" 
							placeholder="<?php echo $lang[10]; ?>" 
						 /></label>
				</div>

				<button class='FormButton' name="submit" value='reg'><?php echo $lang[11]; ?></button>
			</form>
			<!-- Скорее всего перенести в login и добавить Соглашения
			<label><b><?php echo $lang[4]; ?></b>
			<div id='RegWith'><br />//Кнопки соцсетей</div></label>-->
		</div>
	</div>
</body>
</html>