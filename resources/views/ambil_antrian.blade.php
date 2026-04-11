<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#114338;
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            padding:20px;
            box-sizing:border-box;
        }

        .container{
            text-align:center;
            width:100%;
            max-width:500px;
        }

        h2{
            color:#FBB03C;
            margin-bottom:15px;
            font-size:34px;
        }

        p{
            margin-bottom:35px;
            opacity:0.9;
        }

        .btn{
            display:block;
            width:100%;
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
            transform:scale(1.03);
        }

        .btn:disabled{
            opacity:0.7;
            cursor:not-allowed;
            transform:none;
        }

        .message{
            margin-top:20px;
            padding:12px 14px;
            border-radius:8px;
            display:none;
            font-weight:bold;
        }

        .success{
            background:#d4edda;
            color:#155724;
        }

        .error{
            background:#f8d7da;
            color:#721c24;
        }

        .back-home{
            display:inline-block;
            margin-top:25px;
            color:#FBB03C;
            text-decoration:none;
            font-weight:bold;
        }

        .back-home:hover{
            text-decoration:underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Pilih Layanan</h2>
    <p>Silakan pilih layanan untuk mengambil nomor antrean</p>

    @foreach($services as $service)
        <button class="btn" onclick="ambil({{ $service->id }}, this)">
            {{ strtoupper($service->name) }}
        </button>
    @endforeach

    <div id="msgBox" class="message"></div>

    <a href="{{ route('home') }}" class="back-home">← Kembali ke Beranda</a>
</div>

<script>
function showMessage(type, text) {
    const box = document.getElementById('msgBox');
    box.className = 'message ' + type;
    box.style.display = 'block';
    box.innerText = text;
}

function ambil(serviceId, btnEl) {
    const oldText = btnEl.innerHTML;
    btnEl.disabled = true;
    btnEl.innerHTML = 'Memproses...';

    fetch('/ambil', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            service_id: serviceId
        })
    })
    .then(async res => {
        const data = await res.json();
        return { status: res.status, data };
    })
    .then(({ status, data }) => {
        if (status >= 200 && status < 300 && data.success) {
            showMessage('success', data.message || 'Nomor antrean berhasil diambil');
        } else {
            showMessage('error', data.message || 'Gagal mengambil antrean');
        }
    })
    .catch(() => {
        showMessage('error', 'Terjadi kesalahan pada sistem.');
    })
    .finally(() => {
        btnEl.disabled = false;
        btnEl.innerHTML = oldText;
    });
}
</script>

</body>
</html>