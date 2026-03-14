<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .status-waiting { color: orange; }
        .status-called { color: blue; }
        .status-done { color: green; }
        form { margin-bottom: 20px; }
        input, button { padding: 8px; margin: 5px 0; }
        .edit-form { display: none; }
    </style>
</head>
<body>
    <h1>Admin Panel</h1>
    <a href="/logout">Logout</a>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    <h2>Manage Services</h2>
    <form action="/admin/services" method="POST">
        @csrf
        <input type="text" name="code" placeholder="Code (e.g. T)" required>
        <input type="text" name="name" placeholder="Name (e.g. Teller)" required>
        <button type="submit">Add Service</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        @foreach($services as $service)
        <tr>
            <td>{{ $service->id }}</td>
            <td>{{ $service->code }}</td>
            <td>{{ $service->name }}</td>
            <td>
                <button onclick="editService({{ $service->id }}, '{{ $service->code }}', '{{ $service->name }}')">Edit</button>
                <form action="/admin/services/{{ $service->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    <div id="edit-form" style="display:none;">
        <h3>Edit Service</h3>
        <form action="" method="POST" id="update-form">
            @csrf
            @method('PUT')
            <input type="text" name="code" id="edit-code" required>
            <input type="text" name="name" id="edit-name" required>
            <button type="submit">Update</button>
            <button type="button" onclick="cancelEdit()">Cancel</button>
        </form>
    </div>

    <h2>Antrian</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nomor</th>
            <th>Service</th>
            <th>Status</th>
            <th>Loket</th>
            <th>Started At</th>
            <th>Finished At</th>
            <th>Created At</th>
        </tr>
        @foreach($queues as $queue)
        <tr>
            <td>{{ $queue->id }}</td>
            <td>{{ $queue->number }}</td>
            <td>{{ $queue->service->name ?? 'N/A' }}</td>
            <td class="status-{{ $queue->status }}">{{ ucfirst($queue->status) }}</td>
            <td>{{ $queue->loket ?? '—' }}</td>
            <td>{{ $queue->started_at ? $queue->started_at->format('d/m/Y H:i') : '—' }}</td>
            <td>{{ $queue->finished_at ? $queue->finished_at->format('d/m/Y H:i') : '—' }}</td>
            <td>{{ $queue->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </table>

    <script>
        function editService(id, code, name) {
            document.getElementById('edit-code').value = code;
            document.getElementById('edit-name').value = name;
            document.getElementById('update-form').action = '/admin/services/' + id;
            document.getElementById('edit-form').style.display = 'block';
        }

        function cancelEdit() {
            document.getElementById('edit-form').style.display = 'none';
        }
    </script>
</body>
</html>
