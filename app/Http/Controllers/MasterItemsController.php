<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\KategoriItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $data_search = MasterItem::with('kategoriItems');

        if ($request->filled('kode')) $data_search = $data_search->where('kode', $request->kode);
        if ($request->filled('nama')) $data_search = $data_search->where('nama', 'LIKE', '%' . $request->nama . '%');
        if ($request->filled('hargamin')) $data_search = $data_search->where('harga_beli', '>=', $request->hargamin);
        if ($request->filled('hargamax')) $data_search = $data_search->where('harga_beli', '<=', $request->hargamax);

        $data_search = $data_search->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem;
        } else {
            $item = MasterItem::with('kategoriItems')->findOrFail($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategori_list'] = KategoriItem::orderBy('nama')->get();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('kategoriItems')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required',
            'harga_beli' => 'required|numeric',
            'laba' => 'required|numeric',
            'supplier' => 'required',
            'jenis' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'foto.image' => 'Foto harus berupa file gambar (jpeg, png, jpg, webp)',
            'foto.mimes' => 'Foto harus bertipe: jpeg, png, jpg, webp',
            'foto.max' => 'Foto maksimal 5MB',
            'foto.uploaded' => 'Foto gagal diupload. Periksa ukuran file (maks 5MB)',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = MasterItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $path = $file->storeAs('foto_items', $filename, 'public');
            $data_item->foto = $path;
        }

        $data_item->save();

        if ($request->has('kategori_ids')) {
            $data_item->kategoriItems()->sync($request->kategori_ids);
        } else {
            $data_item->kategoriItems()->sync([]);
        }

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::findOrFail($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    public function pdf($id)
    {
        $item = MasterItem::with('kategoriItems')->findOrFail($id);

        $pdf = Pdf::loadView('master_items.pdf.single', compact('item'));

        return $pdf->download('item-' . $item->kode . '.pdf');
    }

    public function exportPdf()
    {
        $items = MasterItem::with('kategoriItems')->orderBy('id')->get();

        $pdf = Pdf::loadView('master_items.pdf.all', compact('items'));

        return $pdf->download('master-items.pdf');
    }

    public function exportExcel()
    {
        $items = MasterItem::with('kategoriItems')->orderBy('id')->get();

        $output = '<table>';
        $output .= '<tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Nama Item</th>
            <th>Supplier</th>
            <th>Harga Beli</th>
            <th>Laba</th>
            <th>Harga Jual</th>
        </tr>';

        $no = 1;
        foreach ($items as $item) {
            $kategoriNames = $item->kategoriItems->pluck('nama')->implode(', ');
            $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);
            $output .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . $kategoriNames . '</td>
                <td>' . $item->nama . '</td>
                <td>' . $item->supplier . '</td>
                <td>' . $item->harga_beli . '</td>
                <td>' . $item->laba . '</td>
                <td>' . round($hargaJual) . '</td>
            </tr>';
        }

        $output .= '</table>';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="master-items.xls"');
        echo $output;
        exit;
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}
