<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body> 
  <!-- excel page -->
    @include(admin.exports.Viewfile),[
        'visitor_logs' => $visitor_logs, 'table_heads' => $table_heads, 'excelheadings' => $excelheadings]
</body>
</html>