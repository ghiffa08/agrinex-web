<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - AgriNex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            /* background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            margin: auto;
            width: 100%;
            background: white;
            border-radius: 30px;
            border: solid 3px #22c55e;
            /* box-shadow: 0 20px 60px rgba(34,197,94,0.13); */
            box-shadow: 0 20px 60px rgba(17, 211, 88, 0.13);
            max-width: 430px;
            overflow: hidden;
        }
        .login-header {
            background: #fff;
            padding: 38px 30px 24px 30px;
            text-align: center;
            color: #16a34a;
            border-bottom: 3px solid #22c55e;
        }
        .login-header img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            border-radius: 14px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(34,197,94,0.10);
            background: #fff;
            border: 2px solid #22c55e;
        }
        .login-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: 1px;
            color: #16a34a;
        }
        .login-header p {
            color: #16a34a;
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        .test-box {
            background: #e0fce2;
            border-left: 4px solid #22c55e;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .form-control:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 2px rgba(34,197,94,0.15);
        }
        .btn-primary {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #16a34a 0%, #22c55e 100%);
        }
        .login-card {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .btn-google {
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #374151;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
        }
        .btn-google:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }
        .btn-google img {
            width: 20px;
            height: 20px;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #9ca3af;
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }
        .divider::before { margin-right: 10px; }
        .divider::after { margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('AgrinexLogo.jpg') }}" alt="AgriNex Logo">
                <h1>Welcome Back</h1>
                <p>AgriNex IoT System</p>
            </div>
            <div class="p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif
                {{-- <div class="test-box">
                    <strong>Test Accounts:</strong>
                    <div>Admin: admin / admin123</div>
                    <div>Operator: operator / operator123</div>
                    <div>Viewer: viewer / viewer123</div>
                </div> --}}
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username or Email</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="checkbox" name="remember" id="r"> <label for="r">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
                </form>

                <div class="divider">or continue with</div>

                <a href="{{ route('google.login') }}" class="btn btn-google w-100">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Logo">
                    Sign in with Google
                </a>

                <div class="text-center mt-4"><a href="{{ route('welcome') }}" class="text-secondary text-decoration-none" style="font-size: 14px;">&larr; Back to Dashboard</a></div>
            </div>
        </div>
    </div>
</body>
</html>
