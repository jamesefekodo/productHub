<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Quotes\NewQuoteMailToAdmin;
use App\Mail\Quotes\NewQuoteMailToCustomer;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Products\QuoteItem;
use App\Models\Products\QuoteRequest;
use App\Models\Products\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


class FrontendController extends Controller
{
    public function checkIfIsAdmin()
    {
        if (Auth::check()) {
            $userType = getUserType();

            if (in_array($userType, ['root', 'admin', 'staff'])) {
                return redirect()->route('adminDashboard');
            }
            // if ($userType === 'customer') {
            //     // return redirect()->route('customerDashboard');
            //     return redirect()->route('customerHomepage');
            // }
        }

        return null; // No redirection, continue execution
    }
    public function homepage(Request $request)
    {
        $redirect = $this->checkIfIsAdmin();
        if ($redirect) {
            return $redirect;
        }

        $products = '';
        $totalProducts = 0;
        $searchKey = $request->input('searchKey');
        $requestType = 'regular';

        $searchKey  = trim($request->input('searchKey'));
        $isSearch   = in_array(Route::currentRouteName(), ['guestSearchProduct', 'customerSearchProduct']);

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
        } else {
            $requestType = 'all';
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

        $data = [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'searchKey' => $searchKey,
            'requestType' => $requestType,
        ];

        return view('frontend.homepage', compact('data'));
    }
    public function viewCategory($slug)
    {
        $redirect = $this->checkIfIsAdmin();
        if ($redirect) {
            return $redirect;
        }
        $category = Category::where('slug', $slug)->where('is_active', true)->first();
        if (!$category) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'category' => $category,
        ];

        return view('users.customer.products.viewCategory', compact('data'));
    }
    public function ViewSubCategory($slug)
    {
        $redirect = $this->checkIfIsAdmin();
        if ($redirect) {
            return $redirect;
        }
        $subCategory = SubCategory::where('slug', $slug)->where('is_active', true)->first();
        if (!$subCategory) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'subCategory' => $subCategory,
        ];

        return view('users.customer.products.viewSubCategory', compact('data'));
    }
    public function viewProduct($slug)
    {
        $redirect = $this->checkIfIsAdmin();
        if ($redirect) {
            return $redirect;
        }
        $product = Product::where('slug', $slug)->where('is_active', true)->first();
        if (!$product) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $data = [
            'product' => $product,
        ];
        return view('users.customer.products.viewProduct', compact('data'));
    }
    public function AddToQuote(Request $request)
    {
        $request->validate([
            'product' => 'required|string|exists:products,slug',
            'variant' => 'nullable|string|exists:product_variants,slug',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();

        //  Get Draft Quote
        if ($user) {
            $draftedRequest = QuoteRequest::where('username', $user->username)
                ->where('status', 'draft')
                ->first();
        } else {
            $draftedRequest = QuoteRequest::where('session_id', currentSession()->id)
                ->where('status', 'draft')
                ->first();
        }

        //  If no draft, create one
        if (!$draftedRequest) {

            do {
                $requestSlug = 'quote-req-' . now()->format('Ym') . '-' . random_int(1000000000, 9999999999);
            } while (QuoteRequest::where('slug', $requestSlug)->exists());


            $draftedRequest = new QuoteRequest();
            $draftedRequest->slug = $requestSlug;
            $draftedRequest->status = 'draft';

            if ($user) {
                $draftedRequest->username = $user->username;
            } else {
                $draftedRequest->session_id = currentSession()->id;
            }

            $draftedRequest->save();
        }

        // 🔹 Check if item already exists
        $existingItem = QuoteItem::where('quote_request_slug', $draftedRequest->slug)
            ->where('product_slug', $request->product)
            ->where('variant_slug', $request->variant)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $request->quantity);
        } else {
            $item = new QuoteItem();
            $item->quote_request_slug = $draftedRequest->slug;
            $item->product_slug = $request->product;
            $item->variant_slug = $request->variant;
            $item->quantity = $request->quantity;
            $item->save();
        }

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function newQuoteList()
    {
        $user = Auth::user();
        if ($user) {
            $draftedRequest = QuoteRequest::where('username', $user->username)
                ->where('status', 'draft')
                ->first();
        } else {
            $draftedRequest = QuoteRequest::where('session_id', currentSession()->id)
                ->where('status', 'draft')
                ->first();
        }


        if (!$draftedRequest) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
        $data = [
            'draftedRequest' => $draftedRequest,
            'user' => $user,
        ];
        return view('users.customer.products.newQuoteRequest', compact('data'));
    }
    public function updateQuoteItem(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:quote_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = QuoteItem::where('id', $request->item_id);
        if (!$item) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $item->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function deleteQuoteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:quote_items,id',
        ]);

        $item = QuoteItem::where('id', $request->item_id);
        if (!$item) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $item->delete();

        // #########################

        $user = Auth::user();
        if ($user) {
            $draftedRequest = QuoteRequest::where('username', $user->username)
                ->where('status', 'draft')
                ->first();
        } else {
            $draftedRequest = QuoteRequest::where('session_id', currentSession()->id)
                ->where('status', 'draft')
                ->first();
        }

        if ($draftedRequest && $draftedRequest->items->isEmpty()) {
            $draftedRequest->delete();
        }


        // refetch
        if ($user) {
            $draftedRequest = QuoteRequest::where('username', $user->username)
                ->where('status', 'draft')
                ->first();
        } else {
            $draftedRequest = QuoteRequest::where('session_id', currentSession()->id)
                ->where('status', 'draft')
                ->first();
        }
        if (!$draftedRequest) {
            return redirect()->route('homepage')->with('success', t('notifications.done'));
        } else {
            return redirect()->back()->with('success', t('notifications.done'));
        }
    }
    public function submitQuoteDetails(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if (Auth::user()) {
            $request->validate([
                'address' => 'nullable',

            ]);
        }




        $user = Auth::user();

        if ($user) {
            $quoteRequest = QuoteRequest::where('username', $user->username)
                ->where('status', 'draft')
                ->first();
        } else {
            $quoteRequest = QuoteRequest::where('session_id', currentSession()->id)
                ->where('status', 'draft')
                ->first();
        }

        if (!$quoteRequest) {
            return redirect()->back()
                ->with('error', t('notifications.noRecordsFound'));
        }

        // Update status
        $quoteRequest->update([
            'status' => 'submitted',
            'submitted_at' => Carbon::now(),
        ]);


        if ($user) {
            $quoteRequest->update([
                'username' => $user->username,
            ]);
        } else {
            $quoteRequest->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }

        $submittedRequest = $quoteRequest->fresh();
        // fresh() reloads from DB (optional but clean)

        // Admin emails
        $admins = User::whereIn('account_type', [0, 1, 2])
            ->whereNotNull('email1')
            ->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email1)
                ->locale($admin->preferred_language ?? 'en')
                ->send(new NewQuoteMailToAdmin($submittedRequest));
        }


        // Mail to customer
        $customerEmail = $submittedRequest->email1
            ?? $submittedRequest->email
            ?? null;

        Mail::to($customerEmail)
            ->send(new NewQuoteMailToCustomer($submittedRequest));
        // try {
        //     if ($customerEmail) {
        //         Mail::to($customerEmail)
        //             ->send(new NewQuoteMailToCustomer($submittedRequest));
        //     }
        // } catch (\Exception $e) {
        //     Log::error('Quote customer mail failed: ' . $e->getMessage());
        // }

        return redirect()->route('homepage')->with('success', t('notifications.done'));
    }
    public function searchSuggestions(Request $request)
    {
        $query = $request->q;

        if (!$query) {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('name_zh', 'LIKE', "%{$query}%")
                    ->orWhere('name_fr', 'LIKE', "%{$query}%");
            })
            ->take(5)
            ->get(['name', 'name_zh', 'name_fr', 'slug']);

        return response()->json($products);
    }
}
