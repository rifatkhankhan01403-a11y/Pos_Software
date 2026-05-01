<!DOCTYPE html>
<html>
<head>
    <title>Purchase Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
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

        .summary {
            margin: 10px 0;
        }

        .summary td {
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h2>{{ $shop->shop_name ?? 'Shop Name' }}</h2>
    <div>Owner: {{ $shop->firstName ?? '-' }}</div>
    <div>Mobile: {{ $shop->mobile ?? '-' }}</div>
    <h4>Purchase Report</h4>
</div>

<!-- FILTER -->
<div class="info">
    <table>
        <tr>
            <td>
                <b>Date:</b>
                {{ $startDate && $endDate ? $startDate.' to '.$endDate : 'All Time' }}
            </td>

            <td style="text-align:right;">
                <b>Search:</b> {{ $search ?? 'All' }}
            </td>
        </tr>
    </table>
</div>

<!-- SUMMARY -->
<div class="summary">
    <table width="100%">
        <tr>
            <td><b>Total Purchase:</b> ${{ number_format($totalPurchase) }}</td>
        </tr>
    </table>
</div>

<!-- TABLE -->
<table class="main">
    <thead>
        <tr>
            <th>SL</th>
            <th>Supplier</th>
            <th>Phone</th>
            <th>Items</th>
            <th>Amount</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($purchases as $index => $p)

        @php
            $items = is_string($p->items)
                ? json_decode($p->items, true)
                : ($p->items ?? []);

            $totalQty = 0;

            if (is_array($items)) {
                foreach ($items as $item) {
                    $totalQty += (float) ($item['qty'] ?? 0);
                }
            }
        @endphp

        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $p->supplier_name ?? '-' }}</td>
            <td>{{ $p->supplier_phone ?? '-' }}</td>
            <td>{{ $totalQty }}</td>
            <td>${{ number_format($p->total_cost) }}</td>
            <td>${{ number_format($p->paid_amount) }}</td>
            <td>${{ number_format($p->due_amount) }}</td>
            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</td>
        </tr>

        @endforeach
    </tbody>
</table>

</body>
</html>
