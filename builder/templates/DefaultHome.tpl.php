<?php include_once '_header.tpl.php'; ?>

<div class="hero-unit">
	<h1><img src="images/banner.png" alt="Phreeze Builder" /></h1>
	<div class="subheader">
		<div>Digite suas informações de conexão MySQL no formulário abaixo.</div>
		<div>Phreeze irá analisar o seu esquema e gerar um aplicativo incrível.</div>
	</div>
</div>

<form action="analyze" method="post" class="form-horizontal">
	<fieldset class="well">
		<div id="hostPortContainer" class="control-group">
			<label class="control-label" for="host">MySQL endereço:Porta</label>
			<div class="controls inline-inputs">
				<input type="text" class="span2" id="host" name="host"  placeholder="example: localhost" /> :
				<input type="text" class="span1" id="port" name="port" value="3306" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="schemaContainer" class="control-group">
			<label class="control-label" for="schema">MySQL Driver</label>
			<div class="controls inline-inputs">
				<select name="type" id="type">
					<option value="MySQL">mysql_connect</option>
					<option value="MySQLi">mysqli_connect</option>
					<option value="MySQL_PDO">PDO</option>
				</select>
				<span class="help-inline"></span>
			</div>
		</div>
		
		<div id="schemaContainer" class="control-group">
			<label class="control-label" for="schema">Nome do Esquema</label>
			<div class="controls inline-inputs">
				<input type="text" class="span3" id="schema" name="schema" placeholder="example: mydatabase" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="usernameContainer" class="control-group">
			<label class="control-label" for="username">MySQL nome de usuário</label>
			<div class="controls inline-inputs">
				<input type="text" class="span3" id="username" name="username" placeholder="" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="passwordContainer" class="control-group">
			<label class="control-label" for="password">MySQL senha</label>
			<div class="controls inline-inputs">
				<input type="password" class="span3" id="password" name="password" placeholder="" />
				<span class="help-inline"></span>
			</div>
		</div>
	</fieldset>

	<p>
		<input type="submit" class="btn btn-inverse" value="Analisar banco de dados &raquo;" />
	</p>
</form>

<?php include_once '_footer.tpl.php'; ?>