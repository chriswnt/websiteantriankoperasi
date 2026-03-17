<!DOCTYPE html>
<html>
<head>

<title>Dashboard Antrean</title>

<style>

body{
margin:0;
font-family:Arial;
background: url('{{ $setting && $setting->background ? asset("storage/".$setting->background) : "" }}');
background-size:cover;
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
}

.video iframe{
width:100%;
height:420px;
border-radius:15px;
border:4px solid #FBB03C;
}

/* LOKET */
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

.nomor{
font-size:90px;
font-weight:bold;
}

</style>

</head>

<body>

<div class="header">

<div class="bank-info">
<h2>{{ $setting->title ?? 'NAMA INSTANSI' }}</h2>
<p>{{ $setting->address ?? '-' }}</p>
<p>Telp: {{ $setting->phone ?? '-' }}</p>
</div>

<div class="clock">
<div id="jam"></div>
<div id="tanggal"></div>
</div>

</div>

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

@php
$youtube_id = null;

if($setting && $setting->youtube){
    if(str_contains($setting->youtube, 'youtu.be')){
        $youtube_id = explode('?', last(explode('/', $setting->youtube)))[0];
    } else {
        $youtube_id = explode('?', \Illuminate\Support\Str::after($setting->youtube, 'v='))[0];
    }
}
@endphp

@if($youtube_id)
<iframe
src="https://www.youtube.com/embed/{{ $youtube_id }}?autoplay=1&mute=1"
allowfullscreen>
</iframe>
@endif

</div>

</div>

<div class="loket-container">

<div class="loket-box">
<h3>Antrian</h3>
<div class="nomor loket1">{{ $loket1->queue_number ?? '-' }}</div>
<h3>LOKET 1</h3>
</div>

<div class="loket-box">
<h3>Antrian</h3>
<div class="nomor loket2">{{ $loket2->queue_number ?? '-' }}</div>
<h3>LOKET 2</h3>
</div>

<div class="loket-box">
<h3>Antrian</h3>
<div class="nomor loket3">{{ $loket3->queue_number ?? '-' }}</div>
<h3>LOKET 3</h3>
</div>

<div class="loket-box">
<h3>Antrian</h3>
<div class="nomor loket4">{{ $loket4->queue_number ?? '-' }}</div>
<h3>LOKET 4</h3>
</div>

</div>

<script>

// JAM
function updateClock(){
    const now = new Date();

    document.getElementById("jam").innerHTML =
    now.toLocaleTimeString();

    document.getElementById("tanggal").innerHTML =
    now.toLocaleDateString();
}
setInterval(updateClock,1000);


// REALTIME UPDATE TANPA RELOAD
let lastQueue = "";

setInterval(() => {

    fetch('/dashboard-data')
    .then(res => res.json())
    .then(data => {

        document.querySelector('.queue-number').innerText =
            data.queue_number ?? '000';

        document.querySelector('.loket').innerText =
            "LOKET " + (data.loket ?? '-');

        document.querySelector('.loket1').innerText = data.loket1 ?? '-';
        document.querySelector('.loket2').innerText = data.loket2 ?? '-';
        document.querySelector('.loket3').innerText = data.loket3 ?? '-';
        document.querySelector('.loket4').innerText = data.loket4 ?? '-';

        // SUARA
        if(data.queue_number && data.queue_number !== lastQueue){
            lastQueue = data.queue_number;

            let msg = new SpeechSynthesisUtterance(
                "Nomor antrian " + data.queue_number +
                ", silakan ke loket " + data.loket
            );
            speechSynthesis.speak(msg);
        }

    });

}, 2000);

</script>

</body>
</html>