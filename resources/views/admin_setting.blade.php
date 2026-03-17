<h2>Kelola Tampilan</h2>

<form action="/admin/setting/update" method="POST" enctype="multipart/form-data">
@csrf

<input type="text" name="title" placeholder="Nama Instansi" value="{{ $setting->title }}"><br><br>
<input type="text" name="address" placeholder="Alamat" value="{{ $setting->address }}"><br><br>
<input type="text" name="phone" placeholder="No Telp" value="{{ $setting->phone }}"><br><br>

<input type="text" name="youtube" placeholder="Link YouTube" value="{{ $setting->youtube }}"><br><br>

<label>Logo</label><br>
<input type="file" name="logo"><br><br>

<label>Background</label><br>
<input type="file" name="background"><br><br>

<button type="submit">Simpan</button>
</form>