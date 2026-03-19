<!DOCTYPE html>
<html>
<head>

<title>Ambil Antrian</title>

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
body{
margin:0;
font-family:Arial;
background:#114338;
color:white;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.container{
text-align:center;
}

h2{
color:#FBB03C;
margin-bottom:40px;
}

.btn{
display:block;
width:300px;
margin:15px auto;
padding:20px;
font-size:20px;
border:none;
border-radius:10px;
cursor:pointer;
background:#FBB03C;
color:#114338;
font-weight:bold;
transition:0.3s;
}

.btn:hover{
background:white;
transform:scale(1.05);
}
</style>

</head>

<body>

<div class="container">

<h2>Pilih Layanan</h2>

<button class="btn" onclick="ambil(1)">TARIK / SETOR</button>
<button class="btn" onclick="ambil(3)">ADMINISTRASI</button>
<button class="btn" onclick="ambil(2)">PINJAMAN</button>

</div>

<script>
function ambil(serviceId){

fetch('/ambil', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
        service_id: serviceId
    })
})
.then(res => res.text())
.then(() => {
    alert('✅ Nomor antrian berhasil diambil');
})
.catch(err => console.error(err));

}
</script>

</body>
</html>