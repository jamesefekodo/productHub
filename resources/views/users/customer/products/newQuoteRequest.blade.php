@extends('layouts.userLayout')
@section('title', t('app.product.draftedQuotes'))
@section('content')

    <div class="desktop-nav-bg"></div>
    <div class="main-content-wrapper">

        <section class="section">
            {{-- @php
                $locale = currentLocale();
                $field = 'category_' . $locale;

                $catName =
                    isset($data['category']->$field) && $data['category']->$field
                        ? $data['category']->$field
                        : $data['category']->category;
            @endphp --}}


            <div class="my-page-header">{{ t('app.product.draftedQuotes') }}</div>

            <div class="my-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-muted small">#</th>
                                        <th scope="row">{{ t('app.thumbnail') }}</th>
                                        <th scope="row">{{ t('app.product.product') }}</th>
                                        <th scope="row">{{ t('app.product.sku') }}</th>
                                        <th scope="row">{{ t('app.product.quantity') }}</th>
                                        <th scope="row" class="">{{ t('app.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data['draftedRequest']->items->isNotEmpty())
                                        @foreach ($data['draftedRequest']->items as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                @if ($item->variant)
                                                    <td>
                                                        <img src="{{ optional($item->variant->mediaFile)->file_name
                                                            ? asset('assets/uploads/products/products/media/images/' . $item->variant->mediaFile->file_name)
                                                            : asset('system/images/icon_placeholder.png') }}"
                                                            alt="{{ $item->variant->name }}" width="90">
                                                    </td>
                                                    <td>
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $variantName = !empty($item->variant->$field)
                                                                ? $item->variant->$field
                                                                : $item->variant->name;
                                                        @endphp
                                                        {{ $variantName }}
                                                        <a href="{{ route(routePrefix() . 'ViewProduct', ['slug' => $item->variant->product->slug]) }}"
                                                            target="_blank" class="badge bg-primary text-white">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $item->variant->sku }}
                                                    </td>
                                                @else
                                                    <td>
                                                        @php
                                                            $thumbnailImage = asset(
                                                                'system/images/icon_placeholder.png',
                                                            );

                                                            if (!$item->product->mediaFiles->isEmpty()) {
                                                                $primaryImage = $item->product->mediaFiles
                                                                    ->where('is_primary', true)
                                                                    ->first();

                                                                if ($primaryImage) {
                                                                    $thumbnailImage = asset(
                                                                        'assets/uploads/products/products/media/images/' .
                                                                            $primaryImage->file_name,
                                                                    );
                                                                }
                                                            }
                                                        @endphp

                                                        <img src="{{ $thumbnailImage }}" alt="{{ $item->product->name }}"
                                                            width="90px">
                                                    </td>
                                                    <td>
                                                        @php
                                                            $locale = currentLocale();
                                                            $field = 'name_' . $locale;
                                                            $productName = !empty($item->product->$field)
                                                                ? $item->product->$field
                                                                : $item->product->name;
                                                        @endphp

                                                        {{ $productName }}
                                                        <a href="{{ route(routePrefix() . 'ViewProduct', ['slug' => $item->product->slug]) }}"
                                                            target="_blank" class="badge bg-primary text-white"> <i
                                                                class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $item->product->sku }}
                                                    </td>
                                                @endif
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-primary">
                                                    <a href="javascript:void(0)"
                                                        class="badge bg-primary text-white m-1 p-2 edit-item"
                                                        data-id="{{ $item->id }}"
                                                        data-quantity="{{ $item->quantity }}">
                                                        <i class="fas fa-edit me-1"></i>
                                                        {{ t('app.buttons.update') }}
                                                    </a>

                                                    <a href="javascript:void(0)"
                                                        class="badge bg-danger text-white m-1 p-2 delete-item"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fas fa-times me-1"></i>
                                                        {{ t('app.buttons.remove') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">
                                                <h3 class="text-danger text-center opacity-50">
                                                    {{ t('app.product.emptyQuoteList') }}!
                                                </h3>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{-- ################# Modal start ################# --}}
                            @if ($data['draftedRequest']->items->isNotEmpty())
                                @foreach ($data['draftedRequest']->items as $item)
                                    <script>
                                        document.addEventListener('click', function(e) {

                                            // EDIT
                                            if (e.target.closest('.edit-item')) {
                                                let button = e.target.closest('.edit-item');
                                                let id = button.dataset.id;
                                                let quantity = button.dataset.quantity;

                                                Swal.fire({
                                                    title: '{{ t('app.product.updateQuantity') }}',
                                                    html: `
                <input type="number" id="swal_quantity"
                       class="swal2-input"
                       value="${quantity}"
                       min="1"
                       required>
            `,
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Update',
                                                    preConfirm: () => {
                                                        return document.getElementById('swal_quantity').value;
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {

                                                        let form = document.createElement('form');
                                                        form.method = 'POST';
                                                        form.action = "{{ route(routePrefix() . 'UpdateQuoteItem') }}";

                                                        form.innerHTML = `
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="item_id" value="${id}">
                    <input type="hidden" name="quantity" value="${result.value}">
                `;

                                                        document.body.appendChild(form);
                                                        form.submit();
                                                    }
                                                });
                                            }

                                            // DELETE
                                            if (e.target.closest('.delete-item')) {
                                                let button = e.target.closest('.delete-item');
                                                let id = button.dataset.id;

                                                Swal.fire({
                                                    title: "{{ t('app.confirm') }}",
                                                    text: "{{ t('app.deleteConfirmText') }}",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: "{{ t('app.buttons.delete') }}",
                                                    confirmButtonColor: '#dc3545'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {

                                                        let form = document.createElement('form');
                                                        form.method = 'POST';
                                                        form.action = "{{ route(routePrefix() . 'DeleteQuoteItem') }}";

                                                        form.innerHTML = `
                                                                @csrf
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="item_id" value="${id}">
                                                            `;

                                                        document.body.appendChild(form);
                                                        form.submit();
                                                    }
                                                });
                                            }

                                        });
                                    </script>
                                @endforeach
                            @endif
                            {{-- ################# Modal End #################### --}}

                        </div>

                    </div>
                </div>
                {{-- Sender's information --}}
                <div class="m-3 mt-5">
                    <form action="{{ route(routePrefix() . 'SubmitQuoteDetails') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <h4 class="text-center fw-bold mb-4 ms-2">
                            {{ t('app.product.quoteRequesterDetailsDescription') }}
                        </h4>

                        @php
                            $user = Auth::user();
                        @endphp

                        @switch(getUserType())
                            @case('customer')
                                {{ $user->first_name }}
                                <!-- Pre-fill from user record -->
                                <div class="mb-3 text-center">
                                    <label for="name" class="form-label">{{ t('app.name') }} <span
                                            class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control text-center" id="name" name="name"
                                        value="{{ trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? '')) }}"
                                        readonly>
                                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('name') }}
                                    </p>
                                </div>
                                <div class="row col-md-12">
                                    <div class="mb-3 col-md-6 text-center">
                                        <label for="email" class="form-label">{{ t('auth.email') }} <span
                                                class="text-danger">*</span>:</label>
                                        <input type="email" class="form-control text-center" id="email" name="email"
                                            value="{{ $user->email1 ?? '' }}" readonly>
                                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('email') }}
                                        </p>

                                    </div>

                                    <div class="mb-3 col-md-6 text-center">
                                        <label for="phone" class="form-label">{{ t('auth.phone') }} <span
                                                class="text-danger">*</span>:</label>
                                        <input type="text" class="form-control text-center" id="phone" name="phone"
                                            value="{{ $user->phone1 ?? '' }}" required>
                                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('phone') }}
                                        </p>
                                    </div>
                                </div>
                            @break

                            @case('guest')
                                <!-- Empty form for guest -->
                                <div class="mb-3 text-center">
                                    <label for="name" class="form-label">{{ t('app.name') }} <span
                                            class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control text-center" id="name" name="name"
                                        placeholder="John Doe" required>
                                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('name') }}
                                    </p>
                                </div>
                                <div class="row col-md-12">
                                    <div class="mb-3 col-md-6 text-center">
                                        <label for="email" class="form-label">{{ t('auth.email') }} <span
                                                class="text-danger">*</span>:</label>
                                        <input type="email" class="form-control text-center" id="email" name="email"
                                            placeholder="you@mail.com" required>
                                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('email') }}
                                        </p>
                                    </div>

                                    <div class="mb-3 col-md-6 text-center">
                                        <label for="phone" class="form-label">{{ t('auth.phone') }} <span
                                                class="text-danger">*</span>:</label>
                                        <input type="text" class="form-control text-center" id="phone" name="phone"
                                            placeholder="+2349012345678" required>
                                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('phone') }}
                                        </p>
                                    </div>

                                    <div class="mb-3 col-md-12 text-center">
                                        <label for="address" class="form-label">{{ t('app.settings.address.address') }} <span
                                                class="text-danger">*</span>:</label>
                                        <textarea class="form-control text-center" id="address" name="address" required>{{ old('address') }}</textarea>
                                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('address') }}
                                        </p>
                                    </div>
                                </div>
                            @break

                            @default
                                <h3 class="text-danger text-center">
                                    {{ t('app.product.userCannotSubmitQuote') }}
                                </h3>
                        @endswitch

                        @if (in_array(getUserType(), ['customer', 'guest']))
                            <hr>
                            <button type="submit" class="btn btn-success w-100 mt-3">
                                {{ t('app.product.submitQuote') }}
                            </button>
                        @endif
                    </form>
                </div>



            </div>
        </section>
    </div>





@endsection
