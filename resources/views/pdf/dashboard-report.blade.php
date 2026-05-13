<!DOCTYPE html>
<html>
<head>

    <title>Dashboard Report</title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            color:#333;
        }

        .header{
            margin-bottom:20px;
        }

        .header h2{
            margin:0;
            color:#d63384;
        }

        .header p{
            margin:3px 0;
        }

        .report-title{
            text-align:center;
            margin:20px 0;
            font-size:18px;
            font-weight:bold;
        }

        .summary-box{
            width:100%;
            margin-bottom:20px;
        }

        .summary-box td{
            border:1px solid #ddd;
            padding:10px;
        }

        .summary-title{
            font-weight:bold;
            background:#f8f8f8;
        }

        table.main{
            width:100%;
            border-collapse:collapse;
        }

        table.main th,
        table.main td{
            border:1px solid #ccc;
            padding:8px;
            text-align:left;
        }

        table.main th{
            background:#d63384;
            color:white;
        }

        .footer{
            margin-top:40px;
            text-align:right;
        }

    </style>

</head>

<body>

<!-- HEADER -->

<!-- HEADER -->
<div class="header">
    <h2>{{ $shop->shop_name ?? 'Shop Name' }}</h2>
    <div>Owner: {{ $shop->firstName ?? '-' }}</div>
    <div>Mobile: {{ $shop->mobile ?? '-' }}</div>
    <h4>Dashboard Report</h4>
</div>



<!-- TITLE -->

<!-- FILTER INFO -->

<table class="summary-box">

    <tr>

        <td>
            <span class="summary-title">Filter:</span>
            {{ ucfirst($filter) }}
        </td>

        <td>
            <span class="summary-title">Generated:</span>
            {{ now()->format('d M Y h:i A') }}
        </td>

    </tr>

</table>



<!-- MAIN TABLE -->

<table class="main">

    <thead>

    <tr>
        <th width="60">SL</th>
        <th>Particular</th>
        <th width="180">Amount / Qty</th>
    </tr>

    </thead>

  <tbody>

<tr>
    <td>1</td>
    <td>{{ ucfirst($filter) }} Sale</td>
   <td>Tk {{ number_format($sell) }}</td>

</tr>

<tr>
    <td>2</td>
    <td>{{ ucfirst($filter) }} Condition Sale</td>
    <td>Tk  {{ number_format($conditionSell) }}</td>
</tr>

<tr>
    <td>3</td>
    <td>{{ ucfirst($filter) }} Purchase</td>
    <td>Tk  {{ number_format($purchase) }}</td>
</tr>

<tr>
    <td>4</td>
    <td>{{ ucfirst($filter) }} Expense</td>
    <td>Tk  {{ number_format($expense) }}</td>
</tr>

<tr>
    <td>5</td>
    <td>Current Total Stock</td>
    <td>{{ number_format($stock) }}</td>
</tr>

<tr>
    <td>6</td>
    <td>{{ ucfirst($filter) }} Stock Sold</td>
    <td>{{ number_format($stockSoldQty) }}</td>
</tr>

<tr>
    <td>7</td>
    <td>Current Receivable</td>
    <td>Tk  {{ number_format($receivable) }}</td>
</tr>

<tr>
    <td>8</td>
    <td>Current Payable</td>
    <td>Tk  {{ number_format($payable) }}</td>
</tr>

<tr>
    <td>9</td>
    <td>Current Net Balance</td>
    <td>
        <b>
            Tk  {{ number_format($net) }}
        </b>
    </td>
</tr>

</tbody>
</table>


<!-- FOOTER -->

<div class="footer">
    Authorized Signature
</div>

</body>
</html>
