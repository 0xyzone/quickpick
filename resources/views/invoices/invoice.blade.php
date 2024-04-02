<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $order->id }}</title>
    <style>
        @page {
            size: 80mm;
            /* Set paper size to 80mm width */
            margin: 0;
            /* Reset margins for the entire page */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            font-size: 10px;
            /* Adjust font size as needed */
        }

        .invoice {
            width: 80mm;
            /* Adjust width to match your printer's paper size */
            margin: 0 auto;
            padding: 10px;
            overflow: hidden;
            /* Hide content that overflows the height */
        }

        .invoice-content {
            /* Set a max-height to prevent overflowing */
            max-height: calc(100vh - 20px);
            /* Calculate available height minus padding */
            overflow: hidden;
            /* Hide content that exceeds max-height */
        }

        .invoice p {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            padding: 0 20px 0 0;
        }

        th {
            text-align: left;
        }

        .total {
            /* text-align: right; */
            font-weight: bold;
            padding-top: 0.25rem;
        }

        .total-amount {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        /* Add additional styles as needed */

    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-content">
            <h1>Order #{{ $order->id }}</h1>
            {{-- <p>Ordered By: {{ $order->user->name }}</p> --}}
            {{-- <p>Address: {{ $order['address'] }}</p> --}}
            <!-- Add more order details here -->
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
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
                        <td class="total" colspan="2">Total</td>
                        <td class="total-amount">{{ rtrim(rtrim(number_format($order->total, 2), '0'), '.') }}</tt>
                    </tr>
                </tfoot>
            </table>
            {{-- <p>Total: {{ $order['total'] }}</p>
            <p>Ordered At: {{ $order['created_at']->format('jS M, Y') }}</p> --}}
        </div>
    </div>
    <script>
        // Adjust the height of the invoice dynamically based on content
        window.onload = function() {
            adjustInvoiceHeight();
            window.addEventListener('resize', adjustInvoiceHeight); // Adjust height on window resize
        };

        // function adjustInvoiceHeight() {
        //     var invoice = document.querySelector('.invoice');
        //     var content = document.querySelector('.invoice-content');
        //     // Calculate the available height (viewport height minus padding)
        //     var availableHeight = window.innerHeight - (parseInt(window.getComputedStyle(invoice).paddingTop) * 2);
        //     // Set the content height to the available height
        //     content.style.maxHeight = availableHeight + 'px';
        // }

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
