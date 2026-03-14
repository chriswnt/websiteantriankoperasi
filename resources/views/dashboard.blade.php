<!DOCTYPE html>
<html>

<head>
<title>Dashboard Antrian</title>

<style>
body{
margin:0;
font-family:Arial;
background:#0f172a;
color:white;
}

.header{
text-align:center;
padding:15px;
font-size:28px;
background:#1e293b;
}

.container{
display:flex;
height:90vh;
}

.video{
width:60%;
padding:10px;
}

.queue{
width:40%;
display:flex;
flex-direction:column;
gap:15px;
padding:15px;
}

.service-section{
flex:1;
}

.service-title{
font-size:24px;
margin-bottom:10px;
text-align:center;
}

.service-queues{
display:grid;
grid-template-columns:1fr 1fr;
gap:10px;
}

.box{
background:#2563eb;
border-radius:10px;
display:flex;
align-items:center;
justify-content:center;
font-size:40px;
height:100px;
}

.teller .teller-petugas{
display:flex;
gap:15px;
}

.teller .petugas-section{
flex:1;
}

.petugas-title{
font-size:18px;
margin-bottom:5px;
text-align:center;
}

.teller .service-queues{
grid-template-columns:1fr 1fr;
gap:10px;
}
</style>

<script>
function loadQueue(){
fetch('/queue-data')
.then(res => res.json())
.then(data => {
updateService('teller-petugas1', data.teller_petugas1);
updateService('teller-petugas2', data.teller_petugas2);
updateService('pinjaman', data.pinjaman);
updateService('admin', data.admin);
});
}

function updateService(serviceType, queues){
let container = document.getElementById(serviceType + '-queues');
let html = "";
queues.forEach(q => {
html += `<div class="box">${q.number}</div>`;
});
container.innerHTML = html;
}

setInterval(loadQueue,1000);
</script>

</head>

<body>

<div class="header">
DASHBOARD ANTRIAN
</div>

<div class="container">

<div class="video">
<iframe width="100%" height="100%"
src="https://www.youtube.com/embed/p6xX8feo5pw?autoplay=1&mute=1"
frameborder="0"
allowfullscreen>
</iframe>
</div>

<div class="queue">

<div class="service-section teller">
<div class="service-title">TELLER</div>
<div class="teller-petugas">
<div class="petugas-section">
<div class="petugas-title">Petugas 1</div>
<div class="service-queues" id="teller-petugas1-queues">
@foreach($tellerPetugas1 as $q)
<div class="box">{{ $q->number }}</div>
@endforeach
</div>
</div>
<div class="petugas-section">
<div class="petugas-title">Petugas 2</div>
<div class="service-queues" id="teller-petugas2-queues">
@foreach($tellerPetugas2 as $q)
<div class="box">{{ $q->number }}</div>
@endforeach
</div>
</div>
</div>
</div>

<div class="service-section pinjaman">
<div class="service-title">PINJAMAN</div>
<div class="service-queues" id="pinjaman-queues">
@foreach($pinjamanQueues as $q)
<div class="box">{{ $q->number }}</div>
@endforeach
</div>
</div>

<div class="service-section admin">
<div class="service-title">ADMIN</div>
<div class="service-queues" id="admin-queues">
@foreach($adminQueues as $q)
<div class="box">{{ $q->number }}</div>
@endforeach
</div>
</div>

</div>

</div>

</body>

</html>