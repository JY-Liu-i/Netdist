<?php
  session_start();
  if (isset($_SESSION['user'])) {
    header('location:index.php');
  }
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
  <!-- header部分 -->
  <?php require_once 'public/layouts/header.php' ?>

  <body>
  <!-- 导航栏 -->
  <?php require_once 'public/layouts/nav.php' ?>
  <!-- 页面主体内容 -->
    <div class="container">
      <div class="content">
          <div class="starter-template">
                <!-- 这里做了修改，其他地方自由发挥 -->
            <h1>Welcome</h1>
            <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
          </div>  
          <!-- 注册表单 -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="register" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">注册</h4>
              </div>
              <form action="admin/Register.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">
                	
				          <!-- 用户名输入 -->
                  <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">用户名:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="username" id="username" minlength="2" maxlength="20" placeholder="请输入用户名" required="">
                    </div>
                    <!-- 错误提示信息 -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>
                  
                  <!--密码输入 -->
                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">密码:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" id="password" placeholder="请输入密码" minlength="12" required="">
                    </div>
                    <h6 style="color: red;" id="dis_pwd"></h6>
                  </div>
                  
				   <!-- 确认密码输入 -->
                  <div class="form-group">
                    <label for="confirm" class="col-sm-4 control-label">确认密码:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="confirm" id="confirm" placeholder="请重复密码" minlength="12" required="">
                    </div>
                    <h6 style="color: red;" id="dis_con_pwd"></h6>
                  </div>
                  <!-- 验证码输入 -->
                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label"> 验证码 :</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="code" placeholder="请输入验证码" required="" maxlength="4" size="100">
                    </div>
                  </div>
                  
                  <!-- 验证码显示 -->
                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                	    <span>换一张</span>
                    </div>
                  </div>
                  <input type="hidden" name="type" value="all">
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">关闭</button>
                  <input type="reset" class="btn btn-warning" value ="重置" />
                  <button type="submit" class="btn btn-primary" id="reg">注册</button>
                </div>
              </form>
              </div>
            </div>
          </div>


          <!-- 登陆表单 -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="login" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
              </div>
              <form action="admin/Login.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">
				  <!-- 用户名输入 -->
                  <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">用户名:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="username" id="username" minlength="2" placeholder="请输入用户名" required="">
                    </div>
                    <!-- 错误提示信息 -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>

                  <!-- 密码输入 -->
                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">密码:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" placeholder="请输入密码" minlength="12" required="">
                    </div>
                  </div>

                  <!-- 验证码输入 -->
                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label">验证码:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="code" placeholder="请输入验证码" required="" maxlength="4">
                    </div>
                  </div>

                  <!-- 验证码图片 -->
                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                      <span>换一张</span>
                    </div>
                  </div>
                  
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">关闭</button>
                  <input type="reset" class="btn btn-warning" value ="重置" />
                  <button type="submit" class="btn btn-primary" name="login">登录</button>
                </div>
              </form>
              </div>
            </div>
          </div>

      </div>

    </div><!-- /.container -->
    
    <!-- 网页底部 -->
    <?php require_once 'public/layouts/footer.php'; ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="public/js/checkReg.js"></script>
  </body>
</html>