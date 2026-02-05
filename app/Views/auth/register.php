<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; }
        .card { border: 1px solid #ccc; padding: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #28a745; color: white; padding: 10px; border: none; width: 100%; }
        .alert { padding: 10px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Register</h2>
        <form id="registerForm">
            <input type="text" id="name" placeholder="Name" required>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <input type="password" id="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <div id="message"></div>
        <p>Already have an account? <a href="<?= base_url('login') ?>">Login</a></p>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                confirm_password: document.getElementById('confirm_password').value
            };
            
            if (formData.password !== formData.confirm_password) {
                document.getElementById('message').innerHTML = 
                    `<div class="alert error">Passwords do not match</div>`;
                return;
            }
            
            try {
                const response = await fetch('<?= base_url('api/register') ?>', {
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
                    document.getElementById('registerForm').reset();
                    setTimeout(() => {
                        window.location.href = '<?= base_url('login') ?>';
                    }, 2000);
                } else {
                    let errorMessage = result.message || 'Registration failed';
                    if (result.messages) {
                        errorMessage = '<ul>';
                        for (const [field, message] of Object.entries(result.messages)) {
                            errorMessage += `<li>${message}</li>`;
                        }
                        errorMessage += '</ul>';
                    }
                    document.getElementById('message').innerHTML = 
                        `<div class="alert error">${errorMessage}</div>`;
                }
            } catch (error) {
                document.getElementById('message').innerHTML = 
                    `<div class="alert error">Network error occurred</div>`;
            }
        });
    </script>
</body>
</html>