@extends('layouts.admin-lte')
@section('css')
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    @media(max-width: 1000px) {
        table {
            display: flex;
            justify-content: space-between;
        }

        tr {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
    }
</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Wallet</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Wallet</a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="col-md-12 no-padding">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Your Wallet Information</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-md-4">
                            <h3><b>Hi {{ Auth()->user()->full_name }} !!<b></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <h5>Berikut informasi tentang wallet anda ....</h5>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        @if (Auth()->user()->access_id == 1)
                        <div class="col-md-6">
                            @else
                            <div class="col-md-4">
                                @endif

                                <div class="info-box">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-address-card"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Address</span>
                                        <span class="info-box-number">{{ Auth()->user()->wallet->address }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            @if (Auth()->user()->access_id == 1)
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-credit-card"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Fix Money</span>
                                        <span class="info-box-number">Rp.{{ App\Models\FixMoney::total() }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            @endif


                            @if (Auth()->user()->access_id == 3)
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Balance</span>
                                        <span class="info-box-number">Rp.{{ Auth()->user()->wallet->balance }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-money-bill"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">CashOut</span>
                                        <span class="info-box-number">Rp.{{ Auth::user()->totalCashouts }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                @endif
                                <!-- /.info-box -->
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            @if (Auth()->user()->access_id == 3)
            <div class="col-md-12 no-padding">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Order History</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="text" id="start_date" class="form-control datepicker" placeholder="Select start date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="text" id="end_date" class="form-control datepicker" placeholder="Select end date">
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Nomor</th>
                                    <th scope="col">Kantin</th>
                                    <th scope="col">Wallet Address</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date And Time</th>
                                </tr>
                            </thead>
                            <tbody id="contentTable">
                                @foreach ($orders as $order)
                                <tr>
                                    <td scope="col">{{ $loop->index + 1 }}</td>
                                    <td scope="col">{{ $order->canteen->name }}</td>
                                    <td scope="col">{{ $order->canteen->user->wallet->address }}</td>
                                    <td scope="col">{{ $order->total }}</td>
                                    <td scope="col">{{ $order->payment == 1 ? "Success" : "Pending" }}</td>
                                    <td scope="col">{{ $order->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
@section('last')
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        function filterOrders() {
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            $('#contentTable tr').each(function() {
                let date = $(this).find('td:last').text().trim();
                let show = true;

                if (startDate && new Date(date) < new Date(startDate)) {
                    show = false;
                }

                if (endDate && new Date(date) > new Date(endDate)) {
                    show = false;
                }

                $(this).toggle(show);
            });
        }

        $('#start_date, #end_date').change(filterOrders);
    });
</script>
@endsection
