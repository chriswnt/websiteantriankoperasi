<!DOCTYPE html>
<html>
<head>
    <title>Kelola Tampilan</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo Pack-02.png') }}">
    <style>
        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            font-family:Arial, sans-serif;
            display:flex;
            min-height:100vh;
            background:#f4f6f9;
            color:#1f2937;
        }

        .sidebar{
            width:180px;
            background:#114338;
            color:white;
            min-height:100vh;
            padding:10px 12px;
            display:flex;
            flex-direction:column;
            flex-shrink:0;
        }

        .sidebar h2{
            color:#FBB03C;
            margin:0 0 24px 2px;
            font-size:20px;
            font-weight:800;
        }

        .sidebar .menu{
            display:flex;
            flex-direction:column;
            gap:10px;
        }

        .sidebar a{
            display:flex;
            align-items:center;
            width:100%;
            height:34px;
            color:white;
            padding:0 12px;
            text-decoration:none;
            border-radius:6px;
            font-size:13px;
            font-weight:700;
            line-height:1;
            transition:.2s ease-in-out;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,.12);
        }

        .sidebar a.active{
            background:rgba(255,255,255,.16);
        }

        .logout-form{
            margin-top:35px;
        }

        .logout-btn{
            width:100%;
            height:32px;
            background:#ef4438;
            color:white;
            border:none;
            border-radius:6px;
            font-size:11px;
            font-weight:700;
            cursor:pointer;
        }

        .logout-btn:hover{
            background:#d93b31;
        }

        .content{
            flex:1;
            padding:32px;
            overflow-x:hidden;
        }

        .content h2{
            margin:0 0 22px;
            font-size:26px;
            font-weight:800;
            color:#111827;
        }

        .setting-layout{
            display:grid;
            grid-template-columns:minmax(320px, 520px) 1fr;
            gap:28px;
            align-items:start;
        }

        .card{
            background:white;
            padding:26px;
            border-radius:16px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
            border:1px solid #e5e7eb;
        }

        label{
            display:block;
            margin-bottom:7px;
            font-weight:700;
            color:#374151;
            font-size:14px;
        }

        input{
            width:100%;
            padding:12px 13px;
            margin-bottom:16px;
            border-radius:10px;
            border:1px solid #d1d5db;
            outline:none;
            font-size:14px;
            transition:.2s ease-in-out;
            background:white;
        }

        input:focus{
            border-color:#114338;
            box-shadow:0 0 0 3px rgba(17,67,56,.12);
        }

        input[type="file"]{
            padding:10px;
            background:#f9fafb;
            cursor:pointer;
        }

        .logo-history,
        .bg-history{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:12px;
            border:1px solid #e5e7eb;
            background:#f9fafb;
            padding:10px;
            border-radius:10px;
        }

        .logo-history img{
            height:55px;
            width:55px;
            object-fit:contain;
            border:1px solid #d1d5db;
            border-radius:8px;
            padding:4px;
            background:white;
            flex-shrink:0;
        }

        .bg-history img{
            width:120px;
            height:70px;
            object-fit:cover;
            border-radius:8px;
            border:1px solid #d1d5db;
            background:white;
            flex-shrink:0;
        }

        .logo-info,
        .bg-info{
            display:flex;
            flex-direction:column;
            min-width:0;
        }

        .logo-name,
        .bg-name{
            font-size:12px;
            font-weight:800;
            color:#114338;
            word-break:break-all;
        }

        .logo-note,
        .bg-note{
            font-size:11px;
            color:#6b7280;
            margin-top:3px;
        }

        .yt-item{
            display:flex;
            gap:8px;
            margin-bottom:10px;
        }

        .yt-item input{
            flex:1;
            margin-bottom:0;
        }

        .add-btn{
            background:#114338;
            color:white;
            border:none;
            padding:9px 12px;
            border-radius:8px;
            font-size:13px;
            font-weight:700;
            cursor:pointer;
            margin-bottom:10px;
        }

        .add-btn:hover{
            background:#0d342c;
        }

        .remove-btn{
            background:#e74c3c;
            color:white;
            border:none;
            border-radius:8px;
            padding:0 11px;
            cursor:pointer;
            font-size:13px;
            font-weight:700;
        }

        .remove-btn:hover{
            background:#cf3f31;
        }

        .helper{
            font-size:12px;
            color:#6b7280;
            margin-top:0;
            margin-bottom:16px;
            line-height:1.5;
        }

        .save-btn{
            width:100%;
            padding:13px;
            background:#114338;
            color:white;
            border:none;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
            font-weight:800;
            transition:.2s ease-in-out;
            margin-top:6px;
        }

        .save-btn:hover{
            background:#0d342c;
            transform:translateY(-1px);
        }

        .preview-card{
            background:white;
            padding:18px;
            border-radius:16px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
            border:1px solid #e5e7eb;
        }

        .preview-title{
            font-size:15px;
            font-weight:800;
            margin:0 0 14px;
            color:#374151;
        }

        .preview{
            background:#114338;
            color:white;
            padding:34px 24px;
            border-radius:14px;
            text-align:center;
            overflow:hidden;
            min-height:300px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;

            @if(!empty($setting->background))
                background-image:
                    linear-gradient(rgba(17,67,56,.85), rgba(17,67,56,.85)),
                    url("{{ asset('storage/' . $setting->background) }}");
            @endif
        }

        .preview-logo{
            height:70px;
            width:auto;
            max-width:120px;
            object-fit:contain;
            margin-bottom:12px;
        }

        .preview h2{
            color:white;
            font-size:24px;
            margin:0 0 10px;
            font-weight:800;
        }

        .preview p{
            margin:4px 0;
            font-size:15px;
        }

        .preview h1{
            font-size:86px;
            line-height:1;
            margin:22px 0 14px;
            font-weight:900;
            letter-spacing:6px;
            color:white;
        }

        .youtube-box{
            margin-top:24px;
            width:100%;
            max-width:720px;
        }

        .youtube-box iframe{
            width:100%;
            height:250px;
            border:0;
            border-radius:14px;
            box-shadow:0 8px 22px rgba(0,0,0,.22);
        }

        .playlist-info{
            margin-top:10px;
            font-size:12px;
            color:#d1fae5;
        }

        @media (max-width: 992px){
            .setting-layout{
                grid-template-columns:1fr;
            }
        }

        @media (max-width: 768px){
            body{
                flex-direction:column;
            }

            .sidebar{
                width:100%;
                min-height:auto;
            }

            .content{
                padding:20px;
            }

            .content h2{
                font-size:22px;
            }

            .preview h1{
                font-size:60px;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>ADMIN</h2>

        <div class="menu">
            <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">Dashboard</a>

            <a href="/admin/user" class="{{ request()->is('admin/user*') ? 'active' : '' }}">
                Manajemen User
            </a>

            <a href="/admin/setting" class="{{ request()->is('admin/setting*') ? 'active' : '' }}">
                Kelola Tampilan
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="content">

        <h2>⚙️ Kelola Tampilan</h2>

        @php
            $youtubeRaw = $setting->youtube ?? '';
            $youtubeLinks = preg_split('/\r\n|\r|\n/', $youtubeRaw);
            $youtubeVideoIds = [];
            $youtubePlaylistId = null;

            foreach ($youtubeLinks as $youtubeUrl) {
                $youtubeUrl = trim($youtubeUrl);

                if (empty($youtubeUrl)) {
                    continue;
                }

                $parsedUrl = parse_url($youtubeUrl);
                $host = $parsedUrl['host'] ?? '';
                $path = $parsedUrl['path'] ?? '';
                $queryString = $parsedUrl['query'] ?? '';

                parse_str($queryString, $query);

                $videoId = null;

                if (!empty($query['list']) && empty($youtubePlaylistId)) {
                    $youtubePlaylistId = $query['list'];
                }

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

                    if (str_contains($path, '/shorts/')) {
                        $segments = explode('/shorts/', $path);
                        $videoId = $segments[1] ?? $videoId;
                    }
                }

                if (!empty($videoId)) {
                    $videoId = explode('?', $videoId)[0];
                    $videoId = explode('&', $videoId)[0];
                    $youtubeVideoIds[] = $videoId;
                }
            }

            $youtubeVideoIds = array_values(array_unique($youtubeVideoIds));
            $youtubeInputLinks = array_filter($youtubeLinks, fn($link) => trim($link) !== '');
        @endphp

        <div class="setting-layout">

            <div class="card">
                <form action="/admin/setting/update" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label>Nama Instansi</label>
                    <input type="text" name="title" value="{{ $setting->title }}">

                    <label>Alamat</label>
                    <input type="text" name="address" value="{{ $setting->address }}">

                    <label>No Telp</label>
                    <input type="text" name="phone" value="{{ $setting->phone }}">

                    <label>Link YouTube / Playlist</label>

                    <div id="youtubeContainer">
                        @if(count($youtubeInputLinks) > 0)
                            @foreach($youtubeInputLinks as $link)
                                <div class="yt-item">
                                    <input type="text" name="youtube[]" value="{{ trim($link) }}" placeholder="Masukkan link YouTube atau playlist">
                                    <button type="button" class="remove-btn" onclick="removeYoutubeField(this)">✕</button>
                                </div>
                            @endforeach
                        @else
                            <div class="yt-item">
                                <input type="text" name="youtube[]" placeholder="Masukkan link YouTube atau playlist">
                                <button type="button" class="remove-btn" onclick="removeYoutubeField(this)">✕</button>
                            </div>
                        @endif
                    </div>

                    <button type="button" class="add-btn" onclick="addYoutubeField()">➕ Tambah Link</button>

                    <div class="helper">
                        Bisa isi 1 link playlist YouTube, atau beberapa link video YouTube.
                        Jika beberapa video, urutan putar mengikuti urutan link dari atas ke bawah.
                    </div>

                    <label>Logo</label>

                    @if(!empty($setting->logo))
                        <div class="logo-history">
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo">
                            <div class="logo-info">
                                <div class="logo-name">{{ basename($setting->logo) }}</div>
                                <div class="logo-note">Logo saat ini</div>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="logo">

                    <label>Background</label>

                    @if(!empty($setting->background))
                        <div class="bg-history">
                            <img src="{{ asset('storage/' . $setting->background) }}" alt="Background">
                            <div class="bg-info">
                                <div class="bg-name">{{ basename($setting->background) }}</div>
                                <div class="bg-note">Background saat ini</div>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="background">

                    <button type="submit" class="save-btn">💾 Simpan Tampilan</button>
                </form>
            </div>

            <div class="preview-card">
                <p class="preview-title">Preview Tampilan Antrean</p>

                <div class="preview">
                    @if(!empty($setting->logo))
                        <img src="{{ asset('storage/' . $setting->logo) }}" class="preview-logo" alt="Logo">
                    @endif

                    <h2>{{ $setting->title ?? 'NAMA INSTANSI' }}</h2>
                    <p>{{ $setting->address ?? '-' }}</p>
                    <p>{{ $setting->phone ?? '-' }}</p>

                    <h1>{{ $queue->queue_number ?? '000' }}</h1>

                    <p>LOKET {{ $queue->loket_id ?? '-' }}</p>

                    @if($youtubePlaylistId || count($youtubeVideoIds) > 0)
                        <div class="youtube-box">
                            <div id="youtubePlayer"></div>

                            <div class="playlist-info">
                                @if($youtubePlaylistId)
                                    Playlist YouTube aktif
                                @else
                                    Memutar {{ count($youtubeVideoIds) }} video sesuai urutan
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function addYoutubeField(){
            const container = document.getElementById('youtubeContainer');

            const div = document.createElement('div');
            div.className = 'yt-item';

            div.innerHTML = `
                <input type="text" name="youtube[]" placeholder="Masukkan link YouTube atau playlist">
                <button type="button" class="remove-btn" onclick="removeYoutubeField(this)">✕</button>
            `;

            container.appendChild(div);
        }

        function removeYoutubeField(button){
            const container = document.getElementById('youtubeContainer');

            if (container.children.length > 1) {
                button.parentElement.remove();
            } else {
                button.parentElement.querySelector('input').value = '';
            }
        }
    </script>

    @if($youtubePlaylistId || count($youtubeVideoIds) > 0)
        <script src="https://www.youtube.com/iframe_api"></script>

        <script>
            let youtubePlayer;
            let currentVideoIndex = 0;

            const youtubePlaylistId = @json($youtubePlaylistId);
            const youtubeVideoIds = @json($youtubeVideoIds);

            function onYouTubeIframeAPIReady() {
                if (youtubePlaylistId) {
                    youtubePlayer = new YT.Player('youtubePlayer', {
                        height: '250',
                        width: '100%',
                        playerVars: {
                            listType: 'playlist',
                            list: youtubePlaylistId,
                            autoplay: 1,
                            controls: 1,
                            rel: 0,
                            loop: 1
                        },
                        events: {
                            'onReady': function(event) {
                                event.target.playVideo();
                            }
                        }
                    });
                } else if (youtubeVideoIds.length > 0) {
                    youtubePlayer = new YT.Player('youtubePlayer', {
                        height: '250',
                        width: '100%',
                        videoId: youtubeVideoIds[currentVideoIndex],
                        playerVars: {
                            autoplay: 1,
                            controls: 1,
                            rel: 0
                        },
                        events: {
                            'onReady': function(event) {
                                event.target.playVideo();
                            },
                            'onStateChange': function(event) {
                                if (event.data === YT.PlayerState.ENDED) {
                                    currentVideoIndex++;

                                    if (currentVideoIndex >= youtubeVideoIds.length) {
                                        currentVideoIndex = 0;
                                    }

                                    youtubePlayer.loadVideoById(youtubeVideoIds[currentVideoIndex]);
                                }
                            }
                        }
                    });
                }
            }
        </script>
    @endif

</body>
</html>