<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaction->id_transaction }}</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            background-color: #fff;
            color: #000;
        }

        .receipt {
            width: 58mm;
            padding: 2mm 4mm;
            margin: 0 auto;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        
        .dashed-line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
            width: 100%;
        }

        .header { margin-bottom: 10px; }
        
        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 5px;
            filter: grayscale(100%) contrast(120%); 
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        td {
            vertical-align: top;
            padding: 2px 0;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 10px;
        }

        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt">
        
        {{-- 1. HEADER --}}
        <div class="header text-center">
            <img src="{{ asset('images/logo_struk.png') }}" alt="[LOGO]" class="logo" onerror="this.style.display='none'">
            
            <div style="font-size: 10px;">{{ $transaction->store->address ?? '-' }}</div>
            <div style="font-size: 10px;">Telp: {{ $transaction->store->phone_number ?? '-' }}</div>
        </div>

        <div class="dashed-line"></div>

        {{-- 2. INFO TRANSAKSI --}}
        <div>
            <table>
                <tr>
                    <td width="30%">Tgl</td>
                    <td>: {{ date('d/m/y H:i', strtotime($transaction->transaction_date)) }}</td>
                </tr>
                <tr>
                    <td>No.Nota</td>
                    <td>: #{{ $transaction->id_transaction }}</td>
                </tr>
                <tr>
                    <td>Store</td>
                    <td>: {{ $transaction->store->store_name }}</td>
                </tr>
                
                {{-- REVISI: Menampilkan Semua Capster yang terlibat --}}
                <tr>
                    <td>Capster</td>
                    <td>: {{ $capsterString }}</td>
                </tr>
            </table>
        </div>

        <div class="dashed-line"></div>

        {{-- 3. ITEM BELANJA (GROUPED) --}}
        <div>
            <table>
                {{-- REVISI: Loop menggunakan $groupedDetails --}}
                @foreach($groupedDetails as $item)
                    <tr>
                        <td colspan="3" class="bold">
                            @if($item->item_type == 'service')
                                {{ $item->service->service_name ?? 'Layanan' }}
                            @elseif($item->item_type == 'product')
                                {{ $item->product->product_name ?? 'Produk' }}
                            @elseif($item->item_type == 'food')
                                {{ $item->food->food_name ?? 'Makanan' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        {{-- Tampilkan Quantity hasil gabungan --}}
                        <td class="text-right" width="20%">{{ $item->quantity }} x</td>
                        <td class="text-right" width="40%">{{ number_format($item->price_at_sale, 0, ',', '.') }}</td>
                        <td class="text-right" width="40%">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="dashed-line"></div>

        {{-- 4. TOTAL & PEMBAYARAN --}}
        <div>
            <table>
                <tr>
                    <td class="text-right bold" colspan="2">TOTAL</td>
                    <td class="text-right bold" style="font-size: 14px;">{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>

                @if($transaction->paymentMethod->method_name == 'Cash')
                    <tr>
                        <td class="text-right" colspan="2">Tunai</td>
                        <td class="text-right">{{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="2">Kembali</td>
                        <td class="text-right">{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="text-right" colspan="2">Bayar via</td>
                        <td class="text-right">{{ $transaction->paymentMethod->method_name }}</td>
                    </tr>
                @endif

                @if($transaction->tips > 0)
                    <tr>
                        <td colspan="3" class="dashed-line"></td>
                    </tr>
                    <tr>
                        <td class="text-right italic" colspan="2">Tips Capster</td>
                        <td class="text-right italic">{{ number_format($transaction->tips, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="dashed-line"></div>

        {{-- 5. FOOTER --}}
        <div class="footer">
            <p class="bold">TERIMA KASIH</p>
            <p>Atas Kunjungan Anda</p>
            <p style="margin-top: 5px;">IG: @tangwincut</p>
            <p>"House of Handsome"</p>
        </div>

    </div>

</body>
</html>