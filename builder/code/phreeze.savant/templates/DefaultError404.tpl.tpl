<?php
	$this->assign('title','{$appname} | File Not Found');
	$this->assign('nav','home');

	$this->display('_Header.tpl.php');
?>

<div class="container">

	<h1>Oh Snap!</h1>

	<!-- this is used by app.js for scraping -->
	<!-- ERROR The page you requested was not found /ERROR -->

	<p>A página que você requisitou não foi encontrada. Verifique se você digitou o URL corretamente.</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>