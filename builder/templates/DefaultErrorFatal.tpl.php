<?php include_once '_header.tpl.php'; ?>

<h1><i class="icon-cogs"></i> Oh não!</h1>

<h2 onclick="$('#stacktrace').show('slow');" class="well" style="cursor: pointer;"><?php echo $this->eprint($this->message); ?></h2>

<p>Você pode querer tentar retornar à página anterior e verificar se todos os campos foram preenchidos corretamente.</p>

<p>Se continuar a experimentar esse erro, entre em contato com o suporte.</p>

<div id="stacktrace" class="well hide">
	<h5>Rastreamento de pilha:</h5>
	<?php if ($this->stacktrace) { ?>
		<p style="white-space: nowrap; overflow: auto; padding-bottom: 15px;">
			<pre><?php echo $this->eprint($this->stacktrace); ?></pre>
		</p>
	<?php } ?>
</div>

<?php include_once '_footer.tpl.php'; ?>