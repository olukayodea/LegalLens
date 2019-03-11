<?php
    include_once("includes/functions.php");
    $response = json_decode($_REQUEST['response'], true);

    if (isset($_GET['mobile'])) {
        $mobile = "mobile_";
        $subscriptions_url = "mobile_subscription";
        $url = "&mobile";
    } else {
        $mobile = "";
        $subscriptions_url = "managesubscription";
        $url = "";
    }

    if (($response['status'] == "successful") && ($response['chargeResponseCode'] == "00")) {
        header("location: ".URL."flConfirm?txRef=".$response['txRef'].$url);
    } else {
        header("location: ".URL.$subscriptions_url."?error=".$response['message']);
    }
?>