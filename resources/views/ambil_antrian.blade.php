@if(session('number'))
<div style="text-align:center; margin-bottom:20px; font-size:24px; color:green;">
Nomor Antrian Anda: {{ session('number') }}
</div>
@endif

@foreach($services as $service)
<form action="/generate-antrian" method="POST">
@csrf
<input type="hidden" name="service_code" value="{{ $service->code }}">
<button type="submit">TIKET {{ $service->code }} - {{ $service->name }}</button>
</form>
@endforeach