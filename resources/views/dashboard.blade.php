<!DOCTYPE html>
<html>
<head>

<title>Dashboard Antrean</title>

<style>

body{
margin:0;
font-family:Arial;
background:#114338;
color:white;
overflow:hidden;
}

/* HEADER */

.header{
background:#114338;
color:#FBB03C;
padding:25px 40px;
display:flex;
justify-content:space-between;
align-items:center;
border-bottom:4px solid #FBB03C;
}

.bank-info h2{
margin:0;
color:#FBB03C;
}

/* MAIN */

.container{
display:flex;
gap:25px;
padding:25px 40px;
}

/* ANTRIAN UTAMA */

.main-queue{
background:#FBB03C;
color:#114338;
width:50%;
text-align:center;
padding:80px 40px;
border-radius:15px;
}

.main-queue h2{
font-size:40px;
margin-bottom:10px;
}

.queue-number{
font-size:200px;
font-weight:bold;
}

.loket{
font-size:40px;
}

/* VIDEO */

.video{
width:50%;
display:flex;
}

.video iframe{
width:100%;
height:100%;
min-height:420px;
border-radius:15px;
border:4px solid #FBB03C;
}

/* LOKET BAWAH */

.loket-container{
display:flex;
gap:20px;
padding:0 40px 40px 40px;
}

.loket-box{
flex:1;
background:#114338;
color:#FBB03C;
padding:40px;
border-radius:15px;
text-align:center;
border:3px solid #FBB03C;
}

.loket-box h3{
font-size:28px;
margin:10px;
}

.nomor{
font-size:90px;
font-weight:bold;
}

</style>

</head>

<body>

<!-- HEADER -->

<div class="header">

<div class="bank-info">

<h2>BANK MANDIRI</h2>

<p>Jalan Ardan inten No.100, Bontang</p>

<p>Telp: 0812457984526</p>

</div>

<div class="clock">

<div id="jam"></div>
<div id="tanggal"></div>

</div>

</div>


<!-- MAIN -->

<div class="container">

<div class="main-queue">

<h2>Antrian</h2>

<div class="queue-number">
{{ $queue->queue_number ?? '000' }}
</div>

<div class="loket">
LOKET {{ $queue->loket_id ?? '-' }}
</div>

</div>

<div class="video">

<iframe
src="https://youtu.be/embed/3JAPQHauiHnJHMsZ"
allowfullscreen>
</iframe>
</div>

</div>


<!-- LOKET BAWAH -->

<div class="loket-container">

<div class="loket-box loket1">

<h3>Antrian</h3>

<div class="nomor">

{{ $loket1->queue_number ?? '-' }}

</div>

<h3>LOKET 1</h3>

</div>


<div class="loket-box loket2">

<h3>Antrian</h3>

<div class="nomor">

{{ $loket2->queue_number ?? '-' }}

</div>

<h3>LOKET 2</h3>

</div>


<div class="loket-box loket3">

<h3>Antrian</h3>

<div class="nomor">

{{ $loket3->queue_number ?? '-' }}

</div>

<h3>LOKET 3</h3>

</div>


<div class="loket-box loket4">

<h3>Antrian</h3>

<div class="nomor">

{{ $loket4->queue_number ?? '-' }}

</div>

<h3>LOKET 4</h3>

</div>

</div>


<script>

function updateClock(){

const now = new Date();

document.getElementById("jam").innerHTML =
now.toLocaleTimeString();

document.getElementById("tanggal").innerHTML =
now.toLocaleDateString();

}

setInterval(updateClock,1000);

</script>

</body>
</html>