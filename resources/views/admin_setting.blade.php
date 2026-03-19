<!DOCTYPE html>
<html>
<head>
<title>Kelola Tampilan</title>

<style>
body{
    margin:0;
    font-family:Arial;
    display:flex;
}

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
    text-decoration:none;
    margin:15px 0;
}

.sidebar a:hover{color:#FBB03C}

/* CONTENT */
.content{
    flex:1;
    background:#f4f6f9;
    padding:30px;
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    max-width:600px;
    margin-bottom:30px;
}

/* INPUT */
input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ccc;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    background:#007bff;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}

button:hover{background:#0056b3}

/* PREVIEW */
.preview{
    background:#114338;
    color:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
}

.preview h1{
    font-size:80px;
    margin:10px 0;
}

</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h2>ADMIN</h2>

<a href="/admin">Dashboard</a>
<a href="/admin/setting">Kelola Tampilan</a>
<a href="/admin/user">Manajemen User</a>
<a href="/logout">Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

<h2>⚙️ Kelola Tampilan</h2>

<!-- FORM -->
<div class="card">

<form action="/admin/setting/update" method="POST" enctype="multipart/form-data">
@csrf

<label>Nama Instansi</label>
<input type="text" name="title" value="{{ $setting->title }}">

<label>Alamat</label>
<input type="text" name="address" value="{{ $setting->address }}">

<label>No Telp</label>
<input type="text" name="phone" value="{{ $setting->phone }}">

<label>Link YouTube</label>
<input type="text" name="youtube" value="{{ $setting->youtube }}">

<label>Logo</label>
<input type="file" name="logo">

<label>Background</label>
<input type="file" name="background">

<button type="submit">💾 Simpan Tampilan</button>

</form>

</div>

<!-- PREVIEW DASHBOARD -->
<div class="preview">

<h2>{{ $setting->title ?? 'NAMA INSTANSI' }}</h2>
<p>{{ $setting->address ?? '-' }}</p>
<p>{{ $setting->phone ?? '-' }}</p>

<h1>
{{ $queue->queue_number ?? '000' }}
</h1>

<p>LOKET {{ $queue->loket_id ?? '-' }}</p>

@if($setting->youtube)
<iframe width="100%" height="250"
src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::after($setting->youtube,'v=') }}"
allowfullscreen>
</iframe>
@endif

</div>

</div>

</body>
</html>