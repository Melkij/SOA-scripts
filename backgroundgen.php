<html>
<body>
<?php
if (file_exists($_FILES['upfile']['tmp_name'])) {
?>
<h1>���������� ���:</h1>
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
default: echo "<h1>�������������� ������ PNG-������</h1>";
}
if (isset($ish)) {
if ((imagesx($ish) > 800) or (imagesy($ish) > 600)) {
	echo "<h3>�������� ������ �������� (".imagesx($ish)."�".imagesy($ish).") ������ �������, �������� �� 800�600.</h3>";
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
<h2><a href="<?=$tempdir.'/background.tar'; ?>">���� ������� ����� ������</a></h2>
<font color="red">������ ������������� �� 07:00 �� ����������� ������� (UTC+03:00)</font><br>
<?php } } else { ?>
����������� ����, ����������!
<br>������ ������ ������������ ��� ������������� ��������� �� ����� ����������� �������� ������ ��� ���� "������� �������".<br>
<br>
�������� � �����! ������ ���, ������.<br>
<? } ?>
<br>
��������� �������� (PNG ������):
<form action="<?=$_SERVER[ 'PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<input type="file" name="upfile" size="60"><br>
������� ���: <input type="input" name="prefix" value="Background"> <small>��� Background_0_0.png ��������� ������ Background</small><br>
<input type="submit" value="���������">
</form>
</body>
</html>
