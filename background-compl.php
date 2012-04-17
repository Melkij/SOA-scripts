<?php
if (file_exists($_FILES['upfile']['tmp_name'])) {
	chdir("background-compl");
	exec("rm -rf *");
	copy($_FILES['upfile']['tmp_name'],"bg.zip");
	exec("unzip -qo bg.zip");
#var_dump(glob('*'));
$img = imagecreatetruecolor(800,600);
for ($y=0;$y<3;$y++) {
	for ($x=0;$x<4;$x++) {
		$filename = glob("*".$x."_".$y.".png");
		if ($filename[0]) {
			$block = imagecreatefrompng($filename[0]);
			imagecopy($img,$block,$x*256,$y*256,0,0,256,256);
			imagedestroy($block);
		}
	}
}
header("Content-type: image/png");
imagepng($img);
} else { ?>
<html>
<head>
<title>Скрипт собирания картинок воедино</title>
</head>
<body>
<p>Приветствую тебя, посетитель!
<br>Данный скрипт предназначен для автоматизации собирания воедино загрузочных картинок миссий для игры "Солдаты Анархии".<br>
<br>
Дерзайте и Удачи! Всегда ваш, Мелкий.</p>
<br>
<p>Отправить части картинки одним файлом в архиве zip:<br>
<small>части должны быть вложены в корень архива zip, иметь тип png, оканчиваться их стандартным порядковым номером ("*_0_0.png" для верхней левой)</small></p>
<form action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<input type="file" name="upfile" size="60"><br>
<input type="submit" value="отправить">
</form>
</body>
</html>
<? } ?>
