<?php
	session_id($_GET['sid']);
	session_start();
	if(isset($_SESSION['copy_file_id'])){
		require '../config.php';
		$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('���ݿ������쳣');
		//���������ַ���
		$result = $db->query ("set names gbk");

		$fid = $_SESSION['copy_file_id'];
		$new_fdir = $_SESSION['current_dir'];
		$current_dir = $_SESSION['current_dir'];
		
		//��鵱ǰ�Ƿ��Ѿ��и��Ƶ��ļ�
		if(!isset($_SESSION['copy_file_id'])){
			echo "<script>alert('û�и��Ƶ��ļ���');history.go(-1);</script>";
            exit();
		}

		//�õ�ԭ�ļ���fname,md5
		$sql = "select fname,md5 from user_file where fid = $fid";
		$result = $db->query($sql);
		$row = $result->fetch_assoc();
		$fname = $row['fname'];
		$md5 = $row['md5'];

		//ԭ�ļ�������
		if(!$result->num_rows){
			$db->close();
			echo "<script>location.href = '/';</script>";
		}

		//�������
		$sql = "select fid,md5 from user_file where fname = '$fname' and fdir = $current_dir";
        $result = $db->query($sql);
        if($result->num_rows){
        	$row = $result->fetch_assoc();
        	$same_name_fid = $row['fid'];
        	$same_name_md5 = $row['md5'];
        	//��ͬ�ļ�ͬ��
        	if($md5!=$same_name_md5){
	            echo "<script>alert('��¼����ͬ���ļ���');history.go(-1);</script>";
	            exit();
	        }
	        //��ͬ�ļ�ͬ������������
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
            echo "<script>alert('��¼����ͬ���ļ��У�');history.go(-1);</script>";
            exit();
        }

		//�õ����ļ���fid
		$sql = "select max(fid) as max from user_file";
		$result = $db->query($sql);
		if(!$result->num_rows){
			$new_fid  = 1;
		}
		else{
			$new_fid = $result->fetch_assoc()['max']+1;
		}
		
		//�����û��ļ���
		$sql = "insert into user_file(fid,fname,fdir,md5,create_time,mod_time,access_time) values($new_fid,'$fname','$new_fdir','$md5',now(),now(),now())";
		$result = $db->query($sql);

		//ָ��serverfile������++
        $sql = "update server_file set count = count+1 where md5 = '$md5'";
        $result = $db->query($sql);

		//�ر����ݿ�
		$db->close();

		echo "<script>location.href = '/';</script>";
	}
?>