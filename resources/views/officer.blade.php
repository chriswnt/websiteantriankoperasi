<meta name="csrf-token" content="{{ csrf_token() }}">
<h1>Officer Panel</h1>

<div style="display:flex; gap:24px; align-items:flex-start;">
    <div style="flex:1; padding:16px; border:1px solid #ccc; border-radius:8px;">
        <h2>Menunggu (Waiting)</h2>
        <div id="waiting-list" style="max-height:300px; overflow:auto; border:1px solid #ddd; padding:8px; border-radius:6px; background:#f8f9fa;"></div>
    </div>

    <div style="flex:1; padding:16px; border:1px solid #ccc; border-radius:8px;">
        <h2>Petugas 1</h2>
        <p>Current: <strong id="current-1">{{ $current1?->number ?? '—' }}</strong></p>
        <p>Elapsed: <strong id="elapsed-1">{{ $current1?->started_at ? '⏱ ' . now()->diffForHumans($current1->started_at, true) : '—' }}</strong></p>
        <form id="finish-1" action="/officer/finish/{{ $current1?->id ?? 0 }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" id="finish-button-1" {{ $current1 ? '' : 'disabled' }}>Selesai</button>
        </form>
    </div>

    <div style="flex:1; padding:16px; border:1px solid #ccc; border-radius:8px;">
        <h2>Petugas 2</h2>
        <p>Current: <strong id="current-2">{{ $current2?->number ?? '—' }}</strong></p>
        <p>Elapsed: <strong id="elapsed-2">{{ $current2?->started_at ? '⏱ ' . now()->diffForHumans($current2->started_at, true) : '—' }}</strong></p>
        <form id="finish-2" action="/officer/finish/{{ $current2?->id ?? 0 }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" id="finish-button-2" {{ $current2 ? '' : 'disabled' }}>Selesai</button>
        </form>
    </div>
</div>

<script>
let lastWaiting = null;

function formatElapsed(seconds) {
    const min = Math.floor(seconds / 60);
    const sec = seconds % 60;
    return `⏱ ${min}m ${sec}s`;
}

function refreshOfficerData() {
    fetch('/officer-data')
        .then(res => res.json())
        .then(data => {
            // Waiting list
            const waitingList = document.getElementById('waiting-list');
            waitingList.innerHTML = '';
            data.waiting.forEach(item => {
                const div = document.createElement('div');
                div.style.padding = '6px 8px';
                div.style.marginBottom = '6px';
                div.style.border = '1px solid #ccc';
                div.style.borderRadius = '6px';
                div.style.background = '#fff';

                const time = new Date(item.created_at).toLocaleTimeString();
                div.innerHTML = `<strong>⏳ ${item.number}</strong> <small>${time}</small>`;

                const btn1 = document.createElement('button');
                btn1.style.marginLeft = '8px';
                btn1.textContent = 'Mulai (Loket 1)';
                btn1.onclick = () => {
                    fetch(`/officer/start/${item.id}/1`, { method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                        .then(() => refreshOfficerData());
                };

                const btn2 = document.createElement('button');
                btn2.style.marginLeft = '4px';
                btn2.textContent = 'Mulai (Loket 2)';
                btn2.onclick = () => {
                    fetch(`/officer/start/${item.id}/2`, { method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                        .then(() => refreshOfficerData());
                };

                div.appendChild(btn1);
                div.appendChild(btn2);

                waitingList.appendChild(div);
            });

            // Notifikasi antrian baru
            if (lastWaiting !== null && data.waiting.length > lastWaiting) {
                alert('Ada antrian baru!');
            }
            lastWaiting = data.waiting.length;

            // Current process update
            const current1 = data.current1;
            const current2 = data.current2;

            document.getElementById('current-1').innerText = current1 ? current1.number : '—';
            document.getElementById('current-2').innerText = current2 ? current2.number : '—';

            const nowSeconds = Math.floor(Date.now() / 1000);
            if (current1 && current1.started_at) {
                const started = Math.floor(new Date(current1.started_at).getTime()/1000);
                document.getElementById('elapsed-1').innerText = formatElapsed(nowSeconds - started);
                document.getElementById('finish-button-1').disabled = false;
                document.getElementById('finish-1').action = `/officer/finish/${current1.id}`;
            } else {
                document.getElementById('elapsed-1').innerText = '—';
                document.getElementById('finish-button-1').disabled = true;
            }

            if (current2 && current2.started_at) {
                const started = Math.floor(new Date(current2.started_at).getTime()/1000);
                document.getElementById('elapsed-2').innerText = formatElapsed(nowSeconds - started);
                document.getElementById('finish-button-2').disabled = false;
                document.getElementById('finish-2').action = `/officer/finish/${current2.id}`;
            } else {
                document.getElementById('elapsed-2').innerText = '—';
                document.getElementById('finish-button-2').disabled = true;
            }
        });
}

setInterval(refreshOfficerData, 2000);
refreshOfficerData();
</script>
