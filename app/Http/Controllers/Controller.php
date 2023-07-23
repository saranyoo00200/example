<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ok($data = [], $message = "")
    {
        return response()->json([
            "status" => true,
            "message" => $message,
            "data" => $data,
        ], 200);
    }

    public function error($message, $data = [], $status = 500)
    {
        return response()->json([
            "status" => false,
            "message" => $message,
            "data" => $data,
        ], $status);
    }

    public function uploadFile($prefix, $file, $id = 0)
    {
        $file_name = $file->getClientOriginalName();
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = uniqid($id . '@') . '.' . $ext;
        return Storage::putFileAs($prefix, $file, $file_name, 'public');
    }
}
