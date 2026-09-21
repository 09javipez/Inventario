<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Cotizacion</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .section { margin-top: 20px; }
    </style>
</head>
<body>

    <div class="title">Detalle de Cotizacion #{{ $model->serie }}-{{ str_pad($model->correlative, 4, '0', STR_PAD_LEFT) }}</div>

    <div>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($model->date)->format('d/m/Y') }}<br>
        <strong>Cliente:</strong> {{ $model->customer->name ?? '—' }}<br>
        <strong>Observación:</strong> {{ $model->observation ?? '—' }}
    </div>

    <div class="section">
        <table>
            {{-- Detalle de los productos --}}
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Descuento</th>
                    <th>IVA</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->products as $i => $product)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>$ {{ number_format($product->pivot->price, 2) }}</td>
                        <td>$ {{ number_format($product->pivot->discount, 2) }}</td>
                        <td>
                            {{ $product->pivot->tax_rate }}%
                        </td>
                        <td> $ {{ number_format($product->pivot->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php
        $subtotal = $model->products->sum(function ($product){
            return ($product->pivot->quantity * $product->pivot->price)
                - $product->pivot->discount;
        });
        $discount = $model->products->sum('pivot.discount');
        $iva = $model->products->sum('pivot.tax_amount');
    @endphp

    <table style="width:300px; margin-top:20px; margin-left:auto;">
        <tr>
            <td><strong>Subtotal:</strong></td>
            <td>{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Descuento:</strong></td>
            <td>{{ number_format($discount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>IVA:</strong></td>
            <td>{{ number_format($iva, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total:</strong></td>
            <td>{{ number_format($model->total, 2) }}</td>
        </tr>
    </table>

</body>
</html>
