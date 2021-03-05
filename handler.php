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
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('���ݿ�����ʧ��');
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
		echo "<script>alert('���Ƴɹ�');history.go(-1);</script>";
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
		//���Ҷ�Ӧ�ļ���md5��
		$sql = "select md5 from user_file where fid=$fid";
		$result = $this->db->query($sql);
		if(!$result->num_rows){
			return;
		}

		$row = $result->fetch_assoc();
		$md5 = $row['md5'];
		//ɾ��user_file�еĶ�Ӧ��
		$sql = "delete from user_file where fid = $fid";
		$result = $this->db->query($sql);
		//server_file.count--
		$sql = "select count from server_file where md5 = '$md5'";
		$result = $this->db->query($sql);
		if(!$result->num_rows){
			return;
		}
		$count = $result->fetch_assoc()['count'];
		//����ָ����ļ������ö���ɾ��
		if($count<=1){
			//ɾ��server_file��Ӧ����
			$sql = "delete from server_file where md5 = '$md5'";
			$result = $this->db->query($sql);
			//ɾ���ļ�
			if(!unlink("file/$md5")){
				echo "<script>alert('�ļ�file/".$md5."������');</script>";
			}
			else{
				//echo "<script>alert('�ļ�file/".$md5."��ɾ��');</script>";
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
			//������ǰĿ¼��idΪpid
			$sql = "select pdid from dir where did = $this->current_dir";
			$result = $this->db->query($sql);
			$pdid = $result->fetch_assoc()['pdid'];
			$_SESSION['current_dir'] = $pdid;
		}
		else{
			//������ǰĿ¼��idΪ��ѡ�е�id
			$_SESSION['current_dir'] = $did;
			echo $did;
		}
		
	}
}

$handler = new Handler();
$handler->handle();

?>