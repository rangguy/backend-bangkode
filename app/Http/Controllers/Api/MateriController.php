<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

//import Model materi
use App\Models\Materi;
use App\Models\Topik;

//return type View
use Illuminate\View\View;

//return type redirectResponse
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

//import Facade "Storage"
use Illuminate\Support\Facades\Storage;

// sweetalert
use Alert;

class MateriController extends Controller
{
    public function index(): View
    {
        //get materi
        $materis = Materi::latest()->paginate(5);
        $topik = Topik::all();
        //render view with posts
        return view('materi.indexAdmin', compact('materis', 'topik'));
    }

    public function create(): View
    {
        $topik = Topik::all();
        return view('materi.create', compact('topik'));
    }

    public function store(Request $request): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'judul_materi'  => 'required',
            'url_materi'     => 'required',
            'deskripsi_materi'   => 'required',
            'id_topik' => 'required',
        ]);

        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/posts', $image->hashName());

        //create post
        Materi::create([
            'judul_materi'  => $request->judul_materi,
            'url_materi'    => $request->url_materi,
            'deskripsi_materi'  => $request->deskripsi_materi,
            'id_topik' => $request->id_topik,
        ]);

        // Pesan berhasil
        Alert::success(' BERHASIL ', ' Berhasil Menambah Materi! ');

        //kembali ke halaman index
        return redirect('/materi');
    }

    public function show(string $id_materi): View
    {
        //get post by ID
        $materi = Materi::findOrFail($id_materi);
        $topik = Topik::all();
        //render view with post
        return view('materi.show', compact('materi'));
    }

    public function edit(string $id_materi): View
    {
        //get post by ID
        $materi = Materi::findOrFail($id_materi);
        $topik = Topik::all();
        //render view with post
        return view('materi.edit', compact('materi', 'topik'));
    }

    public function update(Request $request, $id_materi): RedirectResponse
    {
        //get post by ID
        $materi = Materi::findOrFail($id_materi);

        //update post without image
        $materi->update([
            'judul_materi'  => $request->judul_materi,
            'url_materi'    => $request->url_materi,
            'deskripsi_materi'  => $request->deskripsi_materi,
            'id_topik' => $request->id_topik,
        ]);

       // Pesan berhasil
       Alert::success(' BERHASIL ', ' Berhasil Mengubah Materi! ');

       //kembali ke halaman index
       return redirect('/materi');
    }

    public function destroy($id_materi): RedirectResponse
    {
        //get post by ID
        $materi = Materi::findOrFail($id_materi);

        //delete post
        $materi->delete();

        // Pesan berhasil
        Alert::success(' BERHASIL ', ' Berhasil Menghapus Materi! ');

        //kembali ke halaman index
        return redirect('/materi');
    }
}
