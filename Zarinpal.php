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
    require_once 'nusoap.php';
}

class Zarinpal
{
    private $url;
    private $ERR;
    private $authority;
    private $refid;

    private $url_wsdl;
    private $url_pay;

    public function __construct()
    {
        $url_wsdl = 'https://www.zarinpal.com/pg/services/WebGate/wsdl';
        $url_pay = 'https://www.zarinpal.com/pg/StartPay/';
    }

    public function request($merchant_id, $amount, $desc, $call_back, $mobile = null, $email = null)
    {
        $client = new nusoap_client($this->url_wsdl, 'wsdl');
        $client->soap_defencoding = 'UTF-8';

        $data = [
            'MerchantID'     => $merchant_id,
            'Amount'         => $amount,
            'Description'    => $desc,
            'CallbackURL'    => $call_back,
            ];

        if ($phone) {
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

    public function redirect()
    {
        $CI = &get_instance();
        if (!function_exists('redirect')) {
            $CI->load->helper('url');
        }
        redirect($this->url);
    }

    public function get_error()
    {
        return $this->ERR;
    }

    public function get_authority()
    {
        return $this->authority;
    }

    public function verify($merchant_id, $amount, $authority)
    {
        $client = new nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client->soap_defencoding = 'UTF-8';

        $data = [
            'MerchantID'    => $merchant_id,
            'Amount'        => $amount,
            'Authority'     => $authority,
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

    public function get_ref_id()
    {
        return $this->refid;
    }

    public function sandbox()
    {
        $url_wsdl = 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl';
        $url_pay = 'https://sandbox.zarinpal.com/pg/StartPay/';
    }
}
