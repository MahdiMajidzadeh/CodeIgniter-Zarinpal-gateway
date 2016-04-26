<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter ZarinPal getway library
 *
 * @author              Mahdi Majidzadeh (http://restro.ir)
 * @license             GNU Public License 2.0
 * @package             ZarinPal
 */

if (!class_exists('nusoap_client')) 
	require_once('nusoap.php');

class Zarinpal {

	private $url;
	private $ERR;
	private $authority;
	private $refid;

	public function request($merchant_id , $amount, $desc, $call_back, $mobile = NULL, $email = NULL){

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

	public function redirect(){
		$CI =& get_instance();
		if(!function_exists('redirect')){
			$CI->load->helper('url');
		}
		redirect($this->url);
	}

	public function get_error(){
		return $this->ERR;
	}

	public function get_authority(){
		return $this->authority;
	}

	public function verify($merchant_id , $amount, $authority){

		$client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl'); 
		$client->soap_defencoding = 'UTF-8';

		$data = array(
			'MerchantID' 	=> $merchant_id,
			'Amount' 		=> $amount,
			'Authority' 	=> $authority
			);

		$result = $client->call('PaymentVerification', array($data));

		if($result['Status'] == 100){
			$this->refid = $result['RefID'];
			return TRUE;
		}
		else{
			$this->ERR = $result['Status'];
			return FALSE;
		}
	}

	public function get_ref_id(){
		return $this->refid;
	}

}