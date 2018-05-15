<?php
header('Content-Type:text/xml');
if(isset($_POST['pageUrl'])) {$pageUrl = $_POST['pageUrl']; }

// отделяем префикс от введенного пользователем url
if(preg_match("/^(http:\/\/|https:\/\/|ftp:\/\/)?([^*]+)/i", $pageUrl, $matches)) {
		$pageUrlPrefix = $matches[1];
		$pageUrl = $matches[2];
}

// функция генерирует последовательность псевдослучайных символов
function randStr () {
    $s = "QqWwEeRrTtYyUuIiOoPpAaSsDdFfGgHhJjKkLlZzXxCcVvBbNnMm1234567890";
     return substr(str_shuffle($s), 0, 5);
}

// результирующая строка, которую будем возвращать
$myNewUrl = '';
// переключатель $urlInList - есть ли url в нашем списке 
$urlInList = false;
// если уже формировалась ссылка на такой URL - ищем в нашем списке
$text = explode("\n", file_get_contents('urls.txt')); // разбиваем содержимое urls.txt на массив строк
foreach ($text as $str) { // для каждой строки
	$temp = explode(";", $str); // разбиваем строку на массив
	$strArr[] = $temp[2]; // записываем в массив $strArr ранее сгенерированные URL для сравнения
	if(strcmp($pageUrl, $temp[1]) == 0 ) { // если совпало
		$myNewUrl = $temp[2]; // присваиваем ранее сгенерированную ссылку
		$urlInList = true; // переключатель в положение true
		break;
	}
}

if($urlInList == false) { // если на такой URL не формировалась ссылка
	$myNewUrl = randStr(); // сгенерировали последовательность символов
	while(in_array($myNewUrl, $strArr)) {// во избежание совпадений с имеющимися
		$myNewUrl = randStr();
	}
	if($pageUrlPrefix == ""){// если url был без префикса - присвоим ему по умолчанию http://
		$pageUrlPrefix = "http://"; 
	}
	$fp = fopen("urls.txt", "a" ); // открыли файл для записи
	fwrite ($fp, $pageUrlPrefix.";".$pageUrl.";".$myNewUrl."\n"); // записали новую строку
	fclose($fp);
}

$dom = new DOMDocument; // создание XML ответа
$response = $dom -> createElement("response");
$dom -> appendChild($response);

$newUrl = $dom -> createTextNode($myNewUrl);
$response->appendChild($newUrl);

$xmlString = $dom->saveXML();
echo $xmlString;
?>
