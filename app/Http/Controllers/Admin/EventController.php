<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // <-- WAJIB DITAMBAHKAN UNTUK MEMBUAT SLUG

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $events = Event::with(['category', 'organizer'])->latest()->get();
        } else {
            $events = Event::with('category')->where('user_id', $user->id)->latest()->get();
        }

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'date'        => 'required',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
            'poster'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        // Membuat slug otomatis dari title (contoh: "Event Amikom" menjadi "event-amikom")
        // Dan memastikan slug unik jika ada judul yang sama
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Event::create([
            'user_id'     => Auth::id(), 
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => $slug, // <-- SLUG DIMASUKKAN DI SINI
            'description' => $request->description,
            'date'        => $request->date,
            'location'    => $request->location,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'quota'       => $request->stock, // Samakan quota dengan stock form input
            'poster_path' => $posterPath,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $categories = Category::all();
        
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'date'        => 'required',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $posterPath = $event->poster_path;
        
        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $event->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            // Slug opsional tidak diubah saat update agar URL tidak rusak, 
            // tapi jika ingin diubah bisa tambahkan logika slug di sini.
            'description' => $request->description,
            'date'        => $request->date,
            'location'    => $request->location,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'poster_path' => $posterPath,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus secara permanen!');
    }
}