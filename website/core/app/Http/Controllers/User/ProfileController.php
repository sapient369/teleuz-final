<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChannelCategory;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller {
    public function profile() {
        $pageTitle = "Profile Setting";
        $user      = auth()->user();
        return view('Template::user.profile_setting', compact('pageTitle', 'user'));
    }

    public function submitProfile(Request $request) {
        $request->validate([
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required'  => 'The last name field is required',
        ]);

        $user = auth()->user();

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;

        if ($request->hasFile('image')) {
            try {
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), @$user->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->address = $request->address;
        $user->city    = $request->city;
        $user->state   = $request->state;
        $user->zip     = $request->zip;

        if ($request->adult_pin !== null) {
            $rules = ['adult_pin' => 'digits:4'];
            if ($user->adult_password) {
                $rules['current_adult_pin'] = 'required|digits:4';
            }

            $request->validate($rules);

            if ($user->adult_password) {
                if (!Hash::check($request->current_adult_pin, $user->adult_password)) {
                    $notify[] = ['error', "Current PIN doesn't match"];
                    return back()->withNotify($notify);
                }
            }

            $user->adult_password = Hash::make($request->adult_pin);
            session()->forget('adult_verified');
        }

        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword() {
        $pageTitle = 'Change Password';
        return view('Template::user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request) {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password       = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }
    
    public function adultPasswordForm(Request $request) {
        $pageTitle = 'Adult Content PIN';
        if ($request->has('category')) {
            session(['adult.category_id' => (int) $request->category]);
        }
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }        
        return view('Template::user.adult_password', compact('pageTitle'));
    }

    public function unlockAdult(Request $request) {
        $request->validate(['pin' => 'required|digits:4']);
        $user = auth()->user();

        if (!$user->adult_password) {
            $notify[] = ['error', 'No PIN set. Please set one in your profile.'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }

        if (!Hash::check($request->pin, $user->adult_password)) {
            $notify[] = ['error', 'Invalid PIN'];
            return back()->withNotify($notify);
        }

        session(['adult_verified' => true]);
        $categoryId = session('adult.category_id');
        $intended   = session()->get('url.intended', route('live.tv'));

        if ($categoryId) {
            $hasSubscription = Subscription::where('user_id', $user->id)
                ->where('channel_category_id', $categoryId)
                ->where('expired_date', '>=', now())
                ->active()
                ->exists();

            if (!$hasSubscription) {
                $category = ChannelCategory::find($categoryId);
                $notify[] = ['success', 'PIN entered correctly, subscription required'];                
                return view('Template::user.adult_password', [
                    'pageTitle'     => $pageTitle = 'Adult Content PIN',
                    'category'      => $category,
                    'showSubscribe' => true,
                ])->withNotify($notify);
            }
        }

        $notify[] = ['success', 'Access granted'];
        
        session()->forget('adult.category_id');
        session()->forget('url.intended');        
        return redirect()->to($intended)->withNotify($notify);
    }
}
