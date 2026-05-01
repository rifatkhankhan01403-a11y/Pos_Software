<!DOCTYPE html>
<html>
<head>
    <title>Condition Sales Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            margin-bottom: 10px;
             text-align: left;
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
    <h4>Condition Sales Report</h4>
</div>

<!-- FILTER INFO -->
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
            <td><b>Total Sales:</b> ৳ {{ number_format($total) }}</td>
            <td><b>Total Paid:</b> ৳ {{ number_format($totalPaid ?? 0) }}</td>
            <td><b>Total Due:</b> ৳ {{ number_format($totalDue ?? 0) }}</td>
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
            <th>Total</th>
            <th>Courier</th>
            <th>Status</th>
            <th>Note</th>
        </tr>
    </thead>

    <tbody>
        @foreach($sales as $index => $sale)

        @php
            $status = $sale->due > 0 ? 'Pending' : 'Paid';
        @endphp

        <tr>
            <!-- ✅ SL ADDED -->
            <td>{{ $index + 1 }}</td>

            <td>{{ $sale->created_at }}</td>
            <td>{{ $sale->customer_name ?? '-' }}</td>
            <td>{{ $sale->customer_mobile ?? '-' }}</td>
            <td>{{ $sale->qty ?? 0 }}</td>
            <td>৳ {{ number_format($sale->total ?? 0) }}</td>
            <td>{{ $sale->courier ?? '-' }}</td>

            <td>
                {{ $status }}
            </td>

            <td>{{ $sale->note ?? '-' }}</td>
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
