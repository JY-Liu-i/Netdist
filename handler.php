<?php
require 'config.php';
class Handler
{
	private $db;
	private $current_dir;
	private $submit_name;
	private $submit_value;
	function __construct()
	{
		session_id($_POST['session_id']);
		session_start();

		$this->current_dir = $_SESSION['current_dir'];
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接失败');
		$result = $this->db->query ("set names gbk");

		foreach($_POST as $key=>$value){
			if($key!='current_dir' and $key!='session_id'){
				$this->submit_name = $key;
				$this->submit_value = $value;
			}
		}

	}

	function __destruct()
    {
        $this->db->close();
    }

	public function handle()
	{
		$op = strstr($this->submit_name, '#',true);
		$id = strstr($this->submit_name, '#');
		$id = substr($id, 1);
		switch ($op) {
			case 'switch_dir':
				$this->switch_dir($id);
				break;
			case 'remove_dir':
				$this->remove_dir($id);
				break;
			case 'copy_file':
				$this->copy_file($id);
				break;
			case 'remove_file':
				$this->remove_file($id);
				break;
			case 'download_file':
				$this->download_file($id);
				break;
			default:
				# code...
				break;
		}
		echo "<script>history.go(-1);</script>";
	}

	public function copy_file($fid)
	{
		$_SESSION["copy_file_id"] = $fid;
		echo "<script>alert('复制成功');history.go(-1);</script>";
	}

	
	public function remove_dir($did)
	{
		$sql = "select fid from user_file where fdir = $did";
		$result = $this->db->query($sql);
		while($row=$result->fetch_assoc()){
			$this->remove_file($row['fid']);
		}
		
		$sql = "select did from dir where pdid = $did";
		$result = $this->db->query($sql);
		while($row=$result->fetch_assoc()){
			$this->remove_dir($row['did']);
		}
		
		$sql = "delete from dir where did = $did";
		$result = $this->db->query($sql);
	}
	
	public function download_file($fid)
	{
		$sql = "select fname,md5 from user_file where fid=$fid";
		$result = $this->db->query($sql);
		$row=$result->fetch_assoc();
		$fname = $row['fname'];
		$md5 = $row['md5'];
		$fpath = "file/".$md5;
		header("Location:directory/download_file.php?fname=$fname&fpath=$fpath");
	}
	
	public function remove_file($fid)
	{
		//查找对应文件的md5码
		$sql = "select md5 from user_file where fid=$fid";
		$result = $this->db->query($sql);
		if(!$result->num_rows){
			return;
		}

		$row = $result->fetch_assoc();
		$md5 = $row['md5'];
		//删除user_file中的对应项
		$sql = "delete from user_file where fid = $fid";
		$result = $this->db->query($sql);
		//server_file.count--
		$sql = "select count from server_file where md5 = '$md5'";
		$result = $this->db->query($sql);
		if(!$result->num_rows){
			return;
		}
		$count = $result->fetch_assoc()['count'];
		//所有指向该文件的引用都被删除
		if($count<=1){
			//删除server_file对应的项
			$sql = "delete from server_file where md5 = '$md5'";
			$result = $this->db->query($sql);
			//删除文件
			if(!unlink("file/$md5")){
				echo "<script>alert('文件file/".$md5."不存在');</script>";
			}
			else{
				//echo "<script>alert('文件file/".$md5."已删除');</script>";
			}
		}
		else{
			//serverfile.count--
            $sql = "update server_file set count = count-1 where md5 = '$md5'";
            $result = $this->db->query($sql);
		}
	}

	public function switch_dir($did)
	{
		if($did=='@@'){
			//更换当前目录的id为pid
			$sql = "select pdid from dir where did = $this->current_dir";
			$result = $this->db->query($sql);
			$pdid = $result->fetch_assoc()['pdid'];
			$_SESSION['current_dir'] = $pdid;
		}
		else{
			//更换当前目录的id为新选中的id
			$_SESSION['current_dir'] = $did;
			echo $did;
		}
		
	}
}

$handler = new Handler();
$handler->handle();

?>