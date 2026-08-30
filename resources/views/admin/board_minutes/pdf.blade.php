<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta - {{ $minute->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #D97706; /* amber-600 */
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h1 {
            color: #D97706;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .date {
            font-style: italic;
            color: #666;
            font-size: 14px;
        }
        .content {
            margin-bottom: 50px;
            text-align: justify;
        }
        .signatures {
            margin-top: 80px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Puedes incluir aquí el logo en Base64 o URL absoluta -->
        <h1>{{ $minute->title }}</h1>
        <div class="date">Fecha de la junta: {{ $minute->date->format('d de F de Y') }}</div>
    </div>

    <div class="content">
        {!! $minute->content !!}
    </div>

    <div class="signatures">
        <div class="signature-box" style="float: left;">
            <div class="signature-line">
                Fdo: Presidente/a
            </div>
        </div>
        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                Fdo: Secretario/a
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
