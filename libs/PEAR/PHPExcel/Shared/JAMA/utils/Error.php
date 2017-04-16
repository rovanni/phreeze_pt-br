<?php
/**
 *	@package JAMA
 *
 *	Error handling
 *	@author Michael Bommarito
 *	@version 01292005
 */

//Language constant
define('JAMALANG', 'PT');


//All errors may be defined by the following format:
//define('ExceptionName', N);
//$error['lang'][ExceptionName] = 'Error message';
$error = array();

/*
I've used Babelfish and a little poor knowledge of Romance/Germanic languages for the translations here.
Feel free to correct anything that looks amiss to you.
*/

define('PolymorphicArgumentException', -1);
$error['EN'][PolymorphicArgumentException] = "Invalid argument pattern for polymorphic function.";
$error['FR'][PolymorphicArgumentException] = "Modèle inadmissible d'argument pour la fonction polymorphe.".
$error['DE'][PolymorphicArgumentException] = "Unzulässiges Argumentmuster für polymorphe Funktion.";
$error['PT'][PolymorphicArgumentException] = "Padrão de argumento inválido para função polimórfica.";

define('ArgumentTypeException', -2);
$error['EN'][ArgumentTypeException] = "Invalid argument type.";
$error['FR'][ArgumentTypeException] = "Type inadmissible d'argument.";
$error['DE'][ArgumentTypeException] = "Unzulässige Argumentart.";
$error['PT'][ArgumentTypeException] = "Tipo de argumento inválido.";

define('ArgumentBoundsException', -3);
$error['EN'][ArgumentBoundsException] = "Invalid argument range.";
$error['FR'][ArgumentBoundsException] = "Gamme inadmissible d'argument.";
$error['DE'][ArgumentBoundsException] = "Unzulässige Argumentstrecke.";
$error['PT'][ArgumentBoundsException] = "Intervalo de argumento inválido.";

define('MatrixDimensionException', -4);
$error['EN'][MatrixDimensionException] = "Matrix dimensions are not equal.";
$error['FR'][MatrixDimensionException] = "Les dimensions de Matrix ne sont pas égales.";
$error['DE'][MatrixDimensionException] = "Matrixmaße sind nicht gleich.";
$error['PT'][MatrixDimensionException] = "As dimensões da matriz não são iguais.";

define('PrecisionLossException', -5);
$error['EN'][PrecisionLossException] = "Significant precision loss detected.";
$error['FR'][PrecisionLossException] = "Perte significative de précision détectée.";
$error['DE'][PrecisionLossException] = "Bedeutender Präzision Verlust ermittelte.";
$error['PT'][PrecisionLossException] = "Perda de precisão significativa detectada.";

define('MatrixSPDException', -6);
$error['EN'][MatrixSPDException] = "Can only perform operation on symmetric positive definite matrix.";
$error['FR'][MatrixSPDException] = "Perte significative de précision détectée.";
$error['DE'][MatrixSPDException] = "Bedeutender Präzision Verlust ermittelte.";
$error['PT'][MatrixSPDException] = "Só pode executar a operação em matriz definida positiva simétrica.";

define('MatrixSingularException', -7);
$error['EN'][MatrixSingularException] = "Can only perform operation on singular matrix.";
$error['PT'][MatrixSingularException] = "Só pode executar a operação em matriz singular.";

define('MatrixRankException', -8);
$error['EN'][MatrixRankException] = "Can only perform operation on full-rank matrix.";
$error['PT'][MatrixRankException] = "Só pode executar a operação em matriz completa.";

define('ArrayLengthException', -9);
$error['EN'][ArrayLengthException] = "Array length must be a multiple of m.";
$error['PT'][ArrayLengthException] = "O comprimento da matriz deve ser um múltiplo de m.";

define('RowLengthException', -10);
$error['EN'][RowLengthException] = "All rows must have the same length.";
$error['PT'][RowLengthException] = "Todas as linhas devem ter o mesmo comprimento.";

/**
 *	Custom error handler
 *	@param int $num Error number
 */
function JAMAError($errorNumber = null) {
	global $error;

	if (isset($errorNumber)) {
		if (isset($error[JAMALANG][$errorNumber])) {
			return $error[JAMALANG][$errorNumber];
		} else {
			return $error['PT'][$errorNumber];
		}
	} else {
		return ("Invalid argument to JAMAError()");
	}
}
