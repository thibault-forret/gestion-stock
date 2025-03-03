<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ str_replace(' ', '_', $warehouse_name) }}_INVOICE_{{ $invoice->invoice_number}}_{{ str_replace(':', '_', str_replace(' ', '_', $invoice->created_at)) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header .invoice-info {
            text-align: right;
        }

        .section {
            margin: 10px 0;
        }

        .section h4 {
            font-size: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #555;
        }

        .details {
            line-height: 1.4;
            margin-bottom: 20px;
        }

        .details p {
            margin: 4px 0;
        }

        .supplier-client-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .divider {
            display: inline-block;
            width: 100%;
            border-bottom: 2px solid #333;
            margin: 10px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table tfoot td {
            font-weight: bold;
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .invoice-details {
            margin-left: auto;
            margin-right: 0;
            width: 30%;
            border-collapse: collapse;
        }

        .invoice-details th, .invoice-details td {
            padding: 4px 0px;
            text-align: right;
        }

        .invoice-details th {
            font-weight: bold;
        }

        .invoice-details td {
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div>
                <div class="supplier-client-title">{{ __('invoice.supplier') }}</div>
                <div class="details">
                    <p><strong>{{ __('invoice.name') }}:</strong> {{ $invoice->entity_name }}</p>
                    <p><strong>{{ __('invoice.email') }}:</strong> {{ $supply->supplier->supplier_email }}</p>
                    <p><strong>{{ __('invoice.phone') }}:</strong> {{ $supply->supplier->supplier_phone }}</p>
                    <p><strong>{{ __('invoice.address') }}:</strong> {{ $invoice->entity_address }}</p>
                    <p><strong>{{ __('invoice.manager') }}:</strong> {{ $invoice->entity_director }}</p>
                </div>

                <div class="supplier-client-title">{{ __('invoice.client') }}</div>
                <div class="details">
                    <p><strong>{{ __('invoice.name') }}:</strong> {{ $invoice->warehouse_name }}</p>
                    <p><strong>{{ __('invoice.location') }}:</strong> {{ $invoice->warehouse_address }}</p>
                    <p><strong>{{ __('invoice.email') }}:</strong> {{ $supply->warehouse->warehouse_email }}</p>
                    <p><strong>{{ __('invoice.phone') }}:</strong> {{ $supply->warehouse->warehouse_phone }}</p>
                    <p><strong>{{ __('invoice.manager') }}:</strong> {{ $invoice->warehouse_director }}</p>
                </div>
            </div>
            <div class="invoice-info">
                <div class="supplier-client-title">{{ __('invoice.invoice') }}</div>
                <table class="invoice-details">
                    <tr>
                        <th>{{ __('invoice.number') }}:</th>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('invoice.date') }}:</th>
                        <td>{{ $invoice->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('invoice.status') }}:</th>
                        <td>{{ $invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID ? __('invoice.settled') : __('invoice.not_settled') }}</td>
                    </tr>
                    @if($invoice->invoice_status === \App\Models\Invoice::INVOICE_STATUS_PAID)
                        <tr>
                            <th>{{ __('invoice.settled_on') }}:</th>
                            <td>{{ $invoice->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="section">
            <h4>{{ __('invoice.items') }}</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('order.id') }}</th>
                        <th>{{ __('order.name') }}</th>
                        <th>{{ __('order.quantity') }}</th>
                        <th>{{ __('order.unit_price') }}</th>
                        <th>{{ __('invoice.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($supply->supplyLines as $line)
                        <tr>
                            <td>{{ $line->product->id }}</td>
                            <td>{{ $line->product->product_name }}</td>
                            <td>{{ $line->quantity_supplied }}</td>
                            <td>{{ number_format($line->unit_price, 2, ',', ' ') }}</td>
                            <td>{{ number_format($line->unit_price * $line->quantity_supplied, 2, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;">{{ __('invoice.total') }}</td>
                        <td>{{ number_format($total_amount, 2, ',', ' ') }} €</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('invoice.thanks') }}</p>
            <p>{{ __('invoice.generated') }}</p>
        </div>
    </div>
</body>
</html>
