<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .password-toggle {
            cursor: pointer;
        }
        .alert {
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 80%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-4 text-center">Login</h1>

                @if(session('message'))
                    <div class="alert alert-info">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="login">Email atau Username:</label>
                        <input type="text" 
                               class="form-control @error('login') is-invalid @enderror" 
                               id="login" 
                               name="login" 
                               value="{{ old('login') }}" 
                               placeholder="Masukkan Email atau Username">
                        @error('login')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password">
                            <div class="input-group-append">
                                <span class="input-group-text password-toggle" onclick="togglePassword('password')">👁️</span>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>

                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar disini</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function togglePassword(id) {
            const passwordField = document.getElementById(id);
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        }
    </script>
</body>
</html>