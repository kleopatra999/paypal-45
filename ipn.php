<?php
/**
* IPN variables: https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
* IPN simulator: https://developer.paypal.com/developer/ipnSimulator/
* IPN listener 1: https://developer.paypal.com/webapps/developer/docs/classic/ipn/gs_IPN/
* IPN listener 2: https://github.com/paypal/ipn-code-samples/blob/master/paypal_ipn.php
*/

/**
 * 1. catch IPN from PayPal
 * ---------------------------
 * read POST data using PHP input stream instead of $_POST
 * php://input reads raw data from the request body as read-only stream
 * reference: http://php.net/manual/en/wrappers.php.php
 */
// get incoming POST data
$raw_post_data = file_get_contents('php://input');
// group by keyvals
$raw_post_array = explode('&', $raw_post_data);
// pass keyvals to array
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2) {
		$myPost[$keyval[0]] = urldecode($keyval[1]);
	}
}

/**
 * 2. send modified data to PayPal
 * ---------------------------
 */
// prepend 'cmd', and assign all to $req
$req = 'cmd=_notify-validate';
// check for magic_quotes
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
// add all data to $req
foreach ($myPost as $key => $value) {
	// escape data values (deprecated?)
	//  don't let magic_quotes add backslashes
	//  else encode normally
	//  asign all values
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}
// post modified data back to PayPal
if($_REQUEST['test_ipn'] == 1) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}
$ch = curl_init($paypal_url); // curl handle
if ($ch == FALSE) { return FALSE; }
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// execute curl session
$res = curl_exec($ch);

// process curl error
if (curl_errno($ch) != 0) {
	// cURL error
	curl_close($ch); exit;
} else {
	// if no error, close connection
	curl_close($ch);
}

/**
 * 3. recieve verification status from PayPal
 * -----------------------------------------------
 */

// get verification status
$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));
// evaluate verification status with string comparison
if (strcmp ($res, "VERIFIED") == 0) {
	$mail_headers    = "From: ORGANIZATION <" . $_POST['receiver_email'] . ">" . PHP_EOL;
	$mail_headers   .= "Reply-To: ORGANIZATION <" . $_POST['receiver_email'] . ">" . PHP_EOL;
	$mail_headers   .= "Bcc: ADMIN <nama@email.com>" . PHP_EOL;
	$mail_headers   .= "MIME-Version: 1.0" . PHP_EOL;
	$mail_headers   .= "Content-Type: text/plain; charset=UTF-8" . PHP_EOL;
	$mail_to         = "=?UTF-8?B?" . base64_encode($_POST['address_name']) . "?=" . " <" . $_POST['payer_email'] . ">";
	$mail_subject    = "=?UTF-8?B?" . base64_encode("Transacción Exitosa") . "?=";
	$mail_body		 = "Hola, " . $_POST['first_name'] . "." . PHP_EOL . PHP_EOL . "Gracias por tu compra de “" . $_POST['item_name'] . "”" . PHP_EOL . "Has invertido $" . $_POST['payment_gross'] . PHP_EOL . PHP_EOL . "Que lo aproveches." . PHP_EOL ."Estamos para servirte.";
	mail($mail_to, $mail_subject, $mail_body, $mail_headers);
} else if (strcmp ($res, "INVALID") == 0) {
	$mail_headers    = "From: ORGANIZATION <name@email.com>" . PHP_EOL;
	$mail_headers   .= "Reply-To: ORGANIZATION <name@email.com>" . PHP_EOL;
	$mail_headers   .= "MIME-Version: 1.0" . PHP_EOL;
	$mail_headers   .= "Content-Type: text/plain; charset=UTF-8" . PHP_EOL;
	$mail_to         = "ADMIN <name@email.com>";
	$mail_subject    = "Error en la transaccion";
	$mail_body       = "Ha ocurrido un error en un proceso de compra." . PHP_EOL . "PayPal IPN response status is “INVALID”" . PHP_EOL . "Email: " . $_POST['payer_email'] . PHP_EOL . "Name: " . $_POST['address_name'] . PHP_EOL . PHP_EOL . $req . PHP_EOL . PHP_EOL . curl_error($ch);
	mail($mail_to, $mail_subject, $mail_body, $mail_headers);
}

// IPN process ends here
// PayPal continues to given data-return="{{URL}}"

?>
