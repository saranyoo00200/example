<?php

namespace App\Http\Controllers\api;

use App\Models\Product;
use App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    public function getProductByShopName()
    {
        $products = Product::select('tb_product.*')
            ->join('tb_shop', 'tb_shop.id', 'tb_product.shop_id')
            ->where('tb_shop.name', 'rudy shop')
            ->get();

        return $this->ok($products);
    }

    public function updateProductStatusAll()
    {
        $products = Product::select('tb_product.*')
            ->join('tb_shop', 'tb_shop.id', 'tb_product.shop_id')
            ->where('tb_shop.name', 'rudy shop')
            ->update(['tb_product.status' => 0]);

        if (!$products) return $this->error('update.error');

        return $this->ok('update.success');
    }

    public function format()
    {
        // get product
        $products = Product::all();
        // format date
        $data = [];
        foreach ($products as $key => $product) {
            $data[$key] = [
                'name' => $product['name'],
                'create_date' => date('d-m-Y', strtotime($product['created_at'])),
                'update_date' => date('d-m-Y', strtotime($product['update_date']))
            ];
        }

        // format number
        $number_float = floatval(100300001000090);
        $number_double = doubleval(1045000010000000);
        $format_float = number_format($number_float, 2);
        $format_double = number_format($number_double, 2);

        // send response
        $response = [
            'products' => $data,
            'format_float' => $format_float,
            'format_double' => $format_double,
        ];

        return $this->ok($response);
    }

    public function quotation()
    {
        $product_price = 1000; //ราคาสินค้า
        $discount = 50; //50 บาท ไม่มีขั้นต่ำ
        $vat = 7; // ภาษีมูลค่าเพิ่ม 7 %

        $sub_total = ($product_price - $discount);
        $result_vat = ($sub_total * $vat) / 100;

        $total = $sub_total + $result_vat;

        return $this->ok($total);
    }
}
