<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductApproved;
use App\Models\ProductScrapped;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Picqer\Barcode\BarcodeGeneratorHTML;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $products = Product::with(['category', 'unit'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query());

        return view('products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create', [
            'categories' => Category::all(),
            'units' => Unit::all(),
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product_code = IdGenerator::generate([
            'table' => 'products',
            'field' => 'product_code',
            'length' => 4,
            'prefix' => 'PC'
        ]);

        $rules = [
            'product_image' => 'image|file|max:2048',
            'product_name' => 'required|string',
            'category_id' => 'required|integer',
            'unit_id' => 'required|integer',
            'stock' => 'required|integer',
            'buying_price' => 'required|integer',
            'productApproved_id' => 'required|integer',
            'productScrapped_id' => 'required|integer',
            'maintenance_id' => 'required|integer',
        ];

        $validatedData = $request->validate($rules);

        // Save product code value
        $validatedData['product_code'] = $product_code;

        /**
         * Handle upload image
         */
        if ($file = $request->file('product_image')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/products/';

            /**
             * Upload an image to Storage
             */
            $file->storeAs($path, $fileName);
            $validatedData['product_image'] = $fileName;
        }

        Product::create($validatedData);

        return Redirect::route('products.index')->with('success', 'Product has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128);

        return view('products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', [
            'categories' => Category::all(),
            'units' => Unit::all(),
            'approveds'  => ProductApproved::all(),
            'scrappeds' => ProductScrapped::all(),
            'maintenance' => Maintenance::all(),
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'product_image' => 'image|file|max:2048',
            'product_name' => 'required|string',
            'category_id' => 'required|integer',
            'unit_id' => 'required|integer',
            'stock' => 'required|integer',
            'buying_price' => 'required|integer',
            'productApproved_id' => 'required|integer',
            'productScrapped_id' => 'required|integer',
            'maintenance_id' => 'required|integer',
        ];

        $validatedData = $request->validate($rules);

        /**
         * Handle upload an image
         */
        if ($file = $request->file('product_image')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/products/';

            /**
             * Delete photo if exists.
             */
            if($product->product_image){
                Storage::delete($path . $product->product_image);
            }

            /**
             * Store an image to Storage
             */
            $file->storeAs($path, $fileName);
            $validatedData['product_image'] = $fileName;
        }

        Product::where('id', $product->id)->update($validatedData);

        return Redirect::route('products.index')->with('success', 'Product has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        /**
         * Delete photo if exists.
         */
        if($product->product_image){
            Storage::delete('public/products/' . $product->product_image);
        }

        Product::destroy($product->id);

        return Redirect::route('products.index')->with('success', 'Product has been deleted!');
    }

    public function maintenance()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

         $products = Product::whereIn('maintenance_id',[1,2])
         ->filter(request(['search']))
                 ->sortable()
                 ->paginate($row)
                 ->appends(request()->query());       

        return view('maintenance.index', [
            'products' => $products,
        ]);
    }
    public function scrapped()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }
        $products = Product::whereIn('productScrapped_id',[1,2])
        ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query());  
        

        return view('scrapped.index', [
            'products' => $products,
        ]);
    }

}
