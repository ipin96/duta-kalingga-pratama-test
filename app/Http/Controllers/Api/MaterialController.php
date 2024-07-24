<?php

namespace App\Http\Controllers\Api;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function index(Request $request) {
        $check = Material::count();
        if ($check == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Material tidak ditemukan',
            ], 404);
        }
        $limit = $request->get('limit') ?? 10;
        $material = Material::paginate($limit);
        return response()->json([
            'success' => true,
            'message' => 'Material berhasil diambil',
            'data' => $material
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'StorageBin' => 'required',
                'type' => 'required',
                'Verification_ID' => 'required',
                'material' => 'required',
                'unit' => 'required',
                'Description' => 'required',
                'stock' => 'required|numeric|min:1',
            ],
            [
                'StorageBin.required' => 'Kolom StorageBin wajib diisi.',
                'type.required' => 'Kolom type wajib diisi.',
                'Verification_ID.required' => 'Kolom Verification_ID wajib diisi.',
                'material.required' => 'Kolom material wajib diisi.',
                'unit.required' => 'Kolom unit wajib diisi.',
                'Description.required' => 'Kolom Description wajib diisi.',
                'stock.required' => 'Kolom stock wajib diisi.',
                'stock.numeric' => 'Kolom stock harus berupa angka.',
                'stock.min' => 'Kolom stock minimal 1.',
            ]

        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $material = Material::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Material berhasil diupload',
            'data' => $material
        ], 201);
    }

    public function verify(Request $request) {
        $material = Material::find($request->id);
        if (empty($material)) {
            return response()->json([
                'success' => false,
                'message' => 'Material tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'qty' => 'required|numeric|min:1',
        ], [
            'qty.required' => 'Kolom qty wajib diisi.',
            'qty.numeric' => 'Kolom qty harus berupa angka.',
            'qty.min' => 'Kolom qty minimal 1.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $material->stock = $material->stock + $request->qty;
        $material->save();

        return response()->json([
            'success' => true,
            'message' => 'Material berhasil diverifikasi dan stok material berhasil ditambahkan',
            'data' => $material
        ], 200);
    }
}
