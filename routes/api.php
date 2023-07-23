<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ExampleController;
use App\Models\Product;
use App\Models\Shop;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ----------------------------------------------------------------- ส่วนที่ 1 ---------------------------------------------------------------------------------

// สร้าง Project ใช้ Base Frameworks .js หรือ php หรือภาษาที่ถนัดพร้อมระบุ
//  - api สามารถ Login ได้
//  - ใช้ laravel access token api

Route::post('/login', [AuthController::class, 'login']);
//  - api สามารถ สมัครสมาชิกได้
Route::post('/register', [AuthController::class, 'register']);

Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::get('/me', [AuthController::class, 'me']);
        //  - api สามารถ อับรูปภาพได้
        Route::post('/upload/image', [AuthController::class, 'uploadImage']);

        // ----------------------------------------------------------------- ส่วนที่ 2 ---------------------------------------------------------------------------------

        // 1. ให้อธิบายว่าจะใช้วิธีการอะไรได้บ้างในการป้องกัน brute force attack หรือเดารหัสผ่านใน login form
        //    = rate limiter ในการช่วยเช็คหรือกำหนดการเข้าถึง เส้น api ที่เราต้องการได้ ทำการ กำหนด limit ในการกด ถ้าเกินเท่าไหร่จะให้ทำอะไร เช่น ให้รอ 3 นาที หรือไม่ให้ ip นั้นเข้าใช้งาน

        // 2. จงเขียนตัวอย่าง sql query ในโค้ด โดยให้มีชุดคำสั่ง ที่ช่วยป้องกัน sql injection (ตั้งชื่อตารางชื่อฟิลด์ด้วยตัวเองได้เลย)
        //    = เลี่ยง การเขียนในรูปแบบ query builder หรือ การเขียน โดยแทรก sql ดิบๆ เข้าไป, ให้ validate กำหนด id ตัวแปรที่รับเข้ามาให้ เป็น integer เท่านั้น,
        Route::get('/user/{user_id}', function ($user_id = 1) {
            $response = DB::table('users')->select('*')->whereRaw('id = ?', $user_id)->get();
            return response()->json(['data' => $response], 200);
        });

        // 3. จงเขียน saI query ที่มี sub query ในตำแหน่งต่างๆ อย่าง น้อยสองแบบ (ตั้งชื่อตารางชื่อฟิลด์ด้วยตัวเองได้เลย)
        Route::get('/user/sub/query', function () {
            $response = DB::table('tb_product')->select('tb_product.*', DB::raw("(SELECT name FROM tb_shop
                                WHERE tb_product.shop_id = tb_shop.id) as shop_name"))->get();


            $response = Shop::where('id', '<', function ($query) {
                $query->selectRaw('sum(tb_product.id)')->from('tb_product');
            })->get();

            return response()->json(['data' => $response], 200);
        });

        // 4. จากตาราง tb_product(id,name,status,shop_id) และ tb_shop(id,name) จงเขียน โค้ด select เพื่อแสดงสินค้าของร้าน ที่มีชื่อร้าน "rudy shop"
        Route::get('/product/get_by_shop_name', [ExampleController::class, 'getProductByShopName']);

        // 5. เขียนคำสั่ง update สินค้าทุกตัวของร้าน "rudy shop" ให้ มี status='0'
        Route::get('/product/update_status_all', [ExampleController::class, 'updateProductStatusAll']);

        // 6. จงเขียน function ของ code sql เพื่อปรับรูปแบบการ select ข้อมูล ตามประเภทข้อมูลดังนี้เพื่อให้ได้ผลลัพธืดังตัวอย่าง type date ให้แสดงผลเป็น dd/mm/YYYY type float,double ให้แสดงผลเป็น x,xxx,xxx.xx (สามารถเขียนได้มากกว่า 2 ข้อที่ยกตัวอย่าง)
        Route::get('/product/format', [ExampleController::class, 'format']);

        // 7. จงเขียน code function ในการคำนวณผลลัพธ์ใบเสนอราคาโดยหัวข้อมีดังนี้ ราคาสินค้ารวม = สามารถตั้งเองได้ ส่วนลดรวม = สามารถตั้งเองได้ ราคาสินค้าหลังส่วนลด ภาษีมูลค่าเพิ่ม 7 % ราคารวมสุทธิ
        Route::get('/product/quotation', [ExampleController::class, 'quotation']);
    }
);
