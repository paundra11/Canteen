@extends('layouts.admin-lte')
@section('css')
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endsection
@section('content')

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Order Details</h3>
        </div>
        <div class="card-body">
            <p><strong>User ID:</strong> {{ \DB::table('users')->where('id', $order->user_id)->value('full_name') }}</p>
            <p><strong>Canteen ID:</strong> {{ \DB::table('canteens')->where('id', $order->canteen_id)->value('name') }}</p>
            <p><strong>Description:</strong> {{$order->description}}</p>
            <h5 class="mt-4">List of Food:</h5>
            <div class="row">
                @foreach($list as $ls)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="{{ asset('storage/'.$ls->image) }}" class="card-img-top" alt="Food Image">
                            <div class="card-body">
                                <h5 class="card-title">{{$ls->name}}</h5>
                                <p class="card-text">Jumlah: {{$count[$ls->id]}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p><strong>Total Belanja:</strong> {{$order->total}}</p>
            <p><strong>Pembayaran:</strong> {{$order->total}}</p>
            @if($order->payment == 0)
                <form method="post" action="{{route('panel.canteen.cancel', $order->id)}}">
                    @csrf
                    <input type="text" value="Maaf Pesanan habis" hidden>
                    <button type="submit" class="btn btn-info">Batalkan Pesanan</button>
                </form>
            @else
                <button class="btn btn-dark" disabled>Batalkan Pesanan</button>
            @endif
        </div>
    </div>
</div>

@endsection
