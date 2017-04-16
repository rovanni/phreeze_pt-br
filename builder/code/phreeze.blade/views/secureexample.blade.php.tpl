@layout('Master')

@section('title'){$appname} | Exemplo seguro@endsection

@section('content')
<div class="container">

	@if (isset($feedback))
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			{ldelim}{ldelim} htmlentities($feedback) {rdelim}{rdelim}
		</div>
	@endif
	
	<!-- #### this view/tempalate is used for multiple pages.  the controller sets the 'page' variable to display differnet content ####  -->
	
	@if ($page == 'login')
	
		<div class="hero-unit">
			<h1>Login Exemplo</h1>
			<p>Este é um exemplo de autenticação Phreeze. As credenciais padrão são <strong>demo/pass</strong> e <strong>admin/pass</strong>.</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visitar página do usuário</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visite a página de administrador</a>
				@if (isset($currentUser))
					<a href="logout" class="btn btn-primary btn-large">Sair</a>
				@endif
			</p>
		</div>
	
		<form class="well" method="post" action="login">
			<fieldset>
			<legend>Insira suas credenciais</legend>
				<div class="control-group">
				<input id="username" name="username" type="text" placeholder="Username..." />
				</div>
				<div class="control-group">
				<input id="password" name="password" type="password" placeholder="Password..." />
				</div>
				<div class="control-group">
				<button type="submit" class="btn btn-primary">Entrar</button>
				</div>
			</fieldset>
		</form>
	
	@else
	
		<div class="hero-unit">
			<h1>Seguro @if ($page == 'userpage') User @else Admin @endif Page</h1>
			<p>Esta página é acessível somente para @if ($page == 'userpage') usuários autenticados @else Administradores @endif.  
			Você está logado como '<strong>{ldelim}{ldelim} htmlentities($currentUser->Username) {rdelim}{rdelim}</strong>'</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visitar página do usuário</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visite a página de administrador</a>
				<a href="logout" class="btn btn-primary btn-large">Sair</a>
			</p>
		</div>
	@endif

</div> <!-- /container -->
@endsection
