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
            --text: #153c34;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: #ffffff;
        }

        .page {
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

      
     .page::before {
    content: "";
    position: absolute;
    inset: 0;
    background: url("{{ asset('assets/madanibag.png') }}") center/cover no-repeat;
    filter: grayscale(25%) contrast(1.12);
    opacity: 0.7;
    z-index: 0;
}

.page::after {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            180deg,
            rgba(255,255,255,0.42) 0%,
            rgba(255,255,255,0.58) 50%,
            rgba(255,255,255,0.72) 100%
        );
    z-index: 1;
}
        .topbar,
        .main {
            position: relative;
            z-index: 2;
        }

        .topbar {
            padding: 28px 42px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar img {
            width: 58px;
            height: auto;
            object-fit: contain;
        }

        .brand-text strong {
            display: block;
            font-size: 19px;
            color: var(--green);
            line-height: 1.2;
        }

        .brand-text span {
            font-size: 13px;
            color: #63756f;
        }

        .main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 24px 60px;
        }

        .content {
            width: 100%;
            max-width: 980px;
            text-align: center;
        }

        .label {
            display: inline-block;
            margin-bottom: 14px;
            color: var(--gold-dark);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: 52px;
            line-height: 1.1;
            color: var(--green);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        h1 span {
            color: var(--gold);
        }

        .subtitle {
            margin: 14px auto 42px;
            max-width: 620px;
            font-size: 17px;
            line-height: 1.6;
            color: #657670;
        }

        .service-list {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .btn {
            width: 100%;
            min-height: 86px;
            border: none;
            border-radius: 4px;
            background: var(--gold);
            color: var(--green);
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 7px 0 var(--gold-dark);
            transition: 0.12s ease;
            text-transform: uppercase;
        }

        .btn:hover {
            transform: translateY(-3px);
            background: #ffc257;
            box-shadow: 0 10px 0 var(--gold-dark);
        }

        .btn:active {
            transform: translateY(4px);
            box-shadow: 0 3px 0 var(--gold-dark);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .message {
            width: 100%;
            max-width: 760px;
            margin: 22px auto 0;
            padding: 14px;
            border-radius: 4px;
            display: none;
            font-weight: bold;
            text-align: center;
        }

        .success {
            background: #d9f4e3;
            color: #14532d;
            border: 1px solid #bce8cd;
        }

        .error {
            background: #ffe1e1;
            color: #842020;
            border: 1px solid #ffc4c4;
        }

        .back-home {
            display: inline-block;
            margin-top: 24px;
            padding: 14px 34px;
            border-radius: 4px;
            color: white;
            background: var(--green);
            text-decoration: none;
            font-weight: 800;
            transition: 0.12s ease;
        }

        .back-home:hover {
            background: var(--gold);
            color: var(--green);
        }

        .status-box {
            width: 100%;
            max-width: 900px;
            margin: 42px auto 0;
            padding-top: 26px;
            border-top: 1px solid rgba(17,67,56,0.18);
        }

        .status-header {
            margin-bottom: 18px;
        }

        .status-header h3 {
            margin: 0;
            color: var(--green);
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-header span {
            display: block;
            margin-top: 4px;
            color: #6b7c76;
            font-size: 14px;
        }

        .officer-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .officer {
            background: rgba(255,255,255,0.78);
            border: 1px solid rgba(17,67,56,0.12);
            border-bottom: 4px solid var(--gold);
            padding: 18px;
            text-align: left;
        }

        .officer-name {
            font-weight: 800;
            color: var(--green);
            font-size: 15px;
        }

        .officer-info {
            margin-top: 4px;
            color: #66746f;
            font-size: 13px;
        }

        .counter {
            margin-top: 14px;
            font-size: 38px;
            line-height: 1;
            color: var(--gold);
            font-weight: 900;
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 22px;
            }

            h1 {
                font-size: 34px;
            }

            .subtitle {
                font-size: 15px;
                margin-bottom: 28px;
            }

            .service-list {
                grid-template-columns: 1fr;
            }

            .btn {
                min-height: 68px;
                font-size: 16px;
            }

            .officer-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="page">

    <div class="topbar">
        <img src="{{ asset('assets/Logo Pack-02.png') }}" alt="Logo">
        <div class="brand-text">
            <strong>Credit Union Madani</strong>
            <span>Sistem antrean layanan</span>
        </div>
    </div>

    <main class="main">
        <div class="content">

            <div class="label">Layanan Digital</div>

            <h1>Ambil Nomor <span>Antrean</span></h1>
            <p class="subtitle">
                Silakan pilih layanan yang Anda butuhkan untuk mengambil nomor antrean.
            </p>

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
                    <span>Jumlah antrean yang selesai diproses hari ini</span>
                </div>

                <div class="officer-list" id="officerStatus">
                    <div class="officer">
                        <div class="officer-name">Memuat data...</div>
                        <div class="officer-info">Mohon tunggu</div>
                        <div class="counter">-</div>
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

function loadOfficerStatus() {
    fetch('/queue-status')
        .then(res => res.json())
        .then(data => {
            const wrapper = document.getElementById('officerStatus');
            wrapper.innerHTML = '';

            if (!data || data.length === 0) {
                wrapper.innerHTML = `
                    <div class="officer">
                        <div class="officer-name">Belum ada antrean selesai</div>
                        <div class="officer-info">Data akan muncul setelah officer menyelesaikan antrean</div>
                        <div class="counter">0</div>
                    </div>
                `;
                return;
            }

            data.forEach(item => {
                wrapper.innerHTML += `
                    <div class="officer">
                        <div class="officer-name">${item.officer_name ?? 'Officer'}</div>
                        <div class="officer-info">${item.service_name ?? 'Layanan'}</div>
                        <div class="counter">${item.total_done}</div>
                    </div>
                `;
            });
        })
        .catch(() => {
            const wrapper = document.getElementById('officerStatus');
            wrapper.innerHTML = `
                <div class="officer">
                    <div class="officer-name">Gagal memuat data</div>
                    <div class="officer-info">Periksa route /queue-status</div>
                    <div class="counter">!</div>
                </div>
            `;
        });
}

loadOfficerStatus();
setInterval(loadOfficerStatus, 5000);
</script>

</body>
</html>