<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrian</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --green: #114338;
            --green-dark: #082d26;
            --gold: #FBB03C;
            --gold-dark: #d99222;
            --soft: #f5f7f4;
            --text: #143c34;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--soft);
            color: var(--text);
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 46% 54%;
        }

        /* LEFT IMAGE */
        .image-side {
            position: relative;
            background: url("{{ asset('assets/madanibag.png') }}") center/cover no-repeat;
            overflow: hidden;
        }

        .image-side::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(
                    135deg,
                    rgba(17,67,56,0.88) 0%,
                    rgba(17,67,56,0.48) 52%,
                    rgba(251,176,60,0.28) 100%
                );
        }

        .brand {
            position: relative;
            z-index: 2;
            padding: 34px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .brand img {
            width: 58px;
            height: auto;
            object-fit: contain;
        }

        .brand strong {
            display: block;
            font-size: 19px;
            line-height: 1.2;
        }

        .brand span {
            font-size: 13px;
            opacity: 0.9;
        }

        .hero-text {
            position: absolute;
            z-index: 2;
            left: 36px;
            bottom: 46px;
            color: white;
            max-width: 420px;
        }

        .hero-text small {
            display: inline-block;
            margin-bottom: 12px;
            padding: 7px 12px;
            border-radius: 4px;
            background: var(--gold);
            color: var(--green);
            font-weight: 700;
        }

        .hero-text h1 {
            margin: 0;
            font-size: 42px;
            line-height: 1.15;
        }

        .hero-text h1 span {
            color: var(--gold);
        }

        .hero-text p {
            margin: 12px 0 0;
            line-height: 1.6;
            font-size: 15px;
            opacity: 0.95;
        }

        /* RIGHT CONTENT */
        .content-side {
            padding: 34px 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top right, rgba(251,176,60,0.18), transparent 32%),
                linear-gradient(180deg, #ffffff, #f7f8f5);
        }

        .panel {
            width: 100%;
            max-width: 620px;
        }

        .section-title {
            margin-bottom: 22px;
        }

        .section-title small {
            color: var(--gold-dark);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            font-size: 13px;
        }

        .section-title h2 {
            margin: 6px 0 6px;
            font-size: 34px;
            color: var(--green);
        }

        .section-title p {
            margin: 0;
            color: #66746f;
            font-size: 15px;
        }

        .service-list {
            display: grid;
            gap: 13px;
            margin-bottom: 24px;
        }

        .btn {
            width: 100%;
            height: 68px;
            border: none;
            border-radius: 8px;
            background: var(--gold);
            color: var(--green);
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 5px 0 var(--gold-dark);
            transition: 0.12s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            background: #ffc25a;
        }

        .btn:active {
            transform: translateY(3px);
            box-shadow: 0 2px 0 var(--gold-dark);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* OFFICER STATUS */
        .status-box {
            margin-top: 24px;
            padding: 20px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e5ebe8;
            box-shadow: 0 10px 28px rgba(17,67,56,0.08);
        }

        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .status-header h3 {
            margin: 0;
            font-size: 18px;
            color: var(--green);
        }

        .status-header span {
            font-size: 12px;
            color: #66746f;
        }

        .officer-list {
            display: grid;
            gap: 10px;
        }

        .officer {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 12px;
            padding: 13px 14px;
            border-radius: 8px;
            background: #f4f7f5;
            border-left: 5px solid var(--gold);
        }

        .officer-name {
            font-weight: 700;
            color: var(--green);
            font-size: 15px;
        }

        .officer-info {
            font-size: 13px;
            color: #66746f;
            margin-top: 3px;
        }

        .counter {
            min-width: 46px;
            height: 38px;
            border-radius: 6px;
            background: var(--green);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .message {
            margin-top: 18px;
            padding: 13px;
            border-radius: 8px;
            display: none;
            font-weight: bold;
            text-align: center;
        }

        .success {
            background: #d9f4e3;
            color: #14532d;
        }

        .error {
            background: #ffe1e1;
            color: #842020;
        }

        .back-home {
            display: block;
            margin-top: 16px;
            padding: 14px;
            text-align: center;
            text-decoration: none;
            color: var(--green);
            background: #edf2ef;
            border-radius: 8px;
            font-weight: 700;
        }

        .back-home:hover {
            background: var(--green);
            color: white;
        }

        @media (max-width: 900px) {
            .page {
                grid-template-columns: 1fr;
            }

            .image-side {
                min-height: 360px;
            }

            .content-side {
                padding: 28px 20px;
            }

            .hero-text h1 {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>

<div class="page">

    <section class="image-side">
        <div class="brand">
            <img src="{{ asset('assets/Logo Pack-02.png') }}" alt="Logo">
            <div>
                <strong>Credit Union Madani</strong>
                <span>Sistem antrean layanan</span>
            </div>
        </div>

        <div class="hero-text">
            <small>LAYANAN DIGITAL</small>
            <h1>Ambil Nomor <span>Antrean</span></h1>
            <p>Pilih layanan yang Anda butuhkan. Sistem akan memproses nomor antrean secara otomatis.</p>
        </div>
    </section>

    <main class="content-side">
        <div class="panel">

            <div class="section-title">
                <small>Menu Layanan</small>
                <h2>Pilih Layanan</h2>
                <p>Silakan tekan salah satu tombol layanan di bawah ini.</p>
            </div>

            <div class="service-list">
                @foreach($services as $service)
                    <button class="btn" onclick="ambil({{ $service->id }}, this)">
                        {{ strtoupper($service->name) }}
                    </button>
                @endforeach
            </div>

            <div id="msgBox" class="message"></div>

            <a href="{{ route('home') }}" class="back-home">← Kembali ke Beranda</a>

            <div class="status-box">
                <div class="status-header">
                    <h3>Status Officer</h3>
                    <span>Antrean diproses hari ini</span>
                </div>

                <div class="officer-list">
                    <div class="officer">
                        <div>
                            <div class="officer-name">Officer 1</div>
                            <div class="officer-info">Teller / Tarik Setor</div>
                        </div>
                        <div class="counter">12</div>
                    </div>

                    <div class="officer">
                        <div>
                            <div class="officer-name">Officer 2</div>
                            <div class="officer-info">Pengajuan Pinjaman</div>
                        </div>
                        <div class="counter">8</div>
                    </div>

                    <div class="officer">
                        <div>
                            <div class="officer-name">Officer 3</div>
                            <div class="officer-info">Administrasi Anggota</div>
                        </div>
                        <div class="counter">15</div>
                    </div>
                </div>
            </div>

        </div>
    </main>

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