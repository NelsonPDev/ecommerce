<h1>Venta validada</h1>

<p>Hola {{ $venta->vendedor->nombre }},</p>

<p>La venta del producto <strong>{{ $venta->producto->nombre }}</strong> ha sido validada.</p>

<p>Comprador: {{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</p>
<p>Correo del comprador: {{ $venta->cliente->correo }}</p>
<p>Cantidad: {{ $venta->cantidad }}</p>
