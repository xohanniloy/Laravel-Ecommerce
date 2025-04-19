<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('CategoryListPage', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render( 'CategoryAddPage' );
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
            Category::create( $data );

            return redirect()->route( 'categories.index' )->with( [
                'message' => 'Category created successfully',
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( string $id ) {
        $category = Category::findOrFail( $id );
        return Inertia::render( 'CategoryEditPage', [
            'category' => $category,
        ] );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, string $id ) {
        try {
            $category = Category::findOrFail( $id );

            $request->validate( [
                'name'  => 'required',
            ] );

            $category->name = $request->name;

            if ( $request->hasFile( 'image' ) ) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                ]);
                $image = $request->file( 'image' );
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $image->storeAs( 'images', $fileName, 'shared' );
                $imageLocation = Storage::disk( 'shared' )->url( $destinationPath );
                $category->image = $imageLocation;
            }

            $category->save();

            return redirect()->route( 'categories.index' )->with( [
                'message' => 'Category updated successfully',
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
     * Remove the specified resource from storage.
     */
    public function destroy( string $id ) {
        try {
            $category = Category::findOrFail( $id );

            if ( $category->image ) {
                $imagePath = str_replace( Storage::disk( 'shared' )->url( '' ), '', $category->image );

                if ( Storage::disk( 'shared' )->exists( $imagePath ) ) {
                    Storage::disk( 'shared' )->delete( $imagePath );
                }
            }

            $category->delete();

            return redirect()->route( 'categories.index' )->with( [
                'message' => 'Category deleted successfully',
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
