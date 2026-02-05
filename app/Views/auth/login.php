<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; }
        .card { border: 1px solid #ccc; padding: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #007bff; color: white; padding: 10px; border: none; width: 100%; }
        .alert { padding: 10px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login</h2>
        <form id="loginForm">
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div id="message"></div>
        <p>Don't have an account? <a href="<?= base_url('register') ?>">Register</a></p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };
            
            try {
                const response = await fetch('<?= base_url('api/login') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    document.getElementById('message').innerHTML = 
                        `<div class="alert success">${result.message}</div>`;
                    localStorage.setItem('token', result.data.token);
                    setTimeout(() => {
                        window.location.href = '<?= base_url('dashboard') ?>';
                    }, 1000);
                } else {
                    document.getElementById('message').innerHTML = 
                        `<div class="alert error">${result.message || 'Login failed'}</div>`;
                }
            } catch (error) {
                document.getElementById('message').innerHTML = 
                    `<div class="alert error">Network error occurred</div>`;
            }
        });
    </script>
</body>
</html>