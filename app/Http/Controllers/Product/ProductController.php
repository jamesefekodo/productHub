<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Products\Attribute;
use App\Models\Products\AttributeValue;
use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Colour;
use App\Models\Products\Product;
use App\Models\Products\ProductAttribute;
use App\Models\Products\ProductMedia;
use App\Models\Products\ProductVariant;
use App\Models\Products\SubCategory;
use App\Models\Products\TemporaryQuote;
use App\Models\Products\TemporaryQuoteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    public function brands(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $brands = '';
        $totalBrands = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'brandsSearch';

        $query = Brand::with(['products']);

        $productFields = [
            'name',
            'name_zh',
            'name_fr',
            'description',
            'description_zh',
            'description_fr',
            'short_description',
            'short_description_zh',
            'short_description_fr'
        ];

        if ($isSearch && $searchKey) {

            $brandFields = [
                'brand',
                'description'
            ];

            $query->where(function ($q) use ($searchKey, $brandFields, $productFields) {

                // Search in Brand fields
                foreach ($brandFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related Products
                $q->orWhereRelation('products', function ($prodQuery) use ($searchKey, $productFields) {
                    foreach ($productFields as $field) {
                        $prodQuery->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.brands');
            $requestType = 'all';
        }

        $brands = $query
            ->orderBy('brand') // required for cursor pagination
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalBrands = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Brand::count();



        $data = [
            'searchKey' => $searchKey,
            'brands' => $brands,
            // 'totalBrands' => $totalBrands,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.products.brands.brands', compact('data'));
    }
    public function createNewBrandSubmit(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Generate unique slug
        do {
            $brandSlug = 'brand-' . now()->year . '-' . random_int(1000000000, 9999999999);
        } while (Brand::where('slug', $brandSlug)->exists());

        $brand = new Brand();
        $brand->slug = $brandSlug;
        $brand->brand = trim($request->name);
        $brand->description = trim($request->description);


        // process the image
        if ($request->hasFile('logo')) {

            $file = $request->file('logo');
            $mime = $file->getMimeType();
            $ext  = $file->getClientOriginalExtension();

            $uploadPath = public_path('assets/uploads/products/brands/logo');
            File::ensureDirectoryExists($uploadPath);

            // Default: fallback filename (original file)
            $fallbackName = $brandSlug . '.' . strtolower($ext);
            $fallbackPath = $uploadPath . '/' . $fallbackName;

            /*
    |--------------------------------------------------------------------------
    | Try image processing ONLY if GD + WebP are fully supported
    |--------------------------------------------------------------------------
    */
            if (
                function_exists('imagewebp') &&
                function_exists('imagecreatetruecolor')
            ) {
                $tmp = $file->getPathname();

                $src = match ($mime) {
                    'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($tmp) : null,
                    'image/png'  => function_exists('imagecreatefrompng')  ? @imagecreatefrompng($tmp)  : null,
                    'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmp) : null,
                    default => null,
                };

                if ($src) {
                    // Original dimensions
                    $width  = imagesx($src);
                    $height = imagesy($src);

                    // Resize (max 800px)
                    $maxSize = 800;
                    $scale = min(1, $maxSize / max($width, $height));

                    if ($scale < 1) {
                        $newW = (int) ($width * $scale);
                        $newH = (int) ($height * $scale);

                        $dst = imagecreatetruecolor($newW, $newH);

                        // Preserve transparency
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                        imagefill($dst, 0, 0, $transparent);

                        imagecopyresampled(
                            $dst,
                            $src,
                            0,
                            0,
                            0,
                            0,
                            $newW,
                            $newH,
                            $width,
                            $height
                        );

                        imagedestroy($src);
                        $src = $dst;
                    }

                    // Try WebP compression
                    $webpName = $brandSlug . '.webp';
                    $webpPath = $uploadPath . '/' . $webpName;

                    $quality = 90;
                    do {
                        imagewebp($src, $webpPath, $quality);
                        clearstatcache();
                        if (filesize($webpPath) <= 102400) {
                            imagedestroy($src);
                            $brand->logo = $webpName;
                            return; // ✅ SUCCESS — stop here
                        }
                        $quality -= 5;
                    } while ($quality >= 40);

                    imagedestroy($src);
                    @unlink($webpPath); // cleanup failed attempt
                }
            }

            /*
    |--------------------------------------------------------------------------
    | Silent fallback: upload original file without processing
    |--------------------------------------------------------------------------
    */
            $file->move($uploadPath, $fallbackName);
            $brand->logo = $fallbackName;
        }


        $brand->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function viewBrand($slug)
    {
        $brand = Brand::where('slug', $slug)->first();
        if (!$brand) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'brand' => $brand,
        ];

        return view('users.admin.products.brands.viewBrand', compact('data'));
    }
    public function categories(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $categories = '';
        $totalCategories = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'categoriesSearch';

        $query = Category::with(['subCategories', 'products']);

        if ($isSearch && $searchKey) {

            $categoryFields = [
                'category',
                'category_zh',
                'category_fr',
                'meta_title',
                'meta_title_zh',
                'meta_title_fr',
                'meta_description',
                'meta_description_zh',
                'meta_description_fr'
            ];

            $relationFields = [
                'subCategories' => [
                    'sub_category',
                    'sub_category_zh',
                    'sub_category_fr',
                    'meta_title',
                    'meta_title_zh',
                    'meta_title_fr',
                    'meta_description',
                    'meta_description_zh',
                    'meta_description_fr'
                ],
                'products' => [
                    'name',
                    'name_zh',
                    'name_fr',
                    'description',
                    'description_zh',
                    'description_fr',
                    'short_description',
                    'is_active',
                    'is_featured',
                    'meta_title',
                    'meta_title_zh',
                    'meta_title_fr',
                    'meta_keywords',
                    'meta_description',
                    'meta_description_zh',
                    'meta_description_fr'
                ]
            ];

            $query->where(function ($q) use ($searchKey, $categoryFields, $relationFields) {

                // Search in category fields
                foreach ($categoryFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related models
                foreach ($relationFields as $relation => $fields) {
                    $q->orWhereRelation($relation, function ($relQuery) use ($fields, $searchKey) {
                        foreach ($fields as $field) {
                            $relQuery->orWhere($field, 'LIKE', "{$searchKey}%");
                        }
                    });
                }
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.categories');
            $requestType = 'all';
        }

        $categories = $query
            ->orderBy('category') // required for cursor pagination
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalCategories = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Category::count();



        $data = [
            'searchKey' => $searchKey,
            'categories' => $categories,
            // 'totalCategories' => $totalCategories,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.products.categories.categories', compact('data'));
    }
    public function createNewCategorySubmit(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3145728',
            'name' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',

        ]);

        // Generate unique slug
        do {
            $catSlug = 'cat-' . now()->year . '-' . random_int(1000000000, 9999999999);
        } while (Category::where('slug', $catSlug)->exists());

        $category = new Category();
        $category->slug = $catSlug;
        $category->category = trim($request->name);
        $category->meta_title = trim($request->meta_title);
        $category->meta_description = trim($request->meta_description);

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $mime = $file->getMimeType();
            $ext  = strtolower($file->getClientOriginalExtension());

            $uploadPath = public_path('assets/uploads/products/categories/icons');
            File::ensureDirectoryExists($uploadPath);

            // Fallback (original file)
            $fallbackName = $catSlug . '.' . $ext;
            $fallbackPath = $uploadPath . '/' . $fallbackName;

            /*
    |--------------------------------------------------------------------------
    | Try processing ONLY if GD + WebP are supported
    |--------------------------------------------------------------------------
    */
            if (
                function_exists('imagewebp') &&
                function_exists('imagecreatetruecolor')
            ) {
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

                    // Resize (max 800px)
                    $maxSize = 800;
                    $scale = min(1, $maxSize / max($width, $height));

                    if ($scale < 1) {
                        $newW = (int) ($width * $scale);
                        $newH = (int) ($height * $scale);

                        $dst = imagecreatetruecolor($newW, $newH);

                        // Preserve transparency
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                        imagefill($dst, 0, 0, $transparent);

                        imagecopyresampled(
                            $dst,
                            $src,
                            0,
                            0,
                            0,
                            0,
                            $newW,
                            $newH,
                            $width,
                            $height
                        );

                        imagedestroy($src);
                        $src = $dst;
                    }

                    // Try WebP compression
                    $webpName = $catSlug . '.webp';
                    $webpPath = $uploadPath . '/' . $webpName;

                    $quality = 90;
                    do {
                        imagewebp($src, $webpPath, $quality);
                        clearstatcache();

                        if (filesize($webpPath) <= 204800) {
                            imagedestroy($src);
                            $category->image = $webpName;
                            return; // ✅ success
                        }

                        $quality -= 5;
                    } while ($quality >= 40);

                    imagedestroy($src);
                    @unlink($webpPath);
                }
            }

            /*
    |--------------------------------------------------------------------------
    | Silent fallback: store original image
    |--------------------------------------------------------------------------
    */
            $file->move($uploadPath, $fallbackName);
            $category->image = $fallbackName;
        }

        $category->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function viewCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $data = [
            'category' => $category,
        ];

        return view('users.admin.products.categories.viewCategories', compact('data'));
    }
    public function createNewSubCategorySubmit($slug, Request $request)
    {

        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3145728',
            'name' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
        ]);


        // Generate unique slug
        do {
            $subCatSlug = 'sub-cat-' . now()->year . '-' . random_int(1000000000, 9999999999);
        } while (SubCategory::where('slug', $subCatSlug)->exists());

        $subCategory = new SubCategory();
        $subCategory->slug = $subCatSlug;
        $subCategory->category = $category->slug;
        $subCategory->sub_category = trim($request->name);
        $subCategory->meta_title = trim($request->meta_title);
        $subCategory->meta_description = trim($request->meta_description);

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $mime = $file->getMimeType();
            $ext  = strtolower($file->getClientOriginalExtension());

            $uploadPath = public_path('assets/uploads/products/categories/subCategories/icons');
            File::ensureDirectoryExists($uploadPath);

            // Fallback (original file)
            $fallbackName = $subCatSlug . '.' . $ext;
            $fallbackPath = $uploadPath . '/' . $fallbackName;

            /*
    |--------------------------------------------------------------------------
    | Try processing ONLY if GD + WebP are supported
    |--------------------------------------------------------------------------
    */
            if (
                function_exists('imagewebp') &&
                function_exists('imagecreatetruecolor')
            ) {
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

                    // Resize (max 800px)
                    $maxSize = 800;
                    $scale = min(1, $maxSize / max($width, $height));

                    if ($scale < 1) {
                        $newW = (int) ($width * $scale);
                        $newH = (int) ($height * $scale);

                        $dst = imagecreatetruecolor($newW, $newH);

                        // Preserve transparency
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                        imagefill($dst, 0, 0, $transparent);

                        imagecopyresampled(
                            $dst,
                            $src,
                            0,
                            0,
                            0,
                            0,
                            $newW,
                            $newH,
                            $width,
                            $height
                        );

                        imagedestroy($src);
                        $src = $dst;
                    }

                    // Try WebP compression
                    $webpName = $subCatSlug . '.webp';
                    $webpPath = $uploadPath . '/' . $webpName;

                    $quality = 90;
                    do {
                        imagewebp($src, $webpPath, $quality);
                        clearstatcache();

                        if (filesize($webpPath) <= 204800) {
                            imagedestroy($src);
                            $subCategory->image = $webpName;
                            return; //  success
                        }

                        $quality -= 5;
                    } while ($quality >= 40);

                    imagedestroy($src);
                    @unlink($webpPath);
                }
            }

            /*
    |--------------------------------------------------------------------------
    | Silent fallback: store original image
    |--------------------------------------------------------------------------
    */
            $file->move($uploadPath, $fallbackName);
            $subCategory->image = $fallbackName;
        }

        $subCategory->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function editCategory(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $language = $request->query('language');

        $data = [
            'category' => $category,
            'language' => $language,
        ];

        return view('users.admin.products.categories.editCategories', compact('data'));
    }
    public function updateCategorySubmit(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $language = $request->language;
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3145728',
            'category' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'is_featured' => 'required|boolean',
        ]);
        if ($language == 'zh') {
            $category->category_zh = $request->category;
            $category->meta_title_zh = $request->meta_title;
            $category->meta_description_zh = $request->meta_description;
        } elseif ($language == 'fr') {
            $category->category_fr = $request->category;
            $category->meta_title_fr = $request->meta_title;
            $category->meta_description_fr = $request->meta_description;
        } else {
            $category->category = $request->category;
            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
        }

        $category->is_active = $request->is_active;
        $category->is_featured = $request->is_featured;

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $mime = $file->getMimeType();
            $ext  = strtolower($file->getClientOriginalExtension());

            $uploadPath = public_path('assets/uploads/products/categories/icons');
            File::ensureDirectoryExists($uploadPath);

            // Delete existing image if exists
            if (!empty($category->image)) {
                $oldPath = $uploadPath . '/' . $category->image;
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Prepare fallback name and path for new upload
            $fallbackName = $category->slug . '.' . $ext;
            $fallbackPath = $uploadPath . '/' . $fallbackName;

            // Delete existing WebP if present
            $webpName = $category->slug . '.webp';
            $webpPath = $uploadPath . '/' . $webpName;
            if (File::exists($webpPath)) {
                File::delete($webpPath);
            }

            /*
            |--------------------------------------------------------------------------
            | Try processing image with GD + WebP
            |--------------------------------------------------------------------------
            */
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

                    // Resize if bigger than 800px
                    $maxSize = 800;
                    $scale = min(1, $maxSize / max($width, $height));

                    if ($scale < 1) {
                        $newW = (int) ($width * $scale);
                        $newH = (int) ($height * $scale);

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

                    // Try WebP compression
                    $quality = 90;
                    do {
                        imagewebp($src, $webpPath, $quality);
                        clearstatcache();

                        if (filesize($webpPath) <= 204800) {
                            imagedestroy($src);
                            $category->image = $webpName; // store WebP name in DB
                            return; // ✅ success
                        }

                        $quality -= 5;
                    } while ($quality >= 40);

                    imagedestroy($src);
                    @unlink($webpPath);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Fallback: store original image
            |--------------------------------------------------------------------------
            */
            $file->move($uploadPath, $fallbackName);
            $category->image = $fallbackName; // store fallback name in DB
        }
        $category->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function subCategories($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $subCategories = '';
        $totalSubCategories = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'subCategoriesSearch';

        $query = SubCategory::with(['products']);

        if ($isSearch && $searchKey) {

            $subCategoryFields = [
                'brand',
                'description'
            ];

            $productFields = [
                'name',
                'name_zh',
                'name_fr',
                'description',
                'description_zh',
                'description_fr',
                'short_description',
                'short_description_zh',
                'short_description_fr'
            ];

            $query->where(function ($q) use ($searchKey, $subCategoryFields, $productFields) {

                // Search in SubCategory fields
                foreach ($subCategoryFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related Products
                $q->orWhereRelation('products', function ($prodQuery) use ($searchKey, $productFields) {
                    foreach ($productFields as $field) {
                        $prodQuery->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.subCategories');
            $requestType = 'all';
        }

        $subCategories = $query
            ->orderBy('category') // required for cursorPaginate
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalSubCategories = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : SubCategory::count();



        $data = [
            'searchKey' => $searchKey,
            'category' => $category,
            'subCategories' => $subCategories,
            // 'totalSubCategories' => $totalSubCategories,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.products.categories.subCategories.subCategories', compact('data'));
    }
    public function viewSubCategory($category, $slug)
    {
        $subCategory = SubCategory::where('slug', $slug)->where('category', $category)->first();

        if (!$subCategory) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $data = [
            'subCategory' => $subCategory,
            'category' => $subCategory->theCategory,
        ];

        return view('users.admin.products.categories.subCategories.viewSubCategory', compact('data'));
    }
    public function editSubCategory($slug, Request $request)
    {
        $subCategory = SubCategory::where('slug', $slug)->first();

        if (!$subCategory) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $allCategories = Category::orderBy('category')->get();
        $language = $request->query('language');

        $data = [
            'subCategory' => $subCategory,
            'category' => $subCategory->theCategory,
            'language' => $language,
            'allCategories' => $allCategories,
        ];

        return view('users.admin.products.categories.subCategories.editSubCategory', compact('data'));
    }
    public function updateSubCategorySubmit($slug, Request $request)
    {
        $subCategory = SubCategory::where('slug', $slug)->first();

        if (!$subCategory) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $language = $request->language;
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3145728',
            'category' => 'required|string|exists:categories,slug',
            'sub_category' => 'required|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'is_featured' => 'required|boolean',
        ]);

        $subCategory->category = $request->category;
        $subCategory->is_active = $request->is_active;
        $subCategory->is_featured = $request->is_featured;

        if ($language == 'zh') {
            $subCategory->sub_category_zh = trim($request->sub_category);
            $subCategory->meta_title_zh = trim($request->meta_title);
            $subCategory->meta_description_zh = trim($request->meta_description);
        } elseif ($language == 'fr') {
            $subCategory->sub_category_fr = trim($request->sub_category);
            $subCategory->meta_title_fr = trim($request->meta_title);
            $subCategory->meta_description_fr = trim($request->meta_description);
        } else {
            $subCategory->sub_category = trim($request->sub_category);
            $subCategory->meta_title = trim($request->meta_title);
            $subCategory->meta_description = trim($request->meta_description);
        }

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $mime = $file->getMimeType();
            $ext  = strtolower($file->getClientOriginalExtension());

            $uploadPath = public_path('assets/uploads/products/categories/subCategories/icons');
            File::ensureDirectoryExists($uploadPath);

            // Delete existing image if exists
            if (!empty($category->image)) {
                $oldPath = $uploadPath . '/' . $category->image;
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Prepare fallback name and path for new upload
            $fallbackName = $subCategory->slug . '.' . $ext;
            $fallbackPath = $uploadPath . '/' . $fallbackName;

            // Delete existing WebP if present
            $webpName = $subCategory->slug . '.webp';
            $webpPath = $uploadPath . '/' . $webpName;
            if (File::exists($webpPath)) {
                File::delete($webpPath);
            }

            /*
            |--------------------------------------------------------------------------
            | Try processing image with GD + WebP
            |--------------------------------------------------------------------------
            */
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

                    // Resize if bigger than 800px
                    $maxSize = 800;
                    $scale = min(1, $maxSize / max($width, $height));

                    if ($scale < 1) {
                        $newW = (int) ($width * $scale);
                        $newH = (int) ($height * $scale);

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

                    // Try WebP compression
                    $quality = 90;
                    do {
                        imagewebp($src, $webpPath, $quality);
                        clearstatcache();

                        if (filesize($webpPath) <= 204800) {
                            imagedestroy($src);
                            $subCategory->image = $webpName; // store WebP name in DB
                            return; // ✅ success
                        }

                        $quality -= 5;
                    } while ($quality >= 40);

                    imagedestroy($src);
                    @unlink($webpPath);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Fallback: store original image
            |--------------------------------------------------------------------------
            */
            $file->move($uploadPath, $fallbackName);
            $subCategory->image = $fallbackName; // store fallback name in DB
        }


        $subCategory->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function attributes(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $attributes = '';
        $totalAttributes = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'attributesSearch';

        $query = Attribute::with('attributeValues');

        $attributeFields = [
            'name',
            'name_title_zh',
            'name_title_fr'
        ];

        $attributeValueFields = [
            'value',
            'value_zh',
            'value_fr'
        ];

        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey, $attributeFields, $attributeValueFields) {

                // Search in Attribute fields
                foreach ($attributeFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related AttributeValues
                $q->orWhereRelation('attributeValues', function ($valQuery) use ($searchKey, $attributeValueFields) {
                    foreach ($attributeValueFields as $field) {
                        $valQuery->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.attributes');
            $requestType = 'all';
        }

        $attributes = $query
            ->orderBy('name') // required for cursor pagination
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalAttributes = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Attribute::count();



        $data = [
            'searchKey' => $searchKey,
            'attributes' => $attributes,
            // 'totalAttributes' => $totalAttributes,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.products.attributes.attributes', compact('data'));
    }

    public function createNewAttributeSubmit(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|string',
            'value' => 'nullable|array|min:1',
            'value.*' => 'nullable|string|max:255',
        ]);

        // Get the data
        $attributeName = $request->input('name');
        $attributeValues = $request->input('value'); // This is an array

        // Remove empty values
        $attributeValues = array_filter($attributeValues, function ($value) {
            return !empty(trim($value));
        });

        // save attributes
        $attribute = Attribute::create([
            'name' => $attributeName,
        ]);

        // save att values
        foreach ($attributeValues as $value) {
            AttributeValue::create([
                'attribute_id' => $attribute->id,
                'value' => trim($value),
            ]);
        }
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function deleteAttribute($id)
    {
        $attribute = Attribute::findOrFail($id);
        AttributeValue::where('attribute_id', $attribute->id)->delete();
        $attribute->delete();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function editAttribute($id, Request $request)
    {
        $language = $request->query('language');
        $attribute = Attribute::where('id', $id)->first();

        if (!$attribute) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'attribute' => $attribute,
            'language' => $language,
        ];
        return view('users.admin.products.attributes.editAttribute', compact('data'));
    }
    public function deleteAttributeValue($id)
    {
        $value = AttributeValue::where('id', $id)->first();
        if (!$value) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $value->delete();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateAttributeSubmit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'language' => 'required|string', // Add this validation
            'value' => 'nullable|array',
            'value.*' => 'nullable|string|max:255',
            'new_value' => 'nullable|array',
            'new_value.*' => 'nullable|string|max:255',
            'is_active' => 'required'
        ]);

        // Get language from request input (not query)
        $language = $request->input('language');

        $attribute = Attribute::find($id);

        if (!$attribute) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $attribute->is_active = $request->is_active;
        // Update attribute name based on language
        if ($language == 'zh') {
            $attribute->name_zh = $request->name;
        } elseif ($language == 'fr') {
            $attribute->name_fr = $request->name;
        } else {
            $attribute->name = $request->name;
        }

        $attribute->save(); // Don't forget to save!

        // Update existing values
        if ($request->has('value')) {
            foreach ($request->input('value') as $valueId => $valueText) {
                $attributeValue = AttributeValue::find($valueId);
                if ($attributeValue && !empty(trim($valueText))) {
                    // Update language-specific field
                    if ($language == 'zh') {
                        $attributeValue->value_zh = trim($valueText);
                    } elseif ($language == 'fr') {
                        $attributeValue->value_fr = trim($valueText);
                    } else {
                        $attributeValue->value = trim($valueText);
                    }

                    $attributeValue->save();
                }
            }
        }

        // Add new values
        if ($request->has('new_value')) {
            foreach ($request->input('new_value') as $newValue) {
                if (!empty(trim($newValue))) {
                    $data = ['attribute_id' => $attribute->id];

                    // Set the value in the correct language field
                    if ($language == 'zh') {
                        $data['value_zh'] = trim($newValue);
                    } elseif ($language == 'fr') {
                        $data['value_fr'] = trim($newValue);
                    } else {
                        $data['value'] = trim($newValue);
                    }

                    AttributeValue::create($data);
                }
            }
        }

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function colours(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $colours = '';
        $totalColours = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'coloursSearch';

        $query = Colour::query();

        $colourFields = [
            'name',
            'name_zh',
            'name_fr'
        ];

        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey, $colourFields) {
                foreach ($colourFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.colours');
            $requestType = 'all';
        }

        $colours = $query
            ->orderBy('name') // required for cursorPaginate
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalColours = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Colour::count();



        $data = [
            'searchKey' => $searchKey,
            'colours' => $colours,
            // 'totalColours' => $totalColours,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.products.colours.colours', compact('data'));
    }
    public function createNewColourSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'hex_code.regex' => 'The color code must be a valid hex color (e.g., #FF5733)',
        ]);

        Colour::create([
            'name' => $request->name,
            'hex_code' => strtoupper($request->hex_code),
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function deleteColour($id)
    {
        $colour = Colour::where('id', $id)->first();
        if (!$colour) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $colour->delete();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function editColour($id, Request $request)
    {
        $colour = Colour::where('id', $id)->first();
        if (!$colour) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $language = $request->query('language');


        $data = [
            'colour' => $colour,
            'language' => $language,
        ];

        return view('users.admin.products.colours.editColour', compact('data'));
    }
    public function updateColourSubmit($id, Request $request)
    {
        $colour = Colour::where('id', $id)->first();
        if (!$colour) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'language' => 'required',
        ], [
            'hex_code.regex' => 'The color code must be a valid hex color (e.g., #FF5733)',
        ]);

        $language = $request->language;


        if ($language == 'zh') {
            $colour->name_zh = trim($request->name);
        } elseif ($language == 'fr') {
            $colour->name_fr = trim($request->name);
        } else {
            $colour->name = trim($request->name);
        }
        $colour->hex_code = trim($request->hex_code);
        $colour->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function products(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $products = '';
        $totalProducts = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'productsSearch';

        $query = Product::with(['brand', 'category', 'subCategory', 'colour']);

        if ($isSearch && $searchKey) {

            $productFields = [
                'name',
                'name_zh',
                'name_fr',
                'description',
                'description_zh',
                'description_fr',
                'short_description',
                'is_active',
                'is_featured',
                'meta_title',
                'meta_title_zh',
                'meta_title_fr',
                'meta_keywords',
                'meta_description',
                'meta_description_zh',
                'meta_description_fr'
            ];

            $relationFields = [
                'subCategory' => [
                    'sub_category',
                    'sub_category_zh',
                    'sub_category_fr',
                    'meta_title',
                    'meta_title_zh',
                    'meta_title_fr',
                    'meta_description',
                    'meta_description_zh',
                    'meta_description_fr'
                ],
                'category' => [
                    'category',
                    'category_zh',
                    'category_fr',
                    'meta_title',
                    'meta_title_zh',
                    'meta_title_fr',
                    'meta_description',
                    'meta_description_zh',
                    'meta_description_fr'
                ],
                'brand' => [
                    'brand',
                    'description'
                ]
            ];

            $query->where(function ($q) use ($searchKey, $productFields, $relationFields) {

                // Search in Product fields
                foreach ($productFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related models
                foreach ($relationFields as $relation => $fields) {
                    $q->orWhereRelation($relation, function ($relQuery) use ($fields, $searchKey) {
                        foreach ($fields as $field) {
                            $relQuery->orWhere($field, 'LIKE', "{$searchKey}%");
                        }
                    });
                }
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.products');
            $requestType = 'all';
        }

        $products = $query
            ->orderBy('id') // Required for cursor pagination
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalProducts = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Product::count();



        $data = [
            'searchKey' => $searchKey,
            'products' => $products,
            // 'totalProducts' => $totalProducts,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.products.products.products', compact('data'));
    }
    public function addNewProducts()
    {
        $activeCategories = Category::where('is_active', true)->orderBy('category')->get();
        $brands = Brand::orderby('brand')->get();
        $data = [
            'activeCategories' => $activeCategories,
            'brands' => $brands,
        ];

        return view('users.admin.products.products.newProducts', compact('data'));
    }
    public function editProduct($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $activeCategories = Category::where('is_active', true)->orderBy('category')->get();
        $allCategories = Category::where('is_active', true)->orderBy('category')->get();

        $brands = Brand::orderby('brand')->get();
        $language = $request->query('language');

        $data = [
            'activeCategories' => $activeCategories,
            'allCategories' => $allCategories,
            'brands' => $brands,
            'product' => $product,
            'language' => $language
        ];

        return view('users.admin.products.products.editProduct', compact('data'));
    }
    public function getSubCategories($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([], 404);
        }

        $subCategories = SubCategory::where('category', $category->slug)
            ->where('is_active', 1)
            ->get();

        return response()->json($subCategories);
    }
    public function createNewProductSubmit(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3145728',
            'name' => 'required|string',
            'category' => 'required|exists:categories,slug',
            'sub_category' => 'required|exists:sub_categories,slug',
            'brand' => 'required|exists:brands,slug',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable',
            'price' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'meta_title' => 'nullable',
            'meta_keywords' => 'nullable',
            'meta_description' => 'nullable',
            'sku' => 'nullable'
        ]);

        $category = Category::where('slug', $request->category)->first();
        $subCategory = SubCategory::where('slug', $request->sub_category)->first();
        $brand = Brand::where('slug', $request->brand)->first();

        // Check if any of them is null
        if (!$category || !$subCategory || !$brand) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }



        // Generate unique slug
        do {
            $productSlug = 'product-' . now()->year . '-' . random_int(1000000000, 9999999999);
        } while (Product::where('slug', $productSlug)->exists());

        $product = new Product();
        $product->slug = $productSlug;
        $product->name = trim($request->name);
        $product->category_id = $category->id;
        $product->sub_category_id = $subCategory->id;
        $product->brand_id = $brand->id;
        $product->short_description = trim($request->short_description);
        $product->sku = trim($request->sku);
        $product->description = $request->full_description;
        $product->price = $request->price;
        $product->meta_title = trim($request->meta_title);
        $product->meta_keywords = trim($request->meta_keywords);
        $product->meta_description = $request->meta_description;


        // create image/media file
        $media = new ProductMedia();
        $media->product_slug = $productSlug;
        $media->file_type = 'image';
        $media->is_primary = true;
        $media->sort_order = 0;


        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $mime = $file->getMimeType();
            $ext  = strtolower($file->getClientOriginalExtension());

            $uploadPath = public_path('assets/uploads/products/products/media/images');
            File::ensureDirectoryExists($uploadPath);

            // Base filename (slug)
            $baseName = $productSlug;
            $fallbackName = $baseName . '.' . $ext;
            $counter = 1;

            // Avoid overwriting existing files
            while (file_exists($uploadPath . '/' . $fallbackName)) {
                $fallbackName = $baseName . '-' . $counter . '.' . $ext;
                $counter++;
            }

            $fallbackPath = $uploadPath . '/' . $fallbackName;

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

                    // WebP conversion with quality loop
                    $webpName = $baseName . '.webp';
                    $webpCounter = 1;
                    $webpPath = $uploadPath . '/' . $webpName;

                    while (file_exists($webpPath)) {
                        $webpName = $baseName . '-' . $webpCounter . '.webp';
                        $webpPath = $uploadPath . '/' . $webpName;
                        $webpCounter++;
                    }

                    $quality = 90;
                    do {
                        imagewebp($src, $webpPath, $quality);
                        clearstatcache();

                        if (filesize($webpPath) <= 204800) {
                            $processed = true;
                            $media->file_name = $webpName;
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
                $media->file_name = $fallbackName;
            }
        }



        $product->save();
        $media->save();


        return redirect()->route('products')->with('success', t('notifications.done'));
    }
    public function viewProduct($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $temporaryQuotes = TemporaryQuote::get();

        $allColours = Colour::orderBy('name')->get();
        $allAttributes = Attribute::orderBy('name')->get();
        $activeAttributes = Attribute::where('is_active', true)->orderBy('name')->get();
        $data = [
            'product' => $product,
            'allColours' => $allColours,
            'allAttributes' => $allAttributes,
            'activeAttributes' => $activeAttributes,
            'temporaryQuotes' => $temporaryQuotes,
        ];
        return view('users.admin.products.products.viewProduct', compact('data'));
    }
    public function addProductColourSubmit(Request $request)
    {
        $request->validate([
            'colour' => 'required|exists:colours,id',
            'product' => 'required|exists:products,slug',
        ]);

        $product = Product::where('slug', $request->product)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $product->update([
            'colour_id' => $request->colour,
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function removeProductColourSubmit(Request $request)
    {
        $request->validate([
            'product' => 'required|exists:products,slug',
        ]);

        $product = Product::where('slug', $request->product)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $product->update([
            'colour_id' => null,
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function fetchAttributeValue(Request $request)
    {
        $values = AttributeValue::where('attribute_id', $request->attribute_id)->get();

        // Send all data to JS
        $locale = app()->getLocale(); // current locale
        return response()->json([
            'values' => $values->map(function ($value) use ($locale) {
                $field = 'value_' . $locale;
                return [
                    'id' => $value->id,
                    'localized_name' => !empty($value->$field) ? $value->$field : $value->value,
                ];
            }),
        ]);
    }
    public function addProductAttributeSubmit(Request $request)
    {
        $request->validate([
            'product' => 'required|exists:products,slug',
            'attribute_id' => 'required|exists:attributes,id',
            'value_id' => 'required|exists:attribute_values,id',
        ]);
        $attribute = new ProductAttribute();
        $attribute->product_slug = $request->product;
        $attribute->attribute_id = $request->attribute_id;
        $attribute->value_id = $request->value_id;
        $attribute->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function removeProductAttributeSubmit(Request $request)
    {
        $request->validate([
            'product'       => 'required|string',
            'attribute_ids' => 'required|string',
        ]);

        $attributeIds = explode(',', $request->attribute_ids);

        ProductAttribute::whereIn('id', $attributeIds)
            ->where('product_slug', $request->product)
            ->delete();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function addProductMediaSubmit(Request $request)
    {
        $request->validate([
            'file_type' => 'required|in:image,video',
            'media_file' => 'required|file',
            'product' => 'required|exists:products,slug',
            'sort_order' => 'required',
        ]);


        $media = new ProductMedia();
        $media->product_slug = $request->product;
        $media->file_type = $request->file_type;
        $media->sort_order = $request->sort_order;


        if ($request->file_type === 'image') {
            $request->validate([
                'media_file' => 'mimes:jpg,jpeg,png,webp|max:3145728'
            ]);


            if ($request->hasFile('media_file')) {

                $uploadPath = public_path('assets/uploads/products/products/media/images');
                File::ensureDirectoryExists($uploadPath);

                $file = $request->file('media_file');
                $mime = $file->getMimeType();
                $ext  = strtolower($file->getClientOriginalExtension());


                // Base filename (slug)
                $baseName = $request->product;
                $fallbackName = $baseName . '.' . $ext;
                $counter = 1;

                // Avoid overwriting existing files
                while (file_exists($uploadPath . '/' . $fallbackName)) {
                    $fallbackName = $baseName . '-' . $counter . '.' . $ext;
                    $counter++;
                }

                $fallbackPath = $uploadPath . '/' . $fallbackName;

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

                        // WebP conversion with quality loop
                        $webpName = $baseName . '.webp';
                        $webpCounter = 1;
                        $webpPath = $uploadPath . '/' . $webpName;

                        while (file_exists($webpPath)) {
                            $webpName = $baseName . '-' . $webpCounter . '.webp';
                            $webpPath = $uploadPath . '/' . $webpName;
                            $webpCounter++;
                        }

                        $quality = 90;
                        do {
                            imagewebp($src, $webpPath, $quality);
                            clearstatcache();

                            if (filesize($webpPath) <= 204800) {
                                $processed = true;
                                $media->file_name = $webpName;
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
                    $media->file_name = $fallbackName;
                }
            }
        }

        if ($request->file_type === 'video') {

            $request->validate([
                'media_file' => 'required|mimes:mp4|max:15000'
            ]);

            $uploadPath = public_path('assets/uploads/products/products/media/videos');
            File::ensureDirectoryExists($uploadPath);

            $media = new ProductMedia();
            $media->product_slug = $request->product;
            $media->file_type = 'video';
            $media->sort_order = $request->sort_order;

            if ($request->hasFile('media_file')) {

                $file = $request->file('media_file');
                $ext  = strtolower($file->getClientOriginalExtension());

                // Base filename (slug)
                $baseName = $request->product;
                $fileName = $baseName . '.' . $ext;
                $counter = 1;

                // Prevent overwrite
                while (file_exists($uploadPath . '/' . $fileName)) {
                    $fileName = $baseName . '-' . $counter . '.' . $ext;
                    $counter++;
                }

                // Move file
                $file->move($uploadPath, $fileName);

                $media->file_name = $fileName;
            }
        }

        $media->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateProductMedia(Request $request, $id)
    {
        $media = ProductMedia::where('id', $id)->first();
        if (!$media) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'sort_order' => 'required|integer|min:0',
            'is_primary' => 'required|boolean',
        ]);

        // If setting as primary, unset all others first
        if ($request->is_primary) {
            ProductMedia::where('product_slug', $media->product_slug)
                ->where('id', '!=', $media->id)
                ->update(['is_primary' => false]);
        }

        // ─── IMAGE UPDATE ──────────────────────────────────────────────────────────
        if ($media->file_type === 'image') {

            if ($request->hasFile('media_file')) {

                $request->validate([
                    'media_file' => 'mimes:jpg,jpeg,png,webp|max:3145728'
                ]);

                $uploadPath = public_path('assets/uploads/products/products/media/images');
                File::ensureDirectoryExists($uploadPath);

                // Delete old file
                $oldFilePath = $uploadPath . '/' . $media->file_name;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }

                $file     = $request->file('media_file');
                $mime     = $file->getMimeType();
                $ext      = strtolower($file->getClientOriginalExtension());
                $baseName = 'product-media-' . $media->id . '-' . time();

                $fallbackName = $baseName . '.' . $ext;
                $processed    = false;

                /*
            |--------------------------------------------------------------------------
            | GD + WebP processing
            |--------------------------------------------------------------------------
            */
                if (function_exists('imagewebp') && function_exists('imagecreatetruecolor')) {

                    $tmp = $file->getPathname();

                    $src = match ($mime) {
                        'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($tmp) : null,
                        'image/png'  => function_exists('imagecreatefrompng')  ? @imagecreatefrompng($tmp)  : null,
                        'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmp) : null,
                        default      => null,
                    };

                    if ($src) {
                        $width  = imagesx($src);
                        $height = imagesy($src);

                        // Resize if needed
                        $maxSize = 800;
                        $scale   = min(1, $maxSize / max($width, $height));

                        if ($scale < 1) {
                            $newW = (int)($width  * $scale);
                            $newH = (int)($height * $scale);
                            $dst  = imagecreatetruecolor($newW, $newH);

                            // Preserve transparency
                            imagealphablending($dst, false);
                            imagesavealpha($dst, true);
                            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                            imagefill($dst, 0, 0, $transparent);

                            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
                            imagedestroy($src);
                            $src = $dst;
                        }

                        // WebP conversion with quality loop
                        $webpName = $baseName . '.webp';
                        $webpPath = $uploadPath . '/' . $webpName;

                        $quality = 90;
                        do {
                            imagewebp($src, $webpPath, $quality);
                            clearstatcache();

                            if (filesize($webpPath) <= 204800) {
                                $processed        = true;
                                $media->file_name = $webpName;
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
                    $media->file_name = $fallbackName;
                }
            }
        }

        // ─── VIDEO UPDATE ──────────────────────────────────────────────────────────
        if ($media->file_type === 'video') {

            if ($request->hasFile('media_file')) {

                $request->validate([
                    'media_file' => 'mimes:mp4|max:15000'
                ]);

                $uploadPath = public_path('assets/uploads/products/products/media/videos');
                File::ensureDirectoryExists($uploadPath);

                // Delete old file
                $oldFilePath = $uploadPath . '/' . $media->file_name;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }

                $file     = $request->file('media_file');
                $ext      = strtolower($file->getClientOriginalExtension());
                $baseName = 'product-media-' . $media->id . '-' . time();
                $fileName = $baseName . '.' . $ext;

                $file->move($uploadPath, $fileName);

                $media->file_name = $fileName;
            }
        }

        // ─── SAVE COMMON FIELDS ────────────────────────────────────────────────────
        $media->sort_order = $request->sort_order;
        $media->is_primary = $request->is_primary;
        $media->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function deleteProductMedia($id)
    {
        $media = ProductMedia::where('id', $id)->first();
        if (!$media) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        // If deleting a primary image, assign primary to another image first
        if ($media->is_primary && $media->file_type === 'image') {
            $nextImage = ProductMedia::where('product_slug', $media->product_slug)
                ->where('file_type', 'image')
                ->where('id', '!=', $media->id)
                ->first();

            if ($nextImage) {
                $nextImage->is_primary = true;
                $nextImage->save();
            }
        }

        // Delete physical file
        $folder  = $media->file_type === 'video' ? 'videos' : 'images';
        $oldPath = public_path("assets/uploads/products/products/media/{$folder}/{$media->file_name}");
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }

        $media->delete();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateProductSubmit($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'name' => 'required|string',
            'category' => 'required|exists:categories,slug',
            'sub_category' => 'required|exists:sub_categories,slug',
            'brand' => 'required|exists:brands,slug',
            'short_description' => 'nullable|string',
            'description' => 'nullable',
            'price' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'meta_title' => 'nullable',
            'meta_keywords' => 'nullable',
            'meta_description' => 'nullable',
            'sku' => 'nullable',
            'is_active' => 'required',
            'is_featured' => 'required',
            'is_available' => 'required',
        ]);

        $category = Category::where('slug', $request->category)->first();
        $subCategory = SubCategory::where('slug', $request->sub_category)->first();
        $brand = Brand::where('slug', $request->brand)->first();

        // Check if any of them is null
        if (!$category || !$subCategory || !$brand) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $language = $request->language;

        $product->category_id = $category->id;
        $product->sub_category_id = $subCategory->id;
        $product->brand_id = $brand->id;
        $product->sku = trim($request->sku);
        $product->price = $request->price;
        $product->is_active = $request->is_active;
        $product->is_featured = $request->is_featured;
        $product->is_available = $request->is_available;


        if ($language == 'zh') {
            $product->name_zh = trim($request->name);
            $product->description_zh = $request->description;
            $product->short_description_zh = trim($request->short_description);
            $product->meta_title_zh = trim($request->meta_title);
            $product->meta_keywords_zh = trim($request->meta_keywords);
            $category->meta_description_zh = $request->meta_description;
        } elseif ($language == 'fr') {
            $product->name_fr = trim($request->name);
            $product->description_fr = $request->description;
            $product->meta_title_fr = trim($request->meta_title);
            $product->meta_keywords_fr = trim($request->meta_keywords);
            $product->meta_description_fr = $request->meta_description;
            $product->short_description_fr = trim($request->short_description);
        } else {
            $product->name = trim($request->name);
            $product->description = $request->description;
            $product->meta_title = trim($request->meta_title);
            $product->meta_keywords = trim($request->meta_keywords);
            $product->meta_description = $request->meta_description;
            $product->short_description = trim($request->short_description);
        }

        $product->save();


        return redirect()->back()->with('success', t('notifications.done'));
    }

    public function productVariations($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }


        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $productVariations = '';
        $totalProductVariations = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'productVariationsSearch';

        $query = ProductVariant::with(['colour']);

        if ($isSearch && $searchKey) {

            $searchFields = [
                'name',
                'name_zh',
                'name_fr',
                'sku',
                'price'
            ];

            $query->where(function ($q) use ($searchKey, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {

            // Default: show variations of a specific product
            $query->where('product_id', $product->id);

            $pageTitle   = t('app.product.variations');
            $requestType = 'all';
        }

        $productVariations = $query
            ->orderBy('id') // required for cursorPaginate
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalProductVariations = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : ProductVariant::count();



        $data = [
            'searchKey' => $searchKey,
            'productVariations' => $productVariations,
            // 'totalProductVariations' => $totalProductVariations,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
            'product' => $product,
        ];

        return view('users.admin.products.products.variants.productVariants', compact('data'));
    }
    public function addProductVariant($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'product' => $product,
        ];
        return view('users.admin.products.products.variants.newProductVariant', compact('data'));
    }
    public function createNewSubProductSubmit($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3145728',
            'name' => 'required|string',
            'price' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'sku' => 'nullable'
        ]);

        // Generate unique slug
        do {
            $productVariantSlug = 'product-var-' . now()->year . '-' . random_int(1000000000, 9999999999);
        } while (ProductVariant::where('slug', $productVariantSlug)->exists());

        $var = new ProductVariant();
        $var->slug = $productVariantSlug;
        $var->product_id = $product->id;
        $var->name = trim($request->name);
        $var->sku = trim($request->sku);
        $var->price = $request->price;


        // create image/media file
        $media = new ProductMedia();
        $media->product_slug = $product->slug;
        $media->variant_slug = $productVariantSlug;
        $media->file_type = 'image';
        $media->is_primary = false;
        $media->sort_order = 0;


        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $mime = $file->getMimeType();
            $ext  = strtolower($file->getClientOriginalExtension());

            $uploadPath = public_path('assets/uploads/products/products/media/images');
            File::ensureDirectoryExists($uploadPath);

            // Base filename (slug)
            $baseName = $productVariantSlug;
            $fallbackName = $baseName . '.' . $ext;
            $counter = 1;

            // Avoid overwriting existing files
            while (file_exists($uploadPath . '/' . $fallbackName)) {
                $fallbackName = $baseName . '-' . $counter . '.' . $ext;
                $counter++;
            }

            $fallbackPath = $uploadPath . '/' . $fallbackName;

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

                    // WebP conversion with quality loop
                    $webpName = $baseName . '.webp';
                    $webpCounter = 1;
                    $webpPath = $uploadPath . '/' . $webpName;

                    while (file_exists($webpPath)) {
                        $webpName = $baseName . '-' . $webpCounter . '.webp';
                        $webpPath = $uploadPath . '/' . $webpName;
                        $webpCounter++;
                    }

                    $quality = 90;
                    do {
                        imagewebp($src, $webpPath, $quality);
                        clearstatcache();

                        if (filesize($webpPath) <= 204800) {
                            $processed = true;
                            $media->file_name = $webpName;
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
                $media->file_name = $fallbackName;
            }
        }



        $var->save();
        $media->save();


        return redirect()->route('productVariations', ['slug' => $product->slug])->with('success', t('notifications.done'));
    }
    public function viewProductVariant($slug)
    {
        $variant = ProductVariant::where('slug', $slug)->first();
        if (!$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $product = $variant->product;
        $allColours = Colour::orderBy('name')->get();
        $allAttributes = Attribute::orderBy('name')->get();
        $activeAttributes = Attribute::where('is_active', true)->orderBy('name')->get();
        $temporaryQuotes = TemporaryQuote::get();


        $data = [
            'variant' => $variant,
            'product' => $product,
            'allColours' => $allColours,
            'allAttributes' => $allAttributes,
            'activeAttributes' => $activeAttributes,
            'temporaryQuotes' => $temporaryQuotes,
        ];

        return view('users.admin.products.products.variants.viewProductVariant', compact('data'));
    }
    public function addProductVariantColourSubmit(Request $request)
    {
        $request->validate([
            'colour' => 'required|exists:colours,id',
            'variant' => 'required|exists:product_variants,slug',
        ]);

        $variant = ProductVariant::where('slug', $request->variant)->first();
        if (!$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $variant->update([
            'colour_id' => $request->colour,
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function removeProductVariantColourSubmit(Request $request)
    {
        $request->validate([
            'variant' => 'required|exists:product_variants,slug',
        ]);

        $variant = ProductVariant::where('slug', $request->variant)->first();
        if (!$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $variant->update([
            'colour_id' => null,
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function addProductVariantAttributeSubmit(Request $request)
    {
        $request->validate([
            'variant' => 'required|exists:product_variants,slug',
            'attribute_id' => 'required|exists:attributes,id',
            'value_id' => 'required|exists:attribute_values,id',
        ]);

        $variant = ProductVariant::where('slug', $request->variant)->first();
        if (!$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }


        $attribute = new ProductAttribute();
        $attribute->product_slug = $variant->product->slug;
        $attribute->variant_slug = $request->variant;
        $attribute->attribute_id = $request->attribute_id;
        $attribute->value_id = $request->value_id;
        $attribute->save();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function removeProductVariantAttributeSubmit(Request $request)
    {
        $request->validate([
            'variant'       => 'required|string',
            'attribute_ids' => 'required|string',
        ]);

        $attributeIds = explode(',', $request->attribute_ids);

        ProductAttribute::whereIn('id', $attributeIds)
            ->where('variant_slug', $request->variant)
            ->delete();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateVariantImage(Request $request, $id)
    {
        $media = ProductMedia::where('id', $id)->first();
        if (!$media) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $variant = ProductVariant::where('slug', $media->variant_slug)->first();
        if (!$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        // ─── IMAGE UPDATE ──────────────────────────────────────────────────────────
        if ($media->file_type === 'image') {

            if ($request->hasFile('media_file')) {

                $request->validate([
                    'media_file' => 'mimes:jpg,jpeg,png,webp|max:3145728'
                ]);

                $uploadPath = public_path('assets/uploads/products/products/media/images');
                File::ensureDirectoryExists($uploadPath);

                // Delete old file
                $oldFilePath = $uploadPath . '/' . $media->file_name;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }

                $file     = $request->file('media_file');
                $mime     = $file->getMimeType();
                $ext      = strtolower($file->getClientOriginalExtension());
                $baseName = $variant->slug . $media->id . '-' . time();

                $fallbackName = $baseName . '.' . $ext;
                $processed    = false;

                /*
            |--------------------------------------------------------------------------
            | GD + WebP processing
            |--------------------------------------------------------------------------
            */
                if (function_exists('imagewebp') && function_exists('imagecreatetruecolor')) {

                    $tmp = $file->getPathname();

                    $src = match ($mime) {
                        'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($tmp) : null,
                        'image/png'  => function_exists('imagecreatefrompng')  ? @imagecreatefrompng($tmp)  : null,
                        'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmp) : null,
                        default      => null,
                    };

                    if ($src) {
                        $width  = imagesx($src);
                        $height = imagesy($src);

                        // Resize if needed
                        $maxSize = 800;
                        $scale   = min(1, $maxSize / max($width, $height));

                        if ($scale < 1) {
                            $newW = (int)($width  * $scale);
                            $newH = (int)($height * $scale);
                            $dst  = imagecreatetruecolor($newW, $newH);

                            // Preserve transparency
                            imagealphablending($dst, false);
                            imagesavealpha($dst, true);
                            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                            imagefill($dst, 0, 0, $transparent);

                            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
                            imagedestroy($src);
                            $src = $dst;
                        }

                        // WebP conversion with quality loop
                        $webpName = $baseName . '.webp';
                        $webpPath = $uploadPath . '/' . $webpName;

                        $quality = 90;
                        do {
                            imagewebp($src, $webpPath, $quality);
                            clearstatcache();

                            if (filesize($webpPath) <= 204800) {
                                $processed        = true;
                                $media->file_name = $webpName;
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
                    $media->file_name = $fallbackName;
                }
            }
        }

        // ─── VIDEO UPDATE ──────────────────────────────────────────────────────────
        if ($media->file_type === 'video') {

            if ($request->hasFile('media_file')) {

                $request->validate([
                    'media_file' => 'mimes:mp4|max:15000'
                ]);

                $uploadPath = public_path('assets/uploads/products/products/media/videos');
                File::ensureDirectoryExists($uploadPath);

                // Delete old file
                $oldFilePath = $uploadPath . '/' . $media->file_name;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }

                $file     = $request->file('media_file');
                $ext      = strtolower($file->getClientOriginalExtension());
                $baseName = 'product-media-' . $media->id . '-' . time();
                $fileName = $baseName . '.' . $ext;

                $file->move($uploadPath, $fileName);

                $media->file_name = $fileName;
            }
        }

        $media->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function editProductVariant($slug, Request $request)
    {
        $variant = ProductVariant::where('slug', $slug)->first();
        if (!$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $product = $variant->product;
        $language = $request->query('language');

        $data = [
            'variant' => $variant,
            'product' => $product,
            'language' => $language,
        ];
        return view('users.admin.products.products.variants.editProductVariant', compact('data'));
    }
    public function updateSubProductSubmit($slug, Request $request)
    {
        $variant = ProductVariant::where('slug', $slug)->first();
        if (!$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'name' => 'required|string',
            'sku' => 'nullable|string',
            'price' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'is_active' => 'required',
            'is_available' => 'required',

        ]);

        $language = $request->language;

        if ($language == 'zh') {
            $variant->name_zh = trim($request->name);
        } elseif ($language == 'fr') {
            $variant->name_fr = trim($request->name);
        } else {
            $variant->name = trim($request->name);
        }
        $variant->sku = trim($request->sku);
        $variant->price = trim($request->price);
        $variant->is_active = $request->is_active;
        $variant->is_available = $request->is_available;
        $variant->save();

        return redirect()->route('viewProductVariant', ['slug' => $variant->slug])->with('success', t('notifications.done'));
    }
    public function adminAddProductToQuote(Request $request)
    {
        $request->validate([
            'product' => 'required|string|exists:products,slug',
            'variant' => 'nullable|string|exists:product_variants,slug',
            'quantity' => 'required|integer|min:1'
        ]);

        $temporaryQuotes = TemporaryQuote::where('quote_number', $request->quote_number)->first();
        if (!$temporaryQuotes) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }



        $product = Product::where('slug', $request->product)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $variant = ProductVariant::where('slug', $request->variant)->where('product_id', $product->id)->first();
        if ($request->variant && !$variant) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        // Check if item already exists
        $alreadyExists = TemporaryQuoteItem::where('quote_number', $temporaryQuotes->quote_number)
            ->where('product_slug', $request->product)
            ->where('variant_slug', $request->variant)
            ->first();
        if ($alreadyExists) {
            return redirect()->back()->with('error', t('notifications.alreadyInQuote'));
        }

        $unitPrice = optional($variant)->price
            ?? $product->price;

        TemporaryQuoteItem::create([
            'quote_number' => $temporaryQuotes->quote_number,
            'product_slug' => $request->product,
            'variant_slug' => $request->variant,
            'quantity' => $request->quantity,
            'unit_price' => $unitPrice
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
}
