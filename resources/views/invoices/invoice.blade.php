<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $order->id }}</title>
    <style>
        @page {
            size: 72mm 297mm;
            /* Set paper size to 80mm width */
            margin: 0;
            /* Reset margins for the entire page */
        }

        @media print {
            .pagebreak {
                clear: both;
                page-break-before: always;
                page-break-after: always;
            }
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            font-size: 10px;
            /* Adjust font size as needed */
        }

        table {
            width: 100%;
            padding: 0;
            margin: 0 auto;
            table-layout: fixed;
        }

        th {
            text-align: left;
            padding-top: 8px;
        }

        tbody {
            font-size: 11px;
        }

        tbody td {
            padding: 2px 0;
        }

        .total {
            /* text-align: right; */
            font-weight: bold;
            padding-top: 0.25rem;
        }

        .total-amount {
            font-weight: bold;
            text-align: right;
            padding-right: 10px;
        }

        .text-center {
            text-align: center;
        }

        h1 {
            margin: 0;
            padding: 2px 0;
        }

        h2 {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h4 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .orderNumber {
            text-align: center;
            margin-bottom: 2px;
            font-size: 1.25rem;
            color: #fff;
            background: #000;
        }

        .invoice-content {
            padding: 0 16px;
        }

        /* Add additional styles as needed */

    </style>
</head>
<body>
    @php
    use App\Models\Company;
    $company = Company::find(1);
    @endphp
    <div class="invoice">
        <div class="invoice-content">
            <h1 class="orderNumber">Order #{{ $order->id }}</h1>
            <h1 class="text-center">{{ $company->name ?? '' }}</h1>
            <p style="font-weight: bold;">{{ $company->address ?? '' }}</p>
            <p style="font-weight: bold;">{{ $company->contact ?? '' }}</p>
            <p style="padding: 4px 0;">Order Placed {{ Carbon\Carbon::parse($order->created_at)->format('jS M, Y | g:ma') }}</p>
            <table>
                <thead>
                    <tr>
                        <th style="width: 65%;">Item</th>
                        <th class="text-center">Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->item->name }}</td>
                        <td class="text-center">{{ formatNumber($item->quantity) }}</td>
                        <td>{{ $item->item->price * $item->quantity }}</td>
                    </tr>
                    @endforeach
                    @php
                    function formatNumber($number) {
                    $formattedNumber = rtrim(rtrim(number_format($number, 2), '0'), '.');
                    if (strpos($formattedNumber, '.5') !== false) {
                    $formattedNumber = str_replace('.5', '½', $formattedNumber);
                    }
                    return $formattedNumber;
                    }
                    @endphp
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align:center;" colspan='3'>
                            <hr style="border: 2px dotted #000000; border-style: none none dotted; color: #fff; background-color: #fff;">
                        </td>
                    </tr>
                    <tr>
                        <td class="total">Sub Total</td>
                        <td class="total-amount" colspan="2">Rs. {{ rtrim(rtrim(number_format($order->sub_total, 2), '0'), '.') }}</td>
                    </tr>
                    @if ($order->discount_amount != 0)
                    <tr>
                        <td class="total">Discount</td>
                        <td class="total-amount" colspan="2">Rs. {{ rtrim(rtrim(number_format($order->discount_amount, 2), '0'), '.') }}</td>
                    </tr>
                    @endif
                    @if ($order->delivery_charge != 0)
                    <tr>
                        <td class="total">Delivery Charge</td>
                        <td class="total-amount" colspan="2">Rs. {{ rtrim(rtrim(number_format($order->delivery_charge, 2), '0'), '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="total">Grand Total</td>
                        <td class="total-amount" colspan="2">Rs. {{ rtrim(rtrim(number_format($order->total, 2), '0'), '.') }}</tt>
                    </tr>
                    <tr>
                        <td style="text-align:center;" colspan='3'>
                            <hr style="border: 2px dotted #000000; border-style: none none dotted; color: #fff; background-color: #fff;">
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="pagebreak"></div>
            <p style="padding: 0 6px;">Thank you for dining with us! Your presence brightened our day. We hope to see you again soon for more delicious moments.</p>
            <img src="review.png" alt="" style="display: block; margin-left: 50%; transform: translate(-50%, 0); width: 50%; margin-top: 5px; margin-bottom: 5px; ">
            <p style="padding: 0 6px;">Scan the above qr to leave a review for us.</p>
        </div>
    </div>
    <script>
        // Adjust the height of the invoice dynamically based on content
        window.onload = function() {
            adjustInvoiceHeight();
            window.addEventListener('resize', adjustInvoiceHeight); // Adjust height on window resize
        };

        function adjustInvoiceHeight() {
            var invoice = document.querySelector('.invoice');
            var content = document.querySelector('.invoice-content');
            // Calculate the available height(viewport height minus padding)
            var availableHeight = window.innerHeight - (parseInt(window.getComputedStyle(invoice).paddingTop) * 2);
            // Set the content height to the available height
            content.style.maxHeight = availableHeight + 'px';
        }

        function adjustPageHeight() {
            var invoiceContent = document.querySelector('.invoice-content');
            var invoice = document.querySelector('.invoice');
            var contentHeight = invoiceContent.offsetHeight;
            // Set the page height to the content height plus padding
            invoice.style.height = contentHeight + 'px';
        }

    </script>
</body>
</html>
