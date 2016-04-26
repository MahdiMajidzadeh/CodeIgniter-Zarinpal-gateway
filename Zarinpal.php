<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !class_exists('nusoap_client')) 
	require_once('nusoap.php');

class Zarinpal {
    private $payment_meta;
    private $payment_gate;

    public function create( $merchant_id = '', $amount = 0, $gateway = 'ZarinGate' ) {

        if ( $this->payment_meta || ! is_null( $this->payment_meta ) ) {
            return false;
        }
        if ( $merchant_id == '' || strlen( $merchant_id ) != 16 ) {
            return false;
        }
        if ( $amount <= 0 ) {
            return false;
        }
        if ( ! $gateway == 'ZarinGate' || ! $gateway == 'WebGate' ) {
            return false;
        }
        
        $this->payment_meta = array(
            'MerchantID'       =>  $merchant_id,
            'Amount'            =>  $amount
        );
        $this->payment_gate = $gateway;
        return true;
    }

}