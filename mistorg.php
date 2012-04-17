<?php
/*
 * находим 14 нулей подряд с начала файла
 * перед нулями - нарасчиваем счётчик, за ними - вставляем торговлю
 * собираем обратно
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

if (isset($_POST['button']) && isset($_POST['add'])) {
	$count = count($_POST['add'])-1;
	$ntorg = '';
	for ($i=$count;$i>=0;$i--) {
		list($id,$min,$max) = explode(":",$_POST['add'][$i]);
		$min = dechex($min);
			while (strlen($min)!=8) $min = "0".$min;
		$max = dechex($max);
			while (strlen($max)!=8) $max = "0".$max;
		$ntorg .= $id."000000".$id.$min.$max."00000001";
		}
	$file = $_FILES['upfile']['tmp_name'];
	//var_dump($_FILES);
	if (file_exists($file)) {
		$fp = fopen($file, "rb");
		$mission = fread($fp, filesize($file));
		fclose($fp);
		$mission = bin2hex($mission);
		$start = stripos($mission,"00000000000000")-2;
		$count = dechex(hexdec(substr($mission,$start,2))+$count);
		$count = strlen($count)==2 ? $count : "0".$count;
		$ntorg = substr($mission,0,$start).$count."00000000000000".$ntorg;
		//echo strlen($mission),"<br>";
		$mission = $ntorg.substr($mission,$start+16);
		//echo strlen($mission),"<br>";
		$mission = hexbin($mission);
		//file_put_contents("unlockujp.mis",$mission);
		//echo md5_file("ujp.mis"),'<br>',md5_file("unlockujp.mis"); //проверка md5
		header("Content-Type: application/download; charset=windows-1251");
		header("Content-Disposition: attachment; filename=\"".$_FILES['upfile']['name']."\"");
		print $mission;
		exit;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title>Добавление техники торговцу в миссии</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<script type="text/javascript">
	function torgadd() {
		document.getElementById("addtotorg").innerHTML += "<input type='hidden' name='add[]' value=\"" +\
			document.formb.list.value + ":" +\
			document.formb.min.value + ":" +\
			document.formb.max.value + "\">"+\
			document.formb.list.options[document.formb.list.selectedIndex].text + " в количестве от "+
			document.formb.min.value + " до " +\
			document.formb.max.value + "<br>";
		}
	</script>
</head>
<body>
<h3>Приветствую, посетитель!</h3>
<p>Данный скрипт предназначет для добавления техники в меню торговли между миссиями в игре SOA.</p>
<h4 style="color: darkred">Экспериментальная возможность!</h4>
<h5 style="color: darkred">В случае обнаружения ошибок, <a href="http://soa.4bb.ru/viewtopic.php?id=53&amp;p=5">пишите на форум</a></h5>
<form name="formb" action="<?=$_SERVER[ 'PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<p>Укажите файл миссии (с расширением .mis): <input type="file" name="upfile"><br><br></p>
<table border="0"><tr>
<td align="right">
	<select size="15" name="list">
		<?
		$fp = fopen("tech.lst","r") or die("файлы с техникой не найден, сообщите автору");
		while (!feof($fp)) {
			$list = explode(":", trim(fgets($fp)));
			if (!empty($list[0])) echo "<option value=\"".$list[1]."\">".$list[0]."</option>\n";
			}
		fclose($fp);
		?>
	</select>
	<div>
		Мин. количество: <input type="text" name="min" size="2" value="1"><br>
		Макс. количество: <input type="text" name="max" size="2" value="1"><br>
		<input type="button" value="добавить" onclick="torgadd()">
	</div>
</td>
<td valign="top" align="right">К добавлению:<br>
<div id="addtotorg"></div>

</td>
</tr>
</table>
<hr>
<p><input type="submit" name="button" value="сохранить изменения"></p>
</form>
</body>
</html>
