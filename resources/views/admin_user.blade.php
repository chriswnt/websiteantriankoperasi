<h2>Manajemen User</h2>

<form method="POST" action="/admin/user">
@csrf
<input type="text" name="name" placeholder="Nama"><br>
<input type="email" name="email" placeholder="Email"><br>
<input type="password" name="password" placeholder="Password"><br>

<select name="role">
    <option value="admin">Admin</option>
    <option value="officer">Officer</option>
</select><br><br>

<button type="submit">Tambah</button>
</form>

<hr>

<table border="1">
<tr>
    <th>Nama</th>
    <th>Email</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>

@foreach($users as $user)
<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->role }}</td>
    <td>
        <a href="/admin/delete/{{ $user->id }}">Hapus</a>
    </td>
</tr>
@endforeach

</table>