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
                'error' => [
                    'message' => 'Kuota sudah penuh'
                ]
            ])->setStatusCode(422);
        }

        if ($errorMessages) {
            return response()->json([
                'status' => 422,
                'error' => [
                    'message' => [
                        implode(', ', $errorMessages)
                    ]
                ]
            ])->setStatusCode(422);
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

    public function getdata(): JsonResponse
    {
        $academies = Academy::all();

        return response()->json([
            'status' => 200,
            'data' => $academies
        ])->setStatusCode(200);
    }

    public function getDataById($id): JsonResponse
    {
        $academies = Academy::find($id);
        if (!$academies) {
            return response()->json([
                'status' => 404,
                'message' => 'Peserta tidak terdaftar',
                'error' => 'Not Found'
            ])->setStatusCode(404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'data' => $academies
        ])->setStatusCode(200);
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
                'error' => [
                    'message' => 'Gagal mengupdate data peserta'
                ]
            ])->setStatusCode(400);
        }
    }

    public function delete($id): JsonResponse
    {
        $academy = Academy::find($id);

        if (!$academy) {
            return response()->json([
                'status' => 404,
                'error' => [
                    'message' => 'Data peserta tidak ditemukan'
                ]
            ])->setStatusCode(404);
        }

        if ($academy->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Data peserta berhasil dihapus'
            ])->setStatusCode(200);
        } else {
            return response()->json([
                'status' => 400,
                'error' => [
                    'message' => 'Gagal menghapus data peserta'
                ]
            ])->setStatusCode(400);
        }
    }

    public function countdownQuota(): JsonResponse
    {
        $totalRegistrations = Academy::count();

        $remainingQuota = 80 - $totalRegistrations;
        if ($remainingQuota == 0) {
            $remainingQuota = "Kuota sudah penuh";
        }
        // dd($totalRegistrations);
        return response()->json([
            'status' => 200,
            'message' => 'OK',
            'remaining_quota' => $remainingQuota,
        ])->setStatusCode(200);
    }
}
