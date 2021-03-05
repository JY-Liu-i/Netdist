	<link rel="stylesheet" href="https://static.runoob.com/assets/js/jquery-treeview/jquery.treeview.css" />

	<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://static.runoob.com/assets/js/jquery-treeview/jquery.treeview.js" type="text/javascript"></script>

	<script type="text/javascript">
	$(document).ready(function(){
		$("#browser").treeview({
			toggle: function() {
				console.log("%s was toggled.", $(this).find(">span").text());
			}
		});

		$("#add").click(function() {
			var branches = $("<li><span class='folder'>New Sublist</span><ul>" +
				"<li><span class='file'>Item1</span></li>" +
				"<li><span class='file'>Item2</span></li></ul></li>").appendTo("#browser");
			$("#browser").treeview({
				add: branches
			});
		});
	});
	</script>
<?php
class InnerDirectoryDisplay
{
	private $current_user;
	private $db;
	function __construct()
	{
		$this->current_user = $_SESSION['user'];
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
		//设置中文字符集
		$result = $this->db->query ("set names gbk");
	}

	public function get_user_root_did()
	{
		$sql = "select udir from user where uname = '$this->current_user'";
		$result = $this->db->query($sql);;
		$udir = $result->fetch_assoc()['udir'];
		return $udir;
	}
	
	public function get_dirs($did)
	{
		$dir_name_list = array();
		$did_list = array();
		$sql = "select * from dir where pdid = '$did'";
		$result = $this->db->query($sql);
		while($row=$result->fetch_assoc()){
        	$dir_name_list[]=$row["dname"];
        	$did_list[]=$row["did"];
        }
        return array("dir_name_list"=>$dir_name_list,"did_list"=>$did_list);
	}

	public function get_files($did)
	{
		$file_name_list = array();
		$fid_list = array();
        $sql = "select * from user_file where fdir = '$did'";
		$result = $this->db->query($sql);
		while($row=$result->fetch_assoc()){
        	$file_name_list[] = $row["fname"];
        	$fid_list[] = $row["fid"];
        }
        return array("file_name_list"=>$file_name_list,"fid_list"=>$fid_list);
	}
	
	public function show_dir($dname,$did)
	{
		$result = $this->get_dirs($did);
		$dir_name_list = $result["dir_name_list"];
		$did_list = $result["did_list"];
		$dir_num = count($dir_name_list);
		
		$result = $this->get_files($did);
		$file_name_list = $result["file_name_list"];
		$fid_list = $result["fid_list"];
		$file_num = count($file_name_list);
		
		$i = 0;
		echo "<li><span class='folder'><input type='radio' name='did' value='$did'>$dname</span>";
		echo "<ul>";
		for($i=0;$i<$dir_num;$i++){
			$this->show_dir($dir_name_list[$i],$did_list[$i]);
		}
		for($i=0;$i<$file_num;$i++){
			$this->show_file($file_name_list[$i],$fid_list[$i]);
		}
        echo "</ul>";
        echo "</li>";
	}
	
	public function show_file($fname,$fid)
	{
		echo "<li><span class='file'>$fname</file></li>";
	}

	public function display()
	{	
		echo "<div align='left'>";
		echo "<ul id='browser' class='filetree treeview-famfamfam'>";
		$udir = $this->get_user_root_did();
		$this->show_dir($this->current_user,$udir);
		echo "</ul>";
		echo "</div>";
	}
}

$inner = new InnerDirectoryDisplay();
$inner->display();

?>