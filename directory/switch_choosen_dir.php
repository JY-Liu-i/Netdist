<?php
session_id($_POST['session_id']);
session_start();

//连接数据库
require '../config.php';
$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
//设置中文字符集
$result = $db->query ("set names gbk");

$old_dir = $_SESSION['choosen_dir'];
$choosen_dir_name = $_POST['switch_dir'];
if($choosen_dir_name==".."){
	$sql = "select pdid from dir where did = $old_dir";
	$result = $db->query($sql);
	$new_dir = $result->fetch_assoc()['pdid'];
}
else{
	$sql = "select did from dir where pdid = $old_dir and dname = '$choosen_dir_name'";
	$result = $db->query($sql);
	$new_dir = $result->fetch_assoc()['did'];
}
$_SESSION['choosen_dir'] = $new_dir;
echo "<script>history.go(-1);</script>";

?>