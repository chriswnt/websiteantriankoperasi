<!DOCTYPE html>
<html>
<head>

<title>Aplikasi Antrian</title>

<style>

:root{
--emerald:#114338;
--gold:#FBB03C;
}

body{
margin:0;
font-family:'Segoe UI',sans-serif;
color:white;
}

/* HERO BACKGROUND */

.hero{
height:100vh;
display:flex;
align-items:center;
padding-left:120px;

background:
linear-gradient(
90deg,
rgba(17,67,56,0.95) 0%,
rgba(17,67,56,0.85) 35%,
rgba(17,67,56,0.6) 55%,
rgba(17,67,56,0) 70%
),
url("https://lh3.googleusercontent.com/p/AF1QipMayafJhUcGx2uQ8g4GnA5b-ai2hZJWpgG5u2Jj=s1360-w1360-h1020-rw");

background-size:cover;
background-position:center;
}

/* CONTENT */

.content{
max-width:550px;
}

/* LOGO */

.logo{
height:110px;
margin-bottom:25px;
}

/* TITLE */

h1{
font-size:60px;
margin:0 0 20px 0;
line-height:1.1;
}

/* PARAGRAPH */

p{
font-size:18px;
opacity:0.9;
margin-bottom:50px;
}

/* MENU GRID */

.menu{
display:flex;
gap:30px;
}

/* CARD */

.card{
flex:1;
text-align:center;
padding:35px;
border-radius:12px;
text-decoration:none;
font-weight:600;
font-size:20px;
transition:0.25s;
}

/* COLORS */

.btn-gold{
background:var(--gold);
color:var(--emerald);
}

.btn-outline{
border:2px solid var(--gold);
color:var(--gold);
}

/* HOVER */

.card:hover{
transform:translateY(-6px);
}

.btn-outline:hover{
background:var(--gold);
color:var(--emerald);
}

</style>

</head>

<body>

<section class="hero">

<div class="content">

<img src="{{ asset('assets/Logo Pack-11 (1).png') }}" class="logo">

<h1>
Aplikasi <br>
Antrian Pengunjung
</h1>

<p>
Kelola antrean pengunjung dengan mudah dan cepat.
Ambil nomor antrean atau pantau dashboard secara realtime.
</p>

<div class="menu">

<a href="/ambil" class="card btn-gold">
Ambil<br>Antrian
</a>

<a href="/" class="card btn-outline">
Dashboard
</a>

<a href="/officer" class="card btn-outline">
Petugas
</a>

</div>

</div>

</section>

</body>
</html>