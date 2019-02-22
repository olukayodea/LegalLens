<?php
include_once("includes/functions.php");

$url_data = $_REQUEST['data'];
if (isset($_REQUEST['id'])) {
    $id = $common->get_prep($_REQUEST['id']);
    $read = $common->get_prep($_REQUEST['read']);
} else {
    header("location: regulations");
}

if (isset($_REQUEST['return'])) {
    $s = $common->get_prep($_REQUEST['return']);
}
$data = $regulations->getOne($id);
$list_one = $regulations_sections->getOne($read);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="0; url=<?php echo $url_data; ?>">
<title><?php echo $data['title']; ?></title>
<meta name="description" content="<?php echo nl2br($list_one['section_content']); ?>" />
<meta property="og:title" content="<?php echo $data['title']; ?>" />
<meta property="og:description" content="<?php echo nl2br($list_one['section_content']); ?>" />
<meta property="og:url" content="<?php echo $url_data; ?>" />

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="<?php echo $url_data; ?>">
<meta name="twitter:title" content="<?php echo $data['title']; ?>">
<meta name="twitter:description" content="<?php echo nl2br($list_one['section_content']); ?>">
</head>
</html>