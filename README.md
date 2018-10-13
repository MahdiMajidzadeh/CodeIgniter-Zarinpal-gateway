# CodeIgniter-Zarinpal-gateway
[![StyleCI](https://styleci.io/repos/57077375/shield)](https://styleci.io/repos/57077375)

## This library not maintained eny more 

Codeigniter library for Iranian gateway, [Zarinpal](https://www.zarinpal.com/)

## how to install
Copy `Zarinpal.php` and `nusoap` directory to `application/libraries` of your own project.

## how to use
First, load library:
```
$this->load->library('zarinpal');
```

For sending user to gateway:
- webgate (first show zarinpal page):
```
$this->zarinpal->webgate($merchant_id , $amount, $desc, $call_back, $mobile, $email);
```
- zaringate (direct to bank page):
```
$this->zarinpal->zaringate($merchant_id , $amount, $desc, $gate, $call_back, $mobile, $email);
```

list of gate:

| Name  | Bank  |
|---|---|
| zaringate | zarinpal choose bank |
| asan | asan pardackt |
| saman | saman |
| sadad | melli |
| parsian | parsian |
| fanava | fanava tech |

Full code is:
```
if($this->zarinpal->webgate($merchant_id , $amount, $desc, $call_back, $mobile, $email)){
    $authority = $this->zarinpal->getAuthority();
    // do database 
    $this->zarinpal->redirect();
}
else{
    $error = $this->zarinpal->getError();
}
```
For verify user payment:
```
$this->zarinpal->verify($merchant_id , $amount, $authority);
```
Full code is:
```
if($_GET['Status'] == 'OK'){
    if($this->zarinpal->verify($merchant_id , $amount, $authority)){
        $refid = $this->zarinpal->getRefId();
        // do database 
    }
    else{
        $error = $this->zarinpal->getError();
    }
}
else{
    //use cancel payment
}
```
## Sandbox
For test for script, you can turn on sandbox mode:
```
$this->zarinpal->sandbox();
```

## Contributor
- Mahdi Majidzadeh ([github](https://github.com/MahdiMajidzadeh))
