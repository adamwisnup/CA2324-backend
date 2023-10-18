<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AcademyResource;
use App\Http\Requests\AcademyUpdateRequest;
use App\Http\Requests\AcademyRegisterRequest;

class AcademyController extends Controller
{
    public function create(AcademyRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $errorMessages = [];
        foreach (['name', 'nim', 'email', 'phone_number', 'document'] as $field) {
            $existingAcademy = Academy::where($field, $data[$field])->first();
            if ($existingAcademy) {
                $errorMessages[] = ucfirst($field) . ' sudah terdaftar';
            }
        }

        $totalRegistrations = Academy::count();

        if ($totalRegistrations >= 80) {
            return response()->json([
                'status' => 422,
                'message' => 'Kuota sudah penuh',
            ], 422);
        }

        if ($errorMessages) {
            return response()->json([
                'status' => 422,
                'error' => 'Unprocessable Entity',
                'message' => implode(', ', $errorMessages),
            ], 422);
        }

        $academy = new Academy([
            'name' => $data['name'],
            'nim' => $data['nim'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'document' => $data['document'],
            'gender' => $data['gender'],
            'year_of_enrollment' => $data['year_of_enrollment'],
            'faculty' => $data['faculty'],
            'major' => $data['major'],
            'class' => $data['class'],
        ]);
        $academy->save();

        return (new AcademyResource($academy))->response()->setStatusCode(201);
    }

    public function getdata()
    {
        $academies = Academy::all();

        return response()->json([
            'status_code' => 200,
            'data' => $academies
        ]);
    }

    public function getDataById($id)
    {
        $academies = Academy::find($id);
        if (!$academies) {
            return response()->json([
                'status' => 404,
                'message' => 'Peserta tidak terdaftar',
                'error' => 'Not Found'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $academies
        ], 200);
    }


    public function update(AcademyUpdateRequest $request, $id): JsonResponse
    {
        $data = $request->validated();

        $academy = Academy::find($id);

        if ($academy->update($data)) {
            return (new AcademyResource($academy))->response()->setStatusCode(200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Gagal mengupdate data peserta'
            ], 400);
        }
    }

    public function delete($id)
    {
        $academy = Academy::find($id);

        if (!$academy) {
            return response()->json([
                'status' => 404,
                'message' => 'Data peserta tidak ditemukan'
            ], 404);
        }

        if ($academy->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Data peserta berhasil dihapus'
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Gagal menghapus data peserta'
            ], 400);
        }
    }

    public function countdownQuota()
    {
        $totalRegistrations = Academy::count();
        $remainingQuota = 80 - $totalRegistrations;
        if ($remainingQuota == 0) {
            $remainingQuota = "Kuota sudah penuh";
        }

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'remaining_quota' => $remainingQuota,
        ], 200);
    }
}
