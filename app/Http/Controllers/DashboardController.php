<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // runLicenceDataCheck();
        // 0- user, 1-staff (reserved), 2- admin

        $user = Auth::user();


        if ($user) {
            $data = [
                'user' => $user,
            ];
            // 0 User Account
            if ($user->account_type == 3) {
                // return view('users.customer.dashboard', compact('data'));
            }
            // 1 Admin
            if ($user->account_type == 0 || $user->account_type == 1 || $user->account_type == 2) {
                return view('users.admin.dashboard', compact('data'));
            }
        } else {
            return redirect()->route('accessDeniedLogout');
        }
    }
}
