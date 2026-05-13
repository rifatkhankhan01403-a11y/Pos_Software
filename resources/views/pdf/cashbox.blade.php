<!DOCTYPE html>
<html>
<head>
    <title>Cashbox Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
             text-align: left;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 2px;
        }

        .summary {
            margin: 10px 0;
        }

        .summary table {
            width: 100%;
        }

        .summary td {
            padding: 5px;
            border: 1px solid #000;
        }

        table.main {
            width: 100%;
            border-collapse: collapse;
        }

        table.main th, table.main td {
            border: 1px solid #000;
            padding: 6px;
        }

        table.main th {
            background: #eee;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>{{ $shop->shop_name ?? 'Shop Name' }}</h2>
    <div>Owner: {{ $shop->firstName ?? '-' }}</div>
    <div>Mobile: {{ $shop->mobile ?? '-' }}</div>
    <h4>Cashbox Report</h4>
</div>

<!-- FILTER INFO -->
<div class="info">
    <table>
        <tr>
            <td><b>Date Range:</b>
                {{ $startDate && $endDate ? $startDate . ' to ' . $endDate : 'All Time' }}
            </td>
            <td style="text-align:right;">
                <b>Generated:</b> {{ now() }}
            </td>
        </tr>
    </table>
</div>

<!-- SUMMARY -->
<div class="summary">
    <table>
        <tr>
            <td><b>Total Cash In:</b> ৳ {{ number_format($totalIn) }}</td>
            <td><b>Total Cash Out:</b> ৳ {{ number_format($totalOut) }}</td>
            <td><b>Balance:</b> ৳ {{ number_format($balance) }}</td>
        </tr>
    </table>
</div>

<!-- TABLE -->
<table class="main">
    <thead>
        <tr>
            <th>SL</th>
<th>Type</th>
            <th>Date</th>
            <th>Source</th>
            <th>Note</th>
            <th>Amount</th>
        </tr>
    </thead>

    <tbody>
       @foreach($transactions as $index => $t)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $t['type'] }}</td>
            <td>{{ $t['date'] }}</td>
            <td>{{ $t['source'] }}</td>
            <td>{{ $t['note'] }}</td>
            <td>Tk {{ number_format($t['amount']) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- FOOTER -->
<div class="footer">
    <p>Authorized Signature</p>
</div>

</body>
</html>
