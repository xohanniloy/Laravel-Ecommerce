<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::all();
        return Inertia::render( 'SliderListPage', [
            'sliders' => $sliders,
        ] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render( 'SliderAddPage' );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ) {
        try {
            $request->validate( [
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'active' => 'required|in:1,0',
            ] );

            $data = [
                'active' => $request->active,
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
            Slider::create( $data );

            return redirect()->route( 'sliders.index' )->with( [
                'message' => 'Slider created successfully',
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
        $slider = Slider::findOrFail( $id );
        return Inertia::render( 'SliderEditPage', [
            'slider' => $slider,
        ] );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, string $id ) {
        try {
            $slider = Slider::findOrFail( $id );

            $request->validate( [
                'active' => 'required|in:1,0',
            ] );

            $slider->active = $request->active;

            if ( $request->hasFile( 'image' ) ) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
                ]);
                $image = $request->file( 'image' );
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $image->storeAs( 'images', $fileName, 'shared' );
                $imageLocation = Storage::disk( 'shared' )->url( $destinationPath );
                $slider->image = $imageLocation;
            }

            $slider->save();

            return redirect()->route( 'sliders.index' )->with( [
                'message' => 'Slider updated successfully',
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
            $slider = Slider::findOrFail( $id );

            if ( $slider->image ) {
                $imagePath = str_replace( Storage::disk( 'shared' )->url( '' ), '', $slider->image );

                if ( Storage::disk( 'shared' )->exists( $imagePath ) ) {
                    Storage::disk( 'shared' )->delete( $imagePath );
                }
            }

            $slider->delete();

            return redirect()->route( 'sliders.index' )->with( [
                'message' => 'Slider deleted successfully',
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
