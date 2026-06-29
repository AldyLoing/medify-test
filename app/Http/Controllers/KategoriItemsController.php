<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $data_search = KategoriItem::query();

        if ($request->filled('kode')) $data_search = $data_search->where('kode', 'LIKE', '%' . $request->kode . '%');
        if ($request->filled('nama')) $data_search = $data_search->where('nama', 'LIKE', '%' . $request->nama . '%');

        $data_search = $data_search->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new KategoriItem;
        } else {
            $item = KategoriItem::findOrFail($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        if ($method == 'new') {
            $data_item = new KategoriItem;
            $kode = KategoriItem::count('id');
            $kode = $kode + 1;
            $kode = 'KAT-' . str_pad($kode, 4, '0', STR_PAD_LEFT);
        } else {
            $data_item = KategoriItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->kode = $kode;
        $data_item->save();

        return redirect('kategori-items');
    }

    public function singleView($id)
    {
        $data['data'] = KategoriItem::with('masterItems')->findOrFail($id);
        return view('kategori_items.single.index', $data);
    }

    public function delete($id)
    {
        KategoriItem::findOrFail($id)->delete();
        return redirect('kategori-items');
    }

    public function pdf($id)
    {
        $kategori = KategoriItem::with('masterItems')->findOrFail($id);

        $pdf = Pdf::loadView('kategori_items.pdf.index', compact('kategori'));

        return $pdf->download('kategori-' . $kategori->kode . '.pdf');
    }
}
