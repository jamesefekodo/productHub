<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ config('app.url') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ systemWideLogo() }}" alt="logo full" class="logo logo-lg" width="220px" />
                <img src="{{ systemLogo() }}" alt="logo short/abbr" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <li
                    class="nxl-item nxl-hasmenu {{ Str::startsWith(request()->path(), 'admin/dashboard') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">{{ t('app.dashboard.dashboard') }}</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="index.html">CRM</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="analytics.html">Analytics</a></li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-cast"></i></span>
                        <span class="nxl-mtext">Reports</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="reports-sales.html">Sales Report</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="reports-leads.html">Leads Report</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="reports-project.html">Project Report</a>
                        </li>
                        <li class="nxl-item"><a class="nxl-link" href="reports-timesheets.html">Timesheets
                                Report</a></li>
                    </ul>
                </li>

                <li
                    class="nxl-item nxl-hasmenu {{ Str::startsWith(request()->path(), 'admin/product') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-package"></i></span>
                        <span class="nxl-mtext">{{ t('app.product.products') }}</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/product/product') ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('products') }}">{{ t('app.product.products') }}</a>
                        </li>
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/product/categories') ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('categories') }}">{{ t('app.product.categories') }}</a>
                        </li>
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/product/brand') ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('brands') }}">{{ t('app.product.brands') }}</a>
                        </li>
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/product/attributes') ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('attributes') }}">{{ t('app.product.attributes') }}</a>
                        </li>
                        <li class="nxl-item"><a class="nxl-link"
                                href="{{ route('colours') }}">{{ t('app.product.colours') }}</a></li>
                    </ul>
                </li>

                <li
                    class="nxl-item nxl-hasmenu {{ Str::startsWith(request()->path(), 'admin/sales_marketing') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-trending-up"></i></span>
                        <span class="nxl-mtext">{{ t('app.sales.sales') }}/{{ t('app.sales.marketing') }}@if (submittedQuoteRequests()->count() > 0)
                                <span class="badge bg-red text-white">{{ submittedQuoteRequests()->count() }}</span>
                            @endif
                        </span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/sales_marketing/featured_products') ? 'active' : '' }}">
                            <a class="nxl-link"
                                href="{{ route('featuredProducts') }}">{{ t('app.product.featuredProducts') }}</a>
                        </li>
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/sales_marketing/quotes') ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('quoteRequests') }}">{{ t('app.product.quotes') }}
                                @if (submittedQuoteRequests()->count() > 0)
                                    <span class="badge bg-red text-white">{{ submittedQuoteRequests()->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nxl-item"><a class="nxl-link"
                                href="reports-leads.html">{{ t('app.sales.orders') }}</a></li>
                        <li class="nxl-item"><a class="nxl-link"
                                href="reports-sales.html">{{ t('app.product.reviews') }}</a></li>
                        <li class="nxl-item"><a class="nxl-link"
                                href="reports-sales.html">{{ t('app.sales.notifications') }}
                                {{-- (emails/sms/newsletters) --}}</a></li>


                    </ul>
                </li>

                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-cast"></i></span>
                        <span class="nxl-mtext">Customers</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="reports-sales.html">Customers</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="reports-leads.html">Support</a></li>
                    </ul>
                </li>

                <li class="nxl-item nxl-hasmenu {{ Str::startsWith(request()->path(), 'admin/payment') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-dollar-sign"></i></span>
                        <span class="nxl-mtext">{{ t('app.payment.payment') }}</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item {{ Str::startsWith(request()->path(), 'admin/payment/transaction') ? 'active' : '' }}"><a class="nxl-link" href="#">{{ t('app.payment.transactions') }}</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="invoice-create.html">{{ t('app.payment.invoice') }}</a>
                        </li>
                        <li class="nxl-item {{ Str::startsWith(request()->path(), 'admin/payment/setting') ? 'active' : '' }}"><a class="nxl-link"
                                href="{{ route('paymentMethods') }}">{{ t('app.settings.settings') }}</a>
                        </li>
                    </ul>
                </li>

                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-cast"></i></span>
                        <span class="nxl-mtext">Staff</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="reports-sales.html">Staff</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="reports-leads.html">Permissions</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nxl-item nxl-hasmenu {{ Str::startsWith(request()->path(), 'admin/setting') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">{{ t('app.settings.settings') }}</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/setting/basic') ? 'active' : '' }}">
                            <a class="nxl-link" href="{{ route('basicSettings') }}">{{ t('app.basic') }}</a>
                        </li>
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/settings/homepage') ? 'active' : '' }}">
                            <a class="nxl-link"
                                href="{{ route('homePageSliderConfig') }}">{{ t('app.homePage') }}</a>
                        </li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-general.html">General</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-seo.html">SEO</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-tags.html">Tags</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-email.html">Email</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-tasks.html">Tasks</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-leads.html">Leads</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-support.html">Support</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-finance.html">Finance</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-gateways.html">Payments</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-customers.html">Customers</a>
                        </li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-localization.html">Localization</a>
                        </li>
                        <li
                            class="nxl-item {{ Str::startsWith(request()->path(), 'admin/settings/address') ? 'active' : '' }}">
                            <a class="nxl-link"
                                href="{{ route('countries') }}">{{ t('app.settings.address.address') }}</a>
                        </li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-recaptcha.html">reCAPTCHA</a>
                        </li>
                        <li class="nxl-item"><a class="nxl-link" href="settings-miscellaneous.html">Miscellaneous</a>
                        </li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-power"></i></span>
                        <span class="nxl-mtext">Authentication</span><span class="nxl-arrow"><i
                                class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item nxl-hasmenu">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-mtext">Login</span><span class="nxl-arrow"><i
                                        class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item"><a class="nxl-link" href="./auth-login-cover.html">Cover</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link" href="./auth-login-minimal.html">Minimal</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-login-creative.html">Creative</a></li>
                            </ul>
                        </li>
                        <li class="nxl-item nxl-hasmenu">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-mtext">Register</span><span class="nxl-arrow"><i
                                        class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item"><a class="nxl-link" href="./auth-register-cover.html">Cover</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-register-minimal.html">Minimal</a></li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-register-creative.html">Creative</a></li>
                            </ul>
                        </li>
                        <li class="nxl-item nxl-hasmenu">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-mtext">Error-404</span><span class="nxl-arrow"><i
                                        class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item"><a class="nxl-link" href="./auth-404-cover.html">Cover</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link" href="./auth-404-minimal.html">Minimal</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link" href="./auth-404-creative.html">Creative</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nxl-item nxl-hasmenu">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-mtext">Reset Pass</span><span class="nxl-arrow"><i
                                        class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item"><a class="nxl-link" href="./auth-reset-cover.html">Cover</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link" href="./auth-reset-minimal.html">Minimal</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-reset-creative.html">Creative</a></li>
                            </ul>
                        </li>
                        <li class="nxl-item nxl-hasmenu">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-mtext">Verify OTP</span><span class="nxl-arrow"><i
                                        class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item"><a class="nxl-link" href="./auth-verify-cover.html">Cover</a>
                                </li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-verify-minimal.html">Minimal</a></li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-verify-creative.html">Creative</a></li>
                            </ul>
                        </li>
                        <li class="nxl-item nxl-hasmenu">
                            <a href="javascript:void(0);" class="nxl-link">
                                <span class="nxl-mtext">Maintenance</span><span class="nxl-arrow"><i
                                        class="feather-chevron-right"></i></span>
                            </a>
                            <ul class="nxl-submenu">
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-maintenance-cover.html">Cover</a></li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-maintenance-minimal.html">Minimal</a></li>
                                <li class="nxl-item"><a class="nxl-link"
                                        href="./auth-maintenance-creative.html">Creative</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="my-5"></div>
    </div>

</nav>
