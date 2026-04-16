<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin</title>

<style>
body{margin:0;font-family:Arial;display:flex}

/* SIDEBAR */
.sidebar{
width:220px;
background:#114338;
color:white;
height:100vh;
padding:20px;
}

.sidebar h2{color:#FBB03C}

.sidebar a{
display:block;
color:white;
margin:15px 0;
text-decoration:none;
}

/* CONTENT */
.content{
flex:1;
background:#f4f6f9;
padding:30px;
}

/* CARD */
.cards{
display:flex;
gap:20px;
margin-bottom:20px;
flex-wrap: wrap; /* Agar card otomatis turun ke bawah jika penuh */
}

.card{
flex: 1;
min-width: 200px;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
text-align:center;
}

.card h3 {
    margin-bottom: 5px;
    color: #333;
}

.service-name {
    font-size: 13px;
    color: #e67e22;
    font-weight: bold;
    margin-bottom: 10px;
}

.logout-btn{
margin-top:20px;
padding:10px;
background:#e74c3c;
color:white;
border:none;
cursor:pointer;
border-radius:5px;
width:100%;
}

.stat-details {
    margin-top: 15px;
    font-size: 13px;
    color: #555;
    border-top: 1px solid #eee;
    padding-top: 10px;
}
.stat-details span {
    display: block;
    margin-bottom: 5px;
}
.stat-details strong {
    color: #114338;
    font-size: 14px;
}
</style>

</head>

<body>

<div class="sidebar">
<h2>ADMIN</h2>

<a href="/admin">Dashboard</a>
<a href="/admin/user">Manajemen User</a>
<a href="/admin/setting">Kelola Tampilan</a>

<form action="{{ route('logout') }}" method="POST">
@csrf
<button class="logout-btn">Logout</button>
</form>

</div>

<div class="content">

<h2>Dashboard Admin</h2>

<div class="cards">

    <div class="card" style="border-top: 4px solid #114338;">
        <h3>Jumlah User</h3>
        <h1 id="totalUsers">{{ $totalUsers }}</h1>
    </div>

    @foreach($officers as $officer)
    <div class="card">
        <h3>{{ strtoupper($officer->name) }}</h3>
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

            // Looping data dinamis yang dikirim dari controller
            for (const [key, value] of Object.entries(data.stats)) {
                let totalEl = document.getElementById(key + '_total');
                let avgEl = document.getElementById(key + '_avg');
                
                if(totalEl && avgEl) {
                    totalEl.innerText = value.total_done;
                    avgEl.innerText = value.avg_time;
                }
            }
        })
        .catch(err => {
            console.error("Gagal memuat data admin:", err);
            // Ubah semua tulisan "Memuat..." menjadi "Error"
            document.querySelectorAll('[id$="_avg"]').forEach(el => {
                if(el.innerText === 'Memuat...') {
                    el.innerText = 'Error';
                    el.style.color = 'red';
                }
            });
            alert("Gagal memuat statistik! Pesan error: \n\n" + err.message);
        });
    }

    // Inisialisasi Pusher
    var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        forceTLS: true
    });

    var channel = pusher.subscribe('antrean-channel');
    channel.bind('AntreanUpdate', function() {
        // Otomatis refresh data ketika ada officer yang mengubah status antrean
        loadDashboardStats(); 
    });

    // Panggil saat halaman pertama kali dimuat
    loadDashboardStats();
</script>

</body>
</html>