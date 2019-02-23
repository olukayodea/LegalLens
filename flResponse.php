<?php
    include_once("includes/functions.php");
    $response = json_decode($_REQUEST['response'], true);

    if (($response['status'] == "successful") && ($response['chargeResponseCode'] == "00")) {
        header("location: flConfirm?txRef=".$response['txRef']);
    } else {
        header("location: managesubscription?error=".$response['message']);
    }
?>