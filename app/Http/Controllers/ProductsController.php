<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $companyid = Auth::user()->companyid;
        $products = Product::where('company_id', '=', $companyid)->orderBy('created_at','asc')->paginate(10);
        return View('products.index')->with('products', $products);
    }

    public function create()
    {
        $companyid = Auth::user()->companyid;
        return view('products.create');
    }

    public function store(Request $request)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'name' => 'required|unique:products'
        ]);
        $product = new Product;
        $product->company_id = $companyid;
        $product->name = $request->input('name');
        $product->save();

        return redirect('/products')->with('success', 'Product Created');
    }

    public function show($id)
    {
        $companyid = Auth::user()->companyid;
        $product = Product::where('company_id', '=', $companyid)->find($id);
        return response()->json($product);
    }

    public function edit($id)
    {
        $companyid = Auth::user()->companyid;
        $product = Product::find($id);
        
        return view('products.edit', ['product'=> $product]);
    }

    public function update(Request $request, $id)
    {
        $companyid = Auth::user()->companyid;
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        
        $product = Product::find($id);
        $product->name = $request->input('name');
        $product->company_id = $companyid;
        $product->save();

        return redirect('/products')->with('success', 'Product Details Updated');
    }

    public function destroy($id)
    {
        $companyid = Auth::user()->companyid;
        $product = Product::where('company_id', '=', $companyid)->find($id);
        $product->delete();
        return redirect('/products')->with('success', 'Product Removed');
    }
}
