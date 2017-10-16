<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * CodeIgniter ZarinPal getway library
 *
 * @author              Mahdi Majidzadeh (http://majidzadeh.ir)
 * @license             GNU Public License 2.0
 * @package             ZarinPal
 */

if (!class_exists('nusoap_client')) {
    require_once 'nusoap/nusoap.php';
}

class Zarinpal
{
    private $ERR;
    private $authority;
    private $refid;
    private $url;
    private $url_pay = 'https://www.zarinpal.com/pg/StartPay/';
    private $url_wsdl = 'https://www.zarinpal.com/pg/services/WebGate/wsdl';
    private $zaringate = [
        'zaringate' => 'ZarinGat',
        'asan'      => 'Asan',
        'saman'     => 'Sep',
        'sadad'     => 'Sad',
        'parsian'   => 'Pec',
        'fanava'    => 'Fan',
    ];

    public function getAuthority()
    {
        return $this->authority;
    }

    public function getError()
    {
        return $this->ERR;
    }

    public function getRefId()
    {
        return $this->refid;
    }

    public function redirect()
    {
        $CI = &get_instance();
        if (!function_exists('redirect')) {
            $CI->load->helper('url');
        }

        redirect($this->url);
    }

    public function request($merchant_id, $amount, $desc, $call_back, $mobile = null, $email = null)
    {
        $client = new nusoap_client($this->url_wsdl, 'wsdl');
        $client->soap_defencoding = 'UTF-8';

        $data = [
            'MerchantID'  => $merchant_id,
            'Amount'      => $amount,
            'Description' => $desc,
            'CallbackURL' => $call_back,
        ];

        if ($mobile) {
            $data['Mobile'] = $mobile;
        }
        if ($email) {
            $data['Email'] = $email;
        }

        $result = $client->call('PaymentRequest', [$data]);

        if ($result['Status'] == 100) {
            $this->authority = $result['Authority'];
            $this->url = $this->url_pay.$result['Authority'];

            return true;
        } else {
            $this->ERR = $result['Status'];

            return false;
        }
    }

    public function sandbox()
    {
        $this->url_wsdl = 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl';
        $this->url_pay = 'https://sandbox.zarinpal.com/pg/StartPay/';
    }

    public function verify($merchant_id, $amount, $authority)
    {
        $client = new nusoap_client($this->url_wsdl, 'wsdl');
        $client->soap_defencoding = 'UTF-8';

        $data = [
            'MerchantID' => $merchant_id,
            'Amount'     => $amount,
            'Authority'  => $authority,
        ];

        $result = $client->call('PaymentVerification', [$data]);

        if ($result['Status'] == 100) {
            $this->refid = $result['RefID'];

            return true;
        } else {
            $this->ERR = $result['Status'];

            return false;
        }
    }

    public function webgate($merchant_id, $amount, $desc, $call_back, $mobile = null, $email = null)
    {
        return $this->request($merchant_id, $amount, $desc, $call_back, $mobile, $email);
    }

    public function zaringate($merchant_id, $amount, $desc, $call_back, $gate = 'zaringate', $mobile = null, $email = null)
    {
        $result = $this->request($merchant_id, $amount, $desc, $call_back, $mobile, $email);
        $this->url = $this->url_pay.$this->authority.'/'.$this->zaringate[$gate];

        return $result;
    }
}
