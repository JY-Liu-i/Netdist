<?php
	session_id($_GET['sid']);
	session_start();
	if(isset($_SESSION['copy_file_id'])){
		require '../config.php';
		$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
		//设置中文字符集
		$result = $db->query ("set names gbk");

		$fid = $_SESSION['copy_file_id'];
		$new_fdir = $_SESSION['current_dir'];
		$current_dir = $_SESSION['current_dir'];
		
		//检查当前是否已经有复制的文件
		if(!isset($_SESSION['copy_file_id'])){
			echo "<script>alert('没有复制的文件！');history.go(-1);</script>";
            exit();
		}

		//得到原文件的fname,md5
		$sql = "select fname,md5 from user_file where fid = $fid";
		$result = $db->query($sql);
		$row = $result->fetch_assoc();
		$fname = $row['fname'];
		$md5 = $row['md5'];

		//原文件不存在
		if(!$result->num_rows){
			$db->close();
			echo "<script>location.href = '/';</script>";
		}

		//检查重名
		$sql = "select fid,md5 from user_file where fname = '$fname' and fdir = $current_dir";
        $result = $db->query($sql);
        if($result->num_rows){
        	$row = $result->fetch_assoc();
        	$same_name_fid = $row['fid'];
        	$same_name_md5 = $row['md5'];
        	//不同文件同名
        	if($md5!=$same_name_md5){
	            echo "<script>alert('本录下有同名文件！');history.go(-1);</script>";
	            exit();
	        }
	        //相同文件同名，创建副本
	        else{
	        	$exist_same_name=true;
	        	$i = 1;
	        	$fname = $fname."($i)";
	        	while($exist_same_name){
	        		$fname = substr($fname,0,-strlen((string)($i-1))-2);
	        		$fname = $fname."($i)";
	        		$sql = "select *from user_file where fname='$fname' and fdir = $current_dir";
	        		$result = $db->query($sql);
	        		if($result->num_rows){
	        			$exist_same_name = true;
	        		}
	        		else{
	        			$exist_same_name = false;
	        		}
	        		$i++;
	        	}
	        }
        }
        $sql = "select * from dir where dname = '$fname' and pdid = $current_dir";
        $result = $db->query($sql);
        if($result->num_rows){
            echo "<script>alert('本录下有同名文件夹！');history.go(-1);</script>";
            exit();
        }

		//得到新文件的fid
		$sql = "select max(fid) as max from user_file";
		$result = $db->query($sql);
		if(!$result->num_rows){
			$new_fid  = 1;
		}
		else{
			$new_fid = $result->fetch_assoc()['max']+1;
		}
		
		//插入用户文件项
		$sql = "insert into user_file(fid,fname,fdir,md5,create_time,mod_time,access_time) values($new_fid,'$fname','$new_fdir','$md5',now(),now(),now())";
		$result = $db->query($sql);

		//指向serverfile的引用++
        $sql = "update server_file set count = count+1 where md5 = '$md5'";
        $result = $db->query($sql);

		//关闭数据库
		$db->close();

		echo "<script>location.href = '/';</script>";
	}
?>