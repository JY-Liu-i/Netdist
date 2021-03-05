
<?php 
/**
* directoty
*/
class DirectoryDisplay
{
	private $current_dir;
	private $dir_name_list;
	private $did_list;
	private $file_name_list;
	private $file_size_list;
	private $file_time_list;

	private $fid_list;
	private $db;
	function __construct()
	{
		$this->current_dir = $_SESSION['current_dir'];
		$this->dir_name_list = array();
		$this->did_list = array();
		$this->file_name_list = array();
		$this->file_size_list = array();
		$this->fid_list = array();
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
		//设置中文字符集
		$result = $this->db->query ("set names gbk");
	}

	public function convert_file_size_list()
	{
		$tmp_list = array();
		foreach($this->file_size_list as $size){
			$tmp_size = (int)$size;
			if($tmp_size>=1024){
				$tmp_size/=1024;
			}
			else{
				$tmp_list[] = (string)round($tmp_size)."B";
				continue;
			}
			if($tmp_size>=1024){
				$tmp_size/=1024;
			}
			else{
				$tmp_list[] = (string)round($tmp_size)."K";
				continue;
			}
			if($tmp_size>=1024){
				$tmp_size/=1024;
			}
			else{
				$tmp_list[] = (string)round($tmp_size)."M";
				continue;
			}
			$tmp_list[] = (string)round($tmp_size)."G";
			
		}
		$this->file_size_list = $tmp_list;
	}

	public function get_dirs()
	{
		$sql = "select * from dir where pdid = '$this->current_dir'";
		$result = $this->db->query($sql);
		while($row=$result->fetch_assoc()){
        	$this->dir_name_list[]=$row["dname"];
        	$this->did_list[]=$row["did"];
        }
	}

	public function get_files()
	{
        $sql = "select * from user_file where fdir = '$this->current_dir'";
		$result = $this->db->query($sql);
		$i=0;
		$file_md5_list=array();
		while($row=$result->fetch_assoc()){
        	$this->file_name_list[] = $row["fname"];
        	$this->file_time_list[] = $row["mod_time"];
        	$this->fid_list[] = $row["fid"];
        	$file_md5_list[] = $row["md5"];
        }
        foreach($file_md5_list as $md5){
        	$sql = "select size from server_file where md5 = '$md5'";
        	$result = $this->db->query($sql);
        	$size = $result->fetch_assoc()['size'];
        	$this->file_size_list[]=$size;
        }

	}

	public function getPdid()
	{
		$sql = "select pdid from dir where did = '$this->current_dir'";
		$result = $this->db->query($sql);
		$pdid = $result->fetch_assoc()['pdid'];
		return $pdid;
	}

	public function display()
	{
		$this->get_dirs();
		$this->get_files();
		$this->convert_file_size_list();
		$file_num = count($this->file_name_list);
		$dir_num = count($this->dir_name_list);

		echo "<table frame='box'rules='rows' width='950px' align='center'>";
		
		require 'path.php';

		echo "<tr class='table_header'>";
		echo "<td height=40 width=30></td>";
		echo "<td height=40 width=270 align=left>文件名</td>";
		echo "<td height=40 width=50></td>";
		echo "<td height=40 width=50></td>";
		echo "<td height=40 width=50 align='center'>操作</td>";
		echo "<td height=40 width=50></td>";
		echo "<td height=40 width=50></td>";
		echo "<td height=40 width=200 align='center'>文件大小</td>";
		echo "<td height=40 width=200 align='center'>修改时间</td>";
		echo "<tr>";
		echo "<form action='handler.php' method='post' accept-charset='gbk' class='form-horizontal'>";
		if($this->getPdid()!="0"){
			echo "<tr>";
			echo "<td height=35 width=30><img src='public/img/dir.png' style='width:20px;height:20px;margin-right:5px;display:block'></td>";
			echo "<td height=35 width=270 align=left><input type='submit' name='switch_dir#@@' value='..' class='dir_text'/></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=200></td>";
			echo "<td height=35 width=200></td>";
		}
		
	  	echo "</tr>";
		for($i=0;$i<$dir_num;$i++){
			echo "<tr class='table_item'>";
			echo "<td height=35 width=30><img src='public/img/dir.png' style='width:20px;height:20px;margin-right:5px;display:block'></td>";
			echo "<td height=35 width=270 align=left><input type='submit' name='switch_dir#".$this->did_list[$i]."' value='".$this->dir_name_list[$i]."' class='dir_text'/></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=50></td>";
			echo "<td height=35 width=50><button type='submit' class='btn btn-default btn-xs' name='remove_dir#".$this->did_list[$i]."'><span class='glyphicon glyphicon-trash'></span> 删除</button></td>";
			echo "<td height=35 width=50><button type='button' class='btn btn-default btn-xs' data-toggle='modal' data-target='#rename_dir' onclick='rename_did_values(".$this->did_list[$i].")'><span class='glyphicon glyphicon-edit'></span> 改名</button></td>";
			echo "<td height=35 width=200></td>";
			echo "<td height=35 width=200></td>";
		  	echo "</tr>";
		}
		for($i=0;$i<$file_num;$i++){
			echo "<tr class='table_item'>";
			echo "<td height=35 width=30><img src='public/img/file.png' style='width:20px;height:20px;margin-right:5px;display:block'></td>";
			echo "<td width=270 align=left>".$this->file_name_list[$i]."</td>";
			echo "<td height=35 width=50><button type='submit' class='btn btn-default btn-xs' name='download_file#".$this->fid_list[$i]."'><span class='glyphicon glyphicon-cloud-download'></span> 下载</button></td>";
			echo "<td height=35 width=50><button type='submit' class='btn btn-default btn-xs' name='copy_file#".$this->fid_list[$i]."'><span class='glyphicon glyphicon-book'></span> 复制</button></td>";
			echo "<td height=35 width=50><button type='button' class='btn btn-default btn-xs' data-toggle='modal' data-target='#move_file' onclick='move_fid_values(".$this->fid_list[$i].")'><span class='glyphicon glyphicon-arrow-right'></span> 移动</button></td>";
			echo "<td height=35 width=50><button type='submit' class='btn btn-default btn-xs' name='remove_file#".$this->fid_list[$i]."'><span class='glyphicon glyphicon-trash'></span> 删除</button></td>";
			echo "<td height=35 width=50><button type='button' class='btn btn-default btn-xs' data-toggle='modal' data-target='#rename_file' onclick='rename_fid_values(".$this->fid_list[$i].")'><span class='glyphicon glyphicon-edit'></span> 改名</button></td>";
			echo "<td height=35 width=200>".$this->file_size_list[$i]."</td>";
			echo "<td height=35 width=200>".$this->file_time_list[$i]."</td>";
		  	echo "</tr>";
		}
		$sid = session_id();
		echo "<input type='hidden' name='session_id' value=".(string)$sid.">";
		echo "</form>";
		echo "</table>";
	}
}

$dir_dis = new DirectoryDisplay();
$dir_dis->display();

?>