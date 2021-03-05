<?php
$fname = $_GET['fname'];
$fpath = "../".$_GET['fpath'];
header("Content-type: text/html;charset=gbk");
header("content-disposition:attachment;filename=$fname");
header('content-length:'.filesize($fpath));
readfile($fpath);
?>