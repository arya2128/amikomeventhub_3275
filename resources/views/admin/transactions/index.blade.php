@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Manajemen Transaksi</h2>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total</h6>
                    <h4>{{ $stats['total'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Pending</h6>
                    <h4 style="color: #ffc107;">{{ $stats['pending'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Completed</h6>
                    <h4 style="color: #28a745;">{{ $stats['completed'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Failed</h6>
                    <h4 style="color: #dc3545;">{{ $stats['failed'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Cancelled</h6>
                    <h4 style="color: #6c757d;">{{ $stats['cancelled'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari order ID / customer" 
                           value="{{ request('search') }}">
                </div>
                <div class="form-group mr-2">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Transaksi</h5>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Event</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td><strong>{{ $transaction->order_id }}</strong></td>
                                            <td>{{ $transaction->customer_name }}</td>
                                            <td>{{ $transaction->customer_email }}</td>
                                            <td>{{ $transaction->event->title ?? 'N/A' }}</td>
                                            <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
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
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                                                   class="btn btn-sm btn-info">Lihat</a>
                                                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" 
                                                   class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" 
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <p class="text-center text-muted">Tidak ada transaksi.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
