<?php

namespace App\Http\Controllers;

use App\Models\Harga;
use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
// use DataTables;

class BarangController extends Controller
{
    public function index()
    {
        return view('barang.index');
    }
    public function ajaxIndex()
    {
        $barangs = Barang::with('hargas', 'satuans')->get();
        return DataTables::of($barangs)
            ->addColumn('harga', function ($barang) {
                $harga = '<table style="width: 100%">';
                $harga .= '<tr>';
                $harga .= '<th>Minimal</th>';
                $harga .= '<th>Harga</th>';
                $harga .= '<tr>';
                foreach ($barang->hargas as $hargaItem) {
                    // $formattedHarga = number_format($hargaItem->harga, 0, ',', '.');
                    $harga .= '<tr>';
                    $harga .= '<td>' . $hargaItem->minimal . '</td>';
                    $harga .= '<td class="harga-tbl">' . $hargaItem->harga . '</td>';
                    $harga .= '</tr>';
                }
                $harga .= '</table>';
                return $harga;
            })
            ->rawColumns(['harga'])
            ->toJson();
    }
    public function createKomputer()
    {
        $satuans = Satuan::get();
        return view('barang.create-komputer', ['satuans' => $satuans]);
    }
    public function storeKomputer(Request $request)
    {

        // ASSSIGN
        $finalData = $request->finalData;

        // VALIDASI
        $rules = [
            'finalData' => 'required|array',
            'finalData.*.nama' => 'required',
            'finalData.*.satuanid' => 'required',
            'finalData.*.minhar.*.minimal' => 'required',
            'finalData.*.minhar.*.harga' => 'required',
            'finalData.*.src' => 'nullable|string',
            'finalData.*.filename' => 'nullable',
        ];

        $messages = [
            'finalData.required' => 'Data akhir diperlukan.',
            'finalData.array' => 'Data akhir harus berupa array.',
            'finalData.*.nama.required' => 'Nama harus diisi.',
            'finalData.*.satuanid.required' => 'Satuan barang harus diisi.',
            'finalData.*.minhar.*.minimal.required' => 'Minimal harus diisi.',
            'finalData.*.minhar.*.harga.required' => 'Harga harus diisi.',
            'finalData.*.src.string' => 'Sumber harus berupa string.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // SIMPAN DATA
        foreach ($finalData as $data) {
            // CEK KODE BARANG
            $barang = Barang::where('kode', $data['kode'])->first();
            if ($barang) {
                $errors = [
                    'barang_sudah ada' => ['Barang sudah ada']
                ];
                return response()->json(['error' => $errors], 400);
            }
            // JIKA KODE NYA KOSONG
            $data['manual'] = 0;
            if ($data['kode'] == null) {
                $data['kode'] = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $data['manual'] = 1;
            }
            // UNTUK GAMBAR
            if ($data['src']) {
                $base64Image = $data['src'];
                $imageName = Str::random(40); // Nama acak untuk file
                $imagePath = 'barang/' . $imageName . '.png'; // Path lengkap file
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

                Storage::disk('public')->put($imagePath, $imageData);

                $data['src'] = $imagePath;
            }

            $data['nama'] = strtoupper($data['nama']);

            // CREATE BARANG
            $newBarang = Barang::create([
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'satuan_id' => $data['satuanid'],
                'manual' =>  $data['manual'],
                'gambar' => $data['src'],
            ]);
            foreach ($data['minhar'] as $minhar) {
                $newHarga = Harga::create([
                    'barang_id' => $newBarang->id,
                    'minimal' => $minhar['minimal'],
                    'harga' => intval(str_replace(".", "", $minhar['harga'],)),
                ]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
    }

    public function createHp()
    {
        $satuans = Satuan::get();
        return view('barang.create-hp', ['satuans' => $satuans]);
    }

    public function storeHp(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'satuanid' => 'required',
            'minhar.*.minimal' => 'required',
            'minhar.*.harga' => 'required',
            'src' => 'nullable|string',
        ];

        $messages = [
            'nama.required' => 'Nama harus diisi.',
            'satuanid.required' => 'Satuan barang harus diisi.',
            'minhar.*.minimal.required' => 'Minimal harus diisi.',
            'minhar.*.harga.required' => 'Harga harus diisi.',
            'src.string' => 'Sumber harus berupa string.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // CEK KODE BARANG
        $barang = Barang::where('kode', $request->kode)->first();
        if ($barang) {
            $errors = [
                'barang_sudah_ada' => ['Barang sudah ada']
            ];
            return response()->json(['error' => $errors], 400);
        }

        // JIKA KODE NYA KOSONG
        $request->manual = 0;
        if ($request->kode == null) {
            $request->kode = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $request->manual = 1;
        }

        // UNTUK GAMBAR
        if ($request->src) {
            $base64Image = $request->src;
            $imageName = Str::random(40); // Nama acak untuk file
            $imagePath = 'barang/' . $imageName . '.png'; // Path lengkap file
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

            Storage::disk('public')->put($imagePath, $imageData);

            $request->src = $imagePath;
        }

        $request->nama = strtoupper($request->nama);

        // CREATE BARANG
        $newBarang = Barang::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'satuan_id' => $request->satuanid,
            'manual' => $request->manual,
            'gambar' => $request->src,
        ]);

        foreach ($request->minhar as $minhar) {
            $newHarga = Harga::create([
                'barang_id' => $newBarang->id,
                'minimal' => $minhar['minimal'],
                'harga' => intval(str_replace(".", "", $minhar['harga'],)),
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $barang = Barang::with(['satuans', 'hargas'])->findOrFail($id);
        $satuans = Satuan::get();
        return view('barang.edit', ['barang' => $barang, 'satuans' => $satuans]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([$request->all(), $id]);

        // VALIDASI START
        // $rules = [
        //     'nama' => 'required',
        //     'satuanid' => 'required',
        //     'minhar.*.minimal' => 'required',
        //     'minhar.*.harga' => 'required',
        //     'src' => 'nullable|string',
        // ];

        // $messages = [
        //     'nama.required' => 'Nama harus diisi.',
        //     'satuanid.required' => 'Satuan barang harus diisi.',
        //     'minhar.*.minimal.required' => 'Minimal harus diisi.',
        //     'minhar.*.harga.required' => 'Harga harus diisi.',
        //     'src.string' => 'Sumber harus berupa string.',
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages);

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 400);
        // }
        // VALIDASI END

        // VALIDASI KODE START
        // $barangkode = Barang::where('kode', $request->kode)->first();
        // if ($barangkode) {
        //     $errors = [
        //         'barang_sudah_ada' => ['Barang sudah ada']
        //     ];
        //     return response()->json(['error' => $errors], 400);
        // }
        // VALIDASI KODE END

        // $request->manual = 0;
        // if ($request->kode == null) {
        //     $request->kode = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        //     $request->manual = 1;
        // }

        // CARI BARANG START
        // $barang = Barang::findOrFail($id);
        // $barang->update([
        //     'nama' => $request->nama,
        //     'satuan_id' => $request->satuanid,
        //     'gambar' => $request->src,
        //     'manua;' => $request->manual,
        // ]);

    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        if (Storage::exists($barang->gambar)) {
            Storage::delete($barang->gambar);
        }
        $barang->hargas()->delete();
        $barang->delete();
        return redirect(route('barang-index'));
    }
}
