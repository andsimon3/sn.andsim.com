<?php
header("Cache-Control: no-store");
#TODO:CSRF Def
session_start();
include('settings.php');
#Redirect if cookies exist
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



		if($_POST['submit']=='reg'){
			#Registration
			$tokennexist = true;
			$sqlresponse = "SELECT COUNT(*) FROM auth WHERE email='".$email."'";
			$result = $mysqli->query($sqlresponse, MYSQLI_USE_RESULT);
			$result = mysqli_fetch_row($result);
			if(!$result[0]){
				while($tokennexist){
					$usertoken = bin2hex(random_bytes(32));
					$sqlresponse = "SELECT COUNT(*) FROM auth WHERE user_token='$usertoken'";
					$result = $mysqli->query($sqlresponse, MYSQLI_USE_RESULT);
					$row = mysqli_fetch_row($result);
					if($row[0]==0){$tokennexist=false;};
				}

				#$sqlresponse = "INSERT INTO auth(email, pass, user_token, regdate, device_list) 
				#VALUES('".$email."', '".$passwordhash."', '".$usertoken.", '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')";
				#change profile & profile_privacy & profile_data & DEVICE_LIST
				$result = $mysqli->query($sqlresponse);
				#$sqlresponse = "INSERT INTO auth(id, first_name, last_name, regdate) VALUES('".$mysqli->insert_id"', '".$passwordhash."', '".$usertoken.", '".date('Y-m-d H:i:s')."')";
				#$result = $mysqli->query($sqlresponse);
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



		}elseif($_POST['submit']=='login'){
			#Login
			$sqlresponse = "SELECT * FROM auth WHERE email='".$email."'";
			$result = $mysqli->query($sqlresponse, MYSQLI_USE_RESULT);#MYSQL_STORE_RESULT?
			$result = mysqli_fetch_assoc($result);
			if( password_verify($password, $result['pass'])){
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
			#TODO:Redirect to previous page 
			
		}
	}
}
#root
#MuJ3HE7uki8Exa7uPobEK7tE7A7E5E
#mysql
#x62aJ5HoWO63JICAD71Oxuk88E6aM7
$userlang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
$lang = '../../src/lang/login/'.$userlang.'.txt';
if(file_exists($lang)){
	$lang = file($lang);
}else{
	$lang = file('../../src/lang/login/en.txt');
}
#Перевод страниц(массив слов-язык и echo элементов массива )
#echo <head>
#ИКОНКИ САЙТА
?>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $lang[13]; ?></title>
	<link rel="stylesheet" href='/src/styles/login.css' type='text/css' />
</head>
<body>
	<button><?php echo $lang[2]; ?></button>
	<button><?php echo $lang[3]; ?></button>
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
		</div>
		<div id='RegDiv' class='loginBlock'>
			<?php echo $lang[1]; ?><hr>
			<form method="POST" action="login.php">

				<div  class='twoInputDiv'>
					<label><b><?php echo $lang[5]; ?>:</b></br/>
						<input class='twoInput' type="text" name="f_name" 
							placeholder="<?php echo $lang[5]; ?>" 
						/></label>
				</div>
				<div  class='twoInputDiv'>
					<label><b><?php echo $lang[6]; ?>:</b></br/>
						<input class='twoInput' type="text" name="l_name" 
							placeholder="<?php echo $lang[6]; ?>" 
						 /></label>
				</div>

				<label><b><?php echo $lang[7]; ?>:</b>
					<input type="date" name="birth_date"  
							placeholder="<?php echo $lang[7]; ?>" 
						/></label>
				<label><b><?php echo $lang[8]; ?>:</b>
					<input type="text" name="email"  
							placeholder="<?php echo $lang[8]; ?>" 
						/></label>

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
			<label><b><?php echo $lang[4]; ?></b>
			<div id='RegWith'><br />//Кнопки соцсетей</div></label>
		</div>
	</div>
</body>
</html>