<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

//import Model materi
use App\Models\Materi;
use App\Models\Topik;

// import materi resource
use App\Http\Resources\MateriResource;

use Illuminate\Http\Request;


class MateriController extends Controller
{
    public function index()
    {
        //get materi
        $materis = Materi::latest()->paginate(5);
        $topik = Topik::all();
        
        return new MateriResource(true,"List Data Materi!", compact('materis', 'topik'));
    }

    public function store(Request $request)
    {
        //validate form
        $this->validate($request, [
            'judul_materi'  => 'required',
            'url_materi'     => 'required',
            'deskripsi_materi'   => 'required',
            'id_topik' => 'required',
            'id_kategori' => 'required',
        ]);

        //create post
        $materi = Materi::create([
            'judul_materi'  => $request->judul_materi,
            'url_materi'    => $request->url_materi,
            'deskripsi_materi'  => $request->deskripsi_materi,
            'id_topik' => $request->id_topik,
            'id_kategori' => $request->id_kategori,
        ]);

        //return collection of Materi as a resource
        return new MateriResource(true,"Data Materi Berhasil Ditambah!", $materi);
    }

    public function show(string $id_materi)
    {
        //get materi by ID
        $materi = Materi::findOrFail($id_materi);
        $topik = Topik::all();

        //mengembalikan detail materi
        return new MateriResource(true, "Data Materi Ditemukan!", $materi);
    }

    public function update(Request $request, $id_materi)
    {
        //get materi by ID
        $materi = Materi::findOrFail($id_materi);

        //update materi
        $materi->update([
            'judul_materi'  => $request->judul_materi,
            'url_materi'    => $request->url_materi,
            'deskripsi_materi'  => $request->deskripsi_materi,
            'id_topik' => $request->id_topik,
            'id_kategori' => $request->id_kategori,
        ]);

       return new MateriResource(true, "Data Materi Berhasil Diubah!", $materi);
    }

    public function destroy($id_materi)
    {
        //get materi by ID
        $materi = Materi::findOrFail($id_materi);

        //delete materi
        $materi->delete();

        return new MateriResource(true, "Data Materi Berhasil Dihapus!", $materi);
    }
}
