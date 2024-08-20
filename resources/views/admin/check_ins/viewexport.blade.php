<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  <title>Document</title>
  <style>
    table {
      margin: 0px;
      padding: 0px;
    }

    table {
      width: 100%;
      text-align: center;
      border: 1px solid black;
    }

    tr th {
      font-size: 12px;
      text-align: center;
      padding: 5px;
      border: 2px solid black;
    }
    tr td {
        border: 1px solid #000;
        border-top: 0;
        padding: 0 5px;
    }

    .logo-img{
      width: 150px;
      object-fit: contain;
    }

    .section-title-block{
      display: flex;
      align-items: end;
      justify-content: space-between;
      padding-bottom: 10px;

    }
  </style>
</head>

<body style="padding: 20px;">

  <div class="section-title-block">
    <img class="logo-img" src="{{asset('/client/img/logo.png')}}" />
    <span style="font-size:13px;margin-left: auto;">Security Planning Policies, Procedures & Forms</span>
  </div>

  <!-- excel page -->
  @include('admin.exports.Viewfile', [
  'collections' => $visitor_logs, 'headings' => $table_heads, 'excelheadings' => $excelheadings])

  <span style="position:relative;left:640px;top:10px;font-size:13px;margin-top:auto;">Privileged & Confidential</span>
  

</body>

</html>