<!DOCTYPE html>
<html>
<head>

<title>Ambil Nomor Antrean</title>

<style>

:root{
--emerald:#114338;
--gold:#FBB03C;
--ivory:#E9E2D9;
--grey:#808E91;
}

/* BODY */

body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:#f5f5f5;
}

/* HEADER */

.header{
background:white;
padding:15px;
text-align:center;
font-weight:bold;
font-size:22px;
border-bottom:2px solid #ddd;
}

.header span{
color:var(--gold);
}

/* CONTAINER */

.container{
width:90%;
margin:30px auto;
}

/* SERVICES GRID */

.services{
display:grid;
grid-template-columns:1fr 1fr;
gap:15px;
margin-bottom:30px;
}

/* BUTTON */

.service-btn{
width:100%;
padding:30px;
font-size:18px;
background:white;
border:1px solid #ddd;
border-radius:5px;
cursor:pointer;
transition:0.2s;
}

.service-btn:hover{
background:var(--gold);
color:white;
}

/* ANTRIAN */

.queue-section{
text-align:center;
margin-top:20px;
}

.queue-title{
margin:20px 0;
color:#666;
}

.queue-box{
background:white;
padding:30px;
border-radius:8px;
display:inline-block;
}

.queue-number{
font-size:60px;
font-weight:bold;
color:var(--emerald);
}

</style>

</head>

<body>

<div class="header">
#MILIK<span>BERSAMA</span>
</div>

<div class="container">

<div class="services">

@foreach($services as $service)

<form action="/ambil" method="POST">
@csrf

<input type="hidden" name="service_id" value="{{$service->id}}">

<button class="service-btn">
{{$service->name}}
</button>

</form>

@endforeach

</div>

<div class="queue-section">

<div class="queue-title">
ANTRIAN SAAT INI
</div>

@if(session('number'))

<div class="queue-box">

<div class="queue-number">
{{ session('number') }}
</div>

</div>

@endif

</div>

</div>

</body>
</html>