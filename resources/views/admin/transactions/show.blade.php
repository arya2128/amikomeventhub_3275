@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Detail Transaksi</h2>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Order ID</strong></td>
                            <td>{{ $transaction->order_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Customer</strong></td>
                            <td>{{ $transaction->customer_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $transaction->customer_email }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Telepon</strong></td>
                            <td>{{ $transaction->customer_phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>Event</strong></td>
                            <td>{{ $transaction->event->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Harga</strong></td>
                            <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                @if($transaction->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($transaction->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif($transaction->status == 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @else
                                    <span class="badge badge-secondary">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Snap Token</strong></td>
                            <td>{{ $transaction->snap_token ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Aksi</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}" 
                       class="btn btn-warning btn-block mb-2">Edit Status</a>
                    <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" 
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus Transaksi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
