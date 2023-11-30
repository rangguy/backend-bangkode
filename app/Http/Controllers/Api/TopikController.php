<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use File;
use App\Http\Resources\TopikResource;
use App\Models\Kategori;
use App\Models\Topik;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TopikController extends Controller
{
    public function index()
    {
        // get data topik
        $topik = Topik::first()->paginate(5);
        $kategori = Kategori::all();

        return new TopikResource(true, "List Data Topik", $topik);
    }


    public function store(Request $request)
    {
        //Validasi data
        $validator = Validator::make($request->all(), [
            'nama_topik' => 'required',
            'logo_topik' => 'required|mimes:jpeg,jpg,png',
            'id_kategori' => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('logo_topik');
        $newNameImage = time() . '.' . $request->logo_topik->extension();
        $image->storeAs('public/topik', $newNameImage);

        //Memasukan data
        $topik = new Topik;

        $topik->nama_topik = $request['nama_topik'];
        $topik->logo_topik = $newNameImage;
        $topik->id_kategori = $request['id_kategori'];

        $topik->save();

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
    public function update(Request $request, Topik $topik)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'nama_topik' => 'required',
            'logo_topik' => 'mimes:jpg,jpeg,png',
            'id_kategori' => 'required'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('logo_topik')) {

            //upload image
            $logo = $request->file('logo_topik');

            // mengubah nama logo
            $newNameLogo = time() . '.' . $request->logo_topik->extension();
            $logo->storeAs('public/topik', $newNameLogo);

            //delete old logo_topik
            Storage::delete('public/topik/' . $topik->logo_topik);

            //update kategori with new logo
            $topik->update([
                'nama_topik'    => $request->nama_topik,
                'logo_topik'    => $newNameLogo,
                'id_kategori'   => $request->id_kategori
            ]);
        } else {
            $topik->update([
                'nama_topik'    => $request->nama_topik,
                'id_kategori'   => $request->id_kategori
            ]);
        }

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
        $path = 'public/topik';
        Storage::delete($path . $topik->logo_topik);

        // delete kategori
        $topik->delete();

        // delete data topik
        return new TopikResource(true, "Data Topik Berhasil Dihapus!", $topik);
    }

    public function showMateri($id_kategori, $id_topik)
    {
        $topik = Topik::find($id_topik); // Menggunakan $id_topik sebagai parameter untuk mencari Topik berdasarkan ID

        if ($topik && $topik->id_kategori == $id_kategori) {
            $materi = Materi::where('id_topik', $id_topik)->get(); // Mengambil data Materi berdasarkan $id_topik
            if ($materi->isNotEmpty()) {
                return new TopikResource(true, "Data Materi Berhasil Ditemukan", $materi);
            } else {
                return new TopikResource(false, "Data Materi Tidak Ditemukan", "data kosong!");
            }
        } else {
            return new TopikResource(false, "Materi tidak ditemukan untuk id topik atau id Kategori yang diberikan", null);
        }
    }
}
