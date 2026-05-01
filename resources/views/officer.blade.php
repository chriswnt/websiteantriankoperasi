<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Officer Panel - Sistem Antrean</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo Pack-02.png') }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            margin: 0;
            color: #333;
        }

        .header {
            padding: 20px 40px;
            background: white;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            gap: 20px;
        }

        .title {
            font-weight: bold;
            font-size: 22px;
            color: #2c3e50;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .user-info {
            font-size: 14px;
            color: #7f8c8d;
            border-right: 1px solid #ddd;
            padding-right: 15px;
            line-height: 1.5;
        }

        .user-info strong {
            color: #2c3e50;
        }

        .officer-status {
            font-weight: 500;
            color: #7f8c8d;
            border-left: 1px solid #ddd;
            padding-left: 15px;
        }

        .card-container {
            display: flex;
            gap: 20px;
            padding: 20px 40px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            min-width: 200px;
            text-align: center;
            flex: 1;
            border-top: 5px solid #3498db;
        }

        .card h3 {
            margin: 0;
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
            line-height: 1.5;
            letter-spacing: 1px;
        }

        .card h1 {
            margin: 10px 0 0;
            font-size: 36px;
            color: #2c3e50;
        }

        .service-banner {
            margin: 0 40px 10px 40px;
            background: #ffffff;
            border-left: 5px solid #3498db;
            border-radius: 10px;
            padding: 14px 18px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-size: 15px;
            color: #2c3e50;
        }

        .table-container {
            padding: 20px 40px 30px 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            background: #34495e;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 13px;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
            font-size: 13px;
        }

        .btn-call {
            background: #3498db;
            color: white;
            margin-right: 5px;
        }

        .btn-call:hover {
            background: #2980b9;
        }

        .btn-done {
            background: #2ecc71;
            color: white;
        }

        .btn-done:hover {
            background: #27ae60;
        }

        .btn-reset {
            background: #e74c3c;
            color: white;
        }

        .btn-reset:hover {
            background: #c0392b;
        }

        .btn-logout {
            background: #34495e;
            color: white;
        }

        .btn-logout:hover {
            background: #2c3e50;
        }

        .btn:disabled {
            background: #95a5a6 !important;
            cursor: not-allowed;
            opacity: 0.8;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
            font-style: italic;
        }

        .btn-profile {
            background: #3498db;
            color: white;
        }

        .btn-profile:hover {
            background: #2980b9;
        }

        /* dropdown user */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-dropdown-toggle {
            background: white;
            border: 1px solid #dfe6e9;
            color: #2c3e50;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 220px;
            justify-content: space-between;
            font-weight: 600;
        }

        .user-dropdown-toggle:hover {
            background: #f8f9fa;
        }

        .user-icon {
            color: #3498db;
            font-size: 14px;
        }

        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 220px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.12);
            overflow: hidden;
            display: none;
            z-index: 9999;
        }

        .user-dropdown-menu.show {
            display: block;
        }

        .user-dropdown-item {
            width: 100%;
            background: white;
            border: none;
            text-decoration: none;
            color: #2c3e50;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            text-align: left;
            box-sizing: border-box;
        }

        .user-dropdown-item:hover {
            background: #eef6ff;
        }

        .logout-item:hover {
            background: #f8f9fa;
        }

        .menu-icon {
            width: 18px;
            text-align: center;
            color: #4b5563;
        }

        @media (max-width: 900px) {
            .header,
            .card-container,
            .table-container {
                padding-left: 20px;
                padding-right: 20px;
            }

            .service-banner {
                margin-left: 20px;
                margin-right: 20px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .card {
                min-width: unset;
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="header">
    <div class="title">Admin Antrian </div>

    <div class="header-actions">
        <div class="user-info">
            Login sebagai: <strong>{{ auth()->user()->name }}</strong><br>
            Role: <strong>{{ auth()->user()->role }}</strong>
        </div>

        @if(auth()->user()->serviceRelation)
            <div class="user-info">
                Layanan: <strong>{{ auth()->user()->serviceRelation->name }}</strong>
            </div>
        @endif

        <button class="btn btn-reset" onclick="resetQueue(this)"> Reset Antrean</button>

        <div class="user-dropdown">
            <button class="user-dropdown-toggle" onclick="toggleUserMenu()">
                <span>{{ strtoupper(auth()->user()->name) }}</span>
                <span class="user-icon">⌄</span>
            </button>

            <div class="user-dropdown-menu" id="userDropdownMenu">
                <a href="{{ route('profile.index') }}" class="user-dropdown-item">
                    <span class="menu-icon">☺</span>
                    <span>Profil Saya</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="user-dropdown-item logout-item">
                        <span class="menu-icon">↪</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="officer-status">Officer Panel Active</div>
    </div>
</div>

<div class="service-banner">
    Panel ini hanya menampilkan antrean sesuai layanan petugas yang sedang login.
    @if(auth()->user()->serviceRelation)
        <strong>({{ auth()->user()->serviceRelation->name }})</strong>
    @endif
</div>

<div class="card-container">
    <div class="card">
        <h3>Total Antrean</h3>
        <h1 id="total">0</h1>
    </div>

    <div class="card" style="border-top-color: #f1c40f;">
        <h3>Sedang Dipanggil</h3>
        <h1 id="current">-</h1>
    </div>

    <div class="card" style="border-top-color: #e67e22;">
        <h3>Berikutnya</h3>
        <h1 id="next">-</h1>
    </div>

    <div class="card" style="border-top-color: #95a5a6;">
        <h3>Sisa Antrean</h3>
        <h1 id="remaining">0</h1>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Layanan</th>
                <th>Waktu Antri</th>
                <th>Waktu Diproses</th>
                <th>Waktu Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="queueTable">
            <tr>
                <td colspan="7" class="empty-state">Memuat data antrean...</td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
let allQueues = [];

/* dropdown user */
function toggleUserMenu() {
    const menu = document.getElementById('userDropdownMenu');
    menu.classList.toggle('show');
}

document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.user-dropdown');
    const menu = document.getElementById('userDropdownMenu');

    if (dropdown && !dropdown.contains(event.target)) {
        menu.classList.remove('show');
    }
});

/* LOAD DATA OFFICER */
function loadOfficer() {
    fetch('/officer/data', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async res => {
        const data = await res.json();
        return { status: res.status, data };
    })
    .then(({ status, data }) => {
        if (status === 403) {
            document.getElementById('total').innerText = 0;
            document.getElementById('remaining').innerText = 0;
            document.getElementById('current').innerText = '-';
            document.getElementById('next').innerText = '-';
            document.getElementById('queueTable').innerHTML = `
                <tr>
                    <td colspan="7" class="empty-state">${data.message ?? 'Akses ditolak.'}</td>
                </tr>
            `;
            return;
        }

        document.getElementById('total').innerText = data.total ?? 0;
        document.getElementById('remaining').innerText = data.remaining ?? 0;
        document.getElementById('current').innerText = data.current ? (data.current.queue_number || data.current.id) : '-';
        document.getElementById('next').innerText = data.next ? (data.next.queue_number || data.next.id) : '-';

        allQueues = data.queues || [];
        renderTable(allQueues);
    })
    .catch(err => {
        console.error('Gagal memuat data:', err);
        document.getElementById('queueTable').innerHTML = `
            <tr>
                <td colspan="7" class="empty-state">Gagal memuat data antrean.</td>
            </tr>
        `;
    });
}

/* RENDER TABLE */
function renderTable(data) {
    if (!data || data.length === 0) {
        document.getElementById('queueTable').innerHTML = `
            <tr>
                <td colspan="7" class="empty-state">Belum ada antrean untuk layanan ini.</td>
            </tr>
        `;
        return;
    }

    let html = '';

    data.forEach(q => {
        const serviceName = q.service?.name ?? '-';
        const noAntrean = q.queue_number ?? q.id ?? '-';

        let statusColor = '#95a5a6';
        if (q.status === 'done') statusColor = '#2ecc71';
        if (q.status === 'called') statusColor = '#f1c40f';

        let aksiHtml = '';
        if (q.status === 'waiting') {
            aksiHtml = `<button class="btn btn-call" onclick="callQueue(${q.id}, this)">Panggil</button>`;
   
        }  else if (q.status === 'called') {

    if (q.officer_id === {{ auth()->id() }}) {
        aksiHtml = `<button class="btn btn-done" onclick="doneQueue(${q.id}, this)">Selesai</button>`;
    } else {
        aksiHtml = `<span style="color:#e74c3c; font-weight:bold;">Diproses officer lain</span>`;
    }
}

        // FIX: Langsung gunakan waktu yang sudah dikembalikan dan disamakan oleh Controller
        html += `
        <tr>
            <td><strong>${noAntrean}</strong></td>
            <td>${serviceName}</td>
            <td>${q.waktu_antri ?? '-'}</td>
            <td>${q.waktu_diproses ?? '-'}</td>
            <td>${q.waktu_selesai ?? '-'}</td>
            <td><span class="status-badge" style="background:${statusColor}">${q.status}</span></td>
            <td>${aksiHtml}</td>
        </tr>`;
    });

    document.getElementById('queueTable').innerHTML = html;
}

/* PANGGIL */
function callQueue(id, btnEl) {
    btnEl.innerHTML = '⏳ Proses...';
    btnEl.disabled = true;

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/officer/call/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadOfficer();
        } else {
            btnEl.innerHTML = 'Panggil';
            btnEl.disabled = false;
            alert(data.message || 'Gagal memanggil antrean.');
        }
    })
    .catch(() => {
        btnEl.innerHTML = 'Panggil';
        btnEl.disabled = false;
        alert('Terjadi kesalahan pada sistem.');
    });
}

/* SELESAI */
function doneQueue(id, btnEl) {
    btnEl.innerHTML = '⏳ Proses...';
    btnEl.disabled = true;

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/officer/done/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadOfficer();
        } else {
            btnEl.innerHTML = 'Selesai';
            btnEl.disabled = false;
            alert(data.message || 'Gagal menyelesaikan antrean.');
        }
    })
    .catch(() => {
        btnEl.innerHTML = 'Selesai';
        btnEl.disabled = false;
        alert('Terjadi kesalahan pada sistem.');
    });
}

/* RESET */
function resetQueue(btnEl) {
    if (!confirm('⚠️ Yakin ingin mereset semua antrean layanan ini hari ini?')) {
        return;
    }

    const originalText = btnEl.innerHTML;
    btnEl.innerHTML = '⏳ Mereset...';
    btnEl.disabled = true;

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/officer/reset', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Antrean berhasil direset.');
            loadOfficer();
        } else {
            alert(data.message || 'Gagal mereset antrean.');
        }

        btnEl.innerHTML = originalText;
        btnEl.disabled = false;
    })
    .catch(() => {
        alert('Terjadi kesalahan pada sistem.');
        btnEl.innerHTML = originalText;
        btnEl.disabled = false;
    });
}

/* AUTO REFRESH TENGAH MALAM */
function autoRefreshAtMidnight() {
    const now = new Date();
    const night = new Date(
        now.getFullYear(),
        now.getMonth(),
        now.getDate() + 1,
        0, 0, 1
    );

    const msToMidnight = night.getTime() - now.getTime();

    setTimeout(function() {
        window.location.reload(true);
    }, msToMidnight);
}

/* PUSHER */
var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
    forceTLS: true
});

var channel = pusher.subscribe('antrean-channel');
channel.bind('AntreanUpdate', function() {
    loadOfficer();
});

/* FIRST LOAD */
loadOfficer();
autoRefreshAtMidnight();
</script>

</body>
</html>