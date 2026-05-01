<!DOCTYPE html>
<html>
<head>
    <title>Expense Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
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

        .summary {
            margin: 10px 0;
        }

        .summary td {
            border: 1px solid #000;
            padding: 5px;
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
    <h4>Expense Report</h4>
</div>

<!-- FILTER -->
<div class="info">
    <table>
        <tr>
            <td>
                <b>Date Range:</b>
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
    <table width="100%">
        <tr>
            <td><b>Total Expense:</b> ৳ {{ number_format($total) }}</td>
        </tr>
    </table>
</div>

<!-- TABLE -->
<table class="main">
    <thead>
        <tr>
            <th>SL</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Note</th>
        </tr>
    </thead>

    <tbody>
        @foreach($expenses as $index => $e)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $e->category }}</td>
            <td>৳ {{ number_format($e->amount) }}</td>
            <td>{{ $e->date }}</td>
            <td>{{ $e->note }}</td>
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
