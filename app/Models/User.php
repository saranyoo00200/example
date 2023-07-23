<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // -------------------------------------------- method ---------------------------------------------------------

    public function getImageAttribute($value)
    {
        if ($value) {
            return Storage::disk(env('FILESYSTEM_DISK'))->url($value);
        } else {
            return $value;
        }
    }

    public function addFile($request)
    {
        // -------- ให้ส่งไฟล์ที่มีชื่อตามนี้เข้ามา -----------------
        // ---------- image ---------

        $control = new Controller();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $prefix = 'uploads/user/' . $this->id . '/image';

            $path = $control->uploadFile($prefix, $file, $this->id);
            if ($path) {
                $this->image = $path;
            }
        }
    }
}
