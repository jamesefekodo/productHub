<script>
    function editAddressFormModal{{ $address->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.settings.address.address') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('customerUpdateAddressSubmit', ['id' => $address->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                <label class="form-label">{{ t('app.settings.address.country') }}</label>
                <select class="form-select text-center" name="country" id="country-select" required>
                    <option value="">- Select -</option>
                    @foreach ($data['activeCountries'] as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ t('app.settings.address.state') }}</label>
                <select class="form-select text-center" id="state-select" name="state" disabled required>
                    <option value="">- Select -</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ t('app.settings.address.lga') }}</label>
                <select class="form-select text-center" id="lga-select" name="lga" disabled required>
                    <option value="">- Select -</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    {{ t('app.settings.address.moreDetails') }}
                </label>

                <input type="text" name="more_details" class="form-control" placeholder="House/office no, street,..." value="{{ $address->more_details }}">

                <p class="text-danger small mt-1">
                    {{ $errors->first('more_details') }}
                </p>
            </div>
            <div class="mb-3>
                <label class="form-label">{{ t('app.settings.address.primaryAddress') }}</label>
                <select class="form-select text-center" name="is_primary" required>
                    <option value="" disabled>- {{ t('app.select') }} -</option>
                    <option value="1" {{ $address->is_primary ? 'selected' : '' }}>
                        {{ t('app.status.true') }}
                    </option>
                    <option value="0" {{ !$address->is_primary ? 'selected' : '' }}>
                        {{ t('app.status.false') }}
                    </option>
                </select>
                <p id="status-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">
                {{ t('app.buttons.submit') }}
            </button>
                </form>
            `,
            didOpen: () => {

                const container = Swal.getHtmlContainer();
                const countrySelect = container.querySelector('#country-select');
                const stateSelect = container.querySelector('#state-select');
                const lgaSelect = container.querySelector('#lga-select');

                const selectedCountryId = "{{ $address->country }}";
                const selectedStateId = "{{ $address->state }}";
                const selectedLgaId = "{{ $address->city }}";

                //  Force country selection immediately
                countrySelect.value = selectedCountryId;

                function loadStates(countryId) {

                    stateSelect.disabled = true;
                    lgaSelect.disabled = true;

                    stateSelect.innerHTML = '<option>Loading...</option>';
                    lgaSelect.innerHTML = '<option value="">- Select -</option>';

                    $.get("{{ route('fetchStates') }}", {
                        country_id: countryId
                    }, function(res) {

                        stateSelect.innerHTML = '<option value="">- Select -</option>';

                        res.states.forEach(state => {
                            stateSelect.innerHTML +=
                                `<option value="${state.id}">${state.name}</option>`;
                        });

                        stateSelect.disabled = false;

                        //  FORCE select state AFTER options exist
                        if (selectedStateId) {
                            stateSelect.value = selectedStateId;
                            loadLgas(selectedStateId);
                        }
                    });
                }

                function loadLgas(stateId) {

                    lgaSelect.disabled = true;
                    lgaSelect.innerHTML = '<option>Loading...</option>';

                    $.get("{{ route('fetchLgas') }}", {
                        state_id: stateId
                    }, function(res) {

                        lgaSelect.innerHTML = '<option value="">- Select -</option>';

                        res.lgas.forEach(lga => {
                            lgaSelect.innerHTML +=
                                `<option value="${lga.id}">${lga.name}</option>`;
                        });

                        lgaSelect.disabled = false;

                        //  FORCE select lga AFTER options exist
                        if (selectedLgaId) {
                            lgaSelect.value = selectedLgaId;
                        }
                    });
                }

                // When country changes
                countrySelect.addEventListener('change', function() {
                    loadStates(this.value);
                });

                // When state changes
                stateSelect.addEventListener('change', function() {
                    loadLgas(this.value);
                });

                //  AUTO LOAD WHEN MODAL OPENS
                if (selectedCountryId) {
                    loadStates(selectedCountryId);
                }
            }
        });
    }
</script>



<script>
    $(document).on('change', '#country-select', function() {
        const countryId = $(this).val();
        const $stateSelect = $('#state-select');

        $stateSelect.prop('disabled', true)
            .html('<option disabled selected>Loading...</option>');

        $.ajax({
            url: "{{ route('fetchStates') }}",
            method: 'GET',
            data: {
                country_id: countryId
            },

            success: function(response) {
                $stateSelect.empty();

                if (response.states.length) {
                    $stateSelect.append('<option disabled selected>- Select -</option>');
                    response.states.forEach(state => {
                        $stateSelect.append(
                            `<option value="${state.id}">${state.name}</option>`
                        );
                    });
                    $stateSelect.prop('disabled', false);
                } else {
                    $stateSelect.html('<option disabled>No states found</option>');
                }
            },

            error: function() {
                $stateSelect.html('<option disabled>Error loading states</option>');
            }
        });
    });
</script>
