<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * 1. Menampilkan daftar Partner (Dilengkapi fitur pencarian LIKE sesuai Soal 3)
     */
    public function index(Request $request)
    {
        // Mulai query untuk mengambil data partner beserta kategorinya
        $query = Partner::with('category')->latest();

        // JAWABAN SOAL 3: Modifikasi pencarian menggunakan Eloquent LIKE
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Eksekusi query untuk mendapatkan data
        $partners = $query->get();
        
        return view('admin.partners.index', compact('partners'));
    }

    /**
     * 2. Menampilkan form tambah Partner baru
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.partners.create', compact('categories'));
    }

    /**
     * 3. Menyimpan data Partner baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id', // Kategori boleh kosong
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = $request->file('logo')->store('partners', 'public');
        }

        // DISESUAIKAN: Menggunakan nama kolom logo_url sesuai aturan UTS
        Partner::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'logo_url' => $logoUrl,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan!');
    }

    /**
     * 4. Menampilkan form edit Partner
     */
    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        $categories = Category::all();
        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    /**
     * 5. Memperbarui data Partner di database
     */
    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // DISESUAIKAN: Menggunakan properti database logo_url
        $logoUrl = $partner->logo_url;
        
        // Jika ada upload logo baru, hapus logo lama
        if ($request->hasFile('logo')) {
            if ($partner->logo_url) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            $logoUrl = $request->file('logo')->store('partners', 'public');
        }

        // DISESUAIKAN: Simpan pembaruan ke kolom logo_url
        $partner->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'logo_url' => $logoUrl,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diperbarui!');
    }

    /**
     * 6. Menghapus Partner dari database beserta berkas logonya
     */
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        // DISESUAIKAN: Hapus file berdasarkan properti database logo_url
        if ($partner->logo_url) {
            Storage::disk('public')->delete($partner->logo_url);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}