<?php


class PageGenerate
{
	private function header($title, $style)
	{
		return '
		<head>
			<meta charset="utf-8" />
			<title>'.$title.'</title>
			<link rel="stylesheet" href="/src/styles/main.css" type="text/css" />
			<link rel="stylesheet" href="/src/styles/'.$style.'.css" type="text/css" />
		</head>
		';
	}
	private function mainpart($html)
	{
		return '
		<body>'.$html.'</body>
		';
	}
	function __construct($title, $style, $html)
	{
		$result = '<html>'.$this->header($title, $style).$this->mainpart($html).'</html>';
		echo $result;
	}
}
?>