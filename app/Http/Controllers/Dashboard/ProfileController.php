<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Intl\Countries;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    //
    public function edit(){
        $user=Auth::user();
        return view('dashboard.VendorAdmin.profile.edit',[
            'user'=>$user,
            'countries'=>Countries::getNames('en'),
            'locales'=>Languages::getNames('en'),
        ]);
    }
    public function update(Request $request){
        $request->validate([
            'first_name'=>['required','string','max:255'],
            'last_name'=>['required','string','max:255'],
            'birthday'=>['nullable','date','before:today'],
            'gender'=>['in:male,female'],
            'country'=>['required','string','size:2'],
           ]);
           $user=$request->user();
           $user->profile->fill( $request->all() )->save();
           return redirect()->route('profile.edit')
           ->with('success','profile updated!');
           
    }
}
