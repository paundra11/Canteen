@extends('layouts.admin-lte')
@section('css')
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: red;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: green;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }
</style>

@endsection
@section('content')
@if(session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Canteen</h1>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Canteen</a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card card-danger shadow-lg">
                <div class="card-header">
                    <h3 class="card-title"><b>{{Auth()->user()->canteen->name}}</b></h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4"></div>
                        <figure class="figure col-md-4">
                            <img src="{{ asset('storage/' . Auth()->user()->canteen->image) }}" class="figure-img img-fluid rounded" alt="Canteen Image">
                            <figcaption class="figure-caption">Canteen {{ Auth()->user()->canteen->name }}</figcaption>
                        </figure>
                    </div>
                    <div class="row">
                        <h3 class='col-md-4'><b>Nama Toko </b></h3>
                        <h3 class='col-md-4'>: {{ Auth()->user()->canteen->name }}</h3>
                    </div>
                    <div class="row">
                        <h3 class='col-md-4'><b>Pemilik Toko </b></h3>
                        <h3 class='col-md-4'>: {{ Auth()->user()->full_name }}</h3>
                    </div>
                    <div class="row">
                        <h3 class='col-md-4'><b>Menjual </b></h3>
                        <h3 class='col-md-4'>: Makanan Dan Minuman</h3>
                    </div>
                    <div class="row">
                        <h3 class='col-md-4'><b>Status </b></h3>
                        <h3 class='col-md-4'>
                            <form action="{{ route('panel.canteen.toggleStatus') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ Auth()->user()->canteen->id }}">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="active" @if (Auth()->user()->canteen->status == 'active') selected @endif>BUKA</option>
                                    <option value="inactive" @if (Auth()->user()->canteen->status == 'inactive') selected @endif>TUTUP</option>
                                </select>
                            </form>
                        </h3>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-12">
            <div class="card card-danger shadow-lg">
                <div class="card-header">
                    <h3 class="card-title">Menu</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <button class='btn btn-primary col-12 col-md-3 mb-4' id="aa" data-toggle="modal" data-target="#modalbos1">Tambah Menu</button>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="d-flex justify-content-center align-items-center row gap-3 col-12">
                            @foreach ($food as $item)
                            <div class="col-md-4 col-12">
                                <div class="card p-3 shadow-lg">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <img src="{{ asset('storage/'.$item->image) }}" height="200" width="auto" class="rounded mx-auto d-block" alt="{{ $item->name }}">
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-8 col-12">
                                                <h4><b>{{ $item->name }}</b></h4>
                                                <h5><b>Rp. {{ number_format($item->price, 0, ',', '.') }}</b></h5>
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-center align-items-center col-12">
                                                <h1 class="fw-bold">{{ $item->stock }}</h1>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <button class="btn btn-dark rounded-pill col-12 edit-btn" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-type="{{ $item->jenis }}" data-price="{{ $item->price }}" data-stock="{{ $item->stock }}" data-image="{{ asset('storage/'.$item->image) }}">Edit Menu</button>
                                        </div>
                                        <div class="row mt-3">
                                            <form action="{{ route('panel.canteen.destroy', $item->id) }}" method="POST" class="col-12">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger rounded-pill col-12 delete-btn">Delete Menu</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="col-md-12">
                <div class="card card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Order</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        
                        <table class="table table-hover">
                            <thead>
                                <tr>

                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Total_Price</th>
                                    <th scope="col">Wallet Address</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Pay</th>
                                    {{-- <th scope="col">Last Login</th> --}}
                                    <th scope="col">Detail</th>
                                </tr>

                            </thead>
                            <tbody id="contentTable">

                            </tbody>
                        </table>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <!-- /.card -->

        </div>
    </div>
</section>
@endsection
@section('last')
<!-- Modal -->
<div class="modal fade" id="modalbos1" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Menu</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Add New Food</h3>
                <form action="{{route('panel.canteen.add',['user'=>Auth()->user()->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="canteen_id" id="canteen_id" value="{{Auth()->user()->id}}">
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Food Image</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
                        @error('image')
                        {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Name makanan</label>
                        <input type="text" id="name" class="form-control @error(" name") is-invalid @enderror" name="name" value="{{old("name")}}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Jenis Makanan</label>
                        <select class="form-control" id="exampleFormControlSelect1" name='jenis'>
                            <option value='makanan'>Makanan</option>
                            <option value='minuman'>Minuman</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inlineFormInputGroup">Name Canteen</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">RP.</div>
                            </div>
                            <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="0.00" name='price'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Stock :</label>
                        <input type="number" id="quantity" min="1" max="50" name='stock'>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editFoodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFoodForm" action="{{ route('panel.canteen.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="food_id" id="editFoodId">
                    <div class="form-group">
                        <label for="editFoodImage">Food Image</label>
                        <input type="file" class="form-control-file" id="editFoodImage" name="image">
                        @error('image')
                        {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editFoodName">Name makanan</label>
                        <input type="text" id="editFoodName" class="form-control @error('name') is-invalid @enderror" name="name">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="editFoodType">Jenis Makanan</label>
                        <select class="form-control" id="editFoodType" name='jenis'>
                            <option value='makanan'>Makanan</option>
                            <option value='minuman'>Minuman</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editFoodPrice">Name Canteen</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">RP.</div>
                            </div>
                            <input type="text" class="form-control" id="editFoodPrice" name='price'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editFoodStock">Stock :</label>
                        <input type="number" id="editFoodStock" class="form-control" name='stock'>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





@endsection
@section('js')
<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name');
            var type = this.getAttribute('data-type');
            var price = this.getAttribute('data-price');
            var stock = this.getAttribute('data-stock');
            var image = this.getAttribute('data-image');

            document.getElementById('editFoodId').value = id;
            document.getElementById('editFoodName').value = name;
            document.getElementById('editFoodType').value = type;
            document.getElementById('editFoodPrice').value = price;
            document.getElementById('editFoodStock').value = stock;

            // If you want to show the current image in the modal
            var imgPreview = document.createElement('img');
            imgPreview.src = image;
            imgPreview.classList.add('img-fluid', 'mt-2', 'w-50', 'd-block', 'mx-auto');
            var imgContainer = document.getElementById('editFoodImage').parentNode;
            if (imgContainer.lastChild.tagName === 'IMG') {
                imgContainer.removeChild(imgContainer.lastChild);
            }
            imgContainer.appendChild(imgPreview);

            $('#editFoodModal').modal('show');
        });
    });

    var table = document.getElementById('contentTable');
    var xhr = new XMLHttpRequest();
    var url = "{{ route('api.canteen.order-spesific', ['canteen_id' => \DB::table('canteens')->where('user_id',Auth()->user()->id)->first()->id]) }}";
    xhr.onreadystatechange = function() {
        var div = document.createElement('div');
        if (this.readyState == 4 && this.status == 200) {
            responsene = JSON.parse(this.responseText);
            responsene.forEach((element, index) => {
                let myTr = document.createElement("tr");
                let myTh = document.createElement("th");

                myTh.innerText = index + 1;
                myTr.appendChild(myTh)

                myTh = document.createElement("td");
                myTh.innerText = element['full_name'];
                myTr.appendChild(myTh)

                myTh = document.createElement("td");
                myTh.innerText = element['total'];
                myTr.appendChild(myTh);

                myTh = document.createElement("td");
                myTh.innerText = element['address'];
                myTr.appendChild(myTh)

                myTh = document.createElement("td");
                myTh.innerText = element['description'];
                myTr.appendChild(myTh)

                myTh = document.createElement("td");

                if (element['payment'] == 1) {
                    myTh.innerText = "Lunas";
                } else {
                    myTh.innerText = "Belum Lunas";
                }
                myTr.appendChild(myTh);

                myTh = document.createElement("td");
                let button = document.createElement("a");
                button.classList.add("btn");
                button.classList.add("btn-info");
                button.setAttribute("role", "button");
                let url_sementara = "/panel/Canteen/detail/" + element["id"];
                button.setAttribute("href", url_sementara);
                button.innerText = "Detail";



                myTh.appendChild(button);
                myTr.appendChild(myTh)

                div.appendChild(myTr);
            });
            table.innerHTML = div.outerHTML;
        }
    };

    setInterval(function() {
        xhr.open("GET", url, true);
        xhr.send();
    }, 2000);
</script>
@endsection