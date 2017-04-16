{literal}
{extends file="Master.tpl"}

{block name=title}Página não encontrada{/block}

{block name=banner}
	<h1>Página não encontrada</h1>
{/block}

{block name=content}

	<!-- this is used by app.js for scraping -->
	<!-- ERROR The page you requested was not found /ERROR -->

	<p>A página que você requisitou não foi encontrada. Verifique se você digitou o URL corretamente.</p>

{/block}
{/literal}