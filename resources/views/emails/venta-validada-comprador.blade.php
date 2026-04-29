<h1>Compra validada</h1>

<p>Hola {{ $venta->cliente->nombre }},</p>

<p>Tu compra del producto <strong>{{ $venta->producto->nombre }}</strong> ya fue validada.</p>

<p>Puedes contactar al vendedor en el correo: <strong>{{ $venta->vendedor->correo }}</strong>.</p>

<p>Conserva tu ticket y espera el seguimiento del vendedor para completar la entrega.</p>
