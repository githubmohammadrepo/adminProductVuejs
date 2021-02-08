<?php

//<code>
$url_1 = "http://fishopping.ir/serverHypernetShowUnion/GetPriceRegisterBrand.php";
$ch1 = curl_init();
curl_setopt ($ch1, CURLOPT_URL, $url_1);
curl_setopt ($ch1, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch1);
if (curl_errno($ch1)) {
echo curl_error($ch1);
echo "\n<br />";
$price = '0';
} else {
curl_close($ch1);
}

$price = json_decode($content, true);
return "برای عضویت شرکت باید مبلغ {$price} تومان را پرداخت نمایید.";
//</code>