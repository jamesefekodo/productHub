<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Mail\Quotes\QuoteMailToUser;
use App\Models\Config\BasicSystemSetting;
use App\Models\Payment\Discount;
use App\Models\Payment\Tax;
use App\Models\Products\Product;
use App\Models\Products\Quote;
use App\Models\Products\QuoteRequest;
use App\Models\Products\RealQuoteDiscount;
use App\Models\Products\RealQuoteItem;
use App\Models\Products\RealQuoteTax;
use App\Models\Products\TemporaryQuote;
use App\Models\Products\TemporaryQuoteDiscount;
use App\Models\Products\TemporaryQuoteItem;
use App\Models\Products\TemporaryQuoteTax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Mpdf\Mpdf;


class SalesMarketingController extends Controller
{
    public function featuredProducts(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $products = '';
        $totalProducts = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'featuredProductsSearch';

        $query = Product::with(['brand', 'category', 'subCategory', 'colour'])
            ->where('is_featured', true); // compulsory filter

        $productFields = [
            'name',
            'name_zh',
            'name_fr',
            'description',
            'description_zh',
            'description_fr',
            'short_description',
            'meta_title',
            'meta_title_zh',
            'meta_title_fr',
            'meta_keywords',
            'meta_description',
            'meta_description_zh',
            'meta_description_fr'
        ];

        $subCategoryFields = ['sub_category', 'sub_category_zh', 'sub_category_fr'];
        $categoryFields    = ['category', 'category_zh', 'category_fr'];
        $brandFields       = ['brand', 'description'];

        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey, $productFields, $subCategoryFields, $categoryFields, $brandFields) {

                // Search in product fields
                foreach ($productFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related subCategory
                $q->orWhereRelation('subCategory', function ($rel) use ($searchKey, $subCategoryFields) {
                    foreach ($subCategoryFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in related category
                $q->orWhereRelation('category', function ($rel) use ($searchKey, $categoryFields) {
                    foreach ($categoryFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in related brand
                $q->orWhereRelation('brand', function ($rel) use ($searchKey, $brandFields) {
                    foreach ($brandFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.featuredProducts');
            $requestType = 'all';
        }

        $products = $query
            ->orderBy('name') // required for cursor pagination
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalProducts = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Product::where('is_featured', true)->count();



        $data = [
            'searchKey' => $searchKey,
            'products' => $products,
            // 'totalProducts' => $totalProducts,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.products.featuredProducts.featuredProducts', compact('data'));
    }
    public function toggleFeatured($slug, Request $request)
    {
        $request->validate([
            'is_featured' => 'required|boolean'
        ]);

        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $product->is_featured = $request->is_featured;
        $product->save();

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function quoteRequests(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $quotes = '';
        $totalQuotes = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'quoteRequestsSearch';

        $query = QuoteRequest::with('customer')
            ->where('status', '!=', 'draft'); // compulsory filter

        $quoteFields = [
            'name',
            'username',
            'session_id',
            'email',
            'phone',
            'status'
        ];

        $customerFields = [
            'username',
            'first_name',
            'middle_name',
            'last_name',
            'email1',
            'email2',
            'phone1',
            'phone2'
        ];

        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey, $quoteFields, $customerFields) {

                // Search in QuoteRequest fields
                foreach ($quoteFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related customer
                $q->orWhereRelation('customer', function ($custQuery) use ($searchKey, $customerFields) {
                    foreach ($customerFields as $field) {
                        $custQuery->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.quoteRequests');
            $requestType = 'all';
        }

        $quotes = $query
            ->orderBy('created_at', 'desc') // required for cursor pagination
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalQuotes = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : QuoteRequest::where('status', '!=', 'draft')->count();



        $data = [
            'searchKey' => $searchKey,
            'quotes' => $quotes,
            // 'totalQuotes' => $totalQuotes,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.salesMarketing.quotes.quoteRequests', compact('data'));
    }
    public function viewQuoteRequest($slug)
    {
        $quote = QuoteRequest::where('slug', $slug)->first();
        if (!$quote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'quote' => $quote,
        ];

        return view('users.admin.salesMarketing.quotes.viewQuoteRequest', compact('data'));
    }

    public function quotes(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $quotes = '';
        $totalQuotes = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'featuredProductsSearch';

        $query = Quote::with(['theCustomer', 'quoteRequest', 'createdBy', 'lastUpdatedBy']);
        $quoteFields = [
            'request_slug',
            'quote_number',
            'address',
            'issue_date',
            'valid_until',
            'declined_at',
            'internal_note',
            'customer_note',
            'terms_and_conditions',
            'created_by',
        ];

        $customerFields = ['username', 'first_name', 'middle_name', 'last_name', 'email1', 'email2', 'phone1', 'phone2', 'gender', 'nationality'];
        $quoteRequestFields    = ['slug', 'name', 'email', 'phone', 'status'];
        $createdBy       = ['username', 'first_name', 'middle_name', 'last_name', 'email1', 'email2', 'phone1', 'phone2', 'gender', 'nationality'];
        $lastUpdatedByFields       = ['username', 'first_name', 'middle_name', 'last_name', 'email1', 'email2', 'phone1', 'phone2', 'gender', 'nationality'];


        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey, $quoteFields, $customerFields, $quoteRequestFields, $createdBy, $lastUpdatedByFields) {

                // Search in quote fields
                foreach ($quoteFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related items
                $q->orWhereRelation('theCustomer', function ($rel) use ($searchKey, $customerFields) {
                    foreach ($customerFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in related quote request
                $q->orWhereRelation('quoteRequest', function ($rel) use ($searchKey, $quoteRequestFields) {
                    foreach ($quoteRequestFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in creator
                $q->orWhereRelation('createdBy', function ($rel) use ($searchKey, $createdBy) {
                    foreach ($createdBy as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in updator
                $q->orWhereRelation('lastUpdatedBy', function ($rel) use ($searchKey, $lastUpdatedByFields) {
                    foreach ($lastUpdatedByFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.quotes');
            $requestType = 'all';
        }

        $quotes = $query
            ->orderBy('updated_at', 'desc')
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalQuotes = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Product::where('is_featured', true)->count();



        $data = [
            'searchKey' => $searchKey,
            'quotes' => $quotes,
            // 'totalQuotes' => $totalQuotes,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.salesMarketing.quotes.quotes', compact('data'));
    }
    public function createQuoteSubmitRequestNo(Request $request)
    {
        $currentSection = 1;
        $quoteRequest = null;
        $existingTemporary = null;
        $products = '';
        $totalProducts = 0;
        $searchKey = $request->input('searchKey');
        $requestType = 'regular';

        // Generate unique quote number
        do {
            $quoteNumber = 'QUOTE-' . now()->format('Ym') . '-' . random_int(100000, 999999);
        } while (
            TemporaryQuote::where('quote_number', $quoteNumber)->exists() ||
            Quote::where('quote_number', $quoteNumber)->exists()
        );


        if (
            (Route::currentRouteName() === 'createQuoteSubmitRequestNo' &&
                filled($request->request_slug) || Route::currentRouteName() == 'createQuoteSubmitRequestNoFindProduct'
            )
        ) {
            $request->validate([
                'request_slug' => 'required|exists:quote_requests,slug',
            ]);

            $quoteRequest = QuoteRequest::where('slug', $request->request_slug)->first();

            $existingTemporary = null;

            $existingTemporary = TemporaryQuote::where('request_slug', $quoteRequest->slug)
                ->first();


            if (!$existingTemporary) {
                $existingTemporary = new TemporaryQuote();
                $existingTemporary->request_slug = $quoteRequest->slug ?? null;
                $existingTemporary->quote_number = $quoteNumber;
                if ($quoteRequest->customer) {
                    $existingTemporary->customer = $quoteRequest->customer->username;
                } else {
                    $existingTemporary->customer_name = $quoteRequest->name;
                    $existingTemporary->customer_email = $quoteRequest->email;
                    $existingTemporary->customer_phone = $quoteRequest->phone;
                    $existingTemporary->customer_address = $quoteRequest->address;
                }
                $existingTemporary->created_by = Auth::user()->username;
                $existingTemporary->last_update_by = Auth::user()->username;
                $existingTemporary->save();

                if ($existingTemporary && $existingTemporary->quoteRequest && $existingTemporary->quoteRequest->items->isNotEmpty()) {
                    foreach ($existingTemporary->quoteRequest->items as $requestItem) {

                        $unitPrice = optional($requestItem->variant)->price
                            ?? $requestItem->product->price;


                        TemporaryQuoteItem::create([
                            'quote_number' => $quoteNumber,
                            'product_slug' => $requestItem->product_slug,
                            'variant_slug' => $requestItem->variant_slug,
                            'quantity' => $requestItem->quantity,
                            'unit_price'   => $unitPrice,

                        ]);
                    }
                }
            }
            $requestType = 'all';
        }

        if (
            Route::currentRouteName() === 'createQuoteSubmitRequestNo' &&
            !$request->filled('request_slug')
        ) {
            $existingTemporary = new TemporaryQuote();
            $existingTemporary->quote_number = $quoteNumber;
            $existingTemporary->save();
        }

        if (Route::currentRouteName() == 'createQuoteSubmitRequestNoFindProduct') {
            // find product

            $searchKey  = trim($request->input('searchKey'));
            $isSearch   = in_array(Route::currentRouteName(), ['createQuoteSubmitRequestNoFindProduct']);

            $query = Product::with(['brand', 'category', 'subCategory', 'colour'])
                ->where('is_active', true); // compulsory filter

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

                $query->where(function ($q) use ($searchKey, $productFields) {
                    foreach ($productFields as $field) {
                        $q->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                $requestType = 'search';
            }
            // Total matching products
            $totalProducts = $isSearch && $searchKey
                ? (clone $query)->count()
                : Product::where('is_active', true)->count();

            // Cursor-paginated products
            $products = $query
                ->orderBy('name') // required for cursor pagination
                ->cursorPaginate(20)
                ->appends(['searchKey' => $searchKey]);
        }




        $data = [
            'quoteRequest' => $quoteRequest,
            'existingTemporaryQuote' => $existingTemporary,
            'products' => $products,
            'totalProducts' => $totalProducts,
            'searchKey' => $searchKey,
            'requestType' => $requestType,
            'request_slug' => $request->request_slug,
            'currentSection' => $currentSection,
        ];
        return view('users.admin.salesMarketing.quotes.createNewQuote.page1', compact('data'));
    }
    public function draftedQuotes(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $quotes = '';
        $totalQuotes = 0;


        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = Route::currentRouteName() === 'draftedQuotesSearch';

        $query = TemporaryQuote::with(['theCustomer', 'quoteRequest', 'createdBy', 'lastUpdatedBy']);
        $quoteFields = [
            'request_slug',
            'quote_number',
            'address',
            'issue_date',
            'valid_until',
            'declined_at',
            'internal_note',
            'customer_note',
            'terms_and_conditions',
            'created_by',
        ];

        $customerFields = ['username', 'first_name', 'middle_name', 'last_name', 'email1', 'email2', 'phone1', 'phone2', 'gender', 'nationality'];
        $quoteRequestFields    = ['slug', 'name', 'email', 'phone', 'status'];
        $createdBy       = ['username', 'first_name', 'middle_name', 'last_name', 'email1', 'email2', 'phone1', 'phone2', 'gender', 'nationality'];
        $lastUpdatedByFields       = ['username', 'first_name', 'middle_name', 'last_name', 'email1', 'email2', 'phone1', 'phone2', 'gender', 'nationality'];


        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey, $quoteFields, $customerFields, $quoteRequestFields, $createdBy, $lastUpdatedByFields) {

                // Search in quote fields
                foreach ($quoteFields as $field) {
                    $q->orWhere($field, 'LIKE', "{$searchKey}%");
                }

                // Search in related items
                $q->orWhereRelation('theCustomer', function ($rel) use ($searchKey, $customerFields) {
                    foreach ($customerFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in related quote request
                $q->orWhereRelation('quoteRequest', function ($rel) use ($searchKey, $quoteRequestFields) {
                    foreach ($quoteRequestFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in creator
                $q->orWhereRelation('createdBy', function ($rel) use ($searchKey, $createdBy) {
                    foreach ($createdBy as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });

                // Search in updator
                $q->orWhereRelation('lastUpdatedBy', function ($rel) use ($searchKey, $lastUpdatedByFields) {
                    foreach ($lastUpdatedByFields as $field) {
                        $rel->orWhere($field, 'LIKE', "{$searchKey}%");
                    }
                });
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {
            $pageTitle   = t('app.product.draftedQuotes');
            $requestType = 'all';
        }

        $quotes = $query
            ->orderBy('updated_at', 'desc')
            ->cursorPaginate(20)
            ->appends(['searchKey' => $searchKey]);

        // $totalQuotes = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : Product::where('is_featured', true)->count();



        $data = [
            'searchKey' => $searchKey,
            'quotes' => $quotes,
            // 'totalQuotes' => $totalQuotes,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.salesMarketing.quotes.draftedQuotes', compact('data'));
    }
    public function viewDraftedQuote($quote_number)
    {
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $quoteRequest = QuoteRequest::where('slug', $temporaryQuote->request_slug)->first();

        if ($quoteRequest) {
            $quoteRequestSlug = $quoteRequest->slug;
        } else {
            $quoteRequestSlug = null;
        }

        $products = '';
        $totalProducts = 0;
        $searchKey = null;
        $requestType = 'regular';
        $currentSection = 1;


        $data = [
            'quoteRequest' => $quoteRequest,
            'existingTemporaryQuote' => $temporaryQuote,
            'products' => $products,
            'totalProducts' => $totalProducts,
            'searchKey' => $searchKey,
            'requestType' => $requestType,
            'request_slug' => $quoteRequestSlug,
            'currentSection' => $currentSection,
        ];
        return view('users.admin.salesMarketing.quotes.createNewQuote.page1', compact('data'));
    }
    public function adminDeleteTemporaryQuoteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:quote_items,id',
        ]);


        $item = TemporaryQuoteItem::where('id', $request->item_id);
        if (!$item) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }


        $item->quote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );

        $item->delete();



        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function adminUpdateTemporaryQuoteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:temporary_quote_items,id',
            'quote_number' => 'required|exists:temporary_quotes,quote_number',
            'quantity' => 'required',
            'unit_price' => 'required',
        ]);
        $item = TemporaryQuoteItem::where('id', $request->item_id)->where('quote_number', $request->quote_number)->first();

        if (!$item) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $item->quote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );

        $item->update(
            [
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
            ]
        );
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function createQuoteTaxDiscount($quote_number)
    {
        $currentSection = 2;
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }



        $activeTaxes = Tax::where('is_active', true)->get();
        $allTaxes = Tax::get();
        $activeDiscounts = Discount::where('is_active', true)->get();
        $allDiscounts = Discount::get();

        if (request()->routeIs('createQuoteLoadDefaultDiscount')) {

            $defaultDiscounts = Discount::where('is_active', true)
                ->where('is_default', true)
                ->pluck('id');

            $existingDiscounts = TemporaryQuoteDiscount::where('quote_number', $temporaryQuote->quote_number)
                ->pluck('discount_id')
                ->toArray();

            $data = [];

            foreach ($defaultDiscounts as $discountId) {
                if (!in_array($discountId, $existingDiscounts)) {
                    $data[] = [
                        'quote_number' => $temporaryQuote->quote_number,
                        'discount_id'  => $discountId,
                    ];
                }
            }

            if (!empty($data)) {
                TemporaryQuoteDiscount::insert($data);
            }

            $temporaryQuote->update(
                [
                    'last_update_by' => Auth::user()->username,
                ]
            );

            return redirect()->back()->with('success', t('notifications.done'));
        }

        if (request()->routeIs('createQuoteLoadDefaultTaxes')) {

            $defaultTaxes = Tax::where('is_active', true)
                ->where('is_default', true)
                ->pluck('id');

            $existingTaxes = TemporaryQuoteTax::where('quote_number', $temporaryQuote->quote_number)
                ->pluck('tax_id')
                ->toArray();

            $data = [];

            foreach ($defaultTaxes as $taxId) {

                if (!in_array($taxId, $existingTaxes)) {
                    $data[] = [
                        'quote_number' => $temporaryQuote->quote_number,
                        'tax_id' => $taxId,
                    ];
                }
            }

            if (!empty($data)) {
                TemporaryQuoteTax::insert($data);
            }

            $temporaryQuote->update(
                [
                    'last_update_by' => Auth::user()->username,
                ]
            );

            return redirect()->back()->with('success', t('notifications.done'));
        }

        $data = [
            'existingTemporaryQuote' => $temporaryQuote,
            'activeTaxes' => $activeTaxes,
            'allTaxes' => $allTaxes,
            'activeDiscounts' => $activeDiscounts,
            'allDiscounts' => $allDiscounts,
            'currentSection' => $currentSection,
        ];

        return view('users.admin.salesMarketing.quotes.createNewQuote.page2', compact('data'));
    }

    public function addDiscountToQuote(Request $request, $quote_number)
    {
        $request->validate([
            'discount' => 'required|exists:discounts,id',
        ]);
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        // check if it already exists
        $alreadyExists = TemporaryQuoteDiscount::where('quote_number', $quote_number)->where('discount_id', $request->discount)->first();
        if ($alreadyExists) {
            return redirect()->back()->with('error', t('notifications.alreadyExists'));
        }

        $discount = new TemporaryQuoteDiscount();
        $discount->quote_number = $quote_number;
        $discount->discount_id = $request->discount;
        $discount->save();

        $temporaryQuote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function adminDeleteTemporaryQuoteDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required|exists:temporary_quote_discounts,id',
            'quote_number' => 'required:exists:temporary_quote_discounts,quote_number'
        ]);
        $discount = TemporaryQuoteDiscount::where('quote_number', $request->quote_number)->where('id', $request->discount)->first();
        if (!$discount) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $discount->quote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );
        $discount->delete();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function addTaxToQuote(Request $request, $quote_number)
    {
        $request->validate([
            'tax' => 'required|exists:taxes,id',
        ]);
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        // check if it already exists
        $alreadyExists = TemporaryQuoteTax::where('quote_number', $quote_number)->where('tax_id', $request->tax)->first();
        if ($alreadyExists) {
            return redirect()->back()->with('error', t('notifications.alreadyExists'));
        }

        $discount = new TemporaryQuoteTax();
        $discount->quote_number = $quote_number;
        $discount->tax_id = $request->tax;
        $discount->save();

        $temporaryQuote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function adminDeleteTemporaryQuoteTax(Request $request)
    {
        $request->validate([
            'tax' => 'required|exists:temporary_quote_taxes,id',
            'quote_number' => 'required:exists:temporary_quote_taxes,quote_number'

        ]);
        $tax = TemporaryQuoteTax::where('quote_number', $request->quote_number)->where('id', $request->tax)->first();
        if (!$tax) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $tax->quote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );

        $tax->delete();
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function createQuoteCustomer($quote_number)
    {
        $currentSection = 3;
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'currentSection' => $currentSection,
            'existingTemporaryQuote' => $temporaryQuote,
        ];

        return view('users.admin.salesMarketing.quotes.createNewQuote.page3', compact('data'));
    }
    public function updateRegisteredCustomerSubmit($quote_number, Request $request)
    {
        $request->validate([
            'customer' => 'nullable|exists:users,username',
        ]);
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $temporaryQuote->update(
            [
                'customer' => $request->customer,
            ]
        );

        $temporaryQuote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateNonRegisteredCustomerSubmit($quote_number, Request $request)
    {
        $request->validate([
            'customer' => 'nullable|exists:users,username',
        ]);
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $temporaryQuote->update(
            [
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
            ],
        );

        $temporaryQuote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function createQuoteNotes($quote_number)
    {
        $currentSection = 4;
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $quoteTAndCTemplate = null;
        $basicSystemSettings = BasicSystemSetting::first();
        if ($basicSystemSettings) {
            $quoteTAndCTemplate = $basicSystemSettings->quote_t_and_c_template;
        }

        $data = [
            'currentSection' => $currentSection,
            'existingTemporaryQuote' => $temporaryQuote,
            'quoteTAndCTemplate' => $quoteTAndCTemplate,
        ];

        return view('users.admin.salesMarketing.quotes.createNewQuote.page4', compact('data'));
    }
    public function updateQuoteNotesSubmit($quote_number, Request $request)
    {
        $request->validate([
            'terms_and_conditions' => 'required',
            'customer_note' => 'nullable',
            'internal_note' => 'nullable',
        ]);
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $temporaryQuote->update(
            [
                'terms_and_conditions' => $request->terms_and_conditions,
                'customer_note' => $request->customer_note,
                'internal_note' => $request->internal_note,
            ]
        );

        $temporaryQuote->update(
            [
                'last_update_by' => Auth::user()->username,
            ]
        );

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function quoteTermsAndConditions()
    {
        $basicSystemSettings = BasicSystemSetting::first();
        if (!$basicSystemSettings) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $quoteTAndCTemplate = $basicSystemSettings->quote_t_and_c_template;
        $data = [
            'quoteTAndCTemplate' => $quoteTAndCTemplate,
        ];

        return view('users.admin.salesMarketing.quotes.termsAndConditionsTemplate', compact('data'));
    }
    public function updateQuoteTermsAndConditionsSubmit(Request $request)
    {
        $request->validate([
            'quote_t_and_c_template' => 'required',
        ]);
        $basicSystemSettings = BasicSystemSetting::first();
        if (!$basicSystemSettings) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $basicSystemSettings->update([
            'quote_t_and_c_template' => $request->quote_t_and_c_template,
        ]);
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function createQuoteFinish($quote_number)
    {
        $currentSection = 5;
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'currentSection' => $currentSection,
            'existingTemporaryQuote' => $temporaryQuote,
        ];

        return view('users.admin.salesMarketing.quotes.createNewQuote.page5', compact('data'));
    }
    public function updateQuoteDatesSubmit($quote_number, Request $request)
    {
        $request->validate([
            'issue_date'   => 'required|date',
            'valid_until'  => 'required|date|after:issue_date',
        ]);

        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $temporaryQuote->update([
            'issue_date' => $request->issue_date,
            'valid_until' => $request->valid_until,
        ]);
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function createPreviewTemporaryQuote($quote_number)
    {
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $data = [
            'temporaryQuote' => $temporaryQuote,
            'quote' => $temporaryQuote,
        ];

        // Create mPDF instance with default settings

        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }


        $mpdf = new Mpdf([
            'tempDir'           => $tempDir,
            'default_font_size' => 13, // Set default font size
            'default_font' => 'Arial', // Set default font
            'margin_left' => 5,        // Set left margin
            'margin_right' => 5,       // Set right margin
            'margin_top' => 10,         // Set top margin
            'margin_bottom' => 15,     // Set bottom margin
            'format' => 'A4',          // Set default paper size (A4 is the default)
            'autoLangToFont' => true,
            'autoScriptToLang' => true,
        ]);

        // Set footer with page numbers
        // $mpdf->setFooter('Page {PAGENO} of {nb}');
        $mpdf->setFooter('Page {PAGENO} of {nb} >> Quote Number: ' . ($temporaryQuote->quote_number ?? 'N/A'));
        // $mpdf->showImageErrors = true;
        $mpdf->curlAllowUnsafeSslRequests = true;






        // Load the view with data
        $html = view('utilities.printFiles.quotes.printQuote', compact('data'))->render();

        // resources/views/utilities/printFiles/quotes/printQuote.blade.php

        // Add a new page
        $mpdf->AddPage();

        // Write the HTML content to the PDF
        $mpdf->WriteHTML($html);


        // Set the name of the PDF
        $fileName = 'Quote_' . $temporaryQuote->quote_number . date('Y-m-d_H-i-s') . '.pdf'; // Name format with date and time

        // Output the PDF with the specified name (inline display or download)
        $mpdf->Output($fileName, 'I'); // 'I' for inline, 'D' for download
    }
    public function createQuoteFinalizeAndSend($quote_number)
    {
        $temporaryQuote = TemporaryQuote::where('quote_number', $quote_number)->first();
        if (!$temporaryQuote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        // check and reconfirm parameters
        $productRequirements = false;
        $taxAndDiscountRequirements = true;
        $customerRequirements = false;
        $notesRequirements = false;
        $datesRequirements = false;

        if ($temporaryQuote->items->isEmpty()) {
            return redirect()->back()->with('error', t('notifications.returnEmptyQuote'));
        } else {
            $productRequirements = true;
        }

        $customerName  = optional($temporaryQuote->theCustomer)->first_name ?? ($temporaryQuote->customer_name ?? '');
        $customerEmail = optional($temporaryQuote->theCustomer)->email1 ?? ($temporaryQuote->customer_email ?? '');
        $customerPhone = optional($temporaryQuote->theCustomer)->phone1 ?? ($temporaryQuote->customer_phone ?? '');

        if (!$customerName || !$customerEmail || !$customerPhone) {
            return redirect()->back()->with('error', t('notifications.returnNoClientQuote'));
        } else {
            $customerRequirements = true;
        }

        if (empty($temporaryQuote->terms_and_conditions)) {
            return redirect()->back()->with('error', t('notifications.returnNoNote'));
        } else {
            $notesRequirements = true;
        }

        if (empty($temporaryQuote->issue_date) || empty($temporaryQuote->valid_until)) {
            return redirect()->back()->with('error', t('notifications.returnNoDates'));
        } else {
            $datesRequirements = true;
        }

        $temporaryQuote->update([
            'last_update_by' => Auth::user()->username,
            'locked' => true,
            'status' => 'quoted',
            'sent_at' => Carbon::now(),
        ]);


        // create real quote from temporary
        $realQuote = $temporaryQuote->replicate();
        $realQuote->setTable('quotes'); // only needed if model uses a different table
        $realQuote->save();


        // move items
        if ($temporaryQuote->items->isNotEmpty()) {
            foreach ($temporaryQuote->items as $item) {
                $realItem = new RealQuoteItem();
                $realItem->fill($item->toArray());
                $realItem->save();

                $item->delete();
            }
        }


        // move discounts
        if ($temporaryQuote->discounts->isNotEmpty()) {
            foreach ($temporaryQuote->discounts as $discount) {

                $realDiscount = new RealQuoteDiscount();
                $realDiscount->fill($discount->toArray());
                $realDiscount->save();

                $discount->delete();
            }
        }


        // move taxes
        if ($temporaryQuote->taxes->isNotEmpty()) {
            foreach ($temporaryQuote->taxes as $tax) {

                $realTax = new RealQuoteTax();
                $realTax->fill($tax->toArray());
                $realTax->save();

                $tax->delete();
            }
        }


        // delete temporary quote
        $temporaryQuote->delete();


        // update quote request
        $quoteRequest = $realQuote->quoteRequest;
        if ($quoteRequest) {
            $quoteRequest->update([
                'status' => 'quoted'
            ]);
        }

        $locale = $userProfile->locale ?? ($admin->preferred_language ?? 'en');

        Mail::to($customerEmail)
            ->locale($locale)
            ->send(new QuoteMailToUser($realQuote));

        return redirect()->route('quotes')->with('success', t('notifications.done'));
    }
    public function viewRealQuote($quote_number)
    {
        $quote = Quote::where('quote_number', $quote_number)->first();
        if (!$quote) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $data =[
            'quote' => $quote,
        ];
        return view('users.admin.salesMarketing.quotes.viewQuote', compact('data'));
    }
}
