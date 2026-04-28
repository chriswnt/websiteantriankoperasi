<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User</title>

    <style>
        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            font-family:Arial, sans-serif;
            display:flex;
            min-height:100vh;
            background:#f4f6f9;
        }

        /* ================= SIDEBAR KONSISTEN ================= */
        .sidebar{
            width:180px;
            background:#114338;
            color:white;
            min-height:100vh;
            padding:10px 12px;
            display:flex;
            flex-direction:column;
            flex-shrink:0;
        }

        .sidebar h2{
            color:#FBB03C;
            margin:0 0 24px 2px;
            font-size:20px;
            font-weight:800;
        }

        .sidebar .menu{
            display:flex;
            flex-direction:column;
            gap:10px;
        }

        .sidebar a{
            display:flex;
            align-items:center;
            width:100%;
            height:34px;
            color:white;
            padding:0 12px;
            text-decoration:none;
            border-radius:6px;
            font-size:13px;
            font-weight:700;
            line-height:1;
            transition:.2s ease-in-out;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,.12);
        }

        .sidebar a.active{
            background:rgba(255,255,255,.16);
        }

        .logout-form{
            margin-top:35px;
        }

        .logout-btn{
            width:100%;
            height:32px;
            background:#ef4438;
            color:white;
            border:none;
            border-radius:6px;
            font-size:11px;
            font-weight:700;
            cursor:pointer;
        }

        .logout-btn:hover{
            background:#d93b31;
        }

        /* ================= CONTENT ================= */
        .content{
            flex:1;
            background:#f4f6f9;
            padding:24px;
        }

        .content h1{
            margin:0 0 20px;
            font-size:24px;
            color:#1f3552;
        }

        .layout{
            display:grid;
            grid-template-columns: 320px 1fr;
            gap:18px;
            align-items:start;
        }

        .card{
            background:white;
            border-radius:14px;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
            padding:18px;
        }

        .card h2{
            margin:0 0 14px;
            font-size:18px;
            color:#1f3552;
        }

        label{
            display:block;
            font-size:14px;
            font-weight:600;
            color:#333;
            margin-bottom:6px;
        }

        input, select{
            width:100%;
            padding:10px 12px;
            border:1px solid #d6dbe1;
            border-radius:8px;
            margin-bottom:12px;
            font-size:14px;
            outline:none;
        }

        input:focus, select:focus{
            border-color:#1b7cf0;
        }

        .submit-btn{
            width:100%;
            padding:12px;
            background:#1b7cf0;
            color:white;
            border:none;
            border-radius:8px;
            font-weight:700;
            cursor:pointer;
        }

        .submit-btn:hover{
            background:#1368ca;
        }

        .alert{
            padding:12px 14px;
            border-radius:8px;
            margin-bottom:14px;
            font-size:14px;
        }

        .alert-success{
            background:#eaf8ee;
            color:#1f7a36;
            border:1px solid #bfe7ca;
        }

        .alert-error{
            background:#fff1f1;
            color:#b42318;
            border:1px solid #f3c0c0;
        }

        .table-wrap{
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        }

        thead th{
            text-align:left;
            padding:12px 12px;
            background:#f2f4f7;
            color:#234;
            font-size:13px;
        }

        tbody td{
            padding:14px 12px;
            border-bottom:1px solid #edf0f2;
            color:#222;
            vertical-align:middle;
        }

        .badge{
            display:inline-block;
            padding:6px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
        }

        .badge-officer{
            background:#e8f5ea;
            color:#1f8f43;
        }

        .badge-admin{
            background:#e9eefc;
            color:#2d5bdb;
        }

        .delete-form{
            margin:0;
        }

        .delete-btn{
            padding:8px 12px;
            border:none;
            border-radius:8px;
            background:#e74c3c;
            color:white;
            font-size:12px;
            font-weight:700;
            cursor:pointer;
        }

        .delete-btn:hover{
            background:#cf3f31;
        }

        .helper{
            font-size:12px;
            color:#666;
            margin-top:-6px;
            margin-bottom:12px;
        }

        .stats{
            margin-bottom:18px;
        }

        .stats-card{
            background:white;
            border-radius:14px;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
            padding:18px;
            max-width:260px;
            border-top:4px solid #114338;
        }

        .stats-card h3{
            margin:0 0 10px;
            font-size:16px;
            color:#333;
        }

        .stats-card h1{
            margin:0;
            color:#111;
            font-size:40px;
            text-align:center;
        }

        @media (max-width: 900px){
            .layout{
                grid-template-columns:1fr;
            }
        }

        @media (max-width: 768px){
            body{
                flex-direction:column;
            }

            .sidebar{
                width:100%;
                min-height:auto;
            }

            .content{
                padding:18px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>ADMIN</h2>

        <div class="menu">
            <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">Dashboard</a>

            <a href="/admin/user" class="{{ request()->is('admin/user*') ? 'active' : '' }}">
                Manajemen User
            </a>

            <a href="/admin/setting" class="{{ request()->is('admin/setting*') ? 'active' : '' }}">
                Kelola Tampilan
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>Manajemen User</h1>

        <div class="stats">
            <div class="stats-card">
                <h3>Jumlah User</h3>
                <h1>{{ $totalUsers ?? $users->count() }}</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
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

        <div class="layout">
            <!-- FORM -->
            <div class="card">
                <h2>Tambah User Baru</h2>

                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf

                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}">

                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">

                    <label>Password</label>
                    <input type="password" name="password">

                    <label>Role</label>
                    <select name="role" id="roleSelect">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="officer" {{ old('role') == 'officer' ? 'selected' : '' }}>Officer</option>
                    </select>

                    <div id="serviceWrapper" style="{{ old('role') == 'officer' ? '' : 'display:none;' }}">
                        <label>Layanan Officer</label>
                        <select name="service_id">
                            <option value="">Pilih layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="helper">Wajib dipilih jika role = officer</div>
                    </div>

                    <button type="submit" class="submit-btn">Tambah User</button>
                </form>
            </div>

            <!-- TABLE -->
            <div class="card">
                <h2>Daftar User</h2>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>NAMA</th>
                                <th>EMAIL</th>
                                <th>ROLE</th>
                                <th>LAYANAN</th>
                                <th>AKSI</th>
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
                                    <td>
                                        {{ $user->serviceRelation ? $user->serviceRelation->name : '-' }}
                                    </td>
                                    <td>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="delete-form" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-btn">Hapus</button>
                                            </form>
                                        @else
                                            <span style="font-size:12px; color:#777;">Akun aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center; color:#777;">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const serviceWrapper = document.getElementById('serviceWrapper');

        function toggleServiceField() {
            if (roleSelect.value === 'officer') {
                serviceWrapper.style.display = 'block';
            } else {
                serviceWrapper.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', toggleServiceField);
        toggleServiceField();
    </script>

</body>
</html>