<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            left: -30px !important;
            position: relative;
        }



        td {
            vertical-align: top;
            border: 2px solid #AAA;
            margin: 20px;
            text-align: center;
            padding: 15px;
        }

        .barcode {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            @foreach($data as $index => $product)
            <td>
                <p for="">{{ $product->name_item }}</p>
                <!-- {!! DNS1D::getBarcodeHTML($product->barcode, 'C128', 1.3, 45) !!} -->
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, 'C128',2, 55) }}" alt="barcode" />
                <p for="">{{ $product->barcode }}</p>
            </td>
            @if(($index + 1) % 3 == 0)
        </tr>
        <tr>
            @endif
            @endforeach
        </tr>
    </table>
</body>

</html>