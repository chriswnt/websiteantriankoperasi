<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>

<style>
body{
font-family:sans-serif;
margin:0;
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

.sidebar h2{
color:#FBB03C;
}

.sidebar a{
display:block;
color:white;
text-decoration:none;
margin:10px 0;
}

/* CONTENT */
.content{
flex:1;
padding:20px;
background:#f5f5f5;
}

/* CARD */
.card{
background:white;
padding:15px;
margin-bottom:20px;
border-radius:8px;
}

/* BUTTON */
button{
background:#FBB03C;
border:none;
padding:8px 12px;
cursor:pointer;
}

/* INPUT */
input, select{
padding:8px;
margin:5px 0;
width:100%;
}
</style>

</head>

<body>

<div class="sidebar">
<h2>ADMIN</h2>

<a href="/admin">Dashboard</a>
<a href="/admin/setting">Kelola Tampilan</a>
<a href="/admin/user">Manajemen User</a>
<a href="/logout">Logout</a>
</div>

<div class="content">

<h1>Dashboard Admin</h1>

<div class="card">
<h3>Jumlah Antrian Hari Ini</h3>

<p>Loket 1: 0</p>
<p>Loket 2: 0</p>
<p>Loket 3: 0</p>
<p>Loket 4: 0</p>
</div>

<!-- TAMBAH USER -->
<div class="card">
<h3>Tambah User</h3>

<form action="/admin/user" method="POST">
@csrf

<input type="text" name="name" placeholder="Nama">
<input type="email" name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">

<select name="role">
<option value="admin">Admin</option>
<option value="officer">Officer</option>
</select>

<button type="submit">Tambah</button>
</form>
</div>

<!-- SETTING -->
<div class="card">
<h3>Pengaturan Tampilan</h3>

<form action="/admin/settings" method="POST" enctype="multipart/form-data">
@csrf

<input type="text" name="youtube" placeholder="Link YouTube" value="{{ $setting->youtube ?? '' }}">

<input type="file" name="logo">

<input type="file" name="background">

<button type="submit">Simpan Tampilan</button>

</form>
</div>

<!-- LIST USER -->
<div class="card">
<h3>Daftar User</h3>

<table border="1" width="100%" cellpadding="10">
<tr>
<th>Nama</th>
<th>Email</th>
<th>Role</th>
<th>Aksi</th>
</tr>

@foreach($users as $user)
<tr>
<td>{{$user->name}}</td>
<td>{{$user->email}}</td>
<td>{{$user->role}}</td>
<td>
<a href="/admin/delete/{{$user->id}}">
<button>Hapus</button>
</a>
</td>
</tr>
@endforeach

</table>

</div>

</div>

</body>
</html>