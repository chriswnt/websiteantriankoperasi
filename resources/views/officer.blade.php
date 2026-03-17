<!DOCTYPE html>
<html>
<head>
<title>Officer</title>

<style>
body{font-family:Arial;background:#f5f5f5;margin:0}
.container{padding:20px}

/* CARD */
.cards{display:flex;gap:20px;margin-bottom:20px}
.card{flex:1;background:white;padding:20px;border-radius:10px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.1)}
.card h2{margin:0}

/* LOKET */
.loket-box{flex:1;background:white;padding:20px;border-radius:10px;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.1)}

/* TABLE */
table{width:100%;background:white;border-radius:10px;border-collapse:collapse;overflow:hidden}
td,th{padding:12px;text-align:center;border-bottom:1px solid #ddd}

/* BUTTON */
.btn{
padding:6px 12px;
border:none;
border-radius:5px;
cursor:pointer;
}

.btn-call{background:blue;color:white}
.btn-done{background:green;color:white}
.btn:disabled{background:gray;cursor:not-allowed}

</style>

</head>

<body>

<div class="container">

<!-- INFO ATAS -->
<div class="cards">

<div class="card">
<h3>Jumlah Antrian</h3>
<h2>{{ $total }}</h2>
</div>

<div class="card">
<h3>Antrian Sekarang</h3>
<h2>{{ $current->queue_number ?? '-' }}</h2>
</div>

<div class="card">
<h3>Antrian Selanjutnya</h3>
<h2>{{ $next->queue_number ?? '-' }}</h2>
</div>

<div class="card">
<h3>Sisa Antrian</h3>
<h2>{{ $remaining }}</h2>
</div>

</div>

<!-- LOKET -->
<div class="cards">

<div class="loket-box">LOKET 1<br><strong>{{ $loket1->queue_number ?? '-' }}</strong></div>
<div class="loket-box">LOKET 2<br><strong>{{ $loket2->queue_number ?? '-' }}</strong></div>
<div class="loket-box">LOKET 3<br><strong>{{ $loket3->queue_number ?? '-' }}</strong></div>
<div class="loket-box">LOKET 4<br><strong>{{ $loket4->queue_number ?? '-' }}</strong></div>

</div>

<!-- TABLE -->
<h3>Panggil Antrian</h3>

<table>
<tr>
<th>Nomor</th>
<th>Status</th>
<th>Aksi</th>
</tr>

@foreach($queues as $q)
<tr>
<td>{{ $q->queue_number }}</td>
<td>{{ $q->status }}</td>
<td>

<!-- PANGGIL -->
@if($q->status == 'waiting')
<a href="{{ route('call', $q->id) }}">
<button class="btn btn-call">Panggil</button>
</a>
@else
<button class="btn" disabled>Panggil</button>
@endif

<!-- SELESAI -->
@if($q->status == 'called')
<a href="{{ route('done', $q->id) }}">
<button class="btn btn-done">Selesai</button>
</a>
@else
<button class="btn" disabled>Selesai</button>
@endif

</td>
</tr>
@endforeach

</table>

</div>

</body>
</html>