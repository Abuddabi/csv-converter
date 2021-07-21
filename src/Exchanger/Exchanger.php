<?php

namespace Converter\Exchanger;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

/**
 * 
 */
class Exchanger
{
	private $api_key 		= null;
	private $base_uri 		= null;
	private $currency_from  = null;
	private $currency_to 	= null;
	
	function __construct($currency_from = 'USD', $currency_to = 'RUB')
	{
		$config = require $_SERVER['DOCUMENT_ROOT'].'/config.php';
		$config = $config['exchanger'];
		$this->api_key  = $config['api_key'];
		$this->base_uri = $config['base_uri'];

		$this->currency_from = $currency_from;
		$this->currency_to 	 = $currency_to;
	}

	public function getLatestRate()
	{
		$endpoint = 'convert';
		$query = $this->currency_from.'_'.$this->currency_to;
		$uri = $endpoint.'?q='.$query.'&compact=ultra'.'&apiKey='.$this->api_key;

		$response = $this->exchangerRequest($uri);
		
		return $response->$query;
	}

	private function exchangerRequest($uri)
	{
		$client = new Client([
		    'base_uri' => $this->base_uri
		]);

		try {
		    $response = $client->request('GET', $uri);

		    return json_decode($response->getBody()->getContents());
		} catch (RequestException $e) {
		    echo Psr7\Message::toString($e->getRequest());
		    if ($e->hasResponse()) {
		        echo Psr7\Message::toString($e->getResponse());
		    }

		    exit();
		}		
	}
}