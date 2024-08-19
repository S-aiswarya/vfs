<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <title>Document</title>
    <style>
      table{
        width: 100%;
        text-align: center;
        border: 1px solid black;
      }
      tr th{
        font-size: 14px;
        text-align: center;
        padding: 5px;
        border: 1px solid black;
      }
      
    </style>
</head>
<body> 
  <!-- excel page -->
    @include('admin.exports.Viewfile', [
      'collections' => $visitor_logs, 'headings' => $table_heads, 'excelheadings' => $excelheadings])
</body>
</html>