<?php 
/**
* login
*/
class Login
{
	public $username;
	public $password;
	public $rem;
	public $code;
	function __construct()
	{
		if (!isset($_POST['login'])) {
			echo "<script>alert('您访问的页面不存在');history.go(-1);</script>";
			exit();
		}
		session_start();
		require '../config.php';

		$this->username = $_POST['username'];
		$this->password = $_POST['password'];
		$this->code = $_POST['code'];
		$this->rem = $_POST['rem'];
	}

	public function checkPwd()
	{
		//验证密码格式
		if (!trim($this->password) == '') {
			$strlen = strlen($this->password);
			if ($strlen < 12 || $strlen > 20) {
				echo "<script>alert('密码长度不合法。请重试！');history.go(-1);</script>";
				exit();
			}else{
				$this->password = md5($this->password);
			}
		}else{
			echo "<script>alert('密码不能为空。请重试！');history.go(-1);</script>";
			exit();
		}
	}

	public function checkCode()
	{
		//验证码处理
		if ($this->code != $_SESSION['code']) {
			echo "<script>alert('".$this->code."--".$_SESSION['code']."');history.go(-1);</script>";
			echo "<script>alert('验证码不正确。请重试！');history.go(-1);</script>";
			exit();
		}
	}
	
	
	public static function getIP(){
        if( !empty($_SERVER['HTTP_X_FORWARD_FOR'])){
            return $_SERVER['HTTP_X_FORWARD_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
	}

	public function checkUsername()
	{
		//数据库验证
		$db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
		//设置中文字符集
		$result = $db->query ("set names gbk");
		//在数据库中查找用户名和密码
		$sql = "select uid,uname,upassword,udir from user where uname = '$this->username'";
		$result = $db->query($sql);
		if (!$result->num_rows) {
			echo "<script>alert('不存在该用户.请重试!');history.go(-1);</script>";
			exit();
		}else{
			$row = $result->fetch_assoc();
			$upassword = $row['upassword'];
			if($upassword!=$this->password){
				$db->close();
				echo "<script>alert('密码不正确.请重试!');history.go(-1);</script>";
				exit();
			}
			$_SESSION['user'] = $row["uname"];
			$_SESSION['current_dir'] = $row['udir'];
			if ($this->rem == 1) {
			  $_SESSION['rem'] = '1';
			}
			
			//插入登录日志
			$uid = $row['uid'];
			$ip = $this->getIP();
			$sql = "update user_log set ip='$ip',log_time=now() where uid=$uid";
			$result = $db->query($sql);
			
			//关闭数据库		
			$db->close();		
			echo "<script>alert('登录成功！');location.href = '/index.php'</script>";
			exit();
		}
	}

	public function doLogin()
	{
		$this->checkCode();
		$this->checkPwd();
		$this->checkUsername();
	}
}

$login = new Login();
$login->doLogin();

