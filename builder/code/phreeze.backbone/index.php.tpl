<?php
/** @package    {$appname} */

/* GlobalConfig object contains all configuration information for the app */
include_once("_global_config.php");
include_once("_app_config.php");
@include_once("_machine_config.php");

if (!GlobalConfig::$CONNECTION_SETTING)
{
	throw new Exception('GlobalConfig::$CONNECTION_SETTING Não está configurado. Você está ausente _machine_config.php?');
}

/* require framework libs */
require_once("verysimple/Phreeze/Dispatcher.php");

// the global config is used for all dependency injection
$gc = GlobalConfig::GetInstance();

try
{
	Dispatcher::Dispatch(
		$gc->GetPhreezer(),
		$gc->GetRenderEngine(),
		'',
		$gc->GetContext(),
		$gc->GetRouter()
	);
}
catch (exception $ex)
{
	// This is the global error handler which will be called in the event of
	// uncaught errors.  If the endpoint appears to be an API request then
	// render it as JSON, otherwise attempt to render a friendly HTML page
	
	$url = RequestUtil::GetCurrentURL();
	$isApiRequest = (strpos($url,'api/') !== false);
	
	if ($isApiRequest)
	{
		$result = new stdClass();
		$result->success= false;
		$result->message = $ex->getMessage();
		$result->data = $ex->getTraceAsString();
		
		@header('HTTP/1.1 401 Unauthorized');
		echo json_encode($result);
	}
	else
	{
		$gc->GetRenderEngine()->assign("message",$ex->getMessage());
		$gc->GetRenderEngine()->assign("stacktrace",$ex->getTraceAsString());
		$gc->GetRenderEngine()->assign("code",$ex->getCode());
		
		try
		{
			$gc->GetRenderEngine()->display("DefaultErrorFatal.tpl");
		}
		catch (Exception $ex2)
		{
			// Isso significa que há um erro com o modelo, caso em que não podemos exibi-lo bem
			echo "<style>* { font-family: verdana, arial, helvetica, sans-serif; }</style>\n";
			echo "<h1>Erro fatal:</h1>\n";
			echo '<h3>' . htmlentities($ex->getMessage()) . "</h3>\n";
			echo "<h4>Rastreamento de pilha original:</h4>\n";
			echo '<textarea wrap="off" style="height: 200px; width: 100%;">' . htmlentities($ex->getTraceAsString()) . '</textarea>';
			echo "<h4>Além do erro acima, o modelo de erro padrão não pôde ser exibido:</h4>\n";
			echo '<textarea wrap="off" style="height: 200px; width: 100%;">' . htmlentities($ex2->getMessage()) . "\n\n" . htmlentities($ex2->getTraceAsString()) . '</textarea>';
		}
	}
}

?>