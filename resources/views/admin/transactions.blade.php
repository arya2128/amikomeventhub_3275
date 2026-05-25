@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Manajemen Transaksi</h2>
            <p class="text-muted">Daftar semua pembayaran yang masuk</p>
        </div>
    </div>

    <!-- Redirect ke transactions.index -->
    <script>
        window.location.href = "{{ route('admin.transactions.index') }}";
    </script>
    
    <p class="alert alert-info">Mengalihkan ke halaman transaksi...</p>
@endsection