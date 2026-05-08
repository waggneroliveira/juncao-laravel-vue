<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Código de Verificação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f0f0f0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b00;
        }
        .content {
            padding: 30px 20px;
            text-align: center;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #ff6b00;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #f0f0f0;
        }
        .warning {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🍕 Seu Pedido</div>
        </div>
        
        <div class="content">
            <h2>Olá, {{ $fullName }}!</h2>
            <p>Recebemos uma solicitação de verificação para continuar com seu pedido.</p>
            <p>Utilize o código abaixo para confirmar sua identidade:</p>
            
            <div class="code">{{ $code }}</div>
            
            <p>Este código é válido por <strong>10 minutos</strong>.</p>
            <p>Se você não solicitou este código, ignore este e-mail.</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Seu Pedido. Todos os direitos reservados.</p>
            <p class="warning">Este é um e-mail automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>