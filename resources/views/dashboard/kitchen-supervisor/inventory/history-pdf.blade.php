<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inventory History Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h1 {
            text-align: center;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 18px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #e5e7eb;
            padding: 8px;
            border: 1px solid #999;
            text-align: left;
        }

        td {
            padding: 8px;
            border: 1px solid #999;
            vertical-align: top;
        }

        .stock_in {
            color: #166534;
            font-weight: bold;
        }

        .stock_out {
            color: #991b1b;
            font-weight: bold;
        }

        .adjustment {
            color: #1d4ed8;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1>Inventory History Report</h1>

<div class="subtitle">
    Generated on {{ now()->format('d M Y H:i') }}
</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Item</th>
            <th>Action</th>
            <th>Quantity</th>
            <th>Updated By</th>
            <th>Note</th>
        </tr>
    </thead>

    <tbody>
        @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                <td>{{ $transaction->item->name ?? 'N/A' }}</td>
                <td class="{{ $transaction->type }}">
                    {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                </td>
                <td>
                    {{ $transaction->quantity }}
                    {{ $transaction->item->unit ?? '' }}
                </td>
                <td>{{ $transaction->user->name ?? 'System' }}</td>
                <td>{{ $transaction->note ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;">
                    No inventory history found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>