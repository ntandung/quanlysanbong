<title>Khách Hàng</title>
<?php
	include("session.php");
	include("header.php");
?>
<style>
#tblKhachHang table{width:50%;}
#tblKhachHang table td{vertical-align:top;}
</style>
Tên: <input type='text' id='ten-moi' /> Số điện thoại: <input id='sdt-moi' type='text' /> <button id='btn-add'>Thêm</button>
<br />
<br />
<div id='tblKhachHang'></div>

<script>
	$(document).ready(function() {
		
		taoDsKhachHang();
		function taoDsKhachHang() {
			$.ajax({
				url: "/quanlysanbong/api/dskhachhang.php",
				type: "GET",
				cache: false,
				data: {
					action: "view"
				},
				success: function(json) {
					var data = $.parseJSON(json);
					var html = "";
					html += "<table class='mytable'>";
					html += "<thead><tr><th>#</th><th>Tên KH</th><th>Số điện thoại</th><th>Sửa</th></tr></thead>";
					for (var i = 0; i < data.length; i++) {
						html += "<tr>";
						html += "<td>" + (i + 1) + "</td><td>" + data[i].ten + "</td><td>" + data[i].sdt + "</td><td><center><button class='btn-edit' ma_kh='" + data[i].id +"' order='" + (i + 1) + "'>Sửa</button></center></td>";
						html += "</tr>";
					}
					html += "</table>";
					$("#tblKhachHang").html(html);

					$(".btn-edit").click(function() {
						$(this).attr("disabled", "disabled");
						var order = $(this).attr("order");
						var ma_kh = $(this).attr("ma_kh");
						var row = $(".mytable tr")[order];
				
						var ten = $(row).find("td")[1];
						var ten_value = $(ten).text();
						$(ten).html("<input style='background:yellow;' id='ten-" + order + "' type='text' value='" + ten_value + "' /><br /><span class='thongbao'>" + THONG_BAO + "</span>");
						$("#ten-" + order).focus();

						var sdt = $(row).find("td")[2];
						var sdt_value = $(sdt).text();
						$(sdt).html("<input style='background:yellow;' id='sdt-" + order + "' type='text' value='" + sdt_value + "' />");

						$("#ten-" + order + ", #sdt-" + order).keyup(function(e) {
							if (e.keyCode == 27) {	// ESC
								$(ten).find(".thongbao").remove();
								$(ten).html(ten_value);
								$(sdt).html(sdt_value);
								$($(".btn-edit")[order - 1]).removeAttr("disabled");
							}
							if (e.keyCode == 13) {	// ENTER
								var ten_moi = $("#ten-" + order).val();
								var sdt_moi = $("#sdt-" + order).val();
								if ((ten_moi != ten_value || sdt_moi != sdt_value) && kiemtraten(ten_moi) && kiemtrasdt(sdt_moi)) {
									suaKhachHang(ma_kh, ten_moi, sdt_moi);
									$(ten).html(ten_moi);
									$(sdt).html(sdt_moi);
									$(ten).find(".thongbao").remove();
									$($(".btn-edit")[order - 1]).removeAttr("disabled");
								}
							}
						});
						checkInputs();
					});
					checkInputs();
				},
				error: function() {
					thongbaoloi("Khong the lay danh sach khach hang!!!");
				}
			});
		}

		function suaKhachHang(ma_kh, ten_moi, sdt_moi) {
			$.ajax({
				url: "/quanlysanbong/api/dskhachhang.php",
				type: "POST",
				cache: false,
				data: {
					action: "edit",
					ma_kh: ma_kh,
					ten_moi : ten_moi,
					sdt_moi : sdt_moi
				},
				success: function(msg) {
					if (msg.includes("đã tồn tại")) {
						thongbaoloi(msg);
						tailaitrang();
					} else {
						thongbaotot(msg);
						tailaitrang();
					}
				},
				error: function() {
					alert("Khong the cap nhat khach hang " + ma_kh + "!!!");
				}
				
			});
		}
		
		function themKhachHang(ten, sdt) {
			$.ajax({
				url: "/quanlysanbong/api/dskhachhang.php",
				type: "POST",
				cache: false,
				data: {
					action: "add",
					ten : ten,
					sdt : sdt
				},
				success: function(msg) {
					if (msg.includes("đã tồn tại")) {
						thongbaoloi(msg);
					} else {
						thongbaotot(msg);
						tailaitrang();
					}
				},
				error: function() {
					alert("Khong the them khach hang moi!!!");
				}
			});
		}

		$("#btn-add").click(function() {
			var ten_moi = $("#ten-moi").val();
			var sdt_moi = $("#sdt-moi").val();
			if (kiemtraten(ten_moi) && kiemtrasdt(sdt_moi)) {
				themKhachHang(ten_moi, sdt_moi);
				$("#ten-moi").val("");
				$("#sdt-moi").val("");
			}
		});
	});
</script>