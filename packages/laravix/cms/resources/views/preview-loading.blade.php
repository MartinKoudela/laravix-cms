<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif; background: #f9fafb; }
        .spinner { width: 32px; height: 32px; border: 3px solid #e5e7eb; border-top-color: #6b7280; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    <script>setTimeout(() => location.reload(), 1000);</script>
</head>
<body>
    <div class="spinner"></div>
</body>
</html>