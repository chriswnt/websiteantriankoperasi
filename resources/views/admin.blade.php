<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>

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

        .sidebar{
            width:220px;
            background:#114338;
            color:white;
            min-height:100vh;
            padding:20px 16px;
            display:flex;
            flex-direction:column;
            flex-shrink:0;
        }

        .sidebar h2{
            color:#FBB03C;
            margin:10px 0 20px;
            font-size:22px;
            font-weight:bold;
        }

        .sidebar .menu{
            display:flex;
            flex-direction:column;
            gap:8px;
        }

        .sidebar a{
            display:block;
            color:white;
            padding:12px 14px;
            text-decoration:none;
            border-radius:8px;
            transition:0.2s ease-in-out;
            font-weight:600;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,0.12);
        }

        .sidebar a.active{
            background:rgba(255,255,255,0.14);
        }

        .logout-form{
            margin-top:20px;
        }

        .logout-btn{
            margin-top:10px;
            padding:12px;
            background:#e74c3c;
            color:white;
            border:none;
            cursor:pointer;
            border-radius:8px;
            width:100%;
            font-weight:600;
            transition:0.2s ease-in-out;
        }

        .logout-btn:hover{
            background:#cf3f31;
        }

        .content{
            flex:1;
            background:#f4f6f9;
            padding:30px;
        }

        .content h2{
            margin-top:0;
            margin-bottom:20px;
            color:#111;
        }

        .cards{
            display:flex;
            gap:20px;
            margin-bottom:20px;
            flex-wrap:wrap;
        }

        .card{
            flex:1;
            min-width:260px;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            text-align:center;
        }

        .card h3{
            margin-bottom:5px;
            color:#333;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            flex-wrap:wrap;
        }

        .service-name{
            font-size:13px;
            color:#e67e22;
            font-weight:bold;
            margin-bottom:10px;
        }

        .stat-details{
            margin-top:15px;
            font-size:13px;
            color:#555;
            border-top:1px solid #eee;
            padding-top:10px;
        }

        .stat-details span{
            display:block;
            margin-bottom:5px;
        }

        .stat-details strong{
            color:#114338;
            font-size:14px;
        }

        .status-wrap{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            margin-bottom:10px;
        }

        .status-indicator{
            width:10px;
            height:10px;
            border-radius:50%;
            display:inline-block;
        }

        .status-online{
            background:#2ecc71;
            box-shadow:0 0 8px rgba(46, 204, 113, 0.6);
        }

        .status-offline{
            background:#bdc3c7;
        }

        .status-text{
            font-size:12px;
            font-weight:bold;
        }

        .status-text.online{
            color:#2ecc71;
        }

        .status-text.offline{
            color:#7f8c8d;
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
                padding:20px;
            }

            .card{
                min-width:100%;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>ADMIN</h2>

        <div class="menu">
            <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">Dashboard</a>
            <a href="/admin/user" class="{{ request()->is('admin/user') ? 'active' : '' }}">Manajemen User</a>
            <a href="/admin/setting" class="{{ request()->is('admin/setting') ? 'active' : '' }}">Kelola Tampilan</a>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="content">
        <h2>Dashboard Admin</h2>

        <div class="cards">
            <div class="card" style="border-top:4px solid #114338;">
                <h3>Jumlah User</h3>
                <h1 id="totalUsers">{{ $totalUsers }}</h1>
            </div>

            @foreach($officers as $officer)
                <div class="card">
                    <h3>{{ strtoupper($officer->name) }}</h3>

                    <div class="status-wrap">
                        <span id="officer_{{ $officer->id }}_indicator" class="status-indicator status-offline"></span>
                        <span id="officer_{{ $officer->id }}_status" class="status-text offline">Offline</span>
                    </div>

                    <div class="service-name">
                        Layani: {{ $officer->serviceRelation ? $officer->serviceRelation->name : 'Belum diatur' }}
                    </div>

                    <h1 id="officer_{{ $officer->id }}_total">0</h1>

                    <div class="stat-details">
                        <span>Antrean Selesai Hari Ini</span>
                        Rata-rata: <strong id="officer_{{ $officer->id }}_avg">Memuat...</strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        function loadDashboardStats() {
            fetch('/admin/dashboard/stats', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok || data.status === 'error') {
                    throw new Error(data.message || 'Terjadi kesalahan sistem di server');
                }
                return data;
            })
            .then(data => {
                document.getElementById('totalUsers').innerText = data.total_users;

                for (const [key, value] of Object.entries(data.stats)) {
                    let totalEl = document.getElementById(key + '_total');
                    let avgEl = document.getElementById(key + '_avg');
                    let indicatorEl = document.getElementById(key + '_indicator');
                    let statusEl = document.getElementById(key + '_status');

                    if (totalEl) {
                        totalEl.innerText = value.total_done;
                    }

                    if (avgEl) {
                        avgEl.innerText = value.avg_time;
                    }

                    if (indicatorEl && statusEl) {
                        if (value.is_online) {
                            indicatorEl.classList.remove('status-offline');
                            indicatorEl.classList.add('status-online');

                            statusEl.classList.remove('offline');
                            statusEl.classList.add('online');
                            statusEl.innerText = 'Aktif';
                        } else {
                            indicatorEl.classList.remove('status-online');
                            indicatorEl.classList.add('status-offline');

                            statusEl.classList.remove('online');
                            statusEl.classList.add('offline');
                            statusEl.innerText = 'Offline';
                        }
                    }
                }
            })
            .catch(err => {
                console.error("Gagal memuat data admin:", err);

                document.querySelectorAll('[id$="_avg"]').forEach(el => {
                    if (el.innerText === 'Memuat...') {
                        el.innerText = 'Error';
                        el.style.color = 'red';
                    }
                });

                alert("Gagal memuat statistik! Pesan error: \n\n" + err.message);
            });
        }

        var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true
        });

        var channel = pusher.subscribe('antrean-channel');
        channel.bind('AntreanUpdate', function() {
            loadDashboardStats();
        });

        loadDashboardStats();
        setInterval(loadDashboardStats, 30000);
    </script>

</body>
</html>