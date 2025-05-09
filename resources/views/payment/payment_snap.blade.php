<html>
<head>
  <title>Bayar dengan Midtrans</title>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body>
  <script type="text/javascript">
    window.onload = function() {
      snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){ 
            alert("Pembayaran sukses!"); console.log(result); 
            window.location.href = '{{ route('transactions.success', ['transaction' => $orderId]) }}';
        },
        onPending: function(result){ alert("Menunggu pembayaran!"); console.log(result); },
        onError: function(result){ alert("Pembayaran gagal!"); console.log(result); },
        onClose: function(){ alert('Popup ditutup.'); }
      });
    };
  </script>
  
</body>
</html>
