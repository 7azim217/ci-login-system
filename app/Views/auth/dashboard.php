<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; }
        .navbar { background: #f8f9fa; padding: 15px; margin-bottom: 20px; }
        .card { border: 1px solid #ccc; padding: 20px; }
        button { background: #dc3545; color: white; padding: 10px; border: none; }
        pre { background: #f8f9fa; padding: 10px; }
    </style>
</head>
<body>
    <div class="navbar">
        <strong>CodeIgniter 4 Auth</strong>
        <button onclick="logout()" style="float: right">Logout</button>
    </div>
    
    <div class="card">
        <h2>Dashboard</h2>
        <p>Welcome to your dashboard!</p>
        <div id="userInfo"></div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        
        if (!token) {
            window.location.href = '<?= base_url('login') ?>';
        }
        
        function parseJwt(token) {
            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
                return JSON.parse(jsonPayload);
            } catch (e) {
                return null;
            }
        }
        
        const userData = parseJwt(token);
        if (userData) {
            document.getElementById('userInfo').innerHTML = `
                <h3>User Information</h3>
                <pre>${JSON.stringify(userData, null, 2)}</pre>
            `;
        }
        
        function logout() {
            localStorage.removeItem('token');
            window.location.href = '<?= base_url('login') ?>';
        }
    </script>
</body>
</html>