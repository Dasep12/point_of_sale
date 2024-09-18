<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
        }

        .header {
            margin-bottom: 20px;
        }

        .header .company-info {
            float: left;
            width: 40%;
            text-align: left;
        }

        .header .invoice-info {
            float: right;
            width: 20%;
            align-items: flex-end;
        }

        .invoice-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }


        .summary-table th,
        .summary-table td {
            /* border: 1px solid black; */
            padding: 5px;
            text-align: left;
        }

        .invoice-table th,
        .summary-table th {
            border-top: 1px solid black;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 3px;
            text-align: left;
            border-bottom: 1px solid black;
        }

        /* 
        .footer {
            margin-top: 20px;
        } */

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .right-align {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header clearfix">
        <div class="company-info">
            <table>
                <tr>
                    <td align="center"><img style="height: 60px; width: 60px;" src="./assets/images/{{  $store->logo }}"></td>
                    <td style=""><strong style=" font-size:18px;">FAKTUR PENJUALAN</strong><br>
                        <strong style="font-size:15px;">{{ $store->name_company }}</strong><br>
                        {{ $store->address }}<br>
                        {{ $store->phone }}<br>
                    </td>
                </tr>
            </table>
        </div>
        <div class="company-info">
            <table>
                <tr>
                    <td>No Transaksi</td>
                    <td>: {{$header->no_transaksi}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{$header->date_trans}}</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td>: {{$header->name_level}}</td>
                </tr>
            </table>
        </div>

        <div class="invoice-info">
            <table>
                <!-- <tr>
                    <td>Dept.</td>
                    <td>: UTM</td>
                </tr> -->
                <tr>
                    <td>User</td>
                    <td>: {{ $header->fullname }}</td>
                </tr>
            </table>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Jml</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Pot</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach($detail as $dt)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $dt->kode_item }}</td>
                <td>{{ $dt->item_name }}</td>
                <td class="right-align">{{ $dt->out_stock }}</td>
                <td>{{ $dt->unit_name }}</td>
                <td class="right-align">{{ number_format($dt->harga_jual) }}</td>
                <td class="right-align">{{ number_format($dt->discount) }}</td>
                <td class="right-align">{{ number_format($dt->harga_jual * $dt->out_stock) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



    <hr>
    <br>
    <table class="summary-table">
        <tbody>
            <tr>
                <td>Keterangan </td>
                <td>: {{ strtoupper($header->status_bayar) }}</td>
                <td class="right-align">Jml Item </td>
                <td class="right-align">: {{ $detail->count() }} </td>
                <td class="right-align">Sub Total </td>
                <td class="right-align">: {{ number_format($header->sub_total) }}</td>
            </tr>
            <tr>
                <td>Hormat Kami</td>
                <td>Penerima</td>
                <td></td>
                <td class="text-center"></td>
                <td class="right-align">Total Potongan </td>
                <td class="right-align">: {{ number_format($header->total_potongan) }} </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="right-align">{{ $pajak->name }} ({{ $pajak->persentase . '%' }})</td>
                <td class="right-align">: {{ number_format($header->total_bayar * $pajak->persentase / 100  ) }} </td>

            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="right-align">Total Akhir </td>
                <td class="right-align">: {{ number_format($header->total_bayar) }}</td>
            </tr>
            <!-- <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="right-align">Tunai </td>
                <td class="right-align">: {{ number_format($header->uang_bayar) }} </td>
            </tr>
            <tr>
                <td>(...................)</td>
                <td>(...................)</td>
                <td></td>
                <td></td>
                <td class="right-align">Kembali </td>
                <td class="right-align">: {{ number_format($header->kembalian) }} </td>
            </tr> -->
        </tbody>
    </table>

    <p>Terbilang : {{ ucwords(terbilang($header->total_bayar)) }}</p>

    <div class="">
        <p>{{ date('Y/m/d H:i:s') }}</p>
        <p>{{ strtoupper($header->fullname) }}</p>
    </div>
    <br><br>
    {!! DNS1D::getBarcodeHTML($header->no_transaksi, 'C128', 0.9, 45) !!}

</body>

</html>