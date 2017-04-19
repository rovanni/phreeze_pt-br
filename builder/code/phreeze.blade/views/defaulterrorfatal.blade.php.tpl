{literal}
@layout('Master')

@section('title')Error@endsection

@section('content')
<div class="container">

	<h1>Oh Não!</h1>

	<!-- this is used by app.js for scraping -->
	<!-- ERROR {{htmlentities($message)}} /ERROR -->

	<h2><i class="icon-cogs"></i> Oh Não!</h2>

	<h3 onclick="$('#stacktrace').show('slow');" class="well" style="cursor: pointer;">{{htmlentities($message)}}</h3>

	<p>Você pode querer tentar retornar à página anterior e verificar se todos os campos foram preenchidos corretamente.</p>

	<p>Se continuar a experimentar esse erro, entre em contato com o suporte.</p>

	<div id="stacktrace" class="well hide">
		<p style="font-weight: bold;">Rastreamento de pilha:</p>
		@if (isset($stacktrace))
			<p style="white-space: nowrap; overflow: auto; padding-bottom: 15px; font-family: courier new, courier; font-size: 8pt;">{{htmlentities($stacktrace)}}</p>
		@endif
	</div>

</div> <!-- /container -->
@endsection
{/literal}