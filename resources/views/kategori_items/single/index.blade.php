@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('kategori-items')}}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card">
                <div class="card-header">Detail Kategori</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Kode</th>
                            <td>:</td>
                            <td>{{$data->kode}}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                    </table>
                    <a class="btn btn-info" href="{{url('kategori-items/form/edit')}}/{{$data->id}}">Edit</a>
                    <a class="btn btn-danger" href="{{url('kategori-items/delete')}}/{{$data->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    <a class="btn btn-success" href="{{url('kategori-items/pdf')}}/{{$data->id}}">Download PDF</a>
                    <hr>
                    <h5>Daftar Item dalam Kategori Ini</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Item</th>
                                <th>Nama Item</th>
                                <th>Supplier</th>
                                <th>Harga Beli</th>
                                <th>Laba</th>
                                <th>Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($data->masterItems as $item)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$item->kode}}</td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->supplier}}</td>
                                <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td>{{$item->laba}}%</td>
                                <td>{{ number_format($item->harga_beli + ($item->harga_beli * $item->laba / 100), 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection
