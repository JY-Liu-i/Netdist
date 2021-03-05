<?php
$file_name =$_POST["filename"];
$fid = $_POST["fid"];
var_dump($fid);
//连接数据库
require '../config.php';
$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
//设置中文字符集
$result = $db->query ("set names gbk");

//得到当前目录
$sql = "select fdir from user_file where fid=$fid";
$result = $db->query($sql);
$current_dir = $result->fetch_assoc()['fdir'];

//检查重名
$sql = "select * from user_file where fname = '$file_name' and fdir = $current_dir";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('本录下有同名文件！');location.href = '/';</script>";
    exit();
}
$sql = "select * from dir where dname = '$file_name' and pdid = $current_dir";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('本录下有同名文件夹！');location.href = '/';</script>";
    exit();
}

$sql = "update user_file set fname = '$file_name',mod_time=now() where fid = '$fid'";
echo $sql;
$result = $db->query($sql);

//关闭数据库
$db->close();


echo "<script>location.href = '/';</script>";
?>