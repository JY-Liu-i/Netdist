<?php 
session_start();
unset($_SESSION['user']);
unset($_COOKIE['user']);
echo "<script>alert('您已登出');location.href = '/welcome.php'</script>";
exit();