<!DOCTYPE html>
<html>

<head>
    <title>Login - Futsal Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #408bfa, #b092e0);
            height: 100vh;
        }

        .login-card {
            border-radius: 15px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #6610f2;
        }

        button i {
            transition: 0.2s;
        }

        button:hover i {
            transform: scale(1.2);
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="card login-card shadow p-4" style="width: 400px;">

        <h3 class="text-center mb-4">⚽ Futsal Booking</h3>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                Email atau password salah
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label>Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>

                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                        <i id="icon" class="fa fa-eye"></i>
                    </button>
                </div>
            </div>

            <button class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <small>© {{ date('Y') }} Futsal Booking</small>
        </div>

    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('icon');

            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
