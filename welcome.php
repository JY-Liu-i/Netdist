<?php
  session_start();
  if (isset($_SESSION['user'])) {
    header('location:index.php');
  }
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
  <!-- header���� -->
  <?php require_once 'public/layouts/header.php' ?>

  <body>
  <!-- ������ -->
  <?php require_once 'public/layouts/nav.php' ?>
  <!-- ҳ���������� -->
    <div class="container">
      <div class="content">
          <div class="starter-template">
                <!-- ���������޸ģ������ط����ɷ��� -->
            <h1>Welcome</h1>
            <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
          </div>  
          <!-- ע��� -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="register" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">ע��</h4>
              </div>
              <form action="admin/Register.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">
                	
				          <!-- �û������� -->
                  <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">�û���:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="username" id="username" minlength="2" maxlength="20" placeholder="�������û���" required="">
                    </div>
                    <!-- ������ʾ��Ϣ -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>
                  
                  <!--�������� -->
                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">����:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" id="password" placeholder="����������" minlength="12" required="">
                    </div>
                    <h6 style="color: red;" id="dis_pwd"></h6>
                  </div>
                  
				   <!-- ȷ���������� -->
                  <div class="form-group">
                    <label for="confirm" class="col-sm-4 control-label">ȷ������:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="confirm" id="confirm" placeholder="���ظ�����" minlength="12" required="">
                    </div>
                    <h6 style="color: red;" id="dis_con_pwd"></h6>
                  </div>
                  <!-- ��֤������ -->
                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label"> ��֤�� :</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="code" placeholder="��������֤��" required="" maxlength="4" size="100">
                    </div>
                  </div>
                  
                  <!-- ��֤����ʾ -->
                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                	    <span>��һ��</span>
                    </div>
                  </div>
                  <input type="hidden" name="type" value="all">
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">�ر�</button>
                  <input type="reset" class="btn btn-warning" value ="����" />
                  <button type="submit" class="btn btn-primary" id="reg">ע��</button>
                </div>
              </form>
              </div>
            </div>
          </div>


          <!-- ��½�� -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="login" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
              </div>
              <form action="admin/Login.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">
				  <!-- �û������� -->
                  <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">�û���:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="username" id="username" minlength="2" placeholder="�������û���" required="">
                    </div>
                    <!-- ������ʾ��Ϣ -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>

                  <!-- �������� -->
                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">����:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" placeholder="����������" minlength="12" required="">
                    </div>
                  </div>

                  <!-- ��֤������ -->
                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label">��֤��:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="code" placeholder="��������֤��" required="" maxlength="4">
                    </div>
                  </div>

                  <!-- ��֤��ͼƬ -->
                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                      <span>��һ��</span>
                    </div>
                  </div>
                  
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">�ر�</button>
                  <input type="reset" class="btn btn-warning" value ="����" />
                  <button type="submit" class="btn btn-primary" name="login">��¼</button>
                </div>
              </form>
              </div>
            </div>
          </div>

      </div>

    </div><!-- /.container -->
    
    <!-- ��ҳ�ײ� -->
    <?php require_once 'public/layouts/footer.php'; ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="public/js/checkReg.js"></script>
  </body>
</html>