<?php
//CSRF!!!!!!!!
include "../scripts/settings.php";
if(include '../scripts/auth.php' == 0){
	header('Location: http://api.tittle.com/src/pages/login.php');
}else{

}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<form>
	<div>
		<img src=""/>
		<span><?php echo 'Tittle' ;?></span>
	</div>
	<ul>
		<?php //foreach(){echo '<li>'.smt.'</li>'} ;?>
	</ul>
	<button>
		Разрешить
	</button>
</form>
</body>
</html>