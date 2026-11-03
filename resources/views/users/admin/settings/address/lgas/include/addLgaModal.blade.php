<script>
    function addLgaFormModal(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.addNew') }} :: {{ t('app.settings.address.lga') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },

            html: `
        <form id="swalForm"  method="POST" action="{{ route('createNewLgaSubmit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Country</label>
                <select class="form-select text-center" name="country" id="country-select" required>
                    <option value="">- Select -</option>
                    @foreach ($data['activeCountries'] as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">State</label>
                <select class="form-select text-center" id="state-select" name="state" disabled required>
                    <option value="">- Select -</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">LGA</label>
                <input type="text" class="form-control text-center" name="lga" required>
            </div>

         <button type="submit" class="btn btn-primary w-100 mt-2">{{ t('app.buttons.submit') }}</button>

        </form>
        `,

            didOpen: () => {
                const container = Swal.getHtmlContainer();
                const countrySelect = container.querySelector('#country-select');
                const stateSelect = container.querySelector('#state-select');

                countrySelect.addEventListener('change', function() {
                    const countryId = this.value;

                    stateSelect.disabled = true;
                    stateSelect.innerHTML = '<option>Loading...</option>';

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
                                stateSelect.innerHTML += `
                                <option value="${state.id}">${state.name}</option>
                            `;
                            });

                            stateSelect.disabled = false;
                        },

                        error: function() {
                            stateSelect.innerHTML =
                                '<option disabled>Error loading states</option>';
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
