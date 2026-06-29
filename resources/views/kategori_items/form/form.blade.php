<form method="POST">
    @csrf
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode</label>
        <input type="text" class="form-control" name="kode" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{$item->nama ?? ''}}">
    </div>

    <button class="btn btn-primary mt-3">Submit</button>
</form>
