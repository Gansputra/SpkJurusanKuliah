<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCriteriaRequest;
use App\Http\Requests\UpdateCriteriaRequest;
use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Criteria::query();

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        $criteria = $query->orderBy('urutan')->paginate(10)->withQueryString();

        return view('admin.criteria.index', compact('criteria'));
    }

    public function create()
    {
        $nextUrutan = Criteria::max('urutan') + 1;
        return view('admin.criteria.create', compact('nextUrutan'));
    }

    public function store(StoreCriteriaRequest $request)
    {
        Criteria::create($request->validated());

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Criteria $criteria)
    {
        return view('admin.criteria.edit', compact('criteria'));
    }

    public function update(UpdateCriteriaRequest $request, Criteria $criteria)
    {
        $criteria->update($request->validated());

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $criteria)
    {
        $criteria->delete();

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}
