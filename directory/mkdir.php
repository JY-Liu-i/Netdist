<?php 
/**
* mkdir
*/
class Mkdir
{
	private $current_dir;
	private $new_dname;
	private $db;
	function __construct()
	{
		$this->current_dir = $_POST['current_dir'];
		$this->new_dname = $_POST['dirname'];
		
		require '../config.php';
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('���ݿ������쳣');
		//���������ַ���
		$result = $this->db->query ("set names gbk");
	}

	function __destruct()
    {
        $this->db->close();
    }

	public function mkdir()
	{
		//�������
		$sql = "select * from dir where pdid = $this->current_dir and dname = '$this->new_dname'";
		$result = $this->db->query($sql);
		if($result->num_rows){
			echo "<script>alert('��¼����ͬ���ļ��У�');history.go(-1);</script>";
			exit();
		}
		$sql = "select * from user_file where fdir = $this->current_dir and fname = '$this->new_dname'";
		$result = $this->db->query($sql);
		if($result->num_rows){
			echo "<script>alert('��¼����ͬ���ļ���');history.go(-1);</script>";
			exit();
		}

		//��did
		$sql = "select max(did) as max from dir";
		$result = $this->db->query($sql);
		if($result->num_rows){
			$did = $result->fetch_assoc()['max']+1;		
		}
		else{
			$did = 1;
		}
		
		//
		$pdid = $this->current_dir;

		//��dpath
		$sql = "select dpath from dir where did = $this->current_dir";
		echo $sql;
		$result = $this->db->query($sql);
		$dpath = $result->fetch_assoc()['dpath'];
		$dpath.="-$did";
		echo $dpath;

		$sql = "insert into dir (did,dname,pdid,dpath) values($did,'".$this->new_dname."',$pdid,'$dpath')";
		$resutl = $this->db->query($sql);
		if ($result) {
			echo "<script>location.href = '/';</script>";
			exit();
		}else{
			echo $this->db->error;
			exit();
		}
	}
}

$ins = new Mkdir();
$ins->mkdir();