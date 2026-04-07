<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Cliente</title>
</head>
<body>
    <h1>Dashboard de Cliente</h1>
    <p>Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}!</p>
    <p>Tu rol es: {{ Auth::user()->rol }}</p>
    <a href="/productos">Ver Productos</a>
    <a href="/mis-compras">Mis Compras</a>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
