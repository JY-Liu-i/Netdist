<?php
$fid = $_POST["fid"];
$dest_did = $_POST["did"];
var_dump($fid);
var_dump($dest_did);
//�������ݿ�
require '../config.php';
$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('���ݿ������쳣');
//���������ַ���
$result = $db->query ("set names gbk");

//�õ������ļ�������
$sql = "select fname from user_file where fid = $fid";
$result = $db->query($sql);
$fname = $result->fetch_assoc()['fname'];

//�������
$sql = "select * from user_file where fdir = $dest_did and fname = '$fname'";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('ѡ���Ŀ¼����ͬ���ļ���');location.href = '/';</script>";
    exit();
}

$sql = "select * from dir where dname = '$fname' and pdid = $dest_did";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('��¼����ͬ���ļ��У�');location.href = '/';</script>";
    exit();
}

$sql = "update user_file set fdir = $dest_did , mod_time=now() where fid = $fid";
$result = $db->query($sql);

//�ر����ݿ�
$db->close();

echo "<script>location.href = '/';</script>";
?>