<?php
session_start();

/*
  if (isset($_COOKIE['user'])) {
    $_SESSION['user'] = $_COOKIE['user'];
    
    $db = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME) or die('????????');
    //?????????
    $result = $this->db->query ("set names gbk");
    $sql = "select udir from user where uname = '".$_SESSION['user']."'";
    $result = $db->query($sql);
    $_SESSION['current_dir'] = mysqli_fetch_row($result)['udir'];
    $db->close();
  }
  else{
*/
if (!isset($_SESSION['user'])) {
    header('location:welcome.php');
    exit();
  }

if (isset($_SESSION['rem'])) {
  setcookie('user',$_SESSION['user'],time()+3600);
  unset($_SESSION['rem']);
}

require 'config.php';
?>

<!DOCTYPE html>
<html lang="zh-CN">
  <!-- header????-->
  <?php require_once 'public/layouts/header.php'; ?>
  <body>
    <?php require_once 'public/layouts/nav.php' ?>
    <div class="container">
      <div class="content">
        <div class="starter-template">
          <!-- ????????????????????-->
            <?php require 'directory.php';?>
            
        </div>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="mkdir" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">新建文件夹</h4>
              </div>

              <form action="directory/mkdir.php" method="post" accept-charset="gbk" class="form-horizontal">
                
                <div class="modal-body">
                  <!-- ?????? -->
                  <div class="form-group">
                    <label for="dirname" class="col-sm-4 control-label">请输入新文件夹名:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="dirname" id="dirname" minlength="1" placeholder="文件夹名" required="">
                    </div>
                    <!-- 错误提示 -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <img height="50" />
                      <span>&nbsp</span>
                    </div>
                  </div>

                  <input type="hidden" name="current_dir" value=<?php echo $_SESSION['current_dir']?> >
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">关闭</button>
                  <button type="submit" class="btn btn-primary">确定</button>
                </div>

              </form>

            </div>
          </div>
        </div>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="upload" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">上传文件</h4>
              </div>

              <form id = "file_form" action="directory/upload.php" method="post" accept-charset="gbk" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-body">
                  
                  <!-- ???????? -->
                  <div class="form-group">
                    <label for="file" class="col-sm-4 control-label">请选择上传文件:</label>
                    <input type="file" name="file" id="file">
                  </div>
                  
                  <div class="progress progress-striped active">
					<div id="progress" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
						
					</div>
				  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <img height="50" />
                      <span>&nbsp</span>
                    </div>
                  </div>

                  <input type="hidden" name="current_dir" value=<?php echo $_SESSION['current_dir']?> >

                </div>
				<div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">关闭</button>
                  <button id="file_submit" type="button" class="btn btn-primary">确定</button>
                </div>
              </form>

            </div>
          </div>
        </div>



		<?php echo "<input type='hidden' id='param1' name='param1' value='".$_SESSION['current_dir']."'>" ?>
		
		
        <script type="text/javascript">
          function rename_fid_values(ID){
            $('#rename_fid').val(ID);
          }
          function move_fid_values(ID){
            $('#move_fid').val(ID);
          }
          function rename_did_values(ID){
            $('#rename_did').val(ID);
          }
          
          function uploadFile() {
            var fd = new FormData();
            fd.append("file", document.getElementById('file').files[0]);
            fd.append("current_dir",document.getElementById('param1').value);
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange=function(){
				if(xhr.readyState==4 && xhr.status==200){
					console.log(xhr.responseText);//此处获取返回数据
					
				}
			}
            xhr.upload.addEventListener("progress", uploadProgress, false);
            xhr.addEventListener("load", uploadComplete, false);
            xhr.addEventListener("error", uploadFailed, false);
            xhr.addEventListener("abort", uploadCanceled, false);
            xhr.open("POST", "directory/upload.php");//修改成自己的接口
            xhr.send(fd);
          }

          function uploadProgress(evt) {
            if (evt.lengthComputable) {
                var percent = Math.round(evt.loaded * 100 / evt.total);
                document.getElementById('progress').innerHTML = percent.toFixed(2) + '%';
                document.getElementById('progress').style.width = percent.toFixed(2) + '%';
            }
            else {
                document.getElementById('progress').innerHTML = 'unable to compute';
            }
          }
          function uploadComplete(evt) {
            document.getElementById('progress').innerHTML="上传完成";
            setTimeout(function(){
			  location.reload();
			},1000)
          }
          function uploadFailed(evt) {
            alert("上传失败");
          }
          function uploadCanceled(evt) {
            alert("上传取消");
          }
          
          
          document.getElementById("file_submit").onclick = function() {

            var fileReader = new FileReader();
            var blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice;
            var file = document.getElementById("file").files[0];
            var chunkSize = 2097152;
            // read in chunks of 2MB
            var chunks = Math.ceil(file.size / chunkSize), currentChunk = 0, spark = new SparkMD5();

            fileReader.onload = function(e) {
                console.log("read chunk nr", currentChunk + 1, "of", chunks);
                spark.appendBinary(e.target.result);
                // append binary string
                currentChunk++;

                if (currentChunk < chunks) {
                    loadNext();
                } else {
                	var md5 = spark.end();
                	var fname = document.getElementById('file').files[0].name;
                	var current_dir = document.getElementById('param1').value;
                    console.log("finished loading");
                    console.info("computed hash", spark.end());
                    $.ajax({
				        type: "post",
				        url: "directory/check_file.php",
				        contentType: "application/x-www-form-urlencoded; charset=GBK",
				        data: {
				        	md5:md5,
				        	fname:fname,
				        	current_dir:current_dir
				        	},
				        dataType: "text",//回调函数接收数据的数据格式
				        success: function(msg){
					        console.log(msg);    //控制台输出
					        
					        if(msg=="file exist"){
					        	alert("秒传成功");
					        	console.log("秒传成功");
					        	location.href = '/';
					        }
					        else if(msg=="file not exist"){
					        	uploadFile();
					        	//document.getElementById("file_form").submit();
					        }
					        else if(msg=="same file"){
					        	alert("本目录下存在同名文件");
					        	console.log("本目录下存在同名文件");
					        }
					        else if(msg=="same dir"){
					        	alert("本目录下存在同名文件夹");
					        	console.log("本目录下存在同名文件夹");
					        }
					        else{
					        	//alert("黑人问号");
					        	console.log("黑人问号");
					        }
				        },
				        error:function(msg){
				          console.log(msg);
				        }
				      });
                }
            };

            function loadNext() {
                var start = currentChunk * chunkSize;
                var end = start + chunkSize >= file.size ? file.size : start + chunkSize;
                fileReader.readAsBinaryString(blobSlice.call(file, start, end));
            }

            loadNext();
        };
        
        </script>
        
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="rename_file" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">重命名</h4>
              </div>

              <form action="directory/rename_file.php" method="post" accept-charset="gbk" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-body">
                  
                  <div class="form-group">
                    <label for="filename" class="col-sm-4 control-label">请输入新文件名:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="filename" minlength="1" placeholder="文件名" required="">
                    </div>
                    <!-- 错误提示 -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <img height="50" />
                      <span>&nbsp</span>
                    </div>
                  </div>

                  <input type="hidden" id ="rename_fid" name="fid" value=''>

                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">关闭</button>
                  <button type="submit" class="btn btn-primary">确定</button>
                </div>

              </form>

            </div>
          </div>
        </div>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="rename_dir" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">重命名</h4>
              </div>

              <form action="directory/rename_dir.php" method="post" accept-charset="gbk" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-body">
                  
                  <div class="form-group">
                    <label for="dirname" class="col-sm-4 control-label">请输入新文件夹名:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="dirname" minlength="1" placeholder=“文件夹名” required="">
                    </div>
                    <!-- 错误提示 -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <img height="50" />
                      <span>&nbsp</span>
                    </div>
                  </div>

                  <input type="hidden" id ="rename_did" name="did" value=''>

                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">关闭</button>
                  <button type="submit" class="btn btn-primary">确定</button>
                </div>

              </form>

            </div>
          </div>
        </div>

        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="move_file" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">移动文件</h4>
              </div>

              <form action="directory/move_dir.php" method="post" accept-charset="gbk" class="form-horizontal" enctype="multipart/form-data">
              	<div class="modal-body">
			        <?php require 'inner_directory.php' ?>
              	</div>
              	
                <input type="hidden" id ="move_fid" name="fid" value=''>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">关闭</button>
                  <button type="submit" class="btn btn-primary">确定</button>
                </div>
              </form>

            </div>
          </div>
        </div>

      </div>
    </div><!-- /.container -->
    
    <!-- ????? -->
    <?php //require_once 'public/layouts/footer.php'; ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="public/js/check.js"></script>
  </body>
</html>