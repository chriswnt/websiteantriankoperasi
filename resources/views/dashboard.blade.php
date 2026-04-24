<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard Antrean Real-time</title>

    <style>
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
            background: #114338; 
            color: white; 
            overflow: hidden; 
            cursor: pointer; 
        }

        .header { 
            background: #114338; 
            color: #FBB03C; 
            padding: 25px 40px; 
            display:flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 4px solid #FBB03C; 
        }

        .container { 
            display: flex; 
            gap: 25px; 
            padding: 25px 40px; 
            align-items: stretch; 
        }

        .main-queue { 
            background: #FBB03C; 
            color: #114338; 
            width: 50%; 
            text-align: center; 
            padding: 80px 40px; 
            border-radius: 15px; 
            box-sizing: border-box;
        }

        .queue-number { 
            font-size: 200px; 
            font-weight: bold; 
            margin: 20px 0; 
        }

        .layanan { 
            font-size: 40px; 
            font-weight: bold; 
        }

        .video { 
            width: 50%;
            display: flex;
        }

        .video-box{
            width: 100%;
            height: 100%;
            border: 4px solid #FBB03C;
            border-radius: 15px;
            overflow: hidden;
            box-sizing: border-box;
            background: #000;
            position: relative;
        }

        .video iframe { 
            position: absolute;
            top: 50%;
            left: 50%;
            width: 160%;
            height: 160%;
            transform: translate(-50%, -50%);
            border: none;
            pointer-events: none;
            display: block;
        }

        .loket-container { 
            display: flex; 
            gap: 20px; 
            padding: 0 40px 40px 40px; 
        }

        .loket-box { 
            flex: 1; 
            background: #114338; 
            color: #FBB03C; 
            padding: 40px; 
            border-radius: 15px; 
            text-align: center; 
            border: 3px solid #FBB03C; 
        }

        .nomor { 
            font-size: 90px; 
            font-weight: bold; 
        }

        .status-audio {
            position: fixed;
            bottom: 10px;
            left: 10px;
            font-size: 12px;
            background: rgba(0,0,0,0.5);
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body onclick="aktifkanAudio()">

@php
    $youtubeUrl = $setting->youtube ?? '';

    $youtubeParams = 'enablejsapi=1&autoplay=1&mute=1&loop=1&controls=0&modestbranding=1&rel=0&playsinline=1';

    $youtubeEmbedUrl = 'https://www.youtube.com/embed/jmKRgqWGrWc?' . $youtubeParams . '&playlist=jmKRgqWGrWc';

    if (!empty($youtubeUrl)) {
        $parsedUrl = parse_url(trim($youtubeUrl));
        $host = $parsedUrl['host'] ?? '';
        $path = $parsedUrl['path'] ?? '';
        $queryString = $parsedUrl['query'] ?? '';

        parse_str($queryString, $query);

        $videoId = null;
        $playlistId = $query['list'] ?? null;

        if (str_contains($host, 'youtu.be')) {
            $videoId = trim($path, '/');
        } elseif (str_contains($host, 'youtube.com')) {
            if (!empty($query['v'])) {
                $videoId = $query['v'];
            }

            if (str_contains($path, '/embed/')) {
                $segments = explode('/embed/', $path);
                $videoId = $segments[1] ?? $videoId;
            }
        }

        if (!empty($playlistId)) {
            $youtubeEmbedUrl = 'https://www.youtube.com/embed/videoseries?list=' . $playlistId . '&' . $youtubeParams;
        } elseif (!empty($videoId)) {
            $youtubeEmbedUrl = 'https://www.youtube.com/embed/' . $videoId . '?' . $youtubeParams . '&playlist=' . $videoId;
        }
    }
@endphp

<div class="status-audio" id="status-text">
    Status: 🔇 Klik layar untuk aktifkan suara
</div>

<div class="header">
    <div>
        <h2>{{ $setting->title ?? 'NAMA INSTANSI' }}</h2>
        <p>{{ $setting->address ?? '-' }}</p>
    </div>

    <div style="text-align: right;">
        <div id="jam" style="font-size: 28px; font-weight: bold;"></div>
        <div id="tanggal"></div>
    </div>
</div>

<div class="container">

    <div class="main-queue">
        <h2>ANTREAN DIPANGGIL</h2>
        <div class="queue-number" id="main-number">000</div>
        <div class="layanan" id="layanan-txt">-</div>
    </div>

    <div class="video">
        <div class="video-box">
            <iframe id="video-yt" 
                src="{{ $youtubeEmbedUrl }}"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>

</div>

<div class="loket-container">
    <div class="loket-box">
        <h3>TELLER</h3>
        <div class="nomor" id="teller">-</div>
    </div>

    <div class="loket-box">
        <h3>ADMINISTRASI</h3>
        <div class="nomor" id="administrasi">-</div>
    </div>

    <div class="loket-box">
        <h3>PINJAMAN</h3>
        <div class="nomor" id="pinjaman">-</div>
    </div>
</div>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>

<script>
var player;

function onYouTubeIframeAPIReady() {
    player = new YT.Player('video-yt');
}

function updateClock(){
    const now = new Date();
    document.getElementById("jam").innerHTML = now.toLocaleTimeString('id-ID');
    document.getElementById("tanggal").innerHTML = now.toLocaleDateString('id-ID', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
}

setInterval(updateClock, 1000);
updateClock();

let speechQueue = [];
let isSpeaking = false;
let previousMainNumber = null;

function formatNomorAntrean(nomor) {
    nomor = String(nomor || '').trim();

    let huruf = nomor.match(/[A-Za-z]+/)?.[0] || '';
    let angka = nomor.match(/\d+/)?.[0] || '';

    if (angka !== '') {
        angka = parseInt(angka, 10);
    }

    if (huruf && angka !== '') {
        return `${huruf.split('').join(' ')} ${angka}`;
    }

    return nomor;
}

function formatLayanan(layanan) {
    layanan = String(layanan || '-').toLowerCase();

    if (layanan === '-' || layanan === '') {
        return 'konter pelayanan';
    }

    return `konter ${layanan}`;
}

function panggilSuara(nomor, layanan) {
    if (isSpeaking) {
        speechQueue.push({nomor, layanan});
        return;
    }

    isSpeaking = true;
    window.speechSynthesis.cancel();

    if (player && typeof player.setVolume === "function") player.setVolume(10);

    setTimeout(() => {
        const synth = window.speechSynthesis;

        const nomorDibaca = formatNomorAntrean(nomor);
        const layananDibaca = formatLayanan(layanan);

        const msg = new SpeechSynthesisUtterance(`Nomor Antrean, ${nomorDibaca}. Silakan menuju ${layananDibaca}.`);

        msg.lang = 'id-ID';
        msg.rate = 0.8;
        msg.pitch = 1;
        msg.volume = 1;

        msg.onend = function() {
            setTimeout(() => {
                if (player && typeof player.setVolume === "function") player.setVolume(100);
                isSpeaking = false;

                if (speechQueue.length > 0) {
                    const next = speechQueue.shift();
                    panggilSuara(next.nomor, next.layanan);
                }
            }, 1000);
        };

        synth.speak(msg);
    }, 500);
}

function loadQueue(withSound = false){
    fetch('/dashboard/data?time=' + new Date().getTime(), {
        method: 'GET',
        cache: 'no-store',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }) 
    .then(res => res.json())
    .then(data => {

        let mainNum = data.main_number && data.main_number !== '-' ? data.main_number : '000';
        let mainSvc = data.main_service && data.main_service !== '-' ? data.main_service.toUpperCase() : '-';

        document.getElementById('main-number').innerText = mainNum;
        document.getElementById('layanan-txt').innerText = mainSvc;

        document.getElementById('teller').innerText = data.teller || '000';
        document.getElementById('administrasi').innerText = data.administrasi || '000';
        document.getElementById('pinjaman').innerText = data.pinjaman || '000';

        if(withSound && mainNum !== '000') {
            if (mainNum !== previousMainNumber) {
                panggilSuara(mainNum, mainSvc);
                previousMainNumber = mainNum;
            }
        }
    })
    .catch(err => {
        console.error('Gagal memuat data dashboard:', err);
    });
}

function aktifkanAudio() {
    window.speechSynthesis.speak(new SpeechSynthesisUtterance(""));
    if (player && typeof player.unMute === "function") player.unMute();
    document.getElementById('status-text').innerHTML = "Status: ✅ Suara Aktif";
}

function autoRefreshAtMidnight() {
    let now = new Date();
    let night = new Date(now.getFullYear(), now.getMonth(), now.getDate()+1,0,0,1);
    let ms = night - now;

    setTimeout(() => location.reload(true), ms);
}

Pusher.logToConsole = false;

var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
    forceTLS: true
});

pusher.connection.bind('connected', function() {
    console.log('Pusher dashboard connected');
});

pusher.connection.bind('error', function(err) {
    console.error('Pusher dashboard error:', err);
});

var channel = pusher.subscribe('antrean-channel');

channel.bind('pusher:subscription_succeeded', function() {
    console.log('Dashboard berhasil subscribe antrean-channel');
});

channel.bind('AntreanUpdate', function(data) {
    console.log('Event AntreanUpdate diterima dashboard:', data);
    loadQueue(true);
});

channel.bind('.AntreanUpdate', function(data) {
    console.log('Event .AntreanUpdate diterima dashboard:', data);
    loadQueue(true);
});

channel.bind('App\\Events\\AntreanUpdate', function(data) {
    console.log('Event App\\Events\\AntreanUpdate diterima dashboard:', data);
    loadQueue(true);
});

channel.bind_global(function(eventName, data) {
    if (
        eventName === 'AntreanUpdate' ||
        eventName === '.AntreanUpdate' ||
        eventName === 'App\\Events\\AntreanUpdate'
    ) {
        console.log('Global event dashboard:', eventName, data);
        loadQueue(true);
    }
});

loadQueue(false);
autoRefreshAtMidnight();
</script>

</body>
</html>