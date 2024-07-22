<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>BCIE</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
   </head>
   <body>
      <div style="max-width:700px; margin:0px auto; position: relative; font-family: 'Roboto', sans-serif;  background-color: #f5f5f5;">
         <div>
            <div style="display: block; margin:15px auto 0; text-align:center">
               <x-logo></x-logo>
            </div>
            @section('content')
            @show
         </div>
      </div>
   </body>
</html>