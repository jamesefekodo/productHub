<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Models\Address\Country;
use App\Http\Controllers\Controller;
use App\Models\Address\City;
use App\Models\Address\State;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



class AddressController extends Controller
{
    public function countries(Request $request)
    {
        $searchKey = $request->input('searchKey');
        $isSearch = Route::currentRouteName() === 'countriesSearch';

        $query = Country::with(['states', 'cities']);

        if ($isSearch && $searchKey) {
            $search = trim($searchKey);

            $query->where(function ($q) use ($search) {

                // Country fields
                $q->where('name', 'LIKE', "{$search}%")
                    ->orWhere('phone_code', 'LIKE', "{$search}%")

                    // States
                    ->orWhereHas('states', function ($stateQuery) use ($search) {
                        $stateQuery->where('name', 'LIKE', "{$search}%");
                    })

                    // Cities
                    ->orWhereHas('cities', function ($cityQuery) use ($search) {
                        $cityQuery->where('name', 'LIKE', "{$search}%");
                    });
            });

            $pageTitle = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {

            $pageTitle = t('app.settings.address.countries');
            $requestType = 'all';
        }

        // IMPORTANT: cursorPaginate requires orderBy
        $countries = $query
            ->orderBy('id')
            ->cursorPaginate(20)
            ->appends([
                'searchKey' => $searchKey
            ]);

        // Optional total count (separate query)
        // $totalCountries = $isSearch && $searchKey
        //     ? $query->count()
        //     : Country::count();

        $data = [
            'searchKey' => $searchKey,
            'countries' => $countries,
            // 'totalCountries' => $totalCountries,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
        ];

        return view('users.admin.settings.address.country.countries', compact('data'));
    }

    public function createNewCountrySubmit(Request $request)
    {
        $request->validate([
            'country'    => 'required|string|max:255|unique:countries,name',
            'phone_code' => 'required|string|max:10',
        ]);

        Country::create([
            'name'       => trim($request->country),
            'phone_code' => trim($request->phone_code),
        ]);

        // On success, redirect back with flash
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateCountrySubmit($id, Request $request)
    {
        $country = Country::where('id', $id)->first();

        if (!$country) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'country'    => 'required|string|max:255|unique:countries,name,' . $country->id,
            'phone_code' => 'required|string|max:10',
            'status' => 'required',
        ]);

        $country->update([
            'name' => trim($request->country),
            'phone_code' => trim($request->phone_code),
            'status' => trim($request->status),
        ]);
        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function states(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $states = '';
        $totalStates = 0;
        $activeCountries = Country::where('status', 'active')->orderBy('name')->get();
        $allCountries = Country::orderBy('name')->get();


        $searchKey = trim($request->input('searchKey'));
        $isSearch  = Route::currentRouteName() === 'statesSearch';

        $query = State::with(['country', 'cities']);

        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey) {

                $q->where('name', 'LIKE', "{$searchKey}%")

                    ->orWhereRelation('country', function ($countryQuery) use ($searchKey) {
                        $countryQuery->where('name', 'LIKE', "{$searchKey}%")
                            ->orWhere('phone_code', 'LIKE', "{$searchKey}%");
                    })

                    ->orWhereRelation('cities', 'name', 'LIKE', "{$searchKey}%");
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {

            $pageTitle   = t('app.settings.address.states');
            $requestType = 'all';
        }

        $states = $query
            ->orderBy('id') // required for cursor pagination
            ->cursorPaginate(20)
            ->appends([
                'searchKey' => $searchKey
            ]);

        // $totalStates = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : State::count();



        $data = [
            'searchKey' => $searchKey,
            'states' => $states,
            // 'totalStates' => $totalStates,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
            'activeCountries' => $activeCountries,
            'allCountries' => $allCountries
        ];

        return view('users.admin.settings.address.states.states', compact('data'));
    }
    public function createNewStateSubmit(Request $request)
    {
        $request->validate([
            'country'    => 'required|string|exists:countries,id',
            'state' => 'required',
        ]);

        State::create([
            'country_id' => $request->country,
            'name' => trim($request->state),
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateStateSubmit($id, Request $request)
    {
        $state = State::where('id', $id)->first();

        if (!$state) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'country'    => 'required|string|exists:countries,id',
            'state' => 'required',
            'status' => 'required',
        ]);

        $state->update([
            'country_id' => $request->country,
            'name' => trim($request->state),
            'status' => trim($request->status),
        ]);
        return redirect()->back()->with('success', t('notifications.done'));
    }

    public function fetchStates(Request $request)
    {
        $countryId = $request->country_id;

        $states = State::where('country_id', $countryId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'states' => $states
        ]);
    }
    public function fetchLgas(Request $request)
    {
        $lgas = City::where('state_id', $request->state_id)->where('status', 'active')
            ->orderBy('name')->get();

        return response()->json([
            'lgas' => $lgas
        ]);
    }


    public function lgas(Request $request)
    {
        $requestType = '';
        $searchKey = '';
        $pageTitle = '';
        $lgas = '';
        $totalLgas = 0;
        $activeCountries = Country::where('status', 'active')->orderBy('name')->get();
        $allCountries = Country::orderBy('name')->get();


        $searchKey = trim($request->input('searchKey'));
        $isSearch  = Route::currentRouteName() === 'lgasSearch';

        $query = City::with(['state', 'country']);
        // adjust relations if your model names differ

        if ($isSearch && $searchKey) {

            $query->where(function ($q) use ($searchKey) {

                $q->where('name', 'LIKE', "{$searchKey}%")

                    ->orWhere('phone_code', 'LIKE', "{$searchKey}%")

                    ->orWhereRelation('state', 'name', 'LIKE', "{$searchKey}%")

                    ->orWhereRelation('country', 'name', 'LIKE', "{$searchKey}%");
            });

            $pageTitle   = t('app.searchResultFor') . ' :: ' . $searchKey;
            $requestType = 'search';
        } else {

            $pageTitle   = t('app.settings.address.lga');
            $requestType = 'all';
        }

        $lgas = $query
            ->orderBy('id') // REQUIRED for cursorPaginate
            ->cursorPaginate(20)
            ->appends([
                'searchKey' => $searchKey
            ]);

        // $totalLgas = $isSearch && $searchKey
        //     ? (clone $query)->count()
        //     : City::count();



        $data = [
            'searchKey' => $searchKey,
            'lgas' => $lgas,
            // 'totalLgas' => $totalLgas,
            'pageTitle' => $pageTitle,
            'requestType' => $requestType,
            'activeCountries' => $activeCountries,
            'allCountries' => $allCountries
        ];

        return view('users.admin.settings.address.lgas.lgas', compact('data'));
    }
    public function createNewLgaSubmit(Request $request)
    {
        $request->validate([
            'country'    => 'required|string|exists:countries,id',
            'state' => 'required|exists:states,id',
            'lga' => 'required',
        ]);



        City::create([
            'country_id' => $request->country,
            'state_id' => $request->state,
            'name' => trim($request->lga),
        ]);

        return redirect()->back()->with('success', t('notifications.done'));
    }
    public function updateLgaSubmit($id, Request $request)
    {
        $lga = City::where('id', $id)->first();

        if (!$lga) {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }

        $request->validate([
            'country'    => 'required|string|exists:countries,id',
            'state' => 'required|exists:states,id',
            'lga' => 'required',
            'status' => 'required',
        ]);

        $lga->update([
            'country_id' => $request->country,
            'state_id' => $request->state,
            'name' => trim($request->lga),
            'status' => trim($request->status),
        ]);
        return redirect()->back()->with('success', t('notifications.done'));
    }
}
