<?php
	session_start();
	
	if (isset($_SESSION['login_user'])) {
		header("location:index.php");
		die();
	}
?>
<title>Đăng nhập</title>
<link rel="shortcut icon" type="image/png" href="favicon.png"/>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script src="lib/jquery-3.4.1.js"></script>
<link rel="stylesheet" type="text/css" href="lib/toast/jquery.toast.min.css" />
<script src="lib/toast/jquery.toast.min.js"></script>
<script src="lib/common.js"></script>
<style>
#loginForm{
position: absolute;margin: auto;top: 0;right: 0;bottom: 0;left: 0;width: 300px;height: 160px;padding: 10px;border: 1px solid #000;border-radius: 5px;background: #ddd;
}
</style>

<div id="loginForm">
	<table>
		<tr>
			<td colspan="2" style='text-align:center;'><h2>QUẢN LÝ SÂN BÓNG ĐÁ</h2></td>
		</tr>
		<tr>
			<td><b>Đăng nhập</b></td>
			<td><input type="text" id="ten" /></td>
		</tr>
		<tr>
			<td><b>Mật khẩu</b></td>
			<td><input type="password" id="matkhau" /></td>
		</tr>
		<tr>
			<td></td>
			<td><button id='btnLogin'>Đăng nhập</button></td>
		</tr>
	</table>
</div>

<script>
$(document).ready(function() {
	checkInputs();
	$("#btnLogin").click(function() {
		
		var u = $("#ten").val().trim();
		var p =  $("#matkhau").val().trim();
		
		if (u == "" || p == "") {
			thongbaoloi("Tên đăng nhập/Mật khẩu không được bỏ trống!!!");
			return;
		}
		
		$.ajax({
			url: "/quanlysanbong/api/dangnhap.php",
			type: "POST",
			cache: false,
			data: {
				action: "dangnhap",
				username: u,
				password: p
			},
			success: function(msg) {
				if (msg == "Đăng nhập thành công") {
					location.href = 'index.php';
				} else {
					thongbaoloi(msg);
				}
				
			}
		});
	});
});
</script>
