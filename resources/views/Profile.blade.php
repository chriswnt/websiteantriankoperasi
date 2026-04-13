<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef1f5;
            color: #2c3e50;
        }

        .topbar {
            height: 68px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #1f2d3d;
        }

        .brand span {
            color: #f4b400;
        }

        .topbar-right {
            font-size: 14px;
            color: #333;
        }

        .page {
            padding: 18px 16px 28px 16px;
        }

        .breadcrumb {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 16px;
        }

        .breadcrumb a {
            color: #7f8c8d;
            text-decoration: none;
        }

        .breadcrumb strong {
            color: #2c3e50;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 18px;
            margin-bottom: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            height: 44px;
            padding: 10px 12px;
            border: 1px solid #d9dde3;
            background: #fff;
            font-size: 14px;
            outline: none;
        }

        input:focus {
            border-color: #3498db;
        }

        .btn {
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 3px;
            padding: 10px 18px;
        }

        .btn-primary {
            background: #1e88e5;
            color: #fff;
        }

        .btn-primary:hover {
            background: #156fc0;
        }

        .btn-upload {
            background: #fff;
            border: 1px solid #d5d9df;
            color: #333;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
        }

        .btn-upload:hover {
            background: #f8f9fa;
        }

        .hidden-file {
            display: none;
        }

        .signature-preview {
            margin-top: 10px;
            min-height: 28px;
            font-size: 13px;
            color: #6b7280;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background: #eafaf1;
            color: #1e8449;
            border: 1px solid #b7e4c7;
        }

        .alert-error {
            background: #fdecea;
            color: #c0392b;
            border: 1px solid #f5b7b1;
        }

        .field-error {
            margin-top: 6px;
            font-size: 13px;
            color: #d93025;
        }

        .inline-actions {
            margin-top: 8px;
        }

        .password-card .btn-primary {
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 0 14px;
                height: auto;
                min-height: 60px;
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                gap: 4px;
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .page {
                padding: 14px;
            }
        }
    </style>
</head>
<body>

 
    <div class="page">
        <div class="breadcrumb">
            <a href="{{ $user->role === 'admin' ? route('admin.index') : route('officer.index') }}">Beranda</a>
            &nbsp;/&nbsp;
            <strong>Profil Saya</strong>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
           <form method="POST" action="{{ route('profile.password.update') }}">
    @csrf

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ strtoupper($user->name) }}"
                        readonly
                    >
                </div>

               

        <div class="card password-card">
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf

                <div class="form-group">
                    <label for="current_password">Password Lama</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        placeholder="-"
                        required
                    >
                    @error('current_password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="-"
                        required
                    >
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="-"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">Perbarui Password</button>
            </form>
        </div>
    </div>

    <script>
        function previewSignature(input) {
            const preview = document.getElementById('signaturePreview');

            if (input.files && input.files[0]) {
                preview.textContent = input.files[0].name;
            } else {
                preview.textContent = 'Belum ada file dipilih.';
            }
        }
    </script>

</body>
</html>