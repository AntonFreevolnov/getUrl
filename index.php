<?php

function getUrl() { // перенаправление на ресурс
    $a = explode("\n", file_get_contents('urls.txt')); // разбиение на массив по строкам
    foreach ($a as $str) {
        $temp = explode(";", $str); // разбиение строки на массив
		if(strcmp($_GET[route], $temp[2]) == 0 ) { // если параметпсовпадение с сгенерированной строкой в списке
			header('Location:'.$temp[0].$temp[1]); // перенаправление на нужный ресурс
		}
	}
}
if(!empty($_GET[route])){ // если есть GET-параметр
	getUrl();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Генератор коротких ссылок</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="ajax.js"></script>
</head>
<body>
<fieldset id="fil">
<form id="myform">
<input id="page_url" type="text" value =""></input>
<input id="button" type="submit" value ="submit"></input>
</form>
</fieldset>


</body>
</html>