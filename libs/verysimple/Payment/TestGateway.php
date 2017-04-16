<?php
/** @package    verysimple::Payment */

/** import supporting libraries */
require_once("PaymentProcessor.php");

/**
 * TestGateway is a PaymentProcessor implementation that does not
 * actually process transactions, but is used specifically for
 * application testing purposes.  To succeed, pass in a credit card
 * number of 4111111111111111 with a valid expiration date.
 * Any other credit card number will cause a fail to occur.
 *
 * @package    verysimple::Payment
 * @author     VerySimple Inc.
 * @copyright  1997-2012 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    1.0
 */
class TestGateway extends PaymentProcessor
{

	/**
	 * Called on contruction
	 */
	function Init($testmode)
	{
	}

	/**
	 * @see PaymentProcessor::Refund()
	 */
	function Refund(RefundRequest $req)
	{
		$resp = new PaymentResponse();
		$resp->OrderNumber = $req->InvoiceId;

		// before bothering with contacting the processor, check for some basic fields
		if ($req->TransactionId == '')
		{
			$resp->IsSuccess = false;
			$resp->ResponseCode = "0";
			$resp->ResponseMessage = "TestGateway: Nenhum ID de transação fornecido";
			$resp->RawResponse = "Envie qualquer valor no campo TransactionId para uma resposta bem-sucedida";
		}
		else
		{
			$resp->IsSuccess = true;
			$resp->TransactionId = rand( 1000000 , 9999999 );
			$resp->ResponseCode = "OK";
			$resp->ResponseMessage = "TestGateway: Valor total devolvido com êxito";
		}

		return $resp;
	}

	/**
	 * Process a PaymentRequest
	 * @param PaymentRequest $req Request object to be processed
	 * @return PaymentResponse
	 */
	function Process(PaymentRequest $req)
	{
		
		// simulate a typical CC purchase lag
		sleep(3);
		
		$resp = new PaymentResponse();
		$resp->OrderNumber = $req->OrderNumber;

		$expdate = strtotime("1/". $req->CCExpMonth . "/" . $this->GetFullYear($req->CCExpYear) . " + 1 month");

		// before bothering with contacting the processor, check for some basic fields
		if ($req->CCNumber == '')
		{
			$resp->IsSuccess = false;
			$resp->ResponseCode = "0";
			$resp->ResponseMessage = "TestGateway: Nenhum número de cartão de crédito fornecido";
			$resp->RawResponse = "Envie o cartão # 4111111111111111 para uma resposta bem sucedida da transação";
		}
		elseif ($req->CCNumber != '4111111111111111')
		{
			$resp->IsSuccess = false;
			$resp->ResponseCode = "1";
			$resp->ResponseMessage = "TestGateway: O número do cartão de crédito '".$req->CCNumber."' é inválido";
			$resp->RawResponse = "Envie o cartão # 4111111111111111 para uma resposta bem sucedida da transação";
		}
		elseif ($expdate < time())
		{
			$resp->IsSuccess = false;
			$resp->ResponseCode = "2";
			$resp->ResponseMessage = "TestGateway: O cartão de crédito expirou";
			$resp->RawResponse = "Definir a data de expiração maior do que hoje para uma resposta de transação bem-sucedida";
		}
		else
		{
			$resp->IsSuccess = true;
			$resp->TransactionId = rand( 1000000 , 9999999 );
			$resp->ResponseCode = "OK";
			$resp->ResponseMessage = "TestGateway: cobrança de " . number_format($req->TransactionAmount,2) . " Postado";
		}

		return $resp;

	}

}

?>