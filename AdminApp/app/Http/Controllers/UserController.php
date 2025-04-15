<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function userLogin( Request $request ) {
        $request->validate( [
            'email'    => 'required|email',
            'password' => 'required',
        ] );
        $email = $request->input( 'email' );
        $password = $request->input( 'password' );
        if(!auth()->attempt(['email' => $email, 'password' => $password])) {
            return redirect( '/login' )->with( [
                'message' => 'Login failed. Please check your credentials.',
                'status'  => false,
                'error'   => '',
            ] );
        }
        return redirect( '/dashboard' )->with( [
            'message' => 'Login successful.',
            'status'  => true,
            'error'   => '',
        ]);


        // $email = $request->input( 'email' );
        // $user = User::where( 'email', $email )->first();

        // if ( !$user || !Hash::check( $request->password, $user->password ) ) {
        //     return redirect( '/login' )->with( [
        //         'message' => 'Login failed. Please check your credentials.',
        //         'status'  => false,
        //         'error'   => '',
        //     ] );
        // }

        // $request->session()->put( 'user_id', $user->id );
        // $request->session()->put( 'user_email', $email );

        // return redirect( '/dashboard' )->with( [
        //     'message' => 'Login successful.',
        //     'status'  => true,
        //     'error'   => '',
        // ] );

    }
    public function loginPage() {
        return Inertia::render( 'LoginPage' );
    }
}
