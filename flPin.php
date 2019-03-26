<?php
    include_once("includes/functions.php");
    $response = $_REQUEST['data'];

    if (isset($_GET['mobile'])) {
        $mobile = "mobile_";
        $subscriptions_url = "mobile_subscription";
        $url = "&mobile";
    } else {
        $mobile = "";
        $subscriptions_url = "managesubscription";
        $url = "";
    }

    if (isset($_POST['submit2'])) {
        $postdata = array(
            'PBFPubKey' => PBFPubKey,
            'transaction_reference' => $_POST['flwRef'],
            'otp' => $_POST['otp']);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, flValidateCharge);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        
        $headers = array('Content-Type: application/json');
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/includes/classes/cacert.pem");
        
        $request = curl_exec($ch);
        $result = json_decode($request, true);

        if (($result['status'] == "success") && ($result['data']['data']['responsecode'] == "00")) {
            header("location: flConfirm?txRef=".$result['data']['tx']['txRef'].$url);
        } else {
            $error = "An error occured ".$result['message'];
        }

    } else if (isset($_POST['submit'])) {
        $postData = json_decode(base64_decode($_POST['post']), true);
        $postData['pin'] = $_POST['pin'];
        $postData['suggested_auth'] = "PIN";
        $trannsData = $transactions->postTransaction($postData);

        $result = json_decode($trannsData, true);
        if (($result['status'] == "success") && ($result['data']['chargeResponseCode'] == "02")) {
            if ($result['data']['authModelUsed'] == "PIN") {
                header("location: ?otp&flwRef=".$result['data']['flwRef']."&data=".$_POST['post']."&msg=".$result['data']['chargeResponseMessage'].$url);
            } else if ($result['data']['authModelUsed'] == "VBVSECURECODE") {
                header("location: ".$result['data']['authurl']);
            } 
        } else {
            header("location: ".$subscriptions_url."?error=".$result['message']);
        }
    }
?>
<!doctype html>
<head>
<meta charset="UTF-8">

<!-- META TAGS -->
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="LegalLens offers fast legal research, Agreement Templates, Nigerian laws, Law dictionary & more">

<title>Validate Payment</title>

<link rel="shortcut icon" href="images/favicon.png" />
<style type="text/css">
    body{
        padding-top:45px;
    }
    form{
        width: 320px;
        margin:auto;
        padding:50px 25px 25px 25px;
        font-family: Tahoma;
        border: 1px solid #ccc;
    }

    .info-text{
        text-align: center;
    }
    span{
            color: #435fbd;
    text-decoration: underline;
    text-decoration-color: black;
    font-size: 12px;
    }

    #otp{
        width: 100%;
        outline: none;
        padding:25px 10px;
        font-size: 25px;
        letter-spacing: 33px;
    }

    #mocksubmit{
        padding: 15px;
    }
    </style>
</head>

<body>
<form name="form1" method="post" action="">
  <?php if (isset($error)) { ?>
    <p style="color:#F00" class="info-text"><?php echo $error; ?></p>
  <?php }?>
  <?php if (isset($_REQUEST['otp'])) { ?>
  <p class="info-text"><?php echo $_REQUEST['msg']; ?></p>
  <input type="text" name="otp" id="otp">
  <input name="flwRef" type="hidden" value="<?php echo $_REQUEST['flwRef']; ?>">
  <input name="post" type="hidden" value="<?php echo $response; ?>">
  <p style="text-align:center">
  <input type="submit" name="submit2" id="submit2" value="Submit">
  <input type="button" name="button2" id="button2" value="Cancel" onClick="location='managesubscription';">
  </p>
  <?php } else { ?>
  <p class="info-text">Enter Card PIN</p>
  <input type="text" name="pin" id="otp">
  <input name="post" type="hidden" value="<?php echo $response; ?>">
  <p style="text-align:center">
  <input type="submit" name="submit" id="submit" value="Submit">
  <input type="button" name="button" id="button" value="Cancel" onClick="location='managesubscription';">
  </p>
  <?php } ?>
</form>
</body>
</html>