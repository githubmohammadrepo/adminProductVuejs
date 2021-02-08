<?php
$post = [
    'mobile' => $_POST["form"]["brandmobile"],
];
$url = "http://fishopping.ir/serverHypernetShowUnion/checkMobilebrand.php";
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url);
curl_setopt($ch1, CURLOPT_POST, 1);
curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($post));
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch1);
curl_close($ch1);
if ($content == "exist") {
    $invalid[] = RSFormProHelper::getComponentId("brandmobile");
} else {
    $brandname = $_POST['form']["brandname"];
    $brandconame = $_POST['form']["brandconame"];
    $brandbranchname = $_POST['form']["brandbranchname"];
    $brandcophone = $_POST['form']["brandcophone"];
    $brandmobile = $_POST['form']["brandmobile"];
    $brandfax = $_POST['form']["brandfax"];
    $brandemail = $_POST['form']["brandemail"];
    $branusername = '';
    $brandpass = '';
    $idusername = $_SESSION["idusername"];
    $marketerid = isset($_SESSION["marketerId"]) ? $_SESSION["marketerId"] : '';
    $brandSelectedId = $_POST['form']['brandIdSelected'][0];

    $url = "http://fishopping.ir/serverHypernetShowUnion/RegisterBrandFirstInfos(changed).php";

    $post = [
        'brandname' => $_POST['form']["brandname"],
        'brandconame' => $_POST['form']["brandconame"],
        'brandbranchname' => $_POST['form']["brandbranchname"],
        'brandcophone' => $_POST['form']["brandcophone"],
        'brandmobile' => $_POST['form']["brandmobile"],
        'brandfax' => $_POST['form']["brandfax"],
        'brandemail' => $_POST['form']["brandemail"],
        'branusername' => '',
        'brandpass' => '',
        'idusername' => $_SESSION["idusername"],
        'marketerid' => isset($_SESSION["marketerId"]) ? $_SESSION["marketerId"] : '',
        'brandSelectedId' => $_POST['form']['brandIdSelected'][0]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
    }
    curl_close($ch);
    $contents = json_decode($output, true);
    $_SESSION["brandSelectedId"] = $_POST['form']['brandIdSelected'][0];
}