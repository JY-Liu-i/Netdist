<?php
$new_dir_name =$_POST["dirname"];
$did = $_POST["did"];

//�������ݿ�
require '../config.php';
$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('���ݿ������쳣');
//���������ַ���
$result = $db->query ("set names gbk");

//�õ���ǰĿ¼
$sql = "select pdid from dir where did=$did";
$result = $db->query($sql);
$current_dir = $result->fetch_assoc()['fdir'];

//�������
$sql = "select * from user_file where fname = '$new_dir_name' and fdir = $current_dir";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('��Ŀ¼����ͬ���ļ���');location.href = '/';</script>";
    exit();
}
$sql = "select * from dir where dname = '$new_dir_name' and pdid = $current_dir";
$result = $db->query($sql);
if($result->num_rows){
    echo "<script>alert('��Ŀ¼����ͬ���ļ��У�');location.href = '/';</script>";
    exit();
}

$sql = "update dir set dname = '$new_dir_name' where did = '$did'";
$result = $db->query($sql);

//�ر����ݿ�
$db->close();

echo "<script>location.href = '/';</script>";
?>