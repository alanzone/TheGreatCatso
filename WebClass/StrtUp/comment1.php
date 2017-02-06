<?php
02
// disabling possible warnings
03
if (version_compare(phpversion(), "5.3.0", ">=")  == 1)
04
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
05
else
06
error_reporting(E_ALL & ~E_NOTICE);
07
require_once('classes/CMySQL.php'); // including service class to work with database
08
$iItemId = (int)$_POST['id']; // obtaining necessary information
09
$sIp = getVisitorIP();
10
$sName = $GLOBALS['MySQL']->escape(strip_tags($_POST['name']));
11
$sText = $GLOBALS['MySQL']->escape(strip_tags($_POST['text']));
12
if ($sName && $sText) {
13
// checking - are you posted any comment recently or not?
14
$iOldId = $GLOBALS['MySQL']->getOne("SELECT `c_item_id` FROM `s163_items_cmts` WHERE `c_item_id` = '{$iItemId}' AND `c_ip` = '{$sIp}' AND `c_when` >= UNIX_TIMESTAMP() - 600 LIMIT 1");
15
if (! $iOldId) {
16
// if all fine - allow to add comment
17
$GLOBALS['MySQL']->res("INSERT INTO `s163_items_cmts` SET `c_item_id` = '{$iItemId}', `c_ip` = '{$sIp}', `c_when` = UNIX_TIMESTAMP(), `c_name` = '{$sName}', `c_text` = '{$sText}'");
18
$GLOBALS['MySQL']->res("UPDATE `s163_items` SET `comments_count` = `comments_count` + 1 WHERE `id` = '{$iItemId}'");
19
// and printing out last 5 comments
20
$sOut = '';
21
$aComments = $GLOBALS['MySQL']->getAll("SELECT * FROM `s163_items_cmts` WHERE `c_item_id` = '{$iItemId}' ORDER BY `c_when` DESC LIMIT 5");
22
foreach ($aComments as $i => $aCmtsInfo) {
23
$sWhen = date('F j, Y H:i', $aCmtsInfo['c_when']);
24
$sOut .= <<<EOF
25
<div class="comment" id="{$aCmtsInfo['c_id']}">
26
<p>Comment from {$aCmtsInfo['c_name']} <span>({$sWhen})</span>:</p>
27
<p>{$aCmtsInfo['c_text']}</p>
28
</div>
29
EOF;
30
}
31
echo $sOut;
32
exit;
33
}
34
}
35
echo 1;
36
exit;
37
function getVisitorIP() {
38
$ip = "0.0.0.0";
39
if( ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) && ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) {
40
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
41
} elseif( ( isset( $_SERVER['HTTP_CLIENT_IP'])) && (!empty($_SERVER['HTTP_CLIENT_IP'] ) ) ) {
42
$ip = explode(".",$_SERVER['HTTP_CLIENT_IP']);
43
$ip = $ip[3].".".$ip[2].".".$ip[1].".".$ip[0];
44
} elseif((!isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) || (empty($_SERVER['HTTP_X_FORWARDED_FOR']))) {
45
if ((!isset( $_SERVER['HTTP_CLIENT_IP'])) && (empty($_SERVER['HTTP_CLIENT_IP']))) {
46
$ip = $_SERVER['REMOTE_ADDR'];
47
}
48
}
49
return $ip;
50
}
51
?>
