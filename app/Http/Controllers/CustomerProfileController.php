<?php

namespace App\Http\Controllers;

use App\Models\Address\City;
use App\Models\Address\Country;
use App\Models\Address\State;
use App\Models\Address\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;





class CustomerProfileController extends Controller
{
    public function customerMyProfile()
    {
        $allCountries = Country::get();
        $activeCountries = Country::where('status', 'active')->get();
        $allStates = State::get();
        $activeStates = State::where('status', 'active')->get();
        $allCity = City::get();
        $activeCity = City::where('status', 'active')->get();
        $user = authUser();

        $data = [
            'user' => $user,
            'allCountries' => $allCountries,
            'activeCountries' => $activeCountries,
            'allStates' => $allStates,
            'activeStates' => $activeStates,
            'allCity' => $allCity,
            'activeCity' => $activeCity,
        ];
        return view('users.customer.profile.viewProfile', compact('data'));
    }
    public function customerAddNewAddressSubmit(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'state' => 'required',
            'lga' => 'required',
            'more_details' => 'required',
        ]);
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $existingAddress = UserAddress::where('username', $user->username)->first();

        $data = [
            'username'     => $user->username,
            'country'      => $request->country,
            'state'        => $request->state,
            'city'         => $request->lga,
            'more_details' => $request->more_details,
        ];

        // If no existing primary address, make this one primary
        if (!$existingAddress) {
            $data['is_primary'] = true;
        }

        UserAddress::create($data);
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function customerAddressDelete($id)
    {
        $user = Auth::user();

        $address = UserAddress::where('id', $id)
            ->where('username', $user->username)
            ->first();

        if (!$address) {
            return redirect()->back()
                ->with('error', t('notifications.noRecordsFound'));
        }

        DB::transaction(function () use ($address, $user) {

            $wasPrimary = $address->is_primary;

            $address->delete();

            // If deleted address was primary, promote another
            if ($wasPrimary) {

                $nextAddress = UserAddress::where('username', $user->username)
                    ->first();

                if ($nextAddress) {
                    $nextAddress->update([
                        'is_primary' => true
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function customerUpdateAddressSubmit($id, Request $request)
    {
        $request->validate([
            'country' => 'required',
            'state' => 'required',
            'lga' => 'required',
            'more_details' => 'required',
            'is_primary' => 'required',
        ]);

        $user = Auth::user();

        $address = UserAddress::where('id', $id)
            ->where('username', $user->username)
            ->first();

        if (!$address) {
            return redirect()->back()
                ->with('error', t('notifications.noRecordsFound'));
        }

        // If this address is now primary, unset the previous primary
        if ((bool)$request->is_primary) {
            $wasPrimary = UserAddress::where('username', $address->username)
                ->where('is_primary', true)
                ->where('id', '!=', $address->id)
                ->first();

            if ($wasPrimary) {
                $wasPrimary->update([
                    'is_primary' => false,
                ]);
            }
        }

        // Update the current address
        $address->update([
            'country'      => $request->country,
            'state'        => $request->state,
            'city'         => $request->lga,
            'more_details' => $request->more_details,
            'is_primary'   => (bool)$request->is_primary,
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function customerEditProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()
                ->with('error', t('notifications.noRecordsFound'));
        }
        $data = [
            'user' => $user,
        ];

        return view('users.customer.profile.editProfile', compact('data'));
    }
    public function userUpdateProfileSubmit(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return redirect()->back()
                ->with('error', t('notifications.noRecordsFound'));
        }

        $user = User::where('username', $authUser->username)->first();

        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',

            'email1' => [
                'required',
                'email',
                'different:email2',
                Rule::unique('users', 'email1')->ignore($user->id),
            ],

            'email2' => [
                'nullable',
                'email',
                'different:email1',
                Rule::unique('users', 'email2')->ignore($user->id),
            ],

            'phone1' => [
                'required',
                'different:phone2',
                Rule::unique('users', 'phone1')->ignore($user->id),
            ],

            'phone2' => [
                'nullable',
                'different:phone1',
                Rule::unique('users', 'phone2')->ignore($user->id),
            ],

            'gender' => 'required',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable',
            'preferred_language' => 'required',
            'require_login_mfa' => 'required|boolean',
        ]);

        if ($request->email1 !== $user->email1) {
            $user->update([
                'email1_verified_at' => null,
                'email1_verification' => 'not_verified',
            ]);
        }
        if ($request->email2 !== $user->email2) {
            $user->update([
                'email1_verified_at' => null,
                'email1_verification' => 'not_verified',
            ]);
        }

        #############################
        DB::transaction(function () use ($user, $request) {
            $updates = [];

            if ($request->email1 !== $user->email1) {
                $updates['email1_verified_at'] = null;
                $updates['email1_verification'] = 'not_verified';
            }

            if ($request->email2 !== $user->email2) {
                $updates['email2_verified_at'] = null;
                $updates['email2_verification'] = 'not_verified';
            }

            // Core user updates
            $updates = array_merge($updates, [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email1' => $request->email1,
                'email2' => $request->email2,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'nationality' => $request->nationality,
                'preferred_language' => $request->preferred_language,
                'require_login_mfa' => $request->require_login_mfa,
            ]);

            $user->update($updates);

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $mime = $file->getMimeType();
                $ext  = strtolower($file->getClientOriginalExtension());

                $uploadPath = public_path('assets/uploads/users/photos');
                File::ensureDirectoryExists($uploadPath);

                // Base filename
                $baseName = $user->username;
                $fallbackName = $baseName . '.' . $ext;
                $fallbackPath = $uploadPath . '/' . $fallbackName;

                // Delete existing file if it exists
                if (file_exists($fallbackPath)) {
                    @unlink($fallbackPath);
                }

                /*
    |--------------------------------------------------------------------------
    | GD + WebP processing
    |--------------------------------------------------------------------------
    */
                $processed = false;

                if (function_exists('imagewebp') && function_exists('imagecreatetruecolor')) {

                    $tmp = $file->getPathname();

                    $src = match ($mime) {
                        'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($tmp) : null,
                        'image/png'  => function_exists('imagecreatefrompng')  ? @imagecreatefrompng($tmp)  : null,
                        'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmp) : null,
                        default => null,
                    };

                    if ($src) {
                        $width  = imagesx($src);
                        $height = imagesy($src);

                        // Resize if needed
                        $maxSize = 800;
                        $scale = min(1, $maxSize / max($width, $height));

                        if ($scale < 1) {
                            $newW = (int)($width * $scale);
                            $newH = (int)($height * $scale);
                            $dst = imagecreatetruecolor($newW, $newH);

                            // Preserve transparency
                            imagealphablending($dst, false);
                            imagesavealpha($dst, true);
                            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                            imagefill($dst, 0, 0, $transparent);

                            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
                            imagedestroy($src);
                            $src = $dst;
                        }

                        // WebP conversion
                        $webpName = $baseName . '.webp';
                        $webpPath = $uploadPath . '/' . $webpName;

                        // Delete existing WebP if exists
                        if (file_exists($webpPath)) {
                            @unlink($webpPath);
                        }

                        $quality = 90;
                        do {
                            imagewebp($src, $webpPath, $quality);
                            clearstatcache();

                            if (filesize($webpPath) <= 204800) {
                                $processed = true;
                                $user->photo = $webpName;
                                imagedestroy($src);
                                break;
                            }

                            $quality -= 5;
                        } while ($quality >= 40);

                        if (!$processed) {
                            @unlink($webpPath);
                        }
                    }
                }

                /*
    |--------------------------------------------------------------------------
    | Fallback: move original file
    |--------------------------------------------------------------------------
    */
                if (!$processed) {
                    $file->move($uploadPath, $fallbackName);
                    $user->photo = $fallbackName;
                }
                $user->save();
            }
        });
        return redirect()->back()->with('success', t('notifications.done'));
    }
}
