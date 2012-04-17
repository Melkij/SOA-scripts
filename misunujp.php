<?php
/*
 * 1. с конца файла ищем unknow name
 * 2. с того места где нашли это. ищем и заменяем ,вниз "0000FFFFFFFF00000000" на "00000000000000000000"
 * также с ТОГОЖЕ МЕСТА ищем и заменяем "0000FFFFFFFF01000000" на "00000000000001000000"
 * (потому-что и та и та запись может являтся блокиратором скрипта)
*/
/* строку в HEX
function strToHex($string) { 
    $hex=''; 
    for ($i=0; $i < strlen($string); $i++) 
    { 
        $hex .= dechex(ord($string[$i])); 
    } 
    return $hex; 
} */

//функция, обратная bin2hex
function hexbin($str) {
	$it = strlen($str);
	$ret = '';
	for ($i=0;$i<$it;$i+=2) $ret .= pack("H",$str[$i]) | pack("h", $str[$i+1]);
	return $ret;
	}
if (isset($_POST['button'])) {
	$file = $_FILES['upfile']['tmp_name'];
	if (file_exists($file)) {
		set_time_limit(120);
		$handle = fopen($file, "rb");
		$mission = fread($handle, filesize($file));
		fclose($handle);
		$mission = bin2hex($mission);
			$start = strripos($mission,"756e6b6e6f776e206e616d65"); //последнее unknown name, см закомментированную функцию strToHex
			$scripts = substr($mission,$start);
			$scripts = str_ireplace("0000FFFFFFFF00000000","00000000000000000000",$scripts);
			$scripts = str_ireplace("0000FFFFFFFF01000000","00000000000001000000",$scripts);
		$mission = substr($mission,0,$start).$scripts;
		$mission = hexbin($mission);
		//file_put_contents("unlockujp.mis",$mission);
		//echo md5_file("ujp.mis"),'<br>',md5_file("unlockujp.mis"); //проверка md5
		header("Content-Type: application/download; charset=windows-1251");
		header("Content-Disposition: attachment; filename=\"".$_FILES['upfile']['name']."\"");
		header("Accept-Ranges: bytes");
		header("Content-Length: ".strlen($mission));
		print $mission;
		exit;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title>Разблокировка скриптов в миссии</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
</head>
<body>
<h3>Приветствую, посетитель!</h3>
Данный скрипт предназначет для разблокирования событий Unit Join Party (смена команды человеком/юнитом) в скриптах самодельных миссий к игре SOA.<br>
<h4 style="color: darkred">Экспериментальная возможность!</h4>
<h5 style="color: darkred">В случае обнаружения ошибок, <a href="http://soa.4bb.ru/viewtopic.php?id=53&p=5">пишите на форум</a></h5>
<p><h4>Особенности, костыли и подпорки:</h4>
<ul><li>файл миссии уже должен содержать события unit join party</li>
<li>события unit join party должны быть ненастроенными (т.е. без выбранных людей)</li>
</ul></p>
<form action="<?=$_SERVER[ 'PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<input type="file" name="upfile"><br>
<input type="submit" name="button" value="отправить">
</form>
</body>
</html>
