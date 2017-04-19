<?php
	$this->assign('title','{$appname|escape} Secure Example');
	$this->assign('nav','secureexample');

	$this->display('_Header.tpl.php');
?>

<div class="container">

	<?php if ($this->feedback) { ?>
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<?php $this->eprint($this->feedback); ?>
		</div>
	<?php } ?>
	
	<!-- #### this view/tempalate is used for multiple pages.  the controller sets the 'page' variable to display differnet content ####  -->
	
	<?php if ($this->page == 'login') { ?>
	
		<div class="hero-unit">
			<h1>Login Exemplo</h1>
			<p>Este é um exemplo de autenticação Phreeze. As credenciais padrão são <strong>demo/pass</strong> e <strong>admin/pass</strong>.</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visitar página do usuário</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visite a página de administrador</a>
				<?php if (isset($this->currentUser)) { ?>
					<a href="logout" class="btn btn-primary btn-large">Sair</a>
				<?php } ?>
			</p>
		</div>
	
		<form class="well" method="post" action="login">
			<fieldset>
			<legend>Insira suas credenciais</legend>
				<div class="control-group">
				<input id="username" name="username" type="text" placeholder="Nome do Usuário..." />
				</div>
				<div class="control-group">
				<input id="password" name="password" type="password" placeholder="Senha..." />
				</div>
				<div class="control-group">
				<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</fieldset>
		</form>
	
	<?php } else { ?>
	
		<div class="hero-unit">
			<h1>Página  <?php $this->eprint($this->page == 'userpage' ? 'User' : 'Admin'); ?> Segura</h1>
			<p>Esta página é acessível somente para <?php $this->eprint($this->page == 'userpage' ? 'usuários autenticados' : 'Administradores'); ?>.  
			Você está logado como '<strong><?php $this->eprint($this->currentUser->Username); ?></strong>'</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visitar página do usuário</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visite a página de administrador</a>
				<a href="logout" class="btn btn-primary btn-large">Sair</a>
			</p>
		</div>
	<?php } ?>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>