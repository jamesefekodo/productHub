<script>
    function addNewAddressFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.settings.address.address') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },

            html: `
        <form id="swalForm"  method="POST" action="{{ route('customerAddNewAddressSubmit') }}">
            @csrf
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

                <input type="text" name="more_details" class="form-control" placeholder="House/office no, street,..." value="{{ old('more_details', $model->more_details ?? '') }}">

                <p class="text-danger small mt-1">
                    {{ $errors->first('more_details') }}
                </p>
            </div>

         <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>

        </form>
        `,

            didOpen: () => {
                const container = Swal.getHtmlContainer();
                const countrySelect = container.querySelector('#country-select');
                const stateSelect = container.querySelector('#state-select');
                const lgaSelect = container.querySelector('#lga-select');

                // Fetch States
                countrySelect.addEventListener('change', function() {
                    const countryId = this.value;

                    stateSelect.disabled = true;
                    lgaSelect.disabled = true;
                    stateSelect.innerHTML = '<option>Loading...</option>';
                    lgaSelect.innerHTML = '<option value="">- Select -</option>';

                    $.ajax({
                        url: "{{ route('fetchStates') }}",
                        method: "GET",
                        data: {
                            country_id: countryId
                        },

                        success: function(res) {
                            stateSelect.innerHTML =
                                '<option value="">- Select -</option>';

                            if (res.states.length === 0) {
                                stateSelect.innerHTML +=
                                    '<option disabled>No states found</option>';
                            }

                            res.states.forEach(state => {
                                stateSelect.innerHTML +=
                                    `<option value="${state.id}">${state.name}</option>`;
                            });

                            stateSelect.disabled = false;
                        },

                        error: function() {
                            stateSelect.innerHTML =
                                '<option disabled>Error loading states</option>';
                        }
                    });
                });

                // Fetch LGAs
                stateSelect.addEventListener('change', function() {
                    const stateId = this.value;

                    lgaSelect.disabled = true;
                    lgaSelect.innerHTML = '<option>Loading...</option>';

                    $.ajax({
                        url: "{{ route('fetchLgas') }}", // 👈 create this route
                        method: "GET",
                        data: {
                            state_id: stateId
                        },

                        success: function(res) {
                            lgaSelect.innerHTML =
                            '<option value="">- Select -</option>';

                            if (res.lgas.length === 0) {
                                lgaSelect.innerHTML +=
                                    '<option disabled>No LGAs found</option>';
                            }

                            res.lgas.forEach(lga => {
                                lgaSelect.innerHTML +=
                                    `<option value="${lga.id}">${lga.name}</option>`;
                            });

                            lgaSelect.disabled = false;
                        },

                        error: function() {
                            lgaSelect.innerHTML =
                                '<option disabled>Error loading LGAs</option>';
                        }
                    });
                });
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
