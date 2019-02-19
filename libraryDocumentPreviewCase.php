<?php
include_once("includes/functions.php");

$url_data = $_REQUEST['data'];
if (isset($_REQUEST['id'])) {
    $id = $common->get_prep($_REQUEST['id']);
} else {
    header("location: caseLaw");
}
if (isset($_REQUEST['read'])) {
    $read = intval($common->get_prep($_REQUEST['read']));
} else {
    header("location: caselaw.view?id=".$id);
}

if (isset($_REQUEST['return'])) {
    $s = $common->get_prep($_REQUEST['return']);
}

$data = $caselaw->getOne($id);
$list = $caselaw_sections->getOne($read);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="0; url=<?php echo $url_data; ?>">
<title><?php echo $data['title']; ?></title>
<meta name="description" content="<?php echo $common->getLine($list['section_content']); ?>" />
<meta property="og:title" content="<?php echo $data['title']; ?>" />
<meta property="og:description" content="<?php echo $common->getLine($list['section_content']); ?>" />
<meta property="og:url" content="<?php echo $url_data; ?>" />

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="<?php echo $url_data; ?>">
<meta name="twitter:title" content="<?php echo $data['title']; ?>">
<meta name="twitter:description" content="<?php echo $common->getLine($list['section_content']); ?>">
</head>
</html>