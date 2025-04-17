<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller {
    public function index() {
        $brands = Brand::all();
        return Inertia::render( 'BrandListPage', [
            'brands' => $brands,
        ]);
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

                Brand::create( $data );

                return redirect()->route( 'brands.index' )->with( [
                    'message' => 'Brand created successfully',
                    'status'  => true,
                    'error'   => '',
                ] );
            }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, string $id ) {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id ) {
        //
    }
}
