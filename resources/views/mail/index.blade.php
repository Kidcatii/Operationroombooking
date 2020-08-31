<!DOCTYPE html>
<html>
<head>
	<title>Test Mail File</title>
	<meta charset="utf-8">
</head>
<body>
	<div id="text">
	<h1>อนุมัติการใช้งานห้องปฏิบัติการ</h1>
	<p>รายการที่:</b>&nbsp;{{ $demo->id }}</p>
	<p>ชื่อผู้จอง:</b>&nbsp;{{ $demo->users }}</p>
	<p>ห้อง:</b>&nbsp;{{ $demo->room }}</p>
	<p>วันที่:</b>&nbsp;{{ $demo->day }}</p>
	<p>เวลา:</b>&nbsp;{{ $demo->start }}</p>
	<p>สิ้นสุด:</b>&nbsp;{{ $demo->end }}</p>
		
	</div>
</body>
</html>