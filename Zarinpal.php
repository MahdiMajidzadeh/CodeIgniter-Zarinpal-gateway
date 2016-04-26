<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!class_exists('nusoap_client')) 
	require_once('nusoap.php');

class Zarinpal {

	private $url;
	private $ERR;
	private $authority;

	public function start($merchant_id , $amount, $desc, $call_back, $moble = NULL, $email = NULL){

		$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
		$client->soap_defencoding = 'UTF-8';

		$data = array(
			'MerchantID' 	=> $merchant_id,
			'Amount' 		=> $amount,
			'Description' 	=> $desc,
			'CallbackURL' 	=> $call_back
			);
		
		if($phone){
			$data['Mobile'] = $mobile;
		}
		if($email){
			$data['Email'] = $email;
		}	

		$result = $client->call('PaymentRequest', array($data));

		if($result['Status'] == 100){
			$this->authority = $result['Authority'];
			$this->url = 'https://www.zarinpal.com/pg/StartPay/'.$result['Authority'];
			return TRUE;
		}
		else{
			$this->ERR = $result['Status'];
			return FALSE;
		}
	}

}