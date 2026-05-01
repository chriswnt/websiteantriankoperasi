<!DOCTYPE html>
<html>
<head>

<title>Login Petugas</title>
<link rel="icon" type="image/png" href="{{ asset('assets/Logo Pack-02.png') }}">
<style>

body{
font-family:Arial;
background:#114338;
height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.box{
background:white;
padding:40px;
border-radius:10px;
width:300px;
}

input{
width:100%;
padding:10px;
margin-top:10px;
}

button{
margin-top:15px;
width:100%;
padding:10px;
background:#FBB03C;
border:none;
cursor:pointer;
}

</style>

</head>

<body>

<div class="box">

<h2>Login Petugas</h2>

@if(session('error'))
<p style="color:red">{{session('error')}}</p>
@endif

<form method="POST" action="/login">

@csrf

<input type="email" name="email" placeholder="Email">

<input type="password" name="password" placeholder="Password">

<button type="submit">
Login
</button>

</form>

</div>

</body>
</html>