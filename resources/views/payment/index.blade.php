<form action="{{ route('payment.process') }}" method="POST">
    @csrf
    <label>Nama:</label>
    <input type="text" name="name" required><br>
  
    <label>Plugin:</label>
    <input type="text" name="plugin" required><br>
  
    <label>Harga:</label>
    <input type="number" name="price" required><br>
  
    <button type="submit">Bayar Sekarang</button>
  </form>
  