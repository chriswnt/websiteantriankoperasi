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
}

.card{
flex:1;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
text-align:center;
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

<h2>Dashboard Admin</h2>

<div class="cards">

<div class="card">
<h3>Jumlah User</h3>
<h1>{{ $totalUsers }}</h1>
</div>

<div class="card">
<h3>Loket 1</h3>
<h1>{{ $loket1 }}</h1>
</div>

<div class="card">
<h3>Loket 2</h3>
<h1>{{ $loket2 }}</h1>
</div>

<div class="card">
<h3>Loket 3</h3>
<h1>{{ $loket3 }}</h1>
</div>

<div class="card">
<h3>Loket 4</h3>
<h1>{{ $loket4 }}</h1>
</div>

</div>

</div>

</body>
</html>