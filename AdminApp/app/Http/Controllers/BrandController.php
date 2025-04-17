<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BrandController extends Controller {
    public function index() {
        $brands = Brand::all();
        return Inertia::render( 'BrandListPage', [
            'brands' => $brands,
        ] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return Inertia::render( 'BrandAddPage' );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ) {
        try {
            $request->validate( [
                'name'  => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ] );

            $data = [
                'name' => $request->name,
            ];
            // Handle image upload correctly
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                // $destinationPath = public_path( 'uploads' );
                $destinationPath = $image->storeAs( 'images', $fileName, 'shared' );
                $imageLocation = Storage::disk( 'shared' )->url( $destinationPath );
                $data['image'] = $imageLocation;
            }
            Brand::create( $data );

            return redirect()->route( 'brands.index' )->with( [
                'message' => 'Brand created successfully',
                'status'  => true,
                'error'   => '',
            ] );
        } catch ( \Exception $e ) {
            return redirect()->back()->with( [
                'message' => $e->getMessage(),
                'status'  => false,
                'error'   => '',
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
        $brand = Brand::findOrFail( $id );
        return Inertia::render( 'BrandEditPage', [
            'brand' => $brand,
        ] );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, string $id ) {
        try {
            $brand = Brand::findOrFail( $id );

            $request->validate( [
                'name'  => 'required',
            ] );

            $brand->name = $request->name;

            if ( $request->hasFile( 'image' ) ) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                ]);
                $image = $request->file( 'image' );
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $image->storeAs( 'images', $fileName, 'shared' );
                $imageLocation = Storage::disk( 'shared' )->url( $destinationPath );
                $brand->image = $imageLocation;
            }

            $brand->save();

            return redirect()->route( 'brands.index' )->with( [
                'message' => 'Brand updated successfully',
                'status'  => true,
                'error'   => '',
            ] );
        } catch ( \Exception $e ) {
            return redirect()->back()->with( [
                'message' => $e->getMessage(),
                'status'  => false,
                'error'   => '',
            ] );
        }
    }

    public function destroy( string $id ) {
        try {
            $brand = Brand::findOrFail( $id );

            if ( $brand->image ) {
                $imagePath = str_replace( Storage::disk( 'shared' )->url( '' ), '', $brand->image );

                if ( Storage::disk( 'shared' )->exists( $imagePath ) ) {
                    Storage::disk( 'shared' )->delete( $imagePath );
                }
            }

            $brand->delete();

            return redirect()->route( 'brands.index' )->with( [
                'message' => 'Brand deleted successfully',
                'status'  => true,
                'error'   => '',
            ] );
        } catch ( \Exception $e ) {
            return redirect()->back()->with( [
                'message' => 'Delete failed: ' . $e->getMessage(),
                'status'  => false,
                'error'   => '',
            ] );
        }
    }

}