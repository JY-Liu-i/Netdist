<form action="directory/handler.php" method="post">
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Menu</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">网盘</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
        <?php 
            if (!isset($_SESSION['user'])) {
         ?>
          <li class="active"><a href="#">未登录</a></li>
          <li><a href="#Register" data-toggle="modal" data-target="#register">注册</a></li>
          <li><a href="#Register" data-toggle="modal" data-target="#login">登录</a></li>
          <?php
             }else{ 
           ?>
            <li class="active"><a href="#"><?php echo $_SESSION['user'] ?></a></li>
            <!--li><a href=directory/upload.php>上传</a></li-->
            <li><a href="#Upload" data-toggle="modal" data-target="#upload">上传</a></li>
            <li><a href="#Mkdir" data-toggle="modal" data-target="#mkdir">新建文件夹</a></li>
            <?php
              $sn = session_id();
              echo "<li><a href='directory/paste.php?sid=$sn'>粘贴</a></li>"
            ?>
            <li><a href=admin/Logout.php>登出</a></li>
          <?php
             } 
           ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>
</form>