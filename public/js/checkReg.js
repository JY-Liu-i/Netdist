$(document).ready(function() {
	var nameFlag = true;
	var pwdFlag = true;

	$('#username').blur(function() {
		var length = $(this).val().length;
		if ( length >= 2 && length <= 20 ) {
			$('#dis_un').text('');
			nameFlag=true;
		}else{
			$('#dis_un').text('用户名至少为2字符');
			nameFlag=false;
		}
	});

	$('#password').blur(function(){
		if ($(this).val() == '') {
			$('#dis_pwd').text('密码不能为空');
		}else if($(this).val().length < 12){
			$('#dis_pwd').text('密码至少为12个字符');
		}else{
			$('#dis_pwd').text('');
		}
	});

	$('#confirm').blur(function() {
		var val = $('#password').val();
		if (val != '') {
			if ($(this).val() == '') {
				$('#dis_con_pwd').text('输入不能为空');
				pwdFlag = false;
			}else if($(this).val() != val){
				$('#dis_con_pwd').text('请确认输入密码的一致性');
				pwdFlag = false;
			}else{
				$('#dis_con_pwd').text('');
				pwdFlag = true;
			}
		}else{
			$('#dis_con_pwd').text('');
			pwdFlag = false;
		}
	});

	$('#reg').click(function() {
		if (!(nameFlag && pwdFlag)) {
			alert('请检查你的输入信息!');
			return false;
		}
	});
});
