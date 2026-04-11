<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: #114338;
            color: white;
            padding: 24px 20px;
        }

        .sidebar h2 {
            margin-top: 0;
            color: #FBB03C;
            font-size: 24px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 14px 0;
            padding: 10px 12px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.12);
        }

        .logout-btn {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #e74c3c;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .page-title {
            margin: 0 0 20px 0;
            font-size: 28px;
            color: #2c3e50;
        }

        .grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 24px;
        }

        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 22px;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 18px;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #3498db;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-danger {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            background: #dc3545;
            color: white;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-danger:hover {
            background: #b52b38;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
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

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f8f9fb;
            color: #34495e;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        tr:hover td {
            background: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-admin {
            background: #e8f1ff;
            color: #1d4ed8;
        }

        .badge-officer {
            background: #ecfdf3;
            color: #15803d;
        }

        .empty-text {
            color: #888;
            font-style: italic;
        }

        .self-note {
            color: #888;
            font-size: 13px;
        }

        @media (max-width: 980px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                width: 100%;
            }

            .layout {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="layout">
    <div class="sidebar">
        <h2>ADMIN</h2>

        <a href="{{ route('admin.index') }}">Dashboard</a>
        <a href="{{ route('admin.user') }}" class="active">Manajemen User</a>
        <a href="{{ route('admin.setting') }}">Kelola Tampilan</a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="content">
        <h1 class="page-title">Manajemen User</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
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

        <div class="grid">
            <div class="card">
                <h3>Tambah User Baru</h3>

                <form method="POST" action="{{ route('admin.user.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    </div>

                    <div class="form-group">
                        <label for="roleSelect">Role</label>
                        <select name="role" id="roleSelect" onchange="toggleService()" required>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="officer" {{ old('role') == 'officer' ? 'selected' : '' }}>Officer</option>
                        </select>
                    </div>

                    <div class="form-group" id="serviceBox" style="display:none;">
                        <label for="service_id">Layanan Officer</label>
                        <select name="service_id" id="service_id">
                            <option value="">Pilih Layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Tambah User</button>
                </form>
            </div>

            <div class="card">
                <h3>Daftar User</h3>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Layanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge badge-admin">Admin</span>
                                        @else
                                            <span class="badge badge-officer">Officer</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->serviceRelation->name ?? '-' }}</td>
                                    <td>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger">Hapus</button>
                                            </form>
                                        @else
                                            <span class="self-note">Akun aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-text">Belum ada user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleService() {
        const role = document.getElementById('roleSelect').value;
        document.getElementById('serviceBox').style.display = role === 'officer' ? 'block' : 'none';
    }

    toggleService();
</script>

</body>
</html>