<?php

class Upload
{
    private $file_name;
    private $file_error;
    private $file_size;
    private $file_tmp;
    private $current_dir;
    private $db;
    function __construct()
    {
        $this->file_name = $_FILES["file"]["name"];
        $this->file_error = $_FILES["file"]["error"];
        $this->file_size = $_FILES["file"]["size"];
        $this->file_tmp = $_FILES["file"]["tmp_name"];
        $this->current_dir = $_POST['current_dir'];
        require '../config.php';
        $this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('数据库连接异常');
        //设置中文字符集
        $result = $this->db->query ("set names gbk");
        echo $this->file_name;
        echo "----";
        $this->file_name = iconv("UTF-8", "GBK", $this->file_name);
        echo $this->file_name;
        echo "----";
        echo $this->file_tmp;
    }

    function __destruct()
    {
        $this->db->close();
    }

    function checkError() {
        if($this->file_error>0){ //文件错误码大于0时有错误，为0无错
            $errorMsg = "";
            switch($this->file_error){
                case 1:
                    $errorMsg = "上传文件大小超过php.ini限制";
                    break;
                case 2:
                    $errorMsg = "文件上传大小超过前台表单指定限制";
                    break;
                case 3:
                    $errorMsg = "文件上传不完整";
                    break;
                case 4:
                    $errorMsg = "没有上传文件";
                    break;
                default:
               		$errorMsg = "未知原因";
                	break;
            }
            echo $errorMsg;
            exit();
        }
        else//上传文件通过检验，可以上传
            return true;
    }

    function checkFileName()
    {
        $sql = "select * from user_file where fname = '$this->file_name' and fdir = $this->current_dir";
        $result = $this->db->query($sql);
        if($result->num_rows){
            echo "本录下有同名文件";
            exit();
        }
        $sql = "select * from dir where dname = '$this->file_name' and pdid = $this->current_dir";
        $result = $this->db->query($sql);
        if($result->num_rows){
            echo "本录下有同名文件夹";
            exit();
        }
    }

    function upload()
    {
        //在serverfile中查找哦是否已经有该文件
        $file_md5 = md5_file($this->file_tmp);
        $sql = "select complete from server_file where md5 = '$file_md5'";
        $result = $this->db->query($sql);

        //有该文件
        if($result->num_rows){
            //该文件是完整的
            if($result->fetch_assoc()["complete"]==1){
                //指向serverfile的引用++
                $sql = "update server_file set count = count+1 where md5 = '$file_md5'";
                $result = $this->db->query($sql);
                //插入user_file项
                $sql = "select max(fid) as max from user_file";
                $fid = $this->db->query($sql)->fetch_assoc()['max']+1;
                $sql = "insert into user_file(fid,fname,fdir,md5,create_time,mod_time,access_time) values($fid,'$this->file_name',$this->current_dir,'$file_md5',now(),now(),now())";
                $result = $this->db->query($sql);
                echo "文件上传完成";
            }
            //文件不完整
            else{
                //删除文件在server_file中的记录
                $sql = "delete from server_file where md5 = '$file_md5'";
                $result = $this->db->query($sql);
                //删除../file下不完整的文件
                if (file_exists("../file/$this->file_name" ))
                {
                    unlink("../file/$this->file_name");
                }
            }
        }
        //该文件原来不存在
        else{
            //将文件从临时目录拷贝移动到指定目录
            $file_path = "../file/$file_md5";
            if(!is_dir("../file")){ //文件夹不存在，就创建该文件夹
                echo "文件夹file不存在";
            }
            if(move_uploaded_file($this->file_tmp, $file_path)){
            	//设置上传文件权限
            	chmod($file_path,0777);
                //增加server_file项
                $sql = "insert into server_file (md5,progress,count,complete,size) values('$file_md5','2',1,1,$this->file_size)";
                $result = $this->db->query($sql);
                //增加user_file项
                $sql = "select max(fid) as max from user_file";
                $result = $this->db->query($sql);
                if($result->num_rows){
                    $fid = $result->fetch_assoc()['max']+1;
                }
                else{
                    $fid = 1;
                }
                
                $fdir = $this->current_dir;
                $sql = "insert into user_file(fid,fname,fdir,md5,create_time,mod_time,access_time) values($fid,'$this->file_name',$fdir,'$file_md5',now(),now(),now())";
                $result = $this->db->query($sql);
                echo "文件上传完成";
            }
            else{
                echo "文件上传失败";
                exit();
            }
        }
    }
}

$upload = new Upload();
$upload->checkError();
$upload->checkFileName();
$upload->upload();
?>