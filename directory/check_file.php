<?php
header('Content-type:text/json;charset=gbk');

class CheckFile
{
	private $db;
	private $md5;
	private $fname;
	private $current_dir;
	
	function __construct()
    {
    	$this->md5 = $_POST['md5'];
    	$this->fname = $_POST['fname'];
    	$this->current_dir = $_POST['current_dir'];
    	$this->fname = iconv("UTF-8", "GBK", $this->fname);
        require '../config.php';
        $this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
        //设置中文字符集
        $result = $this->db->query ("set names gbk");
    }

    function __destruct()
    {
        $this->db->close();
    }
    
    public function checkFileName()
    {
        $sql = "select * from user_file where fname = '$this->fname' and fdir = $this->current_dir";
        $result = $this->db->query($sql);
        if($result->num_rows){
            echo "same file";
            exit();
        }
        $sql = "select * from dir where dname = '$this->fname' and pdid = $this->current_dir";
        $result = $this->db->query($sql);
        if($result->num_rows){
            echo "same dir";
            exit();
        }
    }
    
    public function checkMd5()
    {
    	$sql = "select complete from server_file where md5 = '$this->md5'";
        $result = $this->db->query($sql);
        if(!$result->num_rows){
        	echo 'file not exist';
        	exit();
        }
        else{
        	$complete = $result->fetch_assoc()['complete'];
        	if($complete){
        		//指向serverfile的引用++
                $sql = "update server_file set count = count+1 where md5 = '$this->md5'";
                $result = $this->db->query($sql);
                //插入user_file项
                $sql = "select max(fid) as max from user_file";
                $fid = $this->db->query($sql)->fetch_assoc()['max']+1;
                $sql = "insert into user_file(fid,fname,fdir,md5,create_time,mod_time,access_time) values($fid,'$this->fname',$this->current_dir,'$this->md5',now(),now(),now())";
                $result = $this->db->query($sql);
        		echo 'file exist';
        		exit();
        	}
        	else{
        		echo 'file not exist';
        		exit();
        	}
        }
    }
}

$checker = new CheckFile();
$checker->checkFileName();
$checker->checkMd5();

?>