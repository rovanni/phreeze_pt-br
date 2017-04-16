{literal}
{extends file="Master.tpl"}

{block name=title}{/literal}{$appname|escape}{literal} Exemplo seguro{/block}

{block name=banner}
{/block}

{block name=content}


	{if ($feedback)}
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			{$feedback|escape}
		</div>
	{/if}
	
	<!-- #### this view/tempalate is used for multiple pages.  the controller sets the 'page' variable to display differnet content ####  -->
	
	{if ($page == 'login')}
	
		<div class="hero-unit">
			<h1>Login Exemplo</h1>
			<p>Este é um exemplo de autenticação Phreeze. As credenciais padrão são <strong>demo/pass</strong> e <strong>admin/pass</strong>.</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visitar página do usuário</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visite a página de administrador</a>
				{if (isset($currentUser))}
					<a href="logout" class="btn btn-primary btn-large">Sair</a>
				{/if}
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
	
	{else}
	
		<div class="hero-unit">
			<h1>Seguro {if ($page == 'userpage')}User{else}Admin{/if} Page</h1>
			<p>Esta página é acessível somente para {if ($page == 'userpage')}usuários autenticados{else}Administradores{/if}.  
			Você está logado como '<strong>{$currentUser->Username|escape}</strong>'</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visitar página do usuário</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visite a página de administrador</a>
				<a href="logout" class="btn btn-primary btn-large">Sair</a>
			</p>
		</div>
	{/if}

{/block}
{/literal}