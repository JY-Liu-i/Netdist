<?php
class PathDisplay
{
	private $current_dir;
	private $dir_name_list;
	private $did_list;
	private $db;
	function __construct()
	{
		$this->current_dir = $_SESSION['current_dir'];
		$this->dir_name_list = array();
		$this->did_list = array();
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
		//设置中文字符集
		$result = $this->db->query ("set names gbk");
	}

	public function getPathList()
	{
		$this->dir_name_list = array();
		$this->did_list = array();
		$did = $this->current_dir;
		while($did != "0"){
			$sql = "select dname,pdid from dir where did=$did";
			$result = $this->db->query($sql);
			$row = $result->fetch_assoc();
			$dname = $row['dname'];
			$pdid = $row['pdid'];
			$this->dir_name_list[] = $dname;
			$this->did_list[] = $did;
			$did = $pdid;
		}
		$this->dir_name_list = array_reverse($this->dir_name_list);
		$this->did_list = array_reverse($this->did_list);
	}

	public function showPath()
	{
		$this->getPathList();
		$num = count($this->did_list);
		echo "<form action='handler.php' method='post' accept-charset='gbk' class='form-horizontal'>";
		echo "<tr class='table_path'>";
		echo "<td height=40 width=100>当前路径</td>";
		echo "<td height=40 width=850 align='left' colspan='8'>";
		for($i=0;$i<$num;$i++){
			echo "<input type='submit' name='switch_dir#".$this->did_list[$i]."' value='".$this->dir_name_list[$i]."' class='path_text'/>";
			if($i!=$num-1){
				echo "/";
			}
		}
		echo "</td>";
		echo "</tr>";
		$sid = session_id();
		echo "<input type='hidden' name='session_id' value=".(string)$sid.">";
		echo "</form>";
	}
}
$path = new PathDisplay();
$path->showPath();

?>