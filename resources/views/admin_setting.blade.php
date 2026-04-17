<!DOCTYPE html>
<html>
<head>
    <title>Kelola Tampilan</title>

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
        }

        /* SIDEBAR */
        .sidebar{
            width:220px;
            background:#114338;
            color:white;
            min-height:100vh;
            padding:20px 16px;
            display:flex;
            flex-direction:column;
            flex-shrink:0;
        }

        .sidebar h2{
            color:#FBB03C;
            margin:10px 0 20px;
            font-size:22px;
            font-weight:bold;
        }

        .sidebar .menu{
            display:flex;
            flex-direction:column;
            gap:8px;
        }

        .sidebar a{
            display:block;
            color:white;
            padding:12px 14px;
            text-decoration:none;
            border-radius:8px;
            transition:0.2s ease-in-out;
            font-weight:600;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,0.12);
        }

        .sidebar a.active{
            background:rgba(255,255,255,0.14);
        }

        .logout-form{
            margin-top:20px;
        }

        .logout-btn{
            width:100%;
            padding:12px;
            background:#e74c3c;
            color:white;
            border:none;
            border-radius:8px;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:0.2s ease-in-out;
        }

        .logout-btn:hover{
            background:#cf3f31;
        }

        /* CONTENT */
        .content{
            flex:1;
            background:#f4f6f9;
            padding:30px;
        }

        .content h2{
            margin-top:0;
            margin-bottom:20px;
        }

        /* CARD */
        .card{
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            max-width:600px;
            margin-bottom:30px;
        }

        /* FORM */
        label{
            display:block;
            margin-bottom:6px;
            font-weight:600;
            color:#333;
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:15px;
            border-radius:6px;
            border:1px solid #ccc;
        }

        /* BUTTON FORM */
        .save-btn{
            width:100%;
            padding:12px;
            background:#007bff;
            color:white;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
            font-weight:600;
        }

        .save-btn:hover{
            background:#0056b3;
        }

        /* PREVIEW */
        .preview{
            background:#114338;
            color:white;
            padding:20px;
            border-radius:10px;
            text-align:center;
            overflow:hidden;
        }

        .preview h1{
            font-size:80px;
            margin:10px 0;
        }

        .preview iframe{
            margin-top:20px;
            border:0;
            border-radius:10px;
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

            .card{
                max-width:100%;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>ADMIN</h2>

        <div class="menu">
            <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">Dashboard</a>
            <a href="/admin/user" class="{{ request()->is('admin/user') ? 'active' : '' }}">Manajemen User</a>
            <a href="/admin/setting" class="{{ request()->is('admin/setting') ? 'active' : '' }}">Kelola Tampilan</a>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <h2>⚙️ Kelola Tampilan</h2>

        <!-- FORM -->
        <div class="card">
            <form action="/admin/setting/update" method="POST" enctype="multipart/form-data">
                @csrf

                <label>Nama Instansi</label>
                <input type="text" name="title" value="{{ $setting->title }}">

                <label>Alamat</label>
                <input type="text" name="address" value="{{ $setting->address }}">

                <label>No Telp</label>
                <input type="text" name="phone" value="{{ $setting->phone }}">

                <label>Link YouTube</label>
                <input type="text" name="youtube" value="{{ $setting->youtube }}" placeholder="Masukkan link video atau playlist YouTube">

                <label>Logo</label>
                <input type="file" name="logo">

                <label>Background</label>
                <input type="file" name="background">

                <button type="submit" class="save-btn">💾 Simpan Tampilan</button>
            </form>
        </div>

        @php
            $youtubeUrl = $setting->youtube ?? '';
            $youtubeEmbedUrl = null;

            if (!empty($youtubeUrl)) {
                $parsedUrl = parse_url($youtubeUrl);
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
                    $youtubeEmbedUrl = 'https://www.youtube.com/embed/videoseries?list=' . $playlistId;
                } elseif (!empty($videoId)) {
                    $youtubeEmbedUrl = 'https://www.youtube.com/embed/' . $videoId;
                }
            }
        @endphp

        <!-- PREVIEW DASHBOARD -->
        <div class="preview">
            <h2>{{ $setting->title ?? 'NAMA INSTANSI' }}</h2>
            <p>{{ $setting->address ?? '-' }}</p>
            <p>{{ $setting->phone ?? '-' }}</p>

            <h1>{{ $queue->queue_number ?? '000' }}</h1>

            <p>LOKET {{ $queue->loket_id ?? '-' }}</p>

            @if($youtubeEmbedUrl)
                <iframe
                    width="100%"
                    height="250"
                    src="{{ $youtubeEmbedUrl }}"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            @endif
        </div>

    </div>

</body>
</html>