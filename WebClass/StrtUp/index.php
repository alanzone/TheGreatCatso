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
$sCode = '';
09
$iItemId = (int)$_GET['id'];
10
if ($iItemId) { // View item output
11
$aItemInfo = $GLOBALS['MySQL']->getRow("SELECT * FROM `s163_items` WHERE `id` = '{$iItemId}'"); // getting info about item from database
12
$sCode .= '<h1>'.$aItemInfo['title'].'</h1>';
13
$sCode .= '<h3>'.date('F j, Y', $aItemInfo['when']).'</h3>';
14
$sCode .= '<h2>Description:</h2>';
15
$sCode .= '<h3>'.$aItemInfo['description'].'</h3>';
16
$sCode .= '<h3><a href="'.$_SERVER['PHP_SELF'].'">back</a></h3>';
17
// drawing last 5 comments
18
$sComments = '';
19
$aComments = $GLOBALS['MySQL']->getAll("SELECT * FROM `s163_items_cmts` WHERE `c_item_id` = '{$iItemId}' ORDER BY `c_when` DESC LIMIT 5");
20
foreach ($aComments as $i => $aCmtsInfo) {
21
$sWhen = date('F j, Y H:i', $aCmtsInfo['c_when']);
22
$sComments .= <<<EOF
23
<div class="comment" id="{$aCmtsInfo['c_id']}">
24
<p>Comment from {$aCmtsInfo['c_name']} <span>({$sWhen})</span>:</p>
25
<p>{$aCmtsInfo['c_text']}</p>
26
</div>
27
EOF;
28
}
29
ob_start();
30
?>
31
<div class="container" id="comments">
32
<h2>Comments</h2>
33
<script type="text/javascript">
34
function submitComment(e) {
35
var sName = $('#name').val();
36
var sText = $('#text').val();
37
if (sName && sText) {
38
$.post('comment.php', { name: sName, text: sText, id: <?= $iItemId ?> },
39
function(data){
40
if (data != '1') {
41
$('#comments_list').fadeOut(1000, function () {
42
$(this).html(data);
43
$(this).fadeIn(1000);
44
});
45
} else {
46
$('#comments_warning2').fadeIn(1000, function () {
47
$(this).fadeOut(1000);
48
});
49
}
50
}
51
);
52
} else {
53
$('#comments_warning1').fadeIn(1000, function () {
54
$(this).fadeOut(1000);
55
});
56
}
57
};
58
</script>
59
<div id="comments_warning1" style="display:none">Don`t forget to fill both fields (Name and Comment)</div>
60
<div id="comments_warning2" style="display:none">You can post no more than one comment every 10 minutes (spam protection)</div>
61
<form onsubmit="submitComment(this); return false;">
62
<table>
63
<tr><td class="label"><label>Your name: </label></td><td class="field"><input type="text" value="" title="Please enter your name" id="name" /></td></tr>
64
<tr><td class="label"><label>Comment: </label></td><td class="field"><textarea name="text" id="text"></textarea></td></tr>
65
<tr><td class="label">&nbsp;</td><td class="field"><input type="submit" value="Post comment" /></td></tr>
66
</table>
67
</form>
68
<div id="comments_list"><?= $sComments ?></div>
69
</div>
70
<?
71
$sCommentsBlock = ob_get_clean();
72
} else {
73
$sCode .= '<h1>List of items:</h1>';
74
$aItems = $GLOBALS['MySQL']->getAll("SELECT * FROM `s163_items` ORDER by `when` ASC"); // taking info about all items from database
75
foreach ($aItems as $i => $aItemInfo) {
76
$sCode .= '<h2><a href="'.$_SERVER['PHP_SELF'].'?id='.$aItemInfo['id'].'">'.$aItemInfo['title'].' item</a></h2>';
77
}
78
}
79
?>
80
<!DOCTYPE html>
81
<html lang="en" >
82
<head>
83
<meta charset="utf-8" />
84
<title>Creating own commenting system | Script Tutorials</title>
85
<link href="css/main.css" rel="stylesheet" type="text/css" />
86
<script type="text/javascript" src="js/jquery-1.5.2.min.js"></script>
87
</head>
88
<body>
89
<div class="container">
90
<?= $sCode ?>
91
</div>
92
<?= $sCommentsBlock ?>
93
<footer>
94
<h2>Creating own commenting system</h2>
95
<a href="https://www.script-tutorials.com/how-to-create-own-commenting-system/" class="stuts">Back to original tutorial on <span>Script Tutorials</span></a>
96
</footer>
97
</body>
98
</html>
