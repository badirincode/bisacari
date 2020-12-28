<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product = Product::paginate(5);
        return view('product.manage',compact('product'));
    }
    public function saldo(){
        $username   = "083107707213";
$apiKey   = "6435ebd6b02d7659";
$signature  = md5($username.$apiKey.'pl');

$json = '{
          "commands" : "pricelist",
          "username" : "083107707213",
          "sign"     : "933850f1a0d05522b2c27dfbebe7b85a"
        }';

$url = "https://testprepaid.mobilepulsa.net/v1/legacy/index";

$ch  = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
curl_close($ch);

            return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('product.create',compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
        'name'=>'required',
        'product_code'=>'required',
        'price'=>'required',
        'kategori'=>'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         
        ]);
        $imageName = time().'.'.$request->image->extension();  
     
        $request->image->move(public_path('images'), $imageName);
        Product::create([
            'name'=>$request->name,
            'product_code'=>$request->product_code,
            'price'=>$request->price,
            'kategori_id'=>$request->kategori,
            'desc'=>$request->desc,
            'image'=>$imageName
        ]);
        return redirect('product')->with('success','Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        $kategori = Kategori::all();
        return view('product.edit',compact('product','kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $this->validate($request,[
            'name'=>'required',
            'product_code'=>'required',
            'price'=>'required',
            'kategori'=>'required',
            
             
            ]);
        $product->name = $request->name;
        $product->product_code = $request->product_code;
        $product->price = $request->price;
        $product->kategori_id = $request->kategori;
        $product->desc = $request->desc;
        $product->save();
        return redirect('product')->with('success','Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $product->delete();
        return redirect('product')->with('success','Delete Successfully !');
    }
}
