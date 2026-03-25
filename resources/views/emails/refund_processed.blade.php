<!DOCTYPE html>
<html>

<head>
    <title>Pemberitahuan Refund - Tangwin Barbershop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .header {
            background: #1f2937;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            background: #fff;
        }

        .footer {
            font-size: 0.8em;
            text-align: center;
            color: #777;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Refund Anda Telah Diproses</h2>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $refund->reservation->customer_name }}</strong>,</p>
            <p>Pengajuan pengembalian dana (refund) untuk reservasi Anda telah berhasil kami proses dan transfer ke rekening Anda.</p>

            <h3>Detail Refund:</h3>
            <table>
                <tr>
                    <th width="30%">ID Reservasi</th>
                    <td>#{{ $refund->id_reservation }}</td>
                </tr>
                <tr>
                    <th>Bank Tujuan</th>
                    <td>{{ $refund->bank_name }}</td>
                </tr>
                <tr>
                    <th>Nomor Rekening</th>
                    <td>{{ $refund->account_number }}</td>
                </tr>
                <tr>
                    <th>Atas Nama</th>
                    <td>{{ $refund->account_name }}</td>
                </tr>
                <tr>
                    <th>Nominal</th>
                    <td><strong>Rp {{ number_format($refund->amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>

            <p style="margin-top: 20px;">
                Jika admin kami mengunggah bukti transfer, Anda dapat menemukannya pada lampiran email ini. Silakan cek mutasi rekening Anda, membutuhkan waktu beberapa saat tergantung kliring bank.
            </p>

            <p>Terima kasih atas kepercayaan Anda terhadap Tangwin Barbershop.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Tangwin Barbershop. All rights reserved.
        </div>
    </div>
</body>

</html>