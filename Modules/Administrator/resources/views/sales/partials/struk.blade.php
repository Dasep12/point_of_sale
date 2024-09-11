<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 58mm;
            margin: 0;
            padding: 0px;
            color: #333;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            letter-spacing: 2px;
        }

        .header p {
            margin: 0;
            font-size: 12px;
        }

        .content {
            margin-bottom: 10px;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
        }

        .content th,
        .content td {
            text-align: left;
            padding: 4px 0;
        }

        .content th {
            border-bottom: 1px solid #333;
        }

        .content .total {
            font-weight: bold;
            font-size: 14px;
        }

        .line {
            border-top: 1px dashed #333;
            margin: 10px 0;
        }

        .footer p {
            font-size: 10px;
            margin: 5px 0;
        }

        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
        }

        .barcode {
            text-align: center;
            margin-top: 10px;
        }

        .barcode img {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $store->name_company }}</h1>
        <p>{{ $store->address }}</p>
        <p>Tel: {{ $store->phone }}</p>
    </div>

    <div class="content">
        <p>Date: {{ $header->date_trans }}</p>
        <p>Receipt #: {{ $header->no_transaksi }}</p>
        <p>Kasir #: {{ ucwords(strtolower($user->fullname)) }}</p>
        <div class="line"></div>
        <table border="0">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Disc</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $d)
                <tr>
                    <td>{{ ucwords(strtolower($d->item_name)) }}</td>
                    <td>{{ $d->out_stock }}</td>
                    <td>{{ number_format($d->harga_jual) }}</td>
                    <td>{{ $d->discount }}</td>
                    <td>{{ number_format($d->harga_jual * $d->out_stock)  }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="line"></div>
        <table>
            <tr>
                <td class="total">Subtotal</td>
                <td class="total">{{ number_format($header->sub_total) }}</td>
            </tr>
            <tr>
                <td class="total">Total Discount</td>
                <td class="total">{{ number_format($header->total_potongan) }}</td>
            </tr>
            <tr>
                <td class="total">Total</td>
                <td class="total">{{ number_format($header->total_bayar) }}</td>
            </tr>
        </table>
        <div class="line"></div>
        <table>
            <tr>
                <td class="total">Uang Bayar</td>
                <td class="total">{{ number_format($header->uang_bayar) }}</td>
            </tr>
            <tr>
                <td class="total">Kembali</td>
                <td class="total">{{ number_format($header->kembalian) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for your purchase!</p>
    </div>

    <div class="barcode">
        {!! DNS1D::getBarcodeHTML($header->no_transaksi, 'C128', 1.3, 45) !!}
        <!-- <img src="https://via.placeholder.com/150x50.png?text=Barcode" alt="Barcode"> -->
    </div>

    <div class="footer">
        <p>{{ $store->name_company }}</p>
    </div>
</body>

</html>