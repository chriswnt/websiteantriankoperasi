<!DOCTYPE html>
<html>
<head>
    <title>Login Petugas</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo Pack-02.png') }}">

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            background: white;
        }

        .left {
            width: 50%;
            min-height: 100vh;
            padding: 40px 85px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            width: 42px;
            margin-bottom: 28px;
        }

        .form-area {
            width: 100%;
            max-width: 520px;
        }

        h2 {
            margin: 0 0 20px;
            color: #114338;
            font-size: 28px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input {
            width: 100%;
            height: 38px;
            padding: 8px 10px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            outline: none;
        }

        input:focus {
            border-color: #114338;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 60px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 9px;
            font-size: 12px;
            color: #114338;
            cursor: pointer;
            font-weight: bold;
        }

        button {
            width: 90px;
            height: 36px;
            background: #114338;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #0d352c;
        }

        .right {
            width: 50%;
            min-height: 100vh;
            background-image: url("{{ asset('assets/timmanagement2526.jpg') }}");
            background-size: cover;
            background-position: center;
        }

        @media(max-width: 800px) {
            .right {
                display: none;
            }

            .left {
                width: 100%;
                padding: 40px 30px;
            }
        }
    </style>
</head>

<body>

    <div class="left">
        <div class="form-area">
            <img src="{{ asset('assets/Logo Pack-02.png') }}" class="logo" alt="Logo">

            <h2>Login Petugas</h2>

            @if(session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif

            <form method="POST" action="/login">
                @csrf

                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}">

                <div class="password-wrap">
                    <input type="password" name="password" id="password" placeholder="Password">
                    <span class="toggle-password" onclick="togglePassword()">Lihat</span>
                </div>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <div class="right"></div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggle = document.querySelector('.toggle-password');

            if (password.type === 'password') {
                password.type = 'text';
                toggle.innerText = 'Sembunyi';
            } else {
                password.type = 'password';
                toggle.innerText = 'Lihat';
            }
        }
    </script>

</body>
</html>