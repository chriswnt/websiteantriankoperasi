<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Officer Panel - Sistem Antrean</title>

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f6fa; margin: 0; color: #333; }
        .header { padding: 20px 40px; background: white; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .title { font-weight: bold; font-size: 22px; color: #2c3e50; }
        
        .card-container { display: flex; gap: 20px; padding: 20px 40px; flex-wrap: wrap; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); min-width: 200px; text-align: center; flex: 1; border-top: 5px solid #3498db; }
        .card h3 { margin: 0; font-size: 14px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .card h1 { margin: 10px 0 0; font-size: 36px; color: #2c3e50; }

        .tabs { display: flex; gap: 25px; padding: 0 40px; margin-top: 10px; border-bottom: 1px solid #ddd; background: white; }
        .tab { cursor: pointer; padding: 15px 5px; font-weight: 600; color: #7f8c8d; transition: 0.3s; }
        .tab.active { border-bottom: 3px solid #3498db; color: #3498db; }

        .table-container { padding: 30px 40px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: center; border-bottom: 1px solid #f0f0f0; }
        th { background: #34495e; color: white; font-weight: 500; text-transform: uppercase; font-size: 13px; }
        tr:hover { background: #f9f9f9; }

        .btn { padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.2s; font-size: 13px; }
        .btn-call { background: #3498db; color: white; margin-right: 5px; }
        .btn-call:hover { background: #2980b9; }
        .btn-done { background: #2ecc71; color: white; }
        .btn-done:hover { background: #27ae60; }
        
        /* Tambahan style untuk tombol yang sedang loading */
        .btn:disabled { background: #95a5a6 !important; cursor: not-allowed; opacity: 0.8; }
        
        .status-badge { padding: 5px 10px; border-radius: 20px; color: white; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    </style>
</head>

<body>

<div class="header">
    <div class="title">Admin Antrian ✨</div>
    <div style="font-weight: 500; color: #7f8c8d;">Officer Panel Active</div>
</div>

<div class="card-container">
    <div class="card"><h3>Total Antrean</h3><h1 id="total">0</h1></div>
    <div class="card" style="border-top-color: #f1c40f;"><h3>Sedang Dipanggil</h3><h1 id="current">-</h1></div>
    <div class="card" style="border-top-color: #e67e22;"><h3>Berikutnya</h3><h1 id="next">-</h1></div>
    <div class="card" style="border-top-color: #95a5a6;"><h3>Sisa Antrean</h3><h1 id="remaining">0</h1></div>
</div>

<div class="tabs">
    <div class="tab active" onclick="filterService('all',this)">Semua</div>
    <div class="tab" onclick="filterService('teller',this)">Teller</div>
    <div class="tab" onclick="filterService('administrasi',this)">Administrasi</div>
    <div class="tab" onclick="filterService('pinjaman',this)">Pinjaman</div>
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
        <tbody id="queueTable"></tbody>
    </table>
</div>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
let allQueues = [];

// 1. FUNGSI FORMAT WAKTU
function formatWaktu(dateString) {
    if (!dateString) return '-';
    let d = new Date(dateString);
    let jam = String(d.getHours()).padStart(2, '0');
    let mnt = String(d.getMinutes()).padStart(2, '0');
    let dtk = String(d.getSeconds()).padStart(2, '0');
    return `${jam}:${mnt}:${dtk}`;
}

// 2. LOAD DATA DARI SERVER
function loadOfficer(){
    fetch('/officer/data')
    .then(res => res.json())
    .then(data => {
        document.getElementById('total').innerText = data.total;
        document.getElementById('remaining').innerText = data.remaining;
        document.getElementById('current').innerText = data.current ? (data.current.queue_number || data.current.id) : '-';
        document.getElementById('next').innerText = data.next ? (data.next.queue_number || data.next.id) : '-';
        
        allQueues = data.queues;
        renderTable(allQueues);
    })
    .catch(err => console.error('Gagal memuat data:', err));
}

// 3. RENDER TABEL (Menambahkan parameter 'this' di tombol aksi)
function renderTable(data){
    let html = '';
    data.forEach(q => {
        let serviceName = q.service?.name ?? '-';
        let noAntrean = q.queue_number ?? q.id ?? '-';
        let statusColor = q.status === 'done' ? '#2ecc71' : (q.status === 'called' ? '#f1c40f' : '#95a5a6');
        
        let aksiHtml = '';
        if (q.status === 'waiting') {
            aksiHtml = `<button class="btn btn-call" onclick="callQueue(${q.id}, this)">Panggil</button>`;
        } else if (q.status === 'called') {
            aksiHtml = `<button class="btn btn-done" onclick="doneQueue(${q.id}, this)">Selesai</button>`;
        } else {
            aksiHtml = `<span style="color:#2ecc71; font-weight:bold;">✔ Selesai</span>`;
        }

        html += `
        <tr>
            <td><strong>${noAntrean}</strong></td>
            <td>${serviceName}</td>
            <td>${formatWaktu(q.created_at)}</td>
            <td>${formatWaktu(q.called_at)}</td>
            <td>${formatWaktu(q.done_at)}</td>
            <td><span class="status-badge" style="background:${statusColor}">${q.status}</span></td>
            <td>${aksiHtml}</td>
        </tr>`;
    });
    document.getElementById('queueTable').innerHTML = html;
}

// 4. FILTER LAYANAN
function filterService(service, el){
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    if(service === 'all'){ renderTable(allQueues); return; }
    let filtered = allQueues.filter(q => (q.service?.name ?? '').toLowerCase().includes(service.toLowerCase()));
    renderTable(filtered);
}

// 🔥 5. AKSI PANGGIL (Efek Instan / Anti-Delay)
function callQueue(id, btnEl){
    // Efek UI Instan: Ubah tombol jadi loading agar pengguna tahu sistem sedang bekerja
    btnEl.innerHTML = "⏳ Proses...";
    btnEl.disabled = true;

    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/officer/call/' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
    })
    .then(res => res.json())
    .then(data => { 
        if(data.success) {
            loadOfficer(); // Refresh data tabel setelah proses berhasil
        } else {
            btnEl.innerHTML = "Panggil"; // Kembalikan tombol jika gagal
            btnEl.disabled = false;
        }
    })
    .catch(() => {
        btnEl.innerHTML = "Panggil";
        btnEl.disabled = false;
    });
}

// 🔥 6. AKSI SELESAI (Efek Instan / Anti-Delay)
function doneQueue(id, btnEl){
    // Efek UI Instan
    btnEl.innerHTML = "⏳ Proses...";
    btnEl.disabled = true;

    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/officer/done/' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
    })
    .then(res => res.json())
    .then(data => { 
        if(data.success) {
            loadOfficer(); 
        } else {
            btnEl.innerHTML = "Selesai";
            btnEl.disabled = false;
        }
    })
    .catch(() => {
        btnEl.innerHTML = "Selesai";
        btnEl.disabled = false;
    });
}

// 7. SETUP PUSHER
var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
    forceTLS: true
});

var channel = pusher.subscribe('antrean-channel');

channel.bind('AntreanUpdate', function(data) {
    loadOfficer(); // Auto-refresh jika ada petugas lain yang update
});

loadOfficer();

</script>
</body>
</html>