<?php

include('settings.php');

#get status
#if creator
	#get Name Photo Status Content Profiles Friends Groups
	#creator menu
#else
	#get OpenInfo


$userlang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
$lang = '../../src/lang/profile/'.$userlang.'.txt';
if(file_exists($lang)){
	$lang = file($lang);
}else{
	$lang = file('../../src/lang/profile/en.txt');
}

?>
<html>
<head>
	<meta charset="utf-8" />
	<title>Профиль</title>
	<link rel="stylesheet" href='/src/styles/profile.css' type='text/css' />
</head>
<body>

	<div id='MenuUp'>

		<div id='MenuUpProf'>
			<img id='ImgMenuUpProf' src='/src/img/guest.svg' align="left" />
			<div id='NickMenuUp'>
				Имя Фамилия/Ник<br/>
				Статус
			</div>
			<img src='/src/img/arrowdown.svg' id='ChooseMenuUp' />

		</div>

		<div class='menuUpButton'>
			<label style='margin: 0 auto;'><img src='/src/img/news.svg'  id='ImgMenuUp'/>
			<a id='LabelMenuUp'>Новости</a></label>
		</div>

		<div class='menuUpButton'>
			<label style='margin: 0 auto;'><img src='/src/img/massages.svg'  id='ImgMenuUp'/>
			<a id='LabelMenuUp'>Сообщения</a></label>
		</div>

		<div class='menuUpButton'>
			<label style='margin: 0 auto;'><img src='/src/img/settings.svg'  id='ImgMenuUp'/>
			<a id='LabelMenuUp'>Настройки</a></label>
		</div>
	</div>


	<div id='ProfilePage'>
		<img id='ProfileAvatar' src='photo' align='left'/>
		<div id='Infoblock'>
			<b id='NameInInfo'>Имя Фамилия/Ник</b>
		</div>
	</div>

	
</body>
</html>