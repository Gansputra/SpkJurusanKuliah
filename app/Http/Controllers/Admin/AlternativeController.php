<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlternativeRequest;
use App\Http\Requests\UpdateAlternativeRequest;
use App\Models\Alternative;
use Illuminate\Http\Request;

class AlternativeController extends Controller
{
    public function index(Request $request)
    {
        $query = Alternative::withCount('scores');

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        if ($request->has('active')) {
            $query->where('active', $request->active);
        }

        $alternatives = $query->orderBy('kode')->paginate(10)->withQueryString();

        return view('admin.alternatives.index', compact('alternatives'));
    }

    public function create()
    {
        $nextKode = 'A' . (Alternative::count() + 1);
        return view('admin.alternatives.create', compact('nextKode'));
    }

    public function store(StoreAlternativeRequest $request)
    {
        $data = $request->validated();
        $data['active'] = $request->has('active');
        Alternative::create($data);

        return redirect()->route('admin.alternatives.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(Alternative $alternative)
    {
        return view('admin.alternatives.edit', compact('alternative'));
    }

    public function update(UpdateAlternativeRequest $request, Alternative $alternative)
    {
        $data = $request->validated();
        $data['active'] = $request->has('active');
        $alternative->update($data);

        return redirect()->route('admin.alternatives.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Alternative $alternative)
    {
        $alternative->delete();

        return redirect()->route('admin.alternatives.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
