<?php

use App\Http\Controllers\Address\AddressController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Config\SettingsController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\Payment\PaymentSettingsController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\SalesMarketingController;
use Illuminate\Support\Facades\Route;


// Regular requests - no middleware
Route::get('get_sub_departments', [AddressController::class, 'fetchStates'])->name('fetchStates');
Route::get('/fetch-lgas', [AddressController::class, 'fetchLgas'])->name('fetchLgas');
Route::get('/get-subcategories/{slug}', [ProductController::class, 'getSubCategories']);
Route::get('get_attribute_values', [ProductController::class, 'fetchAttributeValue'])->name('fetchAttributeValue');
Route::get('/search/products', [FrontendController::class, 'searchSuggestions'])
    ->name('products.search');





Route::middleware(['authenticationMiddlewares', 'generalMiddlewares'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('homepage');
    });


    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('googleAuthLogin');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


    Route::get('/home', [FrontendController::class, 'homepage'])->name('homepage');
    Route::get('login', [AuthController::class, 'login'])->name('loginPage');
    Route::post('login/submit', [AuthController::class, 'authLogin'])->name('submitLoginRequest');
    Route::get('register', [AuthController::class, 'register'])->name('registrationPage');
    Route::post('create_account', [AuthController::class, 'submitUserRegistrationRequest'])->name('submitUserRegistrationRequest');
    Route::get('logout', [AuthController::class, 'AuthLogout'])->name('logoutRequest');
    Route::get('access_denied/logout', [AuthController::class, 'accessDeniedLogout'])->name('accessDeniedLogout');

    Route::get('fogort_password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('forgort_password/send_password_reset_link', [AuthController::class, 'requestPasswordResetLink'])->name('requestPasswordResetLink');
    Route::get('reset_password/{token}', [AuthController::class, 'passwordResetPage'])->name('passwordResetLink');
    Route::post('reset_password/change_password/{token}', [AuthController::class, 'submitPasswordResetRequest'])->name('submitPasswordResetRequest');

    Route::get('maintenance_mode', [AuthController::class, 'maintenanceMode'])->name('maintenanceMode');

    Route::get('verify_email', [AuthController::class, 'verifyUserEmail'])->name('verifyUserEmail');
    Route::get('verify_email/resend_otp', [AuthController::class, 'resendEmailVerificationCodeEmailtoUser'])->name('verifyUserEmailResendOtp');
    Route::post('verify_emal/submit', [AuthController::class, 'verifyUserEmailSubmit'])->name('verifyUserEmailSubmit');

    Route::get('login/authenticate', [AuthController::class, 'authenticateLogin'])->name('authenticateLogin');
    Route::post('login/authenticate/submit', [AuthController::class, 'verifyLoginOtpSubmit'])->name('verifyLoginOtpSubmit');
    Route::get('login/authenticate/resend_otp', [AuthController::class, 'resendLoginOtp'])->name('resendLoginOtp');
});

// admin middlewares
Route::middleware(['authenticationMiddlewares', 'generalMiddlewares', 'adminMiddlewares'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('adminDashboard');

    // Products
    // products
    Route::get('admin/product/products', [ProductController::class, 'products'])->name('products');
    Route::get('admin/product/products/search', [ProductController::class, 'products'])->name('productsSearch');
    Route::get('admin/product/products/create_new', [ProductController::class, 'addNewProducts'])->name('addNewProducts');
    Route::get('admin/product/products/edit/{slug}', [ProductController::class, 'editProduct'])->name('editProduct');
    Route::put('admin/product/products/update/{slug}', [ProductController::class, 'updateProductSubmit'])->name('updateProductSubmit');



    Route::post('admin/product/products/create_new/submit', [ProductController::class, 'createNewProductSubmit'])->name('createNewProductSubmit');
    Route::get('admin/product/products/view/{slug}', [ProductController::class, 'viewProduct'])->name('viewProduct');
    Route::post('admin/product/products/view/add_colour', [ProductController::class, 'addProductColourSubmit'])->name('addProductColourSubmit');
    Route::post('admin/product/products/view/remove_colour', [ProductController::class, 'removeProductColourSubmit'])->name('removeProductColourSubmit');

    Route::post('admin/product/products/view/add_attribute', [ProductController::class, 'addProductAttributeSubmit'])->name('addProductAttributeSubmit');
    Route::post('admin/product/products/view/remove_attribute/', [ProductController::class, 'removeProductAttributeSubmit'])->name('removeProductAttributeSubmit');

    Route::post('admin/product/products/view/add_media_file', [ProductController::class, 'addProductMediaSubmit'])->name('addProductMediaSubmit');
    Route::put('admin/product/products/view/media_file/update/{id}', [ProductController::class, 'updateProductMedia'])->name('updateProductMedia');
    Route::delete('admin/product/products/view/media_file/delete/{id}', [ProductController::class, 'deleteProductMedia'])->name('deleteProductMedia');



    // product variation
    Route::get('admin/product/products/variations/{slug}', [ProductController::class, 'productVariations'])->name('productVariations');
    Route::get('admin/product/products/variations/search/{slug}', [ProductController::class, 'productVariations'])->name('productVariationsSearch');
    Route::get('admin/product/products/variation/create_new/{slug}', [ProductController::class, 'addProductVariant'])->name('addProductVariant');
    Route::post('admin/product/products/variation/create_new/submit/{slug}', [ProductController::class, 'createNewSubProductSubmit'])->name('createNewSubProductSubmit');
    Route::get('admin/product/products/variation/view/{slug}', [ProductController::class, 'viewProductVariant'])->name('viewProductVariant');
    Route::post('admin/product/products/variation/colour/update', [ProductController::class, 'addProductVariantColourSubmit'])->name('addProductVariantColourSubmit');
    Route::post('admin/product/products/variation/colour/remove', [ProductController::class, 'removeProductVariantColourSubmit'])->name('removeProductVariantColourSubmit');

    Route::post('admin/product/products/variation/attribute/add_new', [ProductController::class, 'addProductVariantAttributeSubmit'])->name('addProductVariantAttributeSubmit');
    Route::post('admin/product/products/variation/attribute/remove', [ProductController::class, 'removeProductVariantAttributeSubmit'])->name('removeProductVariantAttributeSubmit');

    Route::put('admin/product/products/variation/image/update/{id}', [ProductController::class, 'updateVariantImage'])->name('updateVariantImage');

    Route::get('admin/product/products/variation/edit/{slug}', [ProductController::class, 'editProductVariant'])->name('editProductVariant');

    Route::put('admin/product/products/variation/update/submit/{slug}', [ProductController::class, 'updateSubProductSubmit'])->name('updateSubProductSubmit');


    Route::post('admin/product/products/add_to_quote/submit', [ProductController::class, 'adminAddProductToQuote'])->name('adminAddProductToQuote');







    // Brands
    Route::get('admin/product/brands', [ProductController::class, 'brands'])->name('brands');
    Route::get('admin/product/brands/search', [ProductController::class, 'brands'])->name('brandsSearch');
    Route::post('admin/product/brands/create_new', [ProductController::class, 'createNewBrandSubmit'])->name('createNewBrandSubmit');
    Route::get('admin/product/brands/view/{slug}', [ProductController::class, 'viewBrand'])->name('viewBrand');

    // Categories + sub-categories
    Route::get('admin/product/categories', [ProductController::class, 'categories'])->name('categories');
    Route::get('admin/product/categories/search', [ProductController::class, 'categories'])->name('categoriesSearch');
    Route::post('admin/product/categories/create_new', [ProductController::class, 'createNewCategorySubmit'])->name('createNewCategorySubmit');
    Route::get('admin/product/categories/view/{slug}', [ProductController::class, 'viewCategory'])->name('viewCategory');
    Route::post('admin/product/categories/view/add_new_sub/{slug}', [ProductController::class, 'createNewSubCategorySubmit'])->name('createNewSubCategorySubmit');
    Route::get('admin/product/categories/view/edit/{slug}', [ProductController::class, 'editCategory'])->name('editCategory');
    Route::put('admin/product/categories/view/update/{slug}', [ProductController::class, 'updateCategorySubmit'])->name('updateCategorySubmit');

    Route::get('admin/product/categories/sub/{slug}', [ProductController::class, 'subCategories'])->name('subCategories');
    Route::get('admin/product/categories/sub/search/{slug}', [ProductController::class, 'subCategories'])->name('subCategoriesSearch');
    Route::get('admin/product/categories/view/{category}/subcategory/{slug}', [ProductController::class, 'viewSubCategory'])->name('viewSubCategory');
    Route::get('admin/product/categories/view/subcategory/edit/{slug}', [ProductController::class, 'editSubCategory'])->name('editSubCategory');
    Route::put('admin/product/categories/view/subcategory/update/{slug}', [ProductController::class, 'updateSubCategorySubmit'])->name('updateSubCategorySubmit');





    // attributes
    Route::get('admin/product/attributes', [ProductController::class, 'attributes'])->name('attributes');
    Route::get('admin/product/attributes/search', [ProductController::class, 'attributes'])->name('attributesSearch');
    Route::post('admin/product/attributes/create/submit', [ProductController::class, 'createNewAttributeSubmit'])->name('createNewAttributeSubmit');
    Route::delete('admin/product/attributes/delete/{id}', [ProductController::class, 'deleteAttribute'])->name('deleteAttribute');
    Route::get('admin/product/attributes/edit/{id}', [ProductController::class, 'editAttribute'])->name('editAttribute');
    Route::delete('admin/product/attributes/value/delete/{id}', [ProductController::class, 'deleteAttributeValue'])->name('deleteAttributeValue');
    Route::put('admin/product/attributes/value/update/{id}', [ProductController::class, 'updateAttributeSubmit'])->name('updateAttributeSubmit');


    // sales and marketing
    Route::get('admin/sales_marketing/featured_products', [SalesMarketingController::class, 'featuredProducts'])->name('featuredProducts');
    Route::get('admin/sales_marketing/featured_products/search', [SalesMarketingController::class, 'featuredProducts'])->name('featuredProductsSearch');
    Route::post('admin/sales_marketing/featured_products/toggle-featured/{slug}', [SalesMarketingController::class, 'toggleFeatured'])->name('product.toggleFeatured');


    // quotes
    Route::get('admin/sales_marketing/quotes/requests', [SalesMarketingController::class, 'quoteRequests'])->name('quoteRequests');
    Route::get('admin/sales_marketing/quotes/requests/search', [SalesMarketingController::class, 'quoteRequests'])->name('quoteRequestsSearch');
    Route::get('admin/sales_marketing/quotes/requests/view/{slug}', [SalesMarketingController::class, 'viewQuoteRequest'])->name('viewQuote');


    Route::get('admin/sales_marketing/quotes/quotes', [SalesMarketingController::class, 'quotes'])->name('quotes');
    Route::get('admin/sales_marketing/quotes/quotes/search', [SalesMarketingController::class, 'quotes'])->name('quotesSearch');
    Route::get('admin/sales_marketing/quotes/quotes/view/{quote_number}', [SalesMarketingController::class, 'viewRealQuote'])->name('viewRealQuote');

    Route::get('admin/sales_marketing/quotes/quotes/page_1', [SalesMarketingController::class, 'createQuoteSubmitRequestNo'])->name('createQuoteSubmitRequestNo');
    Route::get('admin/sales_marketing/quotes/quotes/page_1/query', [SalesMarketingController::class, 'createQuoteSubmitRequestNo'])->name('createQuoteSubmitRequestNoFindProduct');

    Route::get('admin/sales_marketing/quotes/drafted_quotes', [SalesMarketingController::class, 'draftedQuotes'])->name('draftedQuotes');
    Route::get('admin/sales_marketing/quotes/drafted_quotes/search', [SalesMarketingController::class, 'draftedQuotes'])->name('draftedQuotesSearch');
    Route::put('admin/sales_marketing/quotes/drafted_quotes/page_1/update_item', [SalesMarketingController::class, 'adminUpdateTemporaryQuoteItem'])->name('adminUpdateTemporaryQuoteItem');
    Route::delete('admin/sales_marketing/quotes/drafted_quotes/page_1/delete_item', [SalesMarketingController::class, 'adminDeleteTemporaryQuoteItem'])->name('adminDeleteTemporaryQuoteItem');

    Route::get('admin/sales_marketing/quotes/drafted_quotes/view/{quote_number}', [SalesMarketingController::class, 'viewDraftedQuote'])->name('viewDraftedQuote');

    Route::get('admin/sales_marketing/quotes/drafted_quotes/page_2/{quote_number}', [SalesMarketingController::class, 'createQuoteTaxDiscount'])->name('createQuoteTaxDiscount');
    Route::get('admin/sales_marketing/quotes/drafted_quotes/page_2/default_discount/load/{quote_number}', [SalesMarketingController::class, 'createQuoteTaxDiscount'])->name('createQuoteLoadDefaultDiscount');
    Route::post('admin/sales_marketing/quotes/drafted_quotes/page_2/discount/add_new/{quote_number}', [SalesMarketingController::class, 'addDiscountToQuote'])->name('addDiscountToQuote');
    Route::delete('admin/sales_marketing/quotes/drafted_quotes/page_2/discount/remove', [SalesMarketingController::class, 'adminDeleteTemporaryQuoteDiscount'])->name('adminDeleteTemporaryQuoteDiscount');

    Route::get('admin/sales_marketing/quotes/drafted_quotes/page_2/default_taxes/load/{quote_number}', [SalesMarketingController::class, 'createQuoteTaxDiscount'])->name('createQuoteLoadDefaultTaxes');

    Route::post('admin/sales_marketing/quotes/drafted_quotes/page_2/tax/add_new/{quote_number}', [SalesMarketingController::class, 'addTaxToQuote'])->name('addTaxToQuote');
    Route::delete('admin/sales_marketing/quotes/drafted_quotes/page_2/tax/remove', [SalesMarketingController::class, 'adminDeleteTemporaryQuoteTax'])->name('adminDeleteTemporaryQuoteTax');


    Route::get('admin/sales_marketing/quotes/drafted_quotes/page_3/{quote_number}', [SalesMarketingController::class, 'createQuoteCustomer'])->name('createQuoteCustomer');

    Route::put('admin/sales_marketing/quotes/drafted_quotes/page_3/registered_customer/update/{quote_number}', [SalesMarketingController::class, 'updateRegisteredCustomerSubmit'])->name('updateRegisteredCustomerSubmit');
    Route::put('admin/sales_marketing/quotes/drafted_quotes/page_3/non_registered_customer/update/{quote_number}', [SalesMarketingController::class, 'updateNonRegisteredCustomerSubmit'])->name('updateNonRegisteredCustomerSubmit');


    Route::get('admin/sales_marketing/quotes/drafted_quotes/page_4/{quote_number}', [SalesMarketingController::class, 'createQuoteNotes'])->name('createQuoteNotes');
    Route::put('admin/sales_marketing/quotes/drafted_quotes/page_4/{quote_number}', [SalesMarketingController::class, 'updateQuoteNotesSubmit'])->name('updateQuoteNotesSubmit');

    Route::get('admin/sales_marketing/quotes/drafted_quotes/page_5/{quote_number}', [SalesMarketingController::class, 'createQuoteFinish'])->name('createQuoteFinish');

    Route::put('admin/sales_marketing/quotes/drafted_quotes/page_5/dates/update/{quote_number}', [SalesMarketingController::class, 'updateQuoteDatesSubmit'])->name('updateQuoteDatesSubmit');

     Route::get('admin/sales_marketing/quotes/drafted_quotes/finalize/{quote_number}', [SalesMarketingController::class, 'createQuoteFinalizeAndSend'])->name('createQuoteFinalizeAndSend');


    Route::get('admin/sales_marketing/quotes/drafted_quotes/preview/{quote_number}', [SalesMarketingController::class, 'createPreviewTemporaryQuote'])->name('createPreviewTemporaryQuote');




    Route::get('admin/sales_marketing/quotes/terms_and_conditions', [SalesMarketingController::class, 'quoteTermsAndConditions'])->name('quoteTermsAndConditions');
    Route::put('admin/sales_marketing/quotes/terms_and_conditions/update', [SalesMarketingController::class, 'updateQuoteTermsAndConditionsSubmit'])->name('updateQuoteTermsAndConditionsSubmit');








    // colors
    Route::get('admin/product/colours', [ProductController::class, 'colours'])->name('colours');
    Route::get('admin/product/colours/search', [ProductController::class, 'colours'])->name('coloursSearch');
    Route::post('admin/product/colours/create_new', [ProductController::class, 'createNewColourSubmit'])->name('createNewColourSubmit');
    Route::delete('admin/product/colours/delete/{id}', [ProductController::class, 'deleteColour'])->name('deleteColour');
    Route::get('admin/product/colours/edit/{id}', [ProductController::class, 'editColour'])->name('editColour');
    Route::put('admin/product/colours/update/{id}', [ProductController::class, 'updateColourSubmit'])->name('updateColourSubmit');





    // config
    // Address
    Route::get('admin/settings/address/countries', [AddressController::class, 'countries'])->name('countries');
    Route::get('admin/settings/address/countries/search', [AddressController::class, 'countries'])->name('countriesSearch');
    Route::post('admin/settings/address/countries/create_new', [AddressController::class, 'createNewCountrySubmit'])->name('createNewCountrySubmit');
    Route::put('admin/settings/address/countries/update/{id}', [AddressController::class, 'updateCountrySubmit'])->name('updateCountrySubmit');

    Route::get('admin/settings/address/states', [AddressController::class, 'states'])->name('states');
    Route::get('admin/settings/address/states/search', [AddressController::class, 'states'])->name('statesSearch');
    Route::post('admin/settings/address/states/create_new', [AddressController::class, 'createNewStateSubmit'])->name('createNewStateSubmit');
    Route::put('admin/settings/address/states/update/{id}', [AddressController::class, 'updateStateSubmit'])->name('updateStateSubmit');

    Route::get('admin/settings/address/lga', [AddressController::class, 'lgas'])->name('lgas');
    Route::get('admin/settings/address/lga/search', [AddressController::class, 'lgas'])->name('lgasSearch');
    Route::post('admin/settings/address/lga/create_new', [AddressController::class, 'createNewLgaSubmit'])->name('createNewLgaSubmit');
    Route::put('admin/settings/address/lga/update/{id}', [AddressController::class, 'updateLgaSubmit'])->name('updateLgaSubmit');

    // basic settings
    Route::get('admin/settings/basic', [SettingsController::class, 'basicSettings'])->name('basicSettings');
    Route::put('admin/settings/basic/app_info/update', [SettingsController::class, 'systemSettingsUpdateAppInfo'])->name('systemSettingsUpdateAppInfo');
    Route::put('admin/settings/basic/logo/update', [SettingsController::class, 'systemSettingsUpdateAppLogo'])->name('systemSettingsUpdateAppLogo');
    Route::put('admin/settings/basic/maintenance_mode/update', [SettingsController::class, 'systemSettingsUpdateMaintenanceStatus'])->name('systemSettingsUpdateMaintenanceStatus');
    Route::put('admin/settings/basic/display_product/update', [SettingsController::class, 'systemSettingsUpdatePriceDisplayStatus'])->name('systemSettingsUpdatePriceDisplayStatus');
    Route::put('admin/settings/basic/email_config/update', [SettingsController::class, 'systemSettingsUpdateEmailConfig'])->name('systemSettingsUpdateEmailConfig');
    Route::get('admin/settings/homepage/slider', [SettingsController::class, 'homePageSliderConfig'])->name('homePageSliderConfig');
    Route::get('admin/settings/homepage/slider/add_new', [SettingsController::class, 'addNewSlider'])->name('addNewSlider');
    Route::post('admin/settings/homepage/slider/add_new/submit', [SettingsController::class, 'addNewSliderSubmit'])->name('addNewSliderSubmit');
    Route::get('admin/settings/homepage/slider/edit/{id}', [SettingsController::class, 'editSlider'])->name('editSlider');
    Route::put('admin/settings/homepage/slider/update/{id}', [SettingsController::class, 'updateSliderSubmit'])->name('updateSliderSubmit');

    Route::get('admin/settings/homepage/contacts', [SettingsController::class, 'contacts'])->name('contacts');
    Route::put('admin/settings/homepage/contacts/submit', [SettingsController::class, 'updateContactDetailsSubmit'])->name('updateContactDetailsSubmit');
    Route::post('admin/settings/homepage/contacts/whatsapp_support/add_new', [SettingsController::class, 'addWhatsappNumberSubmit'])->name('addWhatsappNumberSubmit');
    Route::put('admin/settings/homepage/contacts/whatsapp_support/update/{id}', [SettingsController::class, 'updateWhatsAppNumberSubmit'])->name('updateWhatsAppNumberSubmit');


    // payment Settings
    Route::get('admin/payment/setting/payment_methods', [PaymentSettingsController::class, 'paymentMethods'])->name('paymentMethods');
    Route::put('admin/payment/setting/payment_methods/update/{slug}', [PaymentSettingsController::class, 'updatePaymentMethodSubmit'])->name('updatePaymentMethodSubmit');

    // bank accounts
    Route::get('admin/payment/setting/bank_accounts', [PaymentSettingsController::class, 'bankAccounts'])->name('bankAccounts');
    Route::post('admin/payment/setting/bank_accounts/create_new/submit', [PaymentSettingsController::class, 'createNewBankAccount'])->name('createNewBankAccount');
    Route::put('admin/payment/setting/bank_accounts/update/{id}', [PaymentSettingsController::class, 'updateBankAccountSubmit'])->name('updateBankAccountSubmit');

    // taxes
    Route::get('admin/payment/setting/taxes', [PaymentSettingsController::class, 'taxes'])->name('taxes');
    Route::post('admin/payment/setting/taxes/create_new/submit', [PaymentSettingsController::class, 'createTaxAccount'])->name('createTaxAccount');
    Route::put('admin/payment/setting/taxes/update/{id}', [PaymentSettingsController::class, 'updateTaxSubmit'])->name('updateTaxSubmit');


    // discounts
    Route::get('admin/payment/setting/discounts', [PaymentSettingsController::class, 'discounts'])->name('discounts');
    Route::post('admin/payment/setting/discounts/create_new', [PaymentSettingsController::class, 'createDiscountSubmit'])->name('createDiscountSubmit');
    Route::put('admin/payment/setting/discounts/update/{id}', [PaymentSettingsController::class, 'updateDiscountSubmit'])->name('updateDiscountSubmit');
});

// customer middlewares
Route::middleware(['authenticationMiddlewares', 'generalMiddlewares', 'customerMiddlewares'])->group(function () {
    // Route::get('customer/dashboard', [DashboardController::class, 'dashboard'])->name('customerDashboard');
    Route::get('/home_page', [FrontendController::class, 'homepage'])->name('customerHomepage');

    Route::get('customer/products/search', [FrontendController::class, 'homepage'])->name('customerSearchProduct');

    Route::get('customer/category/{slug}', [FrontendController::class, 'viewCategory'])->name('customerViewCategory');
    Route::get('customer/category/sub_category/{slug}', [FrontendController::class, 'ViewSubCategory'])->name('customerViewSubCategory');
    Route::get('customer/product/{slug}', [FrontendController::class, 'viewProduct'])->name('customerViewProduct');
    Route::post('customer/add_to_quote/', [FrontendController::class, 'AddToQuote'])->name('customerAddToQuote');
    Route::get('customer/quotes/new', [FrontendController::class, 'newQuoteList'])->name('customerNewQuoteList');
    Route::put('customer/quotes/update', [FrontendController::class, 'updateQuoteItem'])->name('customerUpdateQuoteItem');
    Route::delete('customer/quotes/delete', [FrontendController::class, 'deleteQuoteItem'])->name('customerDeleteQuoteItem');
    Route::put('customer/quotes/submit', [FrontendController::class, 'submitQuoteDetails'])->name('customerSubmitQuoteDetails');





    Route::get('customer/profile', [CustomerProfileController::class, 'customerMyProfile'])->name('customerMyProfile');
    Route::post('customer/profile/create_new_address/submit', [CustomerProfileController::class, 'customerAddNewAddressSubmit'])->name('customerAddNewAddressSubmit');
    Route::delete('customer/profile/address/delete/{id}', [CustomerProfileController::class, 'customerAddressDelete'])->name('customerAddressDelete');
    Route::put('customer/profile/address/update{id}', [CustomerProfileController::class, 'customerUpdateAddressSubmit'])->name('customerUpdateAddressSubmit');

    Route::get('customer/profile/edit', [CustomerProfileController::class, 'customerEditProfile'])->name('customerEditProfile');
    Route::put('customer/profile/update', [CustomerProfileController::class, 'userUpdateProfileSubmit'])->name('userUpdateProfileSubmit');
});


Route::middleware(['authenticationMiddlewares', 'generalMiddlewares'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('homepage');
    });

    Route::get('/home', [FrontendController::class, 'homepage'])->name('homepage');

    Route::get('products/search', [FrontendController::class, 'homepage'])->name('guestSearchProduct');

    Route::get('category/{slug}', [FrontendController::class, 'viewCategory'])->name('guestViewCategory');
    Route::get('category/sub_category/{slug}', [FrontendController::class, 'ViewSubCategory'])->name('guestViewSubCategory');
    Route::get('product/{slug}', [FrontendController::class, 'viewProduct'])->name('guestViewProduct');
    Route::post('add_to_quote/', [FrontendController::class, 'AddToQuote'])->name('guestAddToQuote');
    Route::get('quotes/new', [FrontendController::class, 'newQuoteList'])->name('guestNewQuoteList');
    Route::put('quotes/update', [FrontendController::class, 'updateQuoteItem'])->name('guestUpdateQuoteItem');
    Route::delete('quotes/delete', [FrontendController::class, 'deleteQuoteItem'])->name('guestDeleteQuoteItem');
    Route::put('quotes/submit', [FrontendController::class, 'submitQuoteDetails'])->name('guestSubmitQuoteDetails');
});
