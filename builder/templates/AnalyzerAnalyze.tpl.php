<?php include_once '_header.tpl.php'; ?>

<h2><i class="icon-check"></i> Selecionar tabelas</h2>

<p>Selecione as tabelas e visualizações a serem incluídas neste aplicativo. Os nomes Singular e Plural são automaticamente detectados e serão usados nos nomes das classes geradas. Você pode ajustá-los aqui. Se você prefixar todas as colunas de uma tabela consistentemente (ex a_id, a_name), o Prefixo de Coluna será removido para propriedades de classe.</p>

<p>Observe que as tabelas sem chave primária ou uma chave primária composta não são suportadas. As exibições são suportadas, mas dependendo do conteúdo da visualização, as operações de atualização podem não funcionar. As visualizações são desativadas por padrão.</p>

<form id="generateForm" action="generate" method="post" class="form-horizontal">

	<table class="collection table table-bordered table-striped">
	<thead>
		<tr>
			<th class="checkboxColumn"><input type="checkbox" id="selectAll" checked="checked"
				onclick="$('input.tableCheckbox').attr('checked', $('#selectAll').attr('checked')=='checked');"/></th>
			<th>Tabela</th>
			<th>Nome Singular</th>
			<th>Nome plural</th>
			<th>Prefixo da coluna</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	
	/* these are reserved words that will conflict with phreeze */
	function is_reserved_table_name($name)
	{
		$reserved = array('criteria','phreezer','phreezable','reporter','controller','dataset');
		return in_array(strtolower($name), $reserved);
	} 

	/* these are property names that cannot be used due to conflicting with the client-side libraries */
	function is_reserved_column_name($name)
	{
		$reserved = array('url','urlroot','idattribute','attributes','isnew','changedattributes','previous','previousattributes','defaults');
		return in_array(strtolower($name), $reserved);
	} 
	?>
	
	<?php foreach ($this->dbSchema->Tables as $table) { 
	
		$invalidColumns = array();
		foreach ($table->Columns as $column) {
			if (is_reserved_column_name($column->NameWithoutPrefix) )
			{
				$invalidColumns[] = $column->Name;
			}
		} 
	?>
		<tr id="">
			<td class="checkboxColumn">
			<?php if (count($invalidColumns)>0) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Esta tabela contém um ou mais nomes de colunas que conflitam com as bibliotecas do lado do cliente. Para incluir esta tabela, mude o nome da (s) seguinte (s) coluna (s):<br/><br/><ul><li><?php $this->eprint( implode("</li><li>", $invalidColumns) ); ?></li></ul>"
					data-original-title="Reserved Word"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } elseif ($table->IsView) { ?>
				<input type="checkbox" class="tableCheckbox" name="table_name[]" value="<?php $this->eprint($table->Name); ?>" />
			<?php } elseif ($table->NumberOfPrimaryKeyColumns() < 1) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Atualmente, o Phreeze não suporta tabelas sem uma coluna de chave primária"
					data-original-title="Nenhuma chave primária"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } elseif ($table->NumberOfPrimaryKeyColumns() < 1) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Atualmente, o Phreeze não suporta tabelas com colunas de chaves múltiplas/composta"
					data-original-title="Chave Primária Composta"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } else { ?>
				<input type="checkbox" class="tableCheckbox" name="table_name[]" value="<?php $this->eprint($table->Name); ?>" checked="checked" />
			<?php } ?>
			</td>
			<td class="tableNameColumn">
			
			<?php if (is_reserved_table_name($table->Name)) { ?>
				<a href="#" class="popover-icon error" rel="popover" onclick="return false;"
					data-content="Este nome de tabela é uma palavra reservada no framework Phreeze. <br/><br/> 'Modelo' foi anexado ao final do nome da sua classe. Você pode mudar isso para outra coisa, contanto que você não use o nome de classe Phreeze reservado como-é."
					data-original-title="Palavra Reservada"><i class="icon-info-sign">&nbsp;</i></a>
			<?php } elseif ($table->IsView) { ?>
				<a href="#" class="popover-icon view" rel="popover" onclick="return false;"
					data-content="As visualizações são suportadas pelo Phreeze, contudo apenas as operações de leitura serão permitidas por predefinição. <br/><br/> Porque as vistas não suportam chaves ou índices, o Phreeze tratará a coluna mais à esquerda da vista como a chave primária. Para obter resultados ótimos, por favor, defina sua visão para que a coluna mais à esquerda retorna um valor exclusivo para cada linha."
					data-original-title="Visualizar informações"><i class="icon-table">&nbsp;</i></a>
			<?php }else{ ?>
				<i class="icon-table">&nbsp;</i>
			<?php } ?>
			<?php $this->eprint($table->Name); ?></td>
			
			<?php if (is_reserved_table_name($table->Name)) { ?>
				<td><input class="objname objname-singular" type="text" id="<?php $this->eprint($table->Name); ?>_singular" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>Model" /></td>
				<td><input class="objname objname-plural" type="text" id="<?php $this->eprint($table->Name); ?>_plural" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>Models" /></td>
			<?php } else { ?>
				<td><input class="objname objname-singular" type="text" id="<?php $this->eprint($table->Name); ?>_singular" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($this->studlycaps( $table->GetObjectName() )); ?>" /></td>
				<td><input class="objname objname-plural" type="text" id="<?php $this->eprint($table->Name); ?>_plural" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($this->studlycaps($this->plural( $table->GetObjectName() ))); ?>" /></td>
			<?php } ?>
			<td><input type="text" class="colprefix span2" id="<?php $this->eprint($table->Name); ?>_prefix" name="<?php $this->eprint($table->Name); ?>_prefix" value="<?php $this->eprint($table->ColumnPrefix); ?>" size="15" /></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>

	<h2><i class="icon-cogs"></i> Opções da Aplicação</h2>

	<p>Essas opções não precisam ser alteradas. A maioria deles simplesmente pre-fill uma configuração em um dos arquivos de configuração para que você não tem que editá-los manualmente para executar o aplicativo. Qualquer uma das opções abaixo pode ser alterada ou reconfigurada após o código ser gerado.</p>

	<fieldset class="well">

		<div id="packageContainer" class="control-group">
			<label class="control-label" for="package">Pacote para gerar <i class="popover-icon icon-question-sign" 
					data-title="Pacote para gerar" 
					data-content="Você pode escolher entre vários pacotes para gerar. O mais provável é que você esteja interessado em escolher a App Phreeze que usa o mecanismo de modelo preferido (RenderEngine) para a camada de visualização. <br/> <br/> O RenderEngine pode ser alterado em <code> _app_config.php </ code> No entanto, alterar o RenderEngine também requer a re-geração dos modelos."></i></label>
			<div class="controls inline-inputs">
				<select name="package" class="input-xxlarge">
				<?php foreach ($this->packages as $package) { ?>
					<option value="<?php $this->eprint($package->GetConfigFile()) ?>"><?php $this->eprint($package->GetName()) ?></option>
				<?php } ?>
				</select>
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="appNameContainer" class="control-group">
			<label class="control-label" for=""appname"">Nome da Aplicação <i class="popover-icon icon-question-sign" 
					data-title="Nome da Aplicação" 
					data-content="O nome do aplicativo aparecerá no navegador / cabeçalho superior, bem como no rodapé do aplicativo. Você pode alterar isso mais tarde na pasta modelos."></i></label>
			<div class="controls inline-inputs">
				<input type="text" name="appname" id="appname" value="<?php $this->eprint(strtoupper($this->appname)); ?>" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="appRootContainer" class="control-group">
			<label class="control-label" for="appRoot">URL da raiz do aplicativo <i class="popover-icon icon-question-sign" 
					data-title="URL de raiz do aplicativo" 
					data-content="Seu aplicativo Phreeze deve saber sua localização de raiz, a fim de suportar URLs limpas. Você precisará garantir que este é o URL correto para seu aplicativo. Ao implantar seu aplicativo em outro servidor, esse valor precisará ser ajustado.<br/><br/>The GlobalConfig::$ROOT_URL setting is found in <code>_machine_config.php</code>"></i></label>
			<div class="controls inline-inputs">
				<span>http://servername/</span>
				<input type="text" class="span2" name="appRoot" id="appRoot" value="<?php $this->eprint(strtolower($this->appname)); ?>/" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="includePathContainer" class="control-group">
			<label class="control-label" for="includePath">Caminho para /phreeze/libs <i class="popover-icon icon-question-sign" 
					data-title="Caminho para Phreeze Libs" 
					data-content="A menos que seu aplicativo seja auto-suficiente (veja a próxima opção), ele deve ser capaz de localizar os arquivos de classe do framework Phreeze em <code>/phreeze/libs/</code>. O aplicativo verificará o caminho de inclusão do PHP, mas você pode especificar um caminho de arquivo relativo adicional aqui. <br/><br/> Essa configuração pode ser ajustada em <code>_app_config.php</code>"></i></label>
			<div class="controls inline-inputs">
				<input type="text" name="includePath" id="includePath" value="../phreeze/libs" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="enableLongPollingContainer" class="control-group">
			<label class="control-label" for="includePhar">Faça Self-Contained <i class="popover-icon icon-question-sign" 
					data-title="Faça Self-Contained" 
					data-content="Selecionar 'Sim' incluirá o Phreeze Framework como um arquivo .phar pré-construído localizado em /libs/. Isso permitirá que seu aplicativo seja autônomo sem a necessidade das bibliotecas Phreeze no servidor. <br/><br/> Isso é recomendado ao distribuir aplicativos pré-empacotados e torná-los mais fáceis de instalar. Não é recomendado durante o desenvolvimento."></i></label>
			<div class="controls inline-inputs">
				<select name="includePhar" id="includePhar"  class="input-xxlarge">
					<option value="0">Não (Exigir Bibliotecas Phreeze Externas)</option>
					<option value="1">Sim (Incluir bibliotecas Phreeze como Phar)</option>
				</select>
				<span class="help-inline"></span>
			</div>
		</div>
		
		<div id="enableLongPollingContainer" class="control-group">
			<label class="control-label" for="enableLongPolling">Pesquisas longas <i class="popover-icon icon-question-sign" 
					data-title="Pesquisas longas" 
					data-content="Com o enquadramento longo ativado, as exibições de tabela irão 'pesquisar' o banco de dados a cada poucos segundos para alterações e atualizar a página automaticamente, se necessário. Isso fará com que seu site pareça ser um aplicativo colaborativo em tempo real. Use com cautela, pois isso também colocará carga adicional no servidor. <br/><br/> Essa configuração pode ser ajustada em <code>/scripts/model.js</code>"></i></label>
			<div class="controls inline-inputs">
				<select name="enableLongPolling" id="enableLongPolling">
					<option value="0">Desativado</option>
					<option value="1">Ativado</option>
				</select>
				<span class="help-inline"></span>
			</div>
		</div>
		
	</fieldset>
	
	<div id="errorContainer"></div>

	<p>
		<input type="hidden" name="host" id="host" value="<?php $this->eprint($this->host) ?>" />
		<input type="hidden" name="port" id="port" value="<?php $this->eprint($this->port) ?>" />
		<input type="hidden" name="type" id="type" value="<?php $this->eprint($this->type) ?>" />
		<input type="hidden" name="schema" id="schema" value="<?php $this->eprint($this->schema) ?>" />
		<input type="hidden" name="username" id="username" value="<?php $this->eprint($this->username) ?>" />
		<input type="hidden" name="password" id="password" value="<?php $this->eprint($this->password) ?>" />

		<button class="btn btn-inverse"><i class="icon-play"></i> Gerar Aplicativo</button>
	</p>
</form>

<script type="text/javascript" src="scripts/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="scripts/analyze.js"></script>

<?php include_once '_footer.tpl.php'; ?>