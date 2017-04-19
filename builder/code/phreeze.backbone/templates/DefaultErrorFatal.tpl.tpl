{literal}
{extends file="Master.tpl"}

{block name=title}Erro{/block}

{block name=banner}
	<h1>Oh Não!</h1>
{/block}

{block name=content}

	<!-- this is used by app.js for scraping -->
	<!-- ERROR {$message|escape} /ERROR -->

	<h2><i class="icon-cogs"></i> Oh Não!</h2>

	<h3 onclick="$('#stacktrace').show('slow');" class="well" style="cursor: pointer;">{$message|escape}</h3>

	<p>Você pode querer tentar retornar à página anterior e verificar se todos os campos foram preenchidos corretamente.</p>

	<p>Se continuar a experimentar esse erro, entre em contato com o suporte.</p>

	<div id="stacktrace" class="well hide">
		<p style="font-weight: bold;">Rastreamento de pilha:</p>
		{if $stacktrace}
			<p style="white-space: nowrap; overflow: auto; padding-bottom: 15px; font-family: courier new, courier; font-size: 8pt;">{$stacktrace|escape|nl2br}</p>
		{/if}
	</div>

{/block}

{block name=customFooterScripts}
{/block}
{/literal}