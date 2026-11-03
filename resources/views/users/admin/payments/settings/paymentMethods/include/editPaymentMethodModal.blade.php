<script>
    function editPaymentMethodFormModal{{ $method->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.payment.paymentMethod') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updatePaymentMethodSubmit', ['slug' => $method->slug]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.status.status') }}</label>
                        <select class="form-select text-center" name="status" required>
                                        <option selected disabled>
                                            -{{ t('app.select') }}-</option>
                                        <option value="active" {{ $method->status == 'active' ? 'selected' : '' }}>
                                            {{ t('app.status.active') }}</option>
                                        <option value="inactive" {{ $method->status == 'inactive' ? 'selected' : '' }}>
                                            {{ t('app.status.inactive') }}</option>
                        </select>
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('status') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ t('app.version') }}</label>
                        <input type="text" name="version" class="form-control text-center" value="{{ $method->version }}">
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('version') }}</p>
                    </div>

                    @switch($method->slug)
                        @case('paystack')
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">{{ t('app.payment.secretKey') }}</label>
                            <input type="text" name="secret_key" class="form-control text-center" value="{{ $method->PaystackApi->secret_key }}" required>
                             <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('secret_key') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ t('app.payment.publicKey') }}</label>
                            <input type="text" name="public_key" class="form-control text-center" value="{{ $method->PaystackApi->public_key }}" required>
                             <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('public_key') }}</p>
                        </div>
                            @break
                            @case('flutterwave')
                            <hr>
                            <div class="mb-3">
                                <label class="form-label">{{ t('app.payment.secretKey') }}</label>
                                <input type="text" name="secret_key" class="form-control text-center" value="{{ $method->flutterwaveApi->secret_key }}" required>
                                <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('secret_key') }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ t('app.payment.publicKey') }}</label>
                                <input type="text" name="public_key" class="form-control text-center" value="{{ $method->flutterwaveApi->public_key }}" required>
                                <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('public_key') }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ t('app.payment.encryptionKey') }}</label>
                                <input type="text" name="encryption_key" class="form-control text-center" value="{{ $method->flutterwaveApi->encryption_key }}" required>
                                <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $errors->first('encryption_key') }}</p>
                            </div>
                            @break

                        @default

                    @endswitch



                    <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>
                </form>
            `
        });
    }
</script>
