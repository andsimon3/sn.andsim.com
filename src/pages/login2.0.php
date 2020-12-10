<?php
include('../scripts/settings.php');
include('../scripts/class.php');


class LoginIn
{
	private function checkPassword($pass, $dbpass)
	{
		return password_verify($pass, $dbpass);
	} 

	private function DataAccess($email)
	{
		include('../scripts/settings.php');
		$stmt = $mysqli->stmt_init();
		if(
			// подготовливаем запрос, там куда будут вствлятся данные отмечаем символом ? (плейсхолдоры)
			($stmt->prepare("SELECT confirm, devices, pass, user_token, id FROM account WHERE email = ?") === FALSE)
			// привязываем переменные к плейсхолдорам
			or ($stmt->bind_param('s', $email) === FALSE)
			// отрправляем даные, которые на данный момент находятся в привязанных переменных
			or ($stmt->execute() === FALSE)
			or (($result = $stmt->get_result()->fetch_row()) === FALSE)
			or ($stmt->close() === FALSE)
		) {
			echo('Connect Error');
		}
		return $result[2];
	} 

	function __construct($email, $pass)
	{
		$result = $this->DataAccess($email);
		if($this->checkPassword($pass, $result[2])){
			echo 123;
		}
	}
}

class CheckAim
{
	
}
$html = "
<div>
Q
</div>
";
if((include('../scripts/auth.php')) != 0){
	header('Location: https://tittle.vercel.app');
}else{
	//if()
	$html = new PageGenerate('Login 2.0', 'login2', $html);
	$login = new LoginIn($_POST['email'], $_POST['pass']);
}
?>