<?php

namespace App\Http\Controllers;

use App\Models\SSLCommerzCredential;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SSLCommerzCredentialController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $settings = SSLCommerzCredential::first();
        return Inertia::render( 'Settings', [
            'setting' => $settings,
        ] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ) {
        //
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
        try {
            $request->validate( [
                'store_id'       => 'required|string',
                'store_password' => 'required|string',
                'currency'       => 'required|string',
                'success_url'    => 'required|string',
                'fail_url'       => 'required|string',
                'cancel_url'     => 'required|string',
                'ipn_url'        => 'required|string',
                'init_url'       => 'required|string',
            ] );
    
            $settings = SSLCommerzCredential::findOrFail( $id );
    
            $settings->update( [
                'store_id'       => $request->store_id,
                'store_password' => $request->store_password,
                'currency'       => $request->currency,
                'success_url'    => $request->success_url,
                'fail_url'       => $request->fail_url,
                'cancel_url'     => $request->cancel_url,
                'ipn_url'        => $request->ipn_url,
                'init_url'       => $request->init_url,
            ] );
    
            return redirect()->back()->with( [
                'message' => 'Profile updated successfully',
                'status'  => true,
                'error'   => '',
            ] );
        } catch ( \Exception $e ) {
            return redirect()->back()->with( [
                'message' => 'Profile update failed. Please check your credentials.',
                'status'  => false,
                'error'   => $e->getMessage(),
            ] );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id ) {
        //
    }
}
