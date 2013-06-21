<?php

# Required File Includes
include("../../../dbconnect.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");

$gatewaymodule = "BIPS"; # Enter your gateway module name here replacing template

$gateway = getGatewayVariables($gatewaymodule);
if (!$gateway["type"]) die("Module Not Activated"); # Checks gateway module is active before accepting callback


$BIPS = $_POST;
$hash = hash('sha512', $BIPS['transaction']['hash'] . $gateway["secret"]);

header('HTTP/1.1 200 OK');
print '1';

if ($BIPS['hash'] == $hash && $BIPS['status'] == 1)
{
	$invoiceid = checkCbInvoiceID($BIPS["custom"]["invoiceid"], $gateway["name"]); # Checks invoice ID is a valid invoice number or ends processing
	
	addInvoicePayment($invoiceid, $BIPS["transaction"]["hash"], $BIPS["fiat"]["amount"], 0, $gatewaymodule); # Apply Payment to Invoice: invoiceid, transactionid, amount paid, fees, modulename
	logTransaction($gateway["name"], $BIPS, 'Successful'); # Save to Gateway Log: name, data array, status
}
else
{
	logTransaction($gateway["name"], $_POST, 'Unsuccessful'); # Save to Gateway Log: name, data array, status
}