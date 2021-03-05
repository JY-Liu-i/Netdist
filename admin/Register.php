<?php 
/**
* register
*/
class Register
{
	private $username;
	private $db;
	private $pwd;
	private $con;
	private $code;
	function __construct()
	{
		$this->username = trim($_POST['username']);
		$this->code = $_POST['code'];
		$this->pwd = trim($_POST['password']);
		$this->con = trim($_POST['confirm']);
		
		if (!isset($_POST['type'])) {
			echo "<script>alert('您访问的页面不存在！');history.go(-1);</script>";
			exit();
		}
		session_start();
		require '../config.php';
		$this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
		//设置中文字符集
		$result = $this->db->query ("set names gbk");
	}

	public function checkCode()
	{
		if ($this->code != $_SESSION['code']) {
			echo "<script>alert('验证码不正确，请重试！');history.go(-1);</script>";
			exit();
		}
	}

	public function checkPwd(){
		if (trim($this->pwd) == '' || strlen($this->pwd) < 12) {
			echo "<script>alert('密码长度小于12，请重试！');history.go(-1);</script>";
			exit();
		}
		
		$lower_num = 0;
		$upper_num = 0;
		$number_num = 0;
		$other_num = 0;
		for($i=0;$i<strlen($this->pwd);$i++){
			if('a'<=$this->pwd[$i]&&$this->pwd[$i]<='z'){
				$lower_num++;
			}
			else if('A'<=$this->pwd[$i]&&$this->pwd[$i]<='Z'){
				$upper_num++;
			}
			else if('0'<=$this->pwd[$i]&&$this->pwd[$i]<='9'){
				$number_num++;
			}
			else{
				$other_num++;
			}
		}
		if((!!$lower_num)+(!!$upper_num)+(!!$number_num)+(!!$other_num)<3){
			echo "<script>alert('至少出现大写、小写、数字、其他符号中的三种，请重试！');history.go(-1);</script>";
			exit();
		}
		
		if ($this->pwd != $this->con) {
			echo "<script>alert('两次密码输入不一致，请重试！');history.go(-1);</script>";
			exit();
		}
		$this->pwd = md5($this->pwd);
	}

	public function checkUsername()
	{
		$length = strlen($this->username);
		if (trim($this->username) == '' || $length < 2 || $length > 20) {
			echo "<script>alert('用户名格式不正确，请重试！');history.go(-1);</script>";
			exit();
		}
		//查询有没有相同的用户名
		$sql = "select uname from user where uname = '".$this->username."'";
		$result = $this->db->query($sql);
		//已经有了相同的用户名
		if($result->num_rows){
			echo "<script>alert('该用户名已被注册！');history.go(-1);</script>";
			exit();
		}
	}
	public function doRegister()
	{
		$this->checkCode();
		$this->checkPwd();
		$this->checkUsername();
		
		//查询目录个数
		$sql = "select max(did) as max from dir";
		$result =  $this->db->query($sql);
		if(!$result->num_rows){
			$did = 0;	
		}
		else{
			$did = $result->fetch_assoc()['max']+1;
		}
    	
    	//插入目录
    	$sql_insert = "insert into dir (did,dname,pdid,dpath) values($did,'".$this->username."',0,'0-$did')";
    	$res_insert = $this->db->query($sql_insert); 
    	
    	//查询用户个数
        $sql = "select max(uid) as max from user";
        $result =  $this->db->query($sql);
    	if(!$result->num_rows){
    		$uid = 1;
    	}
    	else{
    		$uid = $result->fetch_assoc()['max']+1;
    	}
        //插入用户
        $sql_insert = "insert into user (uid,uname,upassword,udir) values($uid,'$this->username','$this->pwd','$did')"; 
        $res_insert = $this->db->query($sql_insert);
        
        //插入用户日志
        $sql = "insert into user_log (uid,reg_time) values($uid,now())";   	
		$result =  $this->db->query($sql);
		if ($res_insert) {
			$this->db->close();
			echo "<script>alert('注册成功！');location.href = '/';</script>";
			exit();
		}else{
			echo $this->db->error;
			exit();
		}
	}
}

$reg = new Register();
switch ($_POST['type']) {
	case 'name':
		$reg->uniqueName();
		break;
	case 'email':
		$reg->uniqueEmail();
		break;
	case 'all':
		$reg->doRegister();
		break;
	default:
		echo "hello world";
		break;
}

