<?php
$new_dir_name =$_POST["dirname"];
$did = $_POST["did"];

//连接数据库
require '../config.php';
$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
//设置中文字符集
$result = $db->query ("set names gbk");

//得到当前目录
$sql = "select pdid from dir where did=$did";
$result = $db->query($sql);
$current_dir = $result->fetch_assoc()['fdir'];

//检查重名
$sql = "select * from user_file where fname = '$new_dir_name' and fdir = $current_dir";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('本目录下有同名文件！');location.href = '/';</script>";
    exit();
}
$sql = "select * from dir where dname = '$new_dir_name' and pdid = $current_dir";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('本目录下有同名文件夹！');location.href = '/';</script>";
    exit();
}

$sql = "update dir set dname = '$new_dir_name' where did = '$did'";
$result = $db->query($sql);

//关闭数据库
$db->close();

echo "<script>location.href = '/';</script>";
?>