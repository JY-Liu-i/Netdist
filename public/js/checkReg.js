$(document).ready(function() {
	var nameFlag = true;
	var pwdFlag = true;

	$('#username').blur(function() {
		var length = $(this).val().length;
		if ( length >= 2 && length <= 20 ) {
			$('#dis_un').text('');
			nameFlag=true;
		}else{
			$('#dis_un').text('�û�������Ϊ2�ַ�');
			nameFlag=false;
		}
	});

	$('#password').blur(function(){
		if ($(this).val() == '') {
			$('#dis_pwd').text('���벻��Ϊ��');
		}else if($(this).val().length < 12){
			$('#dis_pwd').text('��������Ϊ12���ַ�');
		}else{
			$('#dis_pwd').text('');
		}
	});

	$('#confirm').blur(function() {
		var val = $('#password').val();
		if (val != '') {
			if ($(this).val() == '') {
				$('#dis_con_pwd').text('���벻��Ϊ��');
				pwdFlag = false;
			}else if($(this).val() != val){
				$('#dis_con_pwd').text('��ȷ�����������һ����');
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
			alert('�������������Ϣ!');
			return false;
		}
	});
});
