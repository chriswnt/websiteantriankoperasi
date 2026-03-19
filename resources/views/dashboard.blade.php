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
        .container { display: flex; gap: 25px; padding: 25px 40px; }
        .main-queue { 
            background: #FBB03C; 
            color: #114338; 
            width: 50%; 
            text-align: center; 
            padding: 80px 40px; 
            border-radius: 15px; 
        }
        .queue-number { font-size: 200px; font-weight: bold; margin: 20px 0; }
        .layanan { font-size: 40px; font-weight: bold; }
        
        .video { width: 50%; }
        .video iframe { 
            width: 100%; 
            height: 420px; 
            border-radius: 15px; 
            border: 4px solid #FBB03C; 
            pointer-events: none; 
        }
        
        .loket-container { display: flex; gap: 20px; padding: 0 40px 40px 40px; }
        .loket-box { 
            flex: 1; 
            background: #114338; 
            color: #FBB03C; 
            padding: 40px; 
            border-radius: 15px; 
            text-align: center; 
            border: 3px solid #FBB03C; 
        }
        .nomor { font-size: 90px; font-weight: bold; }
        
        /* Notifikasi Kecil di Pojok */
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

<div class="status-audio" id="status-text">Status: 🔇 Klik layar untuk aktifkan suara</div>

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
        <iframe id="video-yt" 
            src="https://www.youtube.com/embed/jmKRgqWGrWc?enablejsapi=1&autoplay=1&mute=1&loop=1&playlist=jmKRgqWGrWc" 
            allowfullscreen>
        </iframe>
    </div>
</div>

<div class="loket-container">
    <div class="loket-box"><h3>TELLER</h3><div class="nomor" id="teller">-</div></div>
    <div class="loket-box"><h3>ADMINISTRASI</h3><div class="nomor" id="administrasi">-</div></div>
    <div class="loket-box"><h3>PINJAMAN</h3><div class="nomor" id="pinjaman">-</div></div>
</div>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>

<script>
// 1. YOUTUBE API
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('video-yt');
}

// 2. JAM & TANGGAL
function updateClock(){
    const now = new Date();
    document.getElementById("jam").innerHTML = now.toLocaleTimeString('id-ID');
    document.getElementById("tanggal").innerHTML = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}
setInterval(updateClock, 1000);
updateClock();

// 3. LOGIKA SUARA & ANTREAN
let speechQueue = [];
let isSpeaking = false;

function panggilSuara(nomor, layanan) {
    if (isSpeaking) {
        speechQueue.push({nomor, layanan});
        return;
    }

    isSpeaking = true;
    window.speechSynthesis.cancel(); // Reset jika ada suara macet

    // Pelankan Video
    if (player && typeof player.setVolume === "function") player.setVolume(10);

    // Langsung panggil suara manusia (Tanpa file MP3 eksternal agar tidak diblokir)
    setTimeout(() => {
        const synth = window.speechSynthesis;
        const eja = nomor.split('').join(' ');
        const msg = new SpeechSynthesisUtterance(`Nomor Antrean, ${eja}, Silakan menuju, ${layanan}`);
        
        msg.lang = 'id-ID';
        msg.rate = 0.8;
        msg.volume = 1;

        msg.onend = function() {
            setTimeout(() => {
                if (player && typeof player.setVolume === "function") player.setVolume(100);
                isSpeaking = false;
                // Cek apakah ada antrean suara berikutnya
                if (speechQueue.length > 0) {
                    const next = speechQueue.shift();
                    panggilSuara(next.nomor, next.layanan);
                }
            }, 1000);
        };

        msg.onerror = function() { isSpeaking = false; };

        synth.speak(msg);
        console.log("📢 Sedang memanggil: " + msg.text);
    }, 500);
}

// 4. LOAD DATA AJAX
function loadQueue(withSound = false){
    fetch('/dashboard/data') 
    .then(res => res.json())
    .then(data => {
        document.getElementById('main-number').innerText = data.main_number;
        document.getElementById('layanan-txt').innerText = data.main_service.toUpperCase();
        document.getElementById('teller').innerText = data.teller;
        document.getElementById('administrasi').innerText = data.administrasi;
        document.getElementById('pinjaman').innerText = data.pinjaman;

        if(withSound && data.main_number !== '000' && data.main_number !== '-') {
            panggilSuara(data.main_number, data.main_service);
        }
    })
    .catch(err => console.error('Gagal ambil data:', err));
}

// 5. AKTIVASI AUDIO
function aktifkanAudio() {
    window.speechSynthesis.speak(new SpeechSynthesisUtterance(""));
    if (player && typeof player.unMute === "function") player.unMute();
    document.getElementById('status-text').innerHTML = "Status: ✅ Suara Aktif";
    console.log("✅ Izin suara didapat!");
}

// 6. PUSHER
var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
    forceTLS: true
});

var channel = pusher.subscribe('antrean-channel');
channel.bind('AntreanUpdate', () => {
    console.log("⚡ Sinyal masuk!");
    loadQueue(true);
});

// Jalankan load pertama
loadQueue(false);
</script>

</body>
</html>