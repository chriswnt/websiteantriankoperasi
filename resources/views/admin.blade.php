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

        /* SIDEBAR KONSISTEN */
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
            <a href="/admin/user" class="{{ request()->is('admin/user*') ? 'active' : '' }}">Manajemen User</a>
            <a href="/admin/setting" class="{{ request()->is('admin/setting*') ? 'active' : '' }}">Kelola Tampilan</a>
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
            fetch('/admin/dashboard/stats?time=' + new Date().getTime(), {
                method: 'GET',
                cache: 'no-store',
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

        Pusher.logToConsole = false;

        var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true
        });

        pusher.connection.bind('connected', function() {
            console.log('Pusher admin connected');
        });

        pusher.connection.bind('error', function(err) {
            console.error('Pusher admin error:', err);
        });

        var channel = pusher.subscribe('antrean-channel');

        channel.bind('pusher:subscription_succeeded', function() {
            console.log('Admin berhasil subscribe antrean-channel');
        });

        channel.bind('AntreanUpdate', function(data) {
            console.log('Event AntreanUpdate diterima admin:', data);
            loadDashboardStats();
        });

        channel.bind('.AntreanUpdate', function(data) {
            console.log('Event .AntreanUpdate diterima admin:', data);
            loadDashboardStats();
        });

        channel.bind('App\\Events\\AntreanUpdate', function(data) {
            console.log('Event App\\Events\\AntreanUpdate diterima admin:', data);
            loadDashboardStats();
        });

        channel.bind_global(function(eventName, data) {
            if (
                eventName === 'AntreanUpdate' ||
                eventName === '.AntreanUpdate' ||
                eventName === 'App\\Events\\AntreanUpdate'
            ) {
                console.log('Global event admin:', eventName, data);
                loadDashboardStats();
            }
        });

        loadDashboardStats();
        setInterval(loadDashboardStats, 30000);
    </script>

</body>
</html>