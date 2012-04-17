<html>
<body>
<?php
if (file_exists($_FILES['upfile']['tmp_name'])) {
?>
<h1>Получилось так:</h1>
<table border=0 cellpadding="1" cellspacing="1">
<?php
@mkdir('backgroundgen/',0777);
$tempdir = 'backgroundgen/soa-'.time();
$prefix = isset($_POST["prefix"])?$_POST["prefix"]:"Background";
mkdir($tempdir,0777);
switch (mime_content_type($_FILES['upfile']['tmp_name'])) {
case "image/png":
	$ish = imagecreatefrompng($_FILES['upfile']['tmp_name']);
	break;
default: echo "<h1>поддерживается только PNG-формат</h1>";
}
if (isset($ish)) {
if ((imagesx($ish) > 800) or (imagesy($ish) > 600)) {
	echo "<h3>исходный размер картинки (".imagesx($ish)."х".imagesy($ish).") больше нужного, изменили до 800х600.</h3>";
	$ish2 = imagecreatetruecolor(800,600);
	imagecopyresampled($ish2,$ish,0,0,0,0,800,600,imagesx($ish),imagesy($ish));
	imagepng($ish2,$tempdir.'/a.png');
	imagedestroy($ish);
	$ish = $ish2;
}
$block = imagecreatetruecolor(256,256);
$outp = '';
for ($y=0;$y<3;$y++) {
	echo '<tr>';
		for ($x=0;$x<4;$x++) {
		imagefill($block,0,0,0);
		imagecopy($block,$ish,0,0,$x*256,$y*256,256,256);
		$outp = $tempdir.'/'.$prefix.'_'.$x.'_'.$y.'.png';
		imagepng($block,$outp);
		echo '<td><img src="'.$outp.'" alt="'.$outp.'"><td>';
		}
	echo '</tr>';
	}
chdir($tempdir);
exec("tar cf background.tar *");
imagedestroy($block);
imagedestroy($ish);
?>
</table>
<h2><a href="<?=$tempdir.'/background.tar'; ?>">Итог скачать можно отсюда</a></h2>
<font color="red">ссылка действительна до 07:00 по Московскому времени (UTC+03:00)</font><br>
<?php } } else { ?>
Приветствую тебя, посетитель!
<br>Данный скрипт предназначен для автоматизации разбиения на части загрузочных картинок миссий для игры "Солдаты Анархии".<br>
<br>
Дерзайте и Удачи! Всегда ваш, Мелкий.<br>
<? } ?>
<br>
Отправить картинку (PNG формат):
<form action="<?=$_SERVER[ 'PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<input type="file" name="upfile" size="60"><br>
Префикс имён: <input type="input" name="prefix" value="Background"> <small>для Background_0_0.png префиксом считаю Background</small><br>
<input type="submit" value="отправить">
</form>
</body>
</html>
