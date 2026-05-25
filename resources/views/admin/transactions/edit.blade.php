@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Edit Status Transaksi</h2>
            <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Perbarui Status Transaksi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="order_id"><strong>Order ID</strong></label>
                            <input type="text" class="form-control" id="order_id" 
                                   value="{{ $transaction->order_id }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="customer_name"><strong>Nama Customer</strong></label>
                            <input type="text" class="form-control" id="customer_name" 
                                   value="{{ $transaction->customer_name }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="status"><strong>Status Transaksi</strong></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">-- Pilih Status --</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" 
                                            {{ $transaction->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info" role="alert">
                            <strong>Status saat ini:</strong> 
                            @if($transaction->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($transaction->status == 'completed')
                                <span class="badge badge-success">Completed</span>
                            @elseif($transaction->status == 'failed')
                                <span class="badge badge-danger">Failed</span>
                            @else
                                <span class="badge badge-secondary">Cancelled</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Perbarui Status</button>
                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                           class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
