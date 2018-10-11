<!DOCTYPE html>
<html lang="ja">

<head>
<?=$this->Html->charset(); ?>
<title>
	<?=$this->fetch('title') ?>
</title>

<?php
echo $this->Html->css('costs');
echo $this->fetch('meta');
echo $this->fetch('css');
?>

</head>

<body>

	<?=$this->fetch('content') ?>


</body>

</html>
