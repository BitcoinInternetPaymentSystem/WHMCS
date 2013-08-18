<?php
	function BIPS_config()
	{
		$configarray = array(
			"FriendlyName" => array("Type" => "System", "Value" => "BIPS"),
			"api" => array("FriendlyName" => "API Key", "Type" => "text", "Size" => "35", ),
			"secret" => array("FriendlyName" => "Merchant Secret", "Type" => "text", "Size" => "20", )
		);
		return $configarray;
	}

	function BIPS_link($params)
	{
		$ch = curl_init();
		curl_setopt_array($ch, array(
		CURLOPT_URL => 'https://BIPS.me/api/v1/invoice',
		CURLOPT_USERPWD => $params["api"],
		CURLOPT_POSTFIELDS => 'price=' . number_format($params["amount"], 2, '.', '') . '&currency=' . $params['currency'] . '&item=' . $params["description"] . '&custom=' . json_encode(array('invoiceid' => $params['invoiceid'], 'returnurl' => rawurlencode($params['systemurl']), 'cancelurl' => rawurlencode($params['systemurl']))),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC));
		$url = curl_exec($ch);
		curl_close($ch);

		$link = '<a href="' . $url . '" title="BIPS Invoice"><img src="https://cdn.bips.me/includes/buywithbitcoin.png" alt="Pay with Bitcoin" /></a>';

		return $link;
	}
?>
