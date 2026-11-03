<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config\BasicSystemSetting;
use App\Models\Config\HomepageSlider;
use App\Models\Config\WhatsappSupportLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;



class SettingsController extends Controller
{
    public function basicSettings()
    {
        Artisan::call('config:clear');

        $appName = env('APP_NAME', '');
        $appUrl = env('APP_URL', 'http://localhost');
        $timezone = env('APP_TIMEZONE', 'Etc/GMT');



        $MAIL_MAILER = env('MAIL_MAILER', '');
        $MAIL_HOST = env('MAIL_HOST', '');
        $MAIL_PORT = env('MAIL_PORT', '');
        $MAIL_USERNAME = env('MAIL_USERNAME', '');
        $MAIL_PASSWORD = env('MAIL_PASSWORD', '');
        $MAIL_ENCRYPTION = env('MAIL_ENCRYPTION', '');
        $MAIL_FROM_ADDRESS = env('MAIL_FROM_ADDRESS', '');

        $maintenanceModeStatus = BasicSystemSetting::first()?->maintenance_mode ?? false;
        $displayPriceStatus = BasicSystemSetting::first()?->display_price ?? false;


        $data = [
            'appName' => $appName,
            'appUrl' => $appUrl,
            'timezone' => $timezone,
            'MAIL_MAILER' => $MAIL_MAILER,
            'MAIL_HOST' => $MAIL_HOST,
            'MAIL_PORT' => $MAIL_PORT,
            'MAIL_USERNAME' => $MAIL_USERNAME,
            'MAIL_PASSWORD' => $MAIL_PASSWORD,
            'MAIL_ENCRYPTION' => $MAIL_ENCRYPTION,
            'MAIL_FROM_ADDRESS' => $MAIL_FROM_ADDRESS,
            'maintenanceModeStatus' => $maintenanceModeStatus,
            'displayPriceStatus' => $displayPriceStatus,
        ];

        return view('users.admin.config.basic.basicSetting', compact('data'));
    }
    protected function updateEnv(array $data)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return false;
        }

        $env = file_get_contents($path);

        foreach ($data as $key => $value) {

            $escapedKey = preg_quote($key, '/');

            $pattern = "/^{$escapedKey}=.*/m";

            $replacement = $key . '="' . addslashes($value) . '"';

            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, $replacement, $env);
            } else {
                $env .= "\n" . $replacement;
            }
        }

        file_put_contents($path, $env);

        return true;
    }


    public function systemSettingsUpdateAppInfo(Request $request)
    {
        $request->validate([
            'APP_NAME' => 'required|string',
            'timezone' => 'required|string',
            'APP_URL' => 'required|url',
        ]);


        $data = [
            'APP_NAME' => $request->APP_NAME,
            'APP_TIMEZONE' => $request->timezone,
            'APP_URL' => $request->APP_URL,
        ];


        if ($this->updateEnv($data)) {
            // Clear the cached configuration to apply the changes immediately
            Artisan::call('config:clear');
            Artisan::call('config:cache');

            return redirect()->back()->with('success', 'Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'Update Failed!');
        }
    }
    public function systemSettingsUpdateAppLogo(Request $request)
    {
        $request->validate([
            'image_1' => 'nullable|image|mimes:png|max:2048',
            'image_2' => 'nullable|image|mimes:png|max:2048',
        ]);

        // Get or create system setting row
        $basicConfig = BasicSystemSetting::first();

        if (!$basicConfig) {
            $basicConfig = BasicSystemSetting::create([]);
        }

        $destinationPath = public_path('system/images');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Handle Logo 1
        if ($request->hasFile('image_1')) {

            $file = $request->file('image_1');

            $extension = $file->getClientOriginalExtension();
            $fileName = 'logo.' . $extension;

            $file->move($destinationPath, $fileName);

            $basicConfig->logo = $fileName;
        }

        // Handle Logo 2
        if ($request->hasFile('image_2')) {

            $file = $request->file('image_2');

            $extension = $file->getClientOriginalExtension();
            $fileName = 'logo_full.' . $extension;

            $file->move($destinationPath, $fileName);

            $basicConfig->logo_full = $fileName;
        }

        $basicConfig->save();

        return back()->with('success', 'Updated Successfully!');
    }
    public function systemSettingsUpdateMaintenanceStatus(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'required',
        ]);
        $basicConfig = BasicSystemSetting::first();

        if (!$basicConfig) {
            return back()->with('error', 'Error!');
        }
        $basicConfig->update(
            [
                'maintenance_mode' => $request->maintenance_mode,
            ]
        );
        return back()->with('success', t('notifications.done'));
    }
    public function systemSettingsUpdatePriceDisplayStatus(Request $request)
    {
        $request->validate([
            'display_price' => 'required',
        ]);
        $basicConfig = BasicSystemSetting::first();

        if (!$basicConfig) {
            return back()->with('error', 'Error!');
        }
        $basicConfig->update(
            [
                'display_price' => $request->display_price,
            ]
        );
        return back()->with('success', t('notifications.done'));
    }
    public function systemSettingsUpdateEmailConfig(Request $request)
    {
        $validated = $request->validate([
            'MAIL_MAILER' => 'required|string',
            'MAIL_HOST' => 'required|string',
            'MAIL_PORT' => 'required|integer',
            'MAIL_USERNAME' => 'required|string',
            'MAIL_PASSWORD' => 'required|string',
            'MAIL_ENCRYPTION' => 'nullable|in:tls,ssl',
            'MAIL_FROM_ADDRESS' => 'required|email',
        ]);

        $envUpdate = $this->updateEnv([
            'MAIL_MAILER' => $request->MAIL_MAILER,
            'MAIL_HOST' => $request->MAIL_HOST,
            'MAIL_PORT' => $request->MAIL_PORT,
            'MAIL_USERNAME' => $request->MAIL_USERNAME,
            'MAIL_PASSWORD' => $request->MAIL_PASSWORD,
            'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION ?: null,
            'MAIL_FROM_ADDRESS' => $request->MAIL_FROM_ADDRESS
        ]);


        if ($envUpdate) {
            // Clear the cached configuration to apply the changes immediately
            Artisan::call('config:clear');
            Artisan::call('config:cache');




            // Create intalled folder for flagging
            $folderPath = storage_path('installed');

            // Check if the folder exists, if not, create it
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // Define the file path & save the text
            $filePath = $folderPath . '/installation.txt';
            $currentDateTime = now()->toDateTimeString();
            $content = "installed on $currentDateTime";

            // Create the file and write the content
            File::put($filePath, $content);
            return redirect()->back()->with('success', 'Updated Successfully!');
        } else {
            return back()->with('error', 'Failed to update mail configuration.');
        }
    }
    public function homePageSliderConfig()
    {
        $homePageSlider = HomepageSlider::orderBy('order')->get();

        $data = [
            'homePageSlider' => $homePageSlider,
        ];

        return view('users.admin.config.homepage.sliderConfig', compact('data'));
    }
    public function addNewSlider()
    {
        return view('users.admin.config.homepage.newSliderConfig');
    }
    public function addNewSliderSubmit(Request $request)
    {
        $request->validate([
            'background_image' => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
            'header' => 'required|string',
            'order' => 'required|unique:homepage_sliders,order',
            'link' => 'nullable|url',
            'description' => 'nullable|string',

        ]);

        $orderExists = HomepageSlider::where('order', $request->order)->first();
        if ($orderExists) {
            return back()->with('error', 'Order already exists!');
        }
        $totalExistingSliders = HomepageSlider::count();
        if ($totalExistingSliders >= 5) {
            return back()->with('error', 'You can only add up to 5 slider images!');
        }
        $slider = new HomepageSlider();
        $slider->header = trim($request->header);
        $slider->order = trim($request->order);
        $slider->description = trim($request->description);
        $slider->link = trim($request->link);


        if ($request->hasFile('background_image')) {
            $image = $request->file('background_image');
            $imageExtension = $image->getClientOriginalExtension();

            // Ensure the header value is sanitized
            $imageName = Str::slug($request->header, '_') . '.' . $imageExtension;

            $image->move(public_path('assets/uploads/homepageSlider'), $imageName);
            $slider->background_image = $imageName;
        }

        $slider->save();

        return redirect()->route('homePageSliderConfig')->with('success', t('notifications.done'));
    }
    public function editSlider($id, Request $request)
    {
        $language = $request->query('language');
        $slider = HomepageSlider::where('id', $id)->first();

        if (!$slider) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'slider' => $slider,
            'language' => $language,
        ];

        return view('users.admin.config.homepage.editSliderConfig', compact('data'));
    }
    public function updateSliderSubmit($id, Request $request)
    {
        $slider = HomepageSlider::where('id', $id)->first();
        if (!$slider) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $request->validate([
            'background_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'header' => 'required|string',
            'order' => [
                'required',
                Rule::unique('homepage_sliders', 'order')->ignore($slider->id),
            ],
            'link' => 'nullable|url',
            'description' => 'nullable|string',

        ]);


        if (!$slider) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $slider->link = $request->link;
        $slider->order = $request->order;


        $language = $request->language;

        if ($language == 'zh') {
            $slider->header_zh = trim($request->header);
            $slider->description_zh = trim($request->description);
        } elseif ($language == 'fr') {
            $slider->header_fr = trim($request->header);
            $slider->description_fr = trim($request->description);
        } else {
            $slider->header = trim($request->header);
            $slider->description = trim($request->description);
        }


        if ($request->hasFile('background_image')) {
            $oldImagePath = public_path('assets/uploads/homepageSlider/' . $slider->background_image);

            if ($slider->background_image && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $image = $request->file('background_image');
            $imageExtension = $image->getClientOriginalExtension();

            $imageName = Str::slug($request->header, '_') . '.' . $imageExtension;

            $image->move(public_path('assets/uploads/homepageSlider'), $imageName);
            $slider->background_image = $imageName;
        }

        $slider->save();
        return redirect()->route('homePageSliderConfig')->with('success', t('notifications.done'));
    }

    public function contacts(Request $request)
    {
        $systemSettings = BasicSystemSetting::first();
        if (!$systemSettings) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $whatsAppSupportLines = WhatsappSupportLine::orderBy('name')->get();
        $data = [
            'systemSettings' => $systemSettings,
            'whatsAppSupportLines' => $whatsAppSupportLines,
        ];
        return view('users.admin.config.homepage.contacts', compact('data'));
    }
    public function updateContactDetailsSubmit(Request $request)
    {
        $request->validate([
            'phone' => 'nullable',
            'email' => 'email|nullable',
            'address' => 'nullable',
        ]);
        $systemSettings = BasicSystemSetting::first();
        if (!$systemSettings) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $systemSettings->update([
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function addWhatsappNumberSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required|regex:/^\+?[0-9]{7,15}$/|unique:whatsapp_support_lines,number',
        ]);
        $supportLine = new WhatsappSupportLine();
        $supportLine->name = $request->name;
        $supportLine->number = $request->number;
        $supportLine->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateWhatsAppNumberSubmit($id, Request $request)
    {
        $line = WhatsappSupportLine::where('id', $id)->first();

        if (!$line) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'name' => 'required',
            'number' => 'required|regex:/^\+?[0-9]{7,15}$/|unique:whatsapp_support_lines,number,' . $line->id,
            'status' => 'required',
        ]);

        $line = WhatsappSupportLine::where('id', $id)->first();

        if (!$line) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $line->update([
            'name' => $request->name,
            'number' => $request->number,
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', t('notifications.done'));
    }
}
