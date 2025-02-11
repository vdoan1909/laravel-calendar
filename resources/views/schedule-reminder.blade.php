<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhắc nhở sự kiện</title>
</head>

<body>
    <h2>Xin chào,</h2>
    <p>Bạn có lịch sắp diễn ra:</p>
    <ul>
        <li><strong>Tiêu đề:</strong> {{ $schedule->title }}</li>
        <li><strong>Thời gian:</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}</li>
        <li><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($schedule->start)->format('d/m/Y') }}</li>
        <li><strong>Mô tả:</strong> {{ $schedule->description ?? '0 có' }}</li>
    </ul>
    <p>Trân trọng</p>
</body>

</html>