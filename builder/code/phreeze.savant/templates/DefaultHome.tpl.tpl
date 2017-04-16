<?php
	$this->assign('title','{$appname} | Home');
	$this->assign('nav','home');

	$this->display('_Header.tpl.php');
?>

	<div class="modal hide fade" id="getStartedDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>Introdução ao Phreeze</h3>
		</div>
		<div class="modal-body" style="max-height: 300px">
			<p>Este site foi gerado pelo construtor de classe Phreeze e contém a capacidade básica de leitura e escrita do banco de dados. Uma página de interface do usuário foi criada para cada tabela em seu banco de dados. Clique nos links na barra de navegação superior para visualizar as páginas.</p>

			<p>O aplicativo não se destina a ser usado como está, a menos que você queira apenas uma interface web simples para suas tabelas de dados e que você precise de algum tipo de autorização para acessar o aplicativo. Para converter isso em um aplicativo de trabalho real, você precisará personalizar cada página conforme necessário.
			A filosofia por trás do código gerado automaticamente é gerar mais código do que você precisa. Você pode e deve excluir os controladores, métodos e visualizações que você não precisa.</p>

			<h4>Controles de interface do usuário</h4>

			<p>Os controles de interface do usuário para campos de edição são gerados com base nos tipos de coluna do banco de dados.
			No entanto, o gerador não conhece a <i> finalidade </i> de cada campo. Por exemplo, um campo INT pode ser melhor exibido como uma entrada regular, um controle deslizante ou uma chave liga/desliga. É possível que o campo não possa ser editado pelo usuário. O gerador não sabe essas coisas e por isso faz uma melhor suposição com base em tipos de coluna e tamanhos. É muito provável que você tenha de mudar os controles de interface do usuário que são melhores para sua aplicação. Bootstrap fornece um monte de controles de interface do usuário para você usar.</p>

			<h4>Controladores</h4>

			<p>Um controlador foi criado para cada tabela no aplicativo.
			Os controladores estão localizados em /libs/Controller/.
			Se uma tabela específica não for editável diretamente, os modelos de controle e exibição devem ser excluídos. Um exemplo pode ser uma tabela usada em uma atribuição muitos-para-muitos.</p>

			<h4>Modelos</h4>

			<p>Vários arquivos de modelo foram criados para cada tabela no aplicativo.
			Os arquivos de modelo estão localizados em /libs /Model/.
			Se suas alterações de esquema você pode gerar novamente somente os arquivos DAO (objeto de acesso a dados) selecionando o pacote DAO-Only no construtor de classe. Contanto que você não toque em arquivos na pasta /libs/Model/DAO/, então você pode com segurança fazer alterações em seu esquema de banco de dados e gerar código sem perder nenhuma de suas personalizações.</p>

		</div>
		<div class="modal-footer">
			<button id="okButton" data-dismiss="modal" class="btn btn-primary">Vamos arrasar...</button>
		</div>
	</div>

	<div class="container">

		<!-- Main hero unit for a primary marketing message or call to action -->
		<div class="hero-unit">
			<h1>Tr&#232;s Bon <i class="icon-thumbs-up"></i></h1>
			<p>Esta aplicação foi gerada automaticamente pelo Phreeze.
			Este código deve ser considerado um ponto de partida com parte do trabalho repetitivo feito para você. Isso deixa você a se concentrar na funcionalidade que torna seu aplicativo exclusivo. Leia abaixo para obter mais informações sobre as tecnologias usadas para gerar este aplicativo.</p>
			
			<p>O estilo padrão do Bootstrap deste aplicativo pode ser facilmente customizado e estendido com um tema de substituição drop-in de
			<a href="https://wrapbootstrap.com/?ref=phreeze">{ldelim}wrap{rdelim}bootstrap</a>
			e <a href="http://www.google.com/search?q=bootstrap+themes">muitos outros recursos</a>.</p>
			
			<p><em>Gerado com Phreeze  {$PHREEZE_VERSION}.
			Running on Phreeze <?php $this->eprint($this->PHREEZE_VERSION); ?><?php if ($this->PHREEZE_PHAR) { $this->eprint(' (' . basename($this->PHREEZE_PHAR) . ')'); } ?>.</em></p>
			
			<a class="btn btn-primary btn-large" data-toggle="modal" href="#getStartedDialog">Iniciar &raquo;</a></p>
		</div>

		<!-- Example row of columns -->
		<div class="row">
			<div class="span3">
				<h2><i class="icon-cogs"></i> Phreeze</h2>
				 <p>Phreeze é uma framework MVC + ORM para PHP que fornece roteamento de URL, acesso DB orientado a objetos e serviços RESTful JSON que são consumidos pela camada de visualização.</p>
				<p><a class="btn" href="http://phreeze.com/">Sobre Phreeze &raquo;</a></p>
			</div>
			<div class="span3">
				<h2><i class="icon-th"></i> Backbone</h2>
				 <p>Backbone.js é uma estrutura de Javascript que é utilizada para fornecer modelos do lado do cliente, vinculação de modelo e persistência usando chamadas AJAX para os serviços RESTful do back-end.</p>
				<p><a class="btn" href="http://documentcloud.github.com/backbone/">Sobre Backbone &raquo;</a></p>
		 	</div>
			<div class="span3">
				<h2><i class="icon-twitter-sign"></i> Bootstrap</h2>
				<p>O Bootstrap by Twitter fornece um layout limpo, cross-browser e componentes de interface de usuário. O Bootstrap é um kit completo de ferramentas de front-end com componentes funcionais prontos para uso.</p>
				<p><a class="btn" href="http://twitter.github.com/bootstrap/">Sobre Bootstrap &raquo;</a></p>
			</div>
			<div class="span3">
				<h2><i class="icon-signin"></i> Libraries</h2>
				<p>As seguintes bibliotecas de código aberto são usadas neste aplicativo:
				<a href="https://github.com/eternicode/bootstrap-datepicker">datepicker</a>,
				<a href="https://github.com/danielfarrell/bootstrap-combobox">combobox</a>,
				<a href="http://fortawesome.github.com/Font-Awesome/">FontAwesome</a>,
				<a href="http://jquery.com/">jQuery</a>,
				<a href="http://labjs.com/">LABjs</a>,
				<a href="http://documentcloud.github.com/underscore/">Underscore</a>,
				<a href="http://phpsavant.com/">Savant</a>,
				<a href="https://github.com/jdewit/bootstrap-timepicker">timepicker</a>,
				<a href="http://docs.jquery.com/Qunit">QUnit</a>.
				Todas as bibliotecas e plugins têm uma licença permissiva para uso pessoal e comercial.
				</p>
			</div>
		</div>

	</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>