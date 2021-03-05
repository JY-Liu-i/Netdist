<?php
$fid = $_POST["fid"];
$dest_did = $_POST["did"];
var_dump($fid);
var_dump($dest_did);
//连接数据库
require '../config.php';
$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
//设置中文字符集
$result = $db->query ("set names gbk");

//得到传入文件的名字
$sql = "select fname from user_file where fid = $fid";
$result = $db->query($sql);
$fname = $result->fetch_assoc()['fname'];

//检查重名
$sql = "select * from user_file where fdir = $dest_did and fname = '$fname'";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('选择的目录下有同名文件！');location.href = '/';</script>";
    exit();
}

$sql = "select * from dir where dname = '$fname' and pdid = $dest_did";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('本录下有同名文件夹！');location.href = '/';</script>";
    exit();
}

$sql = "update user_file set fdir = $dest_did , mod_time=now() where fid = $fid";
$result = $db->query($sql);

//关闭数据库
$db->close();

echo "<script>location.href = '/';</script>";
?>