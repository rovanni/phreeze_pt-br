<?php
/** @package    verysimple::Payment */

/** import supporting libraries */
require_once("PaymentProcessor.php");

/**
 * SkipJack extends the generic PaymentProcessor object to process
 * a PaymentRequest through the SkipJack payment gateway.
 *
 * @package    verysimple::Payment
 * @author     VerySimple Inc.
 * @copyright  1997-2012 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    2.1
 */
class SkipJack extends PaymentProcessor
{

	private $liveUrl = "https://www.skipjackic.com/scripts/evolvcc.dll?AuthorizeApi";
	private $testUrl = "https://developer.skipjackic.com/scripts/evolvcc.dll?AuthorizeAPI";
	private $url = "";

	/**
	 * Called on contruction
	 * @param bool $test  set to true to enable test mode.  default = false
	 */
	function Init($testmode)
	{
		// set the post url depending on whether we're in test mode or not
		$this->url = $testmode ? $this->testUrl : $this->liveUrl;
	}

	/**
	 * @see PaymentProcessor::Refund()
	 */
	function Refund(RefundRequest $req)
	{
		throw new Exception("Reembolso não implementado para este gateway");
	}

	/**
	 * Process a PaymentRequest
	 * @param PaymentRequest $req Request object to be processed
	 * @return PaymentResponse
	 */
	function Process(PaymentRequest $req)
	{

		if ($this->_testMode)
		{
			if ($req->SerialNumber == "" || $req->DeveloperSerialNumber == "")
			{
				throw new Exception("SkipJack requer um SerialNumber e DeveloperSerialNumber para transações de teste. As contas de desenvolvedor grátis podem ser obtidas através do SkipJack.com");
			}
		}
		else
		{
			if ($req->SerialNumber == "")
			{
				throw new Exception("SkipJack requer um número de série para transações ao vivo");
			}
		}

		// skipjack requires a funky formatted order string
		if (!$req->OrderString) $req->OrderString = "1~None~0.00~0~N~||";

		$resp = new PaymentResponse();
		$resp->OrderNumber = $req->OrderNumber;

		// post to skipjack service
		$resp->RawResponse = $this->CurlPost($this->url, $this->GetPostData($req) );

		// response is two lines - first line is field name, 2nd line is values
		$lines = explode("\r\n",$resp->RawResponse);

		// strip off the beginning and ending doublequote
		$lines[0] = substr( $lines[0],1,strlen($lines[0])-2);
		$lines[1] = substr( $lines[1],1,strlen($lines[1])-2);

		// split the fields and values
		$fields = explode("\",\"",$lines[0]);
		$vals = explode("\",\"",$lines[1]);

		// convert these two lines into a hash so we can get individual values
		for ($i = 0; $i < count($fields); $i++)
		{
			$resp->ParsedResponse[$fields[$i]] = $vals[$i];
		}

		// convert these codes into a generic response object
		$resp->ResponseCode = $resp->ParsedResponse["szReturnCode"];
		$resp->TransactionId = $resp->ParsedResponse["AUTHCODE"];

		// figure out if the transaction was a total success or not
		$verifyOK = $resp->ParsedResponse["szReturnCode"] == "1";
		$approvedOK = $resp->ParsedResponse["szIsApproved"] == "1";
		$authOK = $resp->ParsedResponse["AUTHCODE"] != "EMPTY" && $resp->ParsedResponse["AUTHCODE"] != "" && $resp->ParsedResponse["szAuthorizationResponseCode"] != "";
		$resp->IsSuccess = ($verifyOK && $approvedOK && $authOK);

		// dependin on the status, get the best description we can
		if ($resp->IsSuccess)
		{
			$resp->ResponseMessage = $this->GetMessage($resp->ParsedResponse["szReturnCode"]);
		}
		else if (!$verifyOK)
		{
			// verification failed
			$resp->ResponseMessage = $this->GetMessage($resp->ParsedResponse["szReturnCode"]);
		}
		else if (!$authOK)
		{
			// verification was ok, but the processor didn't process the transaction
			$resp->ResponseMessage = $resp->ParsedResponse["szAuthorizationDeclinedMessage"];
		}
		else
		{
			// we don't know why it so just display all the possible error messages
			$resp->ResponseMessage = $resp->ParsedResponse["szAuthorizationDeclinedMessage"] . " " . $resp->ParsedResponse["szAVSResponseMessage"] . " " . $resp->ParsedResponse["szCVV2ResponseMessage"];
		}

		return $resp;
	}

	private function GetPostData($req)
	{
		$data = array();
		$data["orderstring"] = $req->OrderString;
		$data["serialnumber"] = $req->SerialNumber;
		$data["developerserialnumber"] = $req->DeveloperSerialNumber;
		$data["sjname"] = $req->CustomerName;
		$data["streetaddress"] = $req->CustomerStreetAddress;
		$data["city"] = $req->CustomerCity;
		$data["state"] = $req->CustomerState;
		$data["zipcode"] = $req->CustomerZipCode;
		$data["shiptophone"] = $req->CustomerPhone;
		$data["email"] = $req->CustomerEmail;
		$data["ordernumber"] = "CC" . substr(md5(time()),0,20);
		$data["transactionamount"] = number_format($req->TransactionAmount,2,".","");
		$data["accountnumber"] = str_replace(array("-"," "),array("",""), $req->CCNumber);
		$data["month"] = $req->CCExpMonth;
		$data["year"] = $req->CCExpYear;
		$data["cvv2"] = $req->CCSecurityCode;
		$data["country"] = $req->CustomerCountry;
		$data["comment"] = $req->Comment;
		return $data;
	}

	/**
	* Returns a text description based on the return code
	* @param string $code the skipjack response code
	* @return string
	*/
	private function GetMessage($code)
	{
		$errors = array();
		$errors ["-1"] = "Erro no pedido Os dados não foram recebidos intactos pelo Skipjack Transaction Network.";
		$errors ["0"] = "Erro de Falha de Comunicação na Solicitação e Resposta no nível de IP. Use Get Transaction Status antes de repetir a transação.";
		$errors ["1"] = "Sucesso";
		$errors ["-35"] = "O número do cartão de crédito não está em conformidade com a verificação Mod10. Tente novamente com o número correto do cartão de crédito.";
		$errors ["-37"] = "O Skipjack não consegue comunicar com o processador de pagamento. Tente novamente.";
		$errors ["-39"] = "Verifique o tamanho do Número de Série HTML e se é um número correto / válido. Confirme que está enviando para o ambiente correto (Desenvolvimento ou Produção)";
		$errors ["-51"] = "Comprimento ou valor do CEP O valor ou comprimento do código postal de faturamento está incorreto.";
		$errors ["-52"] = "O valor ou o comprimento para o envio do código postal está incorreto.";
		$errors ["-53"] = "O valor ou o comprimento do mês de vencimento do cartão de crédito está incorreto.";
		$errors ["-54"] = "O valor ou duração do mês ou ano do número da conta do cartão de crédito estava incorreto.";
		$errors ["-55"] = "O valor ou o comprimento ou o endereço da rua de faturamento está incorreto.";
		$errors ["-56"] = "O valor ou o comprimento do endereço de entrega está incorreto.";
		$errors ["-57"] = "A duração do valor da transação deve ter pelo menos 3 dígitos (excluindo a casa decimal).";
		$errors ["-58"] = "O nome do comerciante associado à conta do Skipjack está mal configurado ou é inválido";
		$errors ["-59"] = "O endereço do comerciante associado à conta do Skipjack está mal configurado ou é inválido Guia de Integração do Skipjack Serviços Financeiros Página 52 de 251";
		$errors ["-60"] = "O estado do comerciante associado à conta do Skipjack está mal configurado ou é inválido";
		$errors ["-61"] = "O valor ou o comprimento para o estado de expedição / província está vazio.";
		$errors ["-62"] = "O valor para o comprimento orderstring está vazio.";
		$errors ["-64"] = "O valor para o número de telefone está incorreto.";
		$errors ["-65"] = "Erro vazio sjname O valor ou o comprimento do nome do faturamento está vazio.";
		$errors ["-66"] = "O valor ou o comprimento do e-mail de cobrança está vazio.";
		$errors ["-67"] = "O valor ou o comprimento para o endereço de faturamento está vazio.";
		$errors ["-68"] = "O valor ou o comprimento da cidade de faturamento está vazio.";
		$errors ["-69"] = "O valor ou o comprimento para o estado de faturamento está vazio.";
		$errors ["-70"] = "O campo Código postal está vazio.";
		$errors ["-71"] = "O campo Ordernumber está vazio.";
		$errors ["-72"] = "O campo do número da conta está vazio";
		$errors ["-73"] = "O campo Month está vazio.";
		$errors ["-74"] = "O campo Ano está vazio.";
		$errors ["-75"] = "O campo do número de série está vazio.";
		$errors ["-76"] = "O campo do valor da transação está vazio.";
		$errors ["-77"] = "O campo Orderstring está vazio.";
		$errors ["-78"] = "O campo Shiptophone está vazio.";
		$errors ["-79"] = "Comprimento ou valor sjname O valor ou o comprimento do nome de faturamento está vazio.";
		$errors ["-80"] = "Erro no comprimento ou no valor do telefone de bordo.";
		$errors ["-81"] = "Comprimento ou valor da localização do Cliente";
		$errors ["-82"] = "O valor ou o comprimento do estado de facturação está vazio.";
		$errors ["-83"] = "O valor ou o comprimento para o envio do telefone está vazio.";
		$errors ["-84"] = "Já existe uma transação pendente existente no registro que compartilha o Número de Pedido publicado.";
		$errors ["-85"] = "O valor do campo da perna da linha aérea é inválido ou vazio.";
		$errors ["-86"] = "O campo info do bilhete aéreo é inválido ou vazio";
		$errors ["-87"] = "O número de roteamento de seleção do Ponto de Venda é inválido ou vazio.";
		$errors ["-88"] = "O número da conta de verificação do ponto de venda é inválido ou vazio.";
		$errors ["-89"] = "Ponto de venda verificar MICR inválido ou vazio.";
		$errors ["-90"] = "Número de verificação de ponto de venda ou inválido Número de verificação de ponto de venda inválido ou vazio.";
		$errors ["-91"] = "\"Tornar o CVV um recurso de campo obrigatório\" habilitado na interface Merchant Account Setup, mas nenhum código CVV foi enviado nos dados da transação.";
		$errors ["-92"] = "Código de Aprovação Inválido. O Código de Aprovação é um código de 6 dígitos.";
		$errors ["-93"] = "Solicitação de créditos cegos Recusado \"Permitir Créditos Cegos\" deve ser ativada na Conta do Comerciante de Bonecos.";
		$errors ["-94"] = "BlindCreditsFailed Skipjack Serviços Financeiros Skipjack Integration GuidePágina 53 de 251";
		$errors ["-95"] = "Solicitação de autorização de voz A opção de autorização de voz recusada deve ser ativada no Skipjack";
		$errors ["-96"] = "Falha nas Autorizações de Voz";
		$errors ["-97"] = "Rejeição de fraude viola a resolução de velocidade.";
		$errors ["-98"] = "Valor de desconto inválido";
		$errors ["-99"] = "PIN de débito PIN de débito específico de BlockDebit";
		$errors ["-100"] = "POS PIN Debit Invalid Key Número de série Debit-specific";
		$errors ["-101"] = "Os dados para o Verified by Visa / MC Secure Code são inválidos.";
		$errors ["-102"] = "Dados de autenticação não permitidos";
		$errors ["-103"] = "A variável POS checkdateofbirth contém uma data de nascimento em um formato incorreto. Use o formato MM / DD / AAAA para esta variável.";
		$errors ["-104"] = "A variável POS checkidentificationtype contém um valor de identificação que é inválido. Use o valor de um dígito onde o número de Segurança Social = 1, Drivers License = 2 para esta variável.";
		$errors ["-105"] = "Track Data está em formato inválido.";
		$errors ["-106"] = "POS Check Invalid Account Type";
		$errors ["-107"] = "Número de Seqüência Inválida de Débito de Ponto POS";
		$errors ["-108"] = "ID de transação inválido";
		$errors ["-109"] = "Tipo de conta inválido";
		$errors ["-110"] = "Pos Erro Inválido Para Tipo de Conta";
		$errors ["-112"] = "Pos Erro Opção de Autenticação Inválida";
		$errors ["-113"] = "Pos Falha na Transação de Erro";
		$errors ["-114"] = "Pos Error Invalid Incoming Eci";
		$errors ["-115"] = "POS Check Invalid Check Type";
		$errors ["-116"] = "POS Check lane ou número de caixa registradora é inválido. Use um número válido de pista ou caixa registradora que tenha sido configurado na Conta do Comerciante de Skipjack.";
		$errors ["-117"] = "POS Check Invalid Cashier Number";


		return (isset ($errors [$ code]))? $errors [$ code]: "Erro desconhecido";
	}

}

?>