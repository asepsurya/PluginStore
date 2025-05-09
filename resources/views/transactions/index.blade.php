<!DOCTYPE html>
<html>
<head>
    <title>Daftar Transaksi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #999;
        }
    </style>
</head>
<body>
    <h2>Daftar Transaksi Pembayaran</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama</th>
                <th>Plugin</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
                <tr>
                    <td>{{ $trx->order_id }}</td>
                    <td>{{ $trx->name }}</td>
                    <td>{{ $trx->plugin }}</td>
                    <td>Rp {{ number_format($trx->price) }}</td>
                    <td>{{ ucfirst($trx->status) }}</td>
                    <td>{{ $trx->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
