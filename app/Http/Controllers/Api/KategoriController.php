<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Topik;
use App\Http\Resources\KategoriResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::get();

        //return collection of kategori as a resource
        return new KategoriResource(true, 'List Data Kategori', $kategori);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validasi data
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'foto' => 'required|mimes:jpeg,png,jpg'
        ]);
         //check if validation fails
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('foto');
        $newNameImage = time() . '.' . $request->foto->extension();
        $image->storeAs('public/kategori', $newNameImage);

        //Memasukan data
        $kategori = new Kategori;

        $kategori->nama_kategori = $request['nama_kategori'];
        $kategori->foto = $newNameImage;

        $kategori->save();

        //return collection of kategori as a resource
        return new KategoriResource(true, 'Data Kategori Berhasil Ditambahkan!', $kategori);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTopik($id_kategori)
    {
        $kategori = Kategori::find($id_kategori);
        $topik = Topik::all();

        //return single kategori as a resource
        return new KategoriResource(true, 'Data Kategori Ditemukan!',compact('kategori', 'topik'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('foto')) {
            //upload image
            $foto = $request->file('foto');
            
            // mengubah nama foto
            $newNameFoto = time() . '.' . $request->foto->extension();
            $foto->storeAs('public/kategori', $newNameFoto);
            
            //delete old foto
            Storage::delete('public/kategori/'.$kategori->foto);

            //update kategori with new foto
            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
                'foto'          => $newNameFoto,
            ]);
        } else {
            $kategori->update([
                'nama_kategori' => $request->nama_kategori
            ]);
        }

        //return response
        return new KategoriResource(true, 'Data Kategori Berhasil Diubah!', $kategori);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_kategori)
    {
        $kategori = Kategori::find($id_kategori);
        $path = 'public/kategori/';
        Storage::delete($path . $kategori->foto);

        // delete kategori
        $kategori->delete();
        
        // return response
        return new KategoriResource(true, 'Data Kategori Berhasil Dihapus!', $kategori);
    }

    public function read()
    {
        $kategori = Kategori::get();

        //return collection of kategori as a resource
        return new KategoriResource(true, 'List Data Kategori', $kategori);
    }
}
