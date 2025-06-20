<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cargo en PDF</title>
    <style>
        table tr td:first-child::after {
            content: "";
            display: inline-block;
            vertical-align: top;
            min-height: 20px;
            max-height: 30px;
        }

        thead {
            display: table-header-group; 
        }
        tbody {
            display: table-body-group; 
        }
        td {
            min-height: 30px;
            margin-bottom: 0;
        }
        tr{ 
            page-break-inside: avoid;
            margin-bottom: -1px !important;
        }
    </style>
</head>
<body>
    @yield('content')
</body>

</html>