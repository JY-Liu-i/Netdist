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
        $this->db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('���ݿ������쳣');
        //���������ַ���
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
        if($this->file_error>0){ //�ļ����������0ʱ�д���Ϊ0�޴�
            $errorMsg = "";
            switch($this->file_error){
                case 1:
                    $errorMsg = "�ϴ��ļ���С����php.ini����";
                    break;
                case 2:
                    $errorMsg = "�ļ��ϴ���С����ǰ̨��ָ������";
                    break;
                case 3:
                    $errorMsg = "�ļ��ϴ�������";
                    break;
                case 4:
                    $errorMsg = "û���ϴ��ļ�";
                    break;
                default:
               		$errorMsg = "δ֪ԭ��";
                	break;
            }
            echo $errorMsg;
            exit();
        }
        else//�ϴ��ļ�ͨ�����飬�����ϴ�
            return true;
    }

    function checkFileName()
    {
        $sql = "select * from user_file where fname = '$this->file_name' and fdir = $this->current_dir";
        $result = $this->db->query($sql);
        if($result->num_rows){
            echo "��¼����ͬ���ļ�";
            exit();
        }
        $sql = "select * from dir where dname = '$this->file_name' and pdid = $this->current_dir";
        $result = $this->db->query($sql);
        if($result->num_rows){
            echo "��¼����ͬ���ļ���";
            exit();
        }
    }

    function upload()
    {
        //��serverfile�в���Ŷ�Ƿ��Ѿ��и��ļ�
        $file_md5 = md5_file($this->file_tmp);
        $sql = "select complete from server_file where md5 = '$file_md5'";
        $result = $this->db->query($sql);

        //�и��ļ�
        if($result->num_rows){
            //���ļ���������
            if($result->fetch_assoc()["complete"]==1){
                //ָ��serverfile������++
                $sql = "update server_file set count = count+1 where md5 = '$file_md5'";
                $result = $this->db->query($sql);
                //����user_file��
                $sql = "select max(fid) as max from user_file";
                $fid = $this->db->query($sql)->fetch_assoc()['max']+1;
                $sql = "insert into user_file(fid,fname,fdir,md5,create_time,mod_time,access_time) values($fid,'$this->file_name',$this->current_dir,'$file_md5',now(),now(),now())";
                $result = $this->db->query($sql);
                echo "�ļ��ϴ����";
            }
            //�ļ�������
            else{
                //ɾ���ļ���server_file�еļ�¼
                $sql = "delete from server_file where md5 = '$file_md5'";
                $result = $this->db->query($sql);
                //ɾ��../file�²��������ļ�
                if (file_exists("../file/$this->file_name" ))
                {
                    unlink("../file/$this->file_name");
                }
            }
        }
        //���ļ�ԭ��������
        else{
            //���ļ�����ʱĿ¼�����ƶ���ָ��Ŀ¼
            $file_path = "../file/$file_md5";
            if(!is_dir("../file")){ //�ļ��в����ڣ��ʹ������ļ���
                echo "�ļ���file������";
            }
            if(move_uploaded_file($this->file_tmp, $file_path)){
            	//�����ϴ��ļ�Ȩ��
            	chmod($file_path,0777);
                //����server_file��
                $sql = "insert into server_file (md5,progress,count,complete,size) values('$file_md5','2',1,1,$this->file_size)";
                $result = $this->db->query($sql);
                //����user_file��
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
                echo "�ļ��ϴ����";
            }
            else{
                echo "�ļ��ϴ�ʧ��";
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