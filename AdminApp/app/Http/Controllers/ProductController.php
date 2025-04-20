<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $products = Product::all()->load( 'category', 'brand', );
        // $products = Product::with(['category', 'brand'])->get();
        return Inertia::render( 'ProductListPage', [
            'products' => $products,
        ] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $categories = Category::all();
        $brands = Brand::all();
        return Inertia::render( 'ProductAddPage', [
            'categories' => $categories,
            'brands'     => $brands,
        ] );
    }

    private function handleImage( $image, $old_url = null ) {
        $imageLocation = $old_url;
        if ( $image ) {
            $fileName = time() . rand( 1000, 9999 ) . '.' . $image->getClientOriginalExtension();
            $destinationPath = $image->storeAs( 'images', $fileName, 'shared' );
            $imageLocation = Storage::disk( 'shared' )->url( $destinationPath );
        }

        return $imageLocation;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ) {
        try {
            $request->validate( [
                'title'          => 'required|string|max:255',
                'short_des'      => 'required|string|max:255',
                'price'          => 'required|numeric',
                'discount_price' => 'nullable|numeric',
                'is_discount'    => 'required|boolean|in:0,1',
                'image'          => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'in_stock'       => 'required|boolean|in:0,1',
                'stock'          => 'required|integer',
                'remark'         => 'required|string|max:255',
                'category'       => 'required|exists:categories,id',
                'brand'          => 'required|exists:brands,id',
                'img1'           => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img2'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img3'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img4'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description'    => 'required|string',
                'color'          => 'required|string|max:255',
                'size'           => 'required|string|max:255',
            ] );
            $product = [
                'title'          => $request->title,
                'short_des'      => $request->short_des,
                'price'          => $request->price,
                'is_discount'    => $request->is_discount,
                'discount_price' => $request->discount_price,
                'image'          => $this->handleImage( $request->file( 'image' ) ),
                'in_stock'       => $request->in_stock,
                'stock'          => $request->stock,
                'remark'         => $request->remark,
                'category_id'    => $request->category,
                'brand_id'       => $request->brand,
            ];

            $product = Product::create( $product );

            $productDetails = [
                'description' => $request->description,
                'color'       => $request->color,
                'size'        => $request->size,
                'img1'        => $this->handleImage( $request->file( 'img1' ) ),
                'img2'        => $this->handleImage( $request->file( 'img2' ) ),
                'img3'        => $this->handleImage( $request->file( 'img3' ) ),
                'img4'        => $this->handleImage( $request->file( 'img4' ) ),
                'product_id'  => $product->id,
            ];

            ProductDetail::create( $productDetails );

            return redirect()->route( 'products.index' )->with( [
                'message' => 'Product created successfully',
                'status'  => true,
                'error'   => '',
            ] );
        } catch ( \Exception $e ) {
            return redirect()->route( 'products.index' )->with( [
                'message' => $e->getMessage(),
                'status'  => false,
                'error'   => $e->getMessage(),
            ] );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( string $id ) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( string $id ) {
        $categories = Category::all();
        $brands = Brand::all();
        $product = Product::findOrFail( $id )->load( 'details' );

        return Inertia::render( 'ProductEditPage', [
            'categories' => $categories,
            'brands'     => $brands,
            'product'    => $product,
        ] );

        // return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, string $id ) {
        try {
           
            $request->validate( [
                'title'          => 'required|string|max:255',
                'short_des'      => 'required|string|max:255',
                'price'          => 'required|numeric',
                'discount_price' => 'nullable|numeric',
                'is_discount'    => 'required|boolean|in:0,1',

                // 'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image' => $request->hasFile('image') ? 'image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable',

                'in_stock'       => 'required|boolean|in:0,1',
                'stock'          => 'required|integer',
                'remark'         => 'required|string|max:255',
                'category'       => 'required|exists:categories,id',
                'brand'          => 'required|exists:brands,id',
                'img1'  => $request->hasFile('img1') ? 'image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable',
                'img2'  => $request->hasFile('img2') ? 'image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable',
                'img3'  => $request->hasFile('img3') ? 'image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable',
                'img4'  => $request->hasFile('img4') ? 'image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable',

                'description'    => 'required|string',
                'color'          => 'required|string|max:255',
                'size'           => 'required|string|max:255',
            ] );
            $product = Product::findOrFail( $id );
            $productDetails = ProductDetail::where( 'product_id', $id )->first();
            $product->update( [
                'title'          => $request->title,
                'short_des'      => $request->short_des,
                'price'          => $request->price,
                'is_discount'    => $request->is_discount,
                'discount_price' => $request->discount_price,
                'image'          => $this->handleImage( $request->file( 'image' ), $product->image ),
                'in_stock'       => $request->in_stock,
                'stock'          => $request->stock,
                'remark'         => $request->remark,
                'category_id'    => $request->category,
                'brand_id'       => $request->brand,
            ] );

            $productDetails->update( [
                'description' => $request->description,
                'color'       => $request->color,
                'size'        => $request->size,
                'img1'        => $this->handleImage( $request->file( 'img1' ), $productDetails->img1 ),
                'img2'        => $this->handleImage( $request->file( 'img2' ), $productDetails->img2 ),
                'img3'        => $this->handleImage( $request->file( 'img3' ), $productDetails->img3 ),
                'img4'        => $this->handleImage( $request->file( 'img4' ), $productDetails->img4 ),
                'product_id'  => $product->id,
            ] );

            return redirect()->route( 'products.index' )->with( [
                'message' => 'Product updated successfully',
                'status'  => true,
                'error'   => '',
            ] );
        } catch ( \Exception $e ) {
            return redirect()->route( 'products.index' )->with( [
                'message' => $e->getMessage(),
                'status'  => false,
                'error'   => $e->getMessage(),
            ] );
        }
    }

    private function handleImageDelete( $image ) {
        if ( $image ) {
            $fileName = basename( $image ); // e.g., 123456.jpg
            $path = 'images/' . $fileName; // adapt this to your folder structure

            if ( Storage::disk( 'shared' )->exists( $path ) ) {
                Storage::disk( 'shared' )->delete( $path );
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id ) {
        try {
            $product = Product::findOrFail( $id );
            $productDetails = ProductDetail::where( 'product_id', $id )->first();

            // Delete images
            $this->handleImageDelete( $product->image );

            if ( $productDetails ) {
                $this->handleImageDelete( $productDetails->img1 );
                $this->handleImageDelete( $productDetails->img2 );
                $this->handleImageDelete( $productDetails->img3 );
                $this->handleImageDelete( $productDetails->img4 );

                // First delete child record
                $productDetails->delete();
            }

            // Then delete parent record
            $product->delete();

            return redirect()->route( 'products.index' )->with( [
                'message' => 'Product deleted successfully',
                'status'  => true,
                'error'   => '',
            ] );
        } catch ( \Exception $e ) {
            return redirect()->route( 'products.index' )->with( [
                'message' => 'Failed to delete product',
                'status'  => false,
                'error'   => $e->getMessage(),
            ] );
        }
    }

}
