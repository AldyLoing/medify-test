<form method="POST" enctype="multipart/form-data">
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
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Foto</label>
        <input type="file" class="form-control" name="foto" accept="image/jpeg,image/png,image/webp,image/jpg">
        @if($method == 'edit' && !empty($item->foto))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" style="max-width: 200px; max-height: 200px;">
        </div>
        @endif
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <div class="row">
            @foreach($kategori_list as $kat)
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="kategori_ids[]" value="{{ $kat->id }}"
                        @if($method == 'edit' && $item->kategoriItems->contains($kat->id)) checked @endif
                    >
                    <label class="form-check-label">{{ $kat->kode }} - {{ $kat->nama }}</label>
                </div>
            </div>
            @endforeach
        </div>
        @if($kategori_list->isEmpty())
        <p class="text-muted">Belum ada kategori. Buat kategori terlebih dahulu.</p>
        @endif
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>
