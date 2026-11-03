<script>
    function addProductFormModal{{ $product->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.product.addToQuote') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: { popup: sizeClass },
            html: `
                <form id="swalForm" method="POST" action="{{ route('adminAddProductToQuote') }}">
                    @csrf

                    <div class="row col-md-12">
                        <input type="hidden" class="form-control" name="product" value="{{ $product->slug }}" required>
                        <input type="hidden" class="form-control" name="variant" value="" required>
                        <input type="hidden" class="form-control" name="quote_number" value="{{ $data['existingTemporaryQuote']->quote_number }}" required>
                        <p class="text-danger">{{ $errors->first('product') }}
                            </p>
                            <p class="text-danger">{{ $errors->first('variant') }}
                            </p>
                            <p class="text-danger">{{ $errors->first('quote_number') }}
                            </p>



                        <div class="mb-1 col-md-12">
                            <label class="form-label">{{ t('app.product.quantity') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="quantity" value="{{ old('quantity') }}" required>
                            <p class="text-danger">{{ $errors->first('quantity') }}
                            </p>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                </form>
            `
        });
    }
    </script>
