<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PartController extends Controller
{
    public function index(Request $request): View
    {
        $query = Part::with(['category', 'stockMovements']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->boolean('low_stock')) {
            $query->whereRaw('stock_qty <= min_stock_qty');
        }

        $parts = $query->latest()->paginate(15)->withQueryString();
        $categories = PartCategory::where('is_active', true)->orderBy('name')->get();

        return view('parts.index', compact('parts', 'categories'));
    }

    public function create(): View
    {
        $categories = PartCategory::where('is_active', true)->orderBy('name')->get();

        return view('parts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:parts,sku'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:part_categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:20'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'min_stock_qty' => ['required', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Part::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter++;
        }

        Part::create($data);

        return redirect()->route('parts.index')
            ->with('success', 'Linh kiện đã được tạo thành công.');
    }

    public function show(Part $part): View
    {
        $part->load(['category', 'stockMovements.user']);

        return view('parts.show', compact('part'));
    }

    public function edit(Part $part): View
    {
        $categories = PartCategory::where('is_active', true)->orderBy('name')->get();

        return view('parts.edit', compact('part', 'categories'));
    }

    public function update(Request $request, Part $part): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:parts,sku,' . $part->id],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:part_categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:20'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'min_stock_qty' => ['required', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name']);
        if ($data['slug'] !== $part->slug) {
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Part::where('slug', $data['slug'])->where('id', '!=', $part->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter++;
            }
        }

        $part->update($data);

        return redirect()->route('parts.show', $part)
            ->with('success', 'Linh kiện đã được cập nhật.');
    }

    public function destroy(Part $part): RedirectResponse
    {
        $part->delete();

        return redirect()->route('parts.index')
            ->with('success', 'Linh kiện đã được xóa.');
    }

    public function addStock(Request $request, Part $part): RedirectResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'reference_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $part->recordMovement(
            'in',
            $data['qty'],
            $data['unit_cost'] ?? null,
            'manual',
            null,
            $data['reference_number'] ?? null,
            $data['notes'] ?? null
        );

        return back()->with('success', 'Đã nhập kho thành công.');
    }

    public function removeStock(Request $request, Part $part): RedirectResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:' . $part->stock_qty],
            'reference_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $part->recordMovement(
            'out',
            $data['qty'],
            null,
            'manual',
            null,
            $data['reference_number'] ?? null,
            $data['notes'] ?? null
        );

        return back()->with('success', 'Đã xuất kho thành công.');
    }

    public function adjustStock(Request $request, Part $part): RedirectResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $part->recordMovement(
            'adjustment',
            $data['qty'],
            null,
            'adjustment',
            null,
            null,
            $data['notes'] ?? null
        );

        return back()->with('success', 'Đã điều chỉnh tồn kho.');
    }
}
