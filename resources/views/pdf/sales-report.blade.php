<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .summary {
            margin: 10px 0;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>{{ $shop->shop_name ?? 'Shop Name' }}</h2>
    <div>Owner: {{ $shop->firstName ?? '-' }}</div>
    <div>Mobile: {{ $shop->mobile ?? '-' }}</div>
    <h4>Sales Report</h4>
</div>

<!-- SUMMARY -->
<div class="summary">
    <table>
        <tr>
            <td><b>Total Sales:</b> ৳ {{ number_format($totalSales) }}</td>
            <td><b>Total Quantity:</b> {{ $totalQty }}</td>
             <td><b>Date:</b> {{ $startDate ?? 'All' }} - {{ $endDate ?? 'All' }}</td>
        </tr>
    </table>


</div>

<!-- TABLE -->
<table class="main">
    <thead>
        <tr>
            <th>SL</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Mobile</th>
            <th>Qty</th>
            <th>Amount</th>
            <th>Source</th>
        </tr>
    </thead>

    <tbody>
       @foreach($transactions as $t)
<tr>
    <td>{{ $t['sl'] }}</td>
    <td>{{ $t['date'] }}</td>
    <td>{{ $t['customer'] }}</td>
    <td>{{ $t['mobile'] }}</td>
    <td>{{ $t['qty'] }}</td>
    <td>৳ {{ number_format($t['amount']) }}</td>
    <td>{{ $t['source'] }}</td>
</tr>
@endforeach
    </tbody>
</table>

</body>
</html>
