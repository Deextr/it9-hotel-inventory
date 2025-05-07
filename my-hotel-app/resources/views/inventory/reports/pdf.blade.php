<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        h2 {
            font-size: 18px;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .meta {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            margin-bottom: 30px;
        }
        .summary-table td {
            width: 50%;
            vertical-align: top;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .card-title {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin: 0;
        }
        .card-value {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-green {
            background-color: #DFF2D8;
            color: #3C763D;
        }
        .badge-blue {
            background-color: #D9EDF7;
            color: #31708F;
        }
        .badge-yellow {
            background-color: #FCF8E3;
            color: #8A6D3B;
        }
        .badge-red {
            background-color: #F2DEDE;
            color: #A94442;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
            <div class="meta">Generated on: {{ $generatedAt }}</div>
            <div class="meta">Period: {{ ucfirst($period) }} ({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})</div>
        </div>
        
        <h2>Summary</h2>
        <table class="summary-table">
            <tr>
                <td>
                    <div class="card">
                        <div class="card-title">Stock Movements</div>
                        <div class="card-value">{{ $summary['stockMovementsCount'] }}</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Items Assigned</div>
                        <div class="card-value">{{ $summary['itemsAssignedCount'] }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="card">
                        <div class="card-title">Items Pulled</div>
                        <div class="card-value">{{ $summary['itemsPulledCount'] }}</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Transfers</div>
                        <div class="card-value">{{ $summary['transfersCount'] }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="card">
                        <div class="card-title">Total Items</div>
                        <div class="card-value">{{ $summary['totalItems'] }}</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Active Items</div>
                        <div class="card-value">{{ $summary['activeItems'] }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="card">
                        <div class="card-title">Purchase Orders</div>
                        <div class="card-value">{{ $summary['purchaseOrdersCount'] }}</div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Delivered POs</div>
                        <div class="card-value">{{ $summary['purchaseOrdersDeliveredCount'] }}</div>
                    </div>
                </td>
            </tr>
        </table>
        
        <div class="page-break"></div>
        <h2>Top Items</h2>
        @if($topItems->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topItems as $item)
                        <tr>
                            <td>{{ $item->item->name }}</td>
                            <td>{{ $item->item->category->name ?? 'N/A' }}</td>
                            <td>{{ $item->total_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available for this period.</p>
        @endif
        
        <div class="page-break"></div>
        
        <h2>Stock Movement History</h2>
        @if($stockMovements->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockMovements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $movement->item->name }}</td>
                            <td>
                                @if($movement->type == 'in')
                                    <span class="badge badge-green">In</span>
                                @elseif($movement->type == 'out')
                                    @if(str_starts_with($movement->notes ?? '', 'Pullout:'))
                                        <span class="badge badge-red">Pulled</span>
                                    @else
                                        <span class="badge badge-blue">Assigned</span>
                                    @endif
                                @else
                                    <span class="badge badge-yellow">Transfer</span>
                                @endif
                            </td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->fromLocation ? $movement->fromLocation->name : 'Inventory' }}</td>
                            <td>{{ $movement->toLocation ? $movement->toLocation->name : 'N/A' }}</td>
                            <td>{{ $movement->notes ?? 'No notes' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No stock movements found for this period.</p>
        @endif
        
        <div class="footer">
            <p>This report was automatically generated from the Hotel Inventory Management System.</p>
            <p>&copy; {{ date('Y') }} Hotel Inventory. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 