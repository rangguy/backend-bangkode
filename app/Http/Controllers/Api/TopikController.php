<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use File;
use Alert;
use App\Http\Resources\TopikResource;
use App\Models\Kategori;
use App\Models\Topik;
use App\Models\Materi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TopikController extends Controller
{
    public function index()
    {
        // get data topik
        $topik = Topik::first()->paginate(5);
        $kategori = Kategori::all();

        return new TopikResource(true, "List Data Topik", compact('topik', 'kategori'));
    }


    public function store(Request $request)
    {
        //Validasi data
        $request->validate([
            'nama_topik' => 'required',
            'logo_topik' => 'required|mimes:jpeg,jpg,png',
            'id_kategori' => 'required'
        ]);
        //upload image
        $newNameImage = time() . '.' . $request->logo_topik->extension();

        $request->logo_topik->move(public_path('image'), $newNameImage);
        //Memasukan data
        $topik = new Topik;

        $topik->nama_topik = $request['nama_topik'];
        $topik->logo_topik = $newNameImage;
        $topik->id_kategori = $request['id_kategori'];

        $topik->save();
        // Pesan berhasil
        Alert::success(' BERHASIL ', ' Berhasil Menambah Topik! ');

        //return collection of Topik as a resource
        return new TopikResource(true, "List Data Topik", $topik);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_topik)
    {
        $validated = $request->validate([
            'nama_topik' => 'required',
            'logo_topik' => 'mimes:jpg,jpeg,png',
            'id_kategori' => 'required'
        ]);

        $topik = Topik::find($id_topik);

        $topik->nama_topik = $request['nama_topik'];
        $topik->id_kategori = $request['id_kategori'];

        if ($request->has('logo_topik')) {
            $path = 'image/';
            File::delete($path . $topik->logo_topik);

            // ubah nama file menjadi unique
            $newNameLogo = time() . '.' . $request->logo_topik->extension();

            // pindahkan file ke folder public di dalam folder image
            $request->logo_topik->move(public_path('image'), $newNameLogo);

            $topik->logo_topik = $newNameLogo;
        }
        // save ke database
        $topik->save();

        // Update data topik
        return new TopikResource(true, "Data Topik Berhasil Diubah!", $topik);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_topik)
    {
        $topik = Topik::find($id_topik);
        $path = 'image/';
        File::delete($path . $topik->logo_topik);
        $topik->delete();

        // delete data topik
        return new TopikResource(true, "Data Topik Berhasil Dihapus!", $topik);
    }

    public function showMateri($id_kategori, $id_topik)
    {
        $kategori = Kategori::find($id_kategori);
        $topik = Topik::find($id_topik);
        $materi = Materi::all();

        // mengembalikan list Materi berdasarkan topiknya
        return new TopikResource(true, "Data Materi Berhasil Ditemukan", compact('kategori', 'topik', 'materi'));
    }
}
