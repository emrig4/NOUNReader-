<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Project And Materials</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .error-container {
            text-align: center;
            padding: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            max-width: 600px;
        }
        .error-code { font-size: 100px; font-weight: bold; opacity: 0.3; margin-bottom: 10px; }
        h1 { font-size: 32px; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        p { font-size: 18px; margin-bottom: 30px; opacity: 0.9; line-height: 1.6; }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: #fff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1>Page Not Found</h1>
        <p></p>
        <a href="/" class="btn">Return to Homepage</a>
    </div>
</body>
</html>