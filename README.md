# CodeIgniter-Zarinpal-getway
Codeigniter library for Iranian getway, [Zarinpal](https://www.zarinpal.com/)

##how to install
Copy `Zarinpal.php` and `nusoap.php` to `application/libraries` of your own project.

##how to use
For sending user to getway:
```
$this->zarinpal->request($merchant_id , $amount, $desc, $call_back, $mobile, $email);
```
full code is:
```
if($this->zarinpal->request($merchant_id , $amount, $desc, $call_back, $mobile, $email)){
    $authority = $this->zarinpal->get_authority();
    // do database 
    $this->zarinpal->redirect();
}
else{
    $error = $this->zarinpal->get_error();
}
```
For verify user payment:
```
$this->zarinpal->verify($merchant_id , $amount, $authority);
```
full code is:
```
if($_GET['Status'] == 'OK'){
    if($this->zarinpal->verify($merchant_id , $amount, $authority)){
        $refid = $this->zarinpal->get_ref_id();
        // do database 
    }
    else{
        $error = $this->zarinpal->get_error();
    }
}
else{
    //use cancel payment
}
```

##Contributor
- Mahdi Majidzadeh ([github](https://github.com/MahdiMajidzadeh))
