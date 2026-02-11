<!DOCTYPE html>
<html>
<head>
    <title>Login - Installment Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #0d1117 0%, #161b22 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            width: 100%;
            max-width: 420px;
        }
        .card-header-custom {
            text-align: center;
            margin-bottom: 30px;
        }
        .card-header-custom h2 {
            color: #58a6ff;
            font-weight: 700;
            margin: 0;
        }
        .divider {
            height: 1px;
            background: #30363d;
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="card">
        <div class="card-body p-4">
            
            <div class="card-header-custom">
                <h2>Login</h2>
                <small style="color: #8b949e;">Access your account</small>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                
                <div class="form-group">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                    @error('password') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3">Sign In</button>
            </form>

            <div class="divider"></div>

            <div class="text-center">
                <small style="color: #8b949e;">Don't have an account? 
                    <a href="/register" style="color: #58a6ff; font-weight: 600;">Create one</a>
                </small>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
