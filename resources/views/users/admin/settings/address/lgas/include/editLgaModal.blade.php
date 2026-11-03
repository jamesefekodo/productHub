<script>
    function editLgaFormModal{{ $lga->id }}(sizeClass = 'swal-size-md mx-5') {
        Swal.fire({
            title: "{{ t('app.buttons.edit') }} :: {{ t('app.settings.address.lga') }}",
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: sizeClass
            },
            html: `
                <form id="swalForm" method="POST" action="{{ route('updateLgaSubmit', ['id' => $lga->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ t('app.settings.address.country') }}</label>
                            <select class="form-select text-center" id="country-select" name="country" required>
                                <option value="" disabled>- {{ t('app.select') }} -</option>
                                @forelse ($data['allCountries'] as $country)
                                    <option value="{{ $country->id }}"
                                        {{ $lga->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @empty
                                    <option disabled>No countries available</option>
                                @endforelse
                            </select>
                            <p id="country-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ t('app.settings.address.state') }}</label>
                            <select class="form-select text-center" id="state-select" name="state" required>
                                {{-- Pre-load with actual state value from DB --}}
                                <option value="{{ $lga->state_id }}" selected>
                                    {{ $lga->state->name ?? 'Select State' }}
                                </option>
                            </select>
                            <p id="state-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ t('app.settings.address.lga') }}</label>
                            <input type="text" class="form-control text-center" name="lga"
                                value="{{ $lga->name }}" required>
                            <p id="lga-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ t('app.status.status') }}</label>
                            <select class="form-select text-center" name="status" required>
                                <option value="" disabled>- Select -</option>
                                <option value="active" {{ $lga->status == 'active' ? 'selected' : '' }}>
                                    {{ t('app.status.active') }}
                                </option>
                                <option value="inactive" {{ $lga->status == 'inactive' ? 'selected' : '' }}>
                                    {{ t('app.status.inactive') }}
                                </option>
                            </select>
                            <p id="status-error" style="color: red; font-size: 12px; margin-top: 5px;"></p>
                        </div>
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

                // Store the original values
                const originalStateId = {{ $lga->state_id ?? 'null' }};
                const originalCountryId = {{ $lga->country_id ?? 'null' }};

                // Function to load states
                function loadStates(countryId, selectedStateId = null) {
                    // Show loading indicator but keep select enabled
                    const loadingOption = document.createElement('option');
                    loadingOption.textContent = 'Loading...';
                    loadingOption.disabled = true;
                    loadingOption.selected = true;
                    stateSelect.innerHTML = '';
                    stateSelect.appendChild(loadingOption);

                    $.ajax({
                        url: "{{ route('fetchStates') }}",
                        method: "GET",
                        data: { country_id: countryId },
                        success: function(res) {
                            stateSelect.innerHTML = '<option value="">- {{ t('app.select') }} -</option>';

                            if (res.states && res.states.length > 0) {
                                res.states.forEach(state => {
                                    const isSelected = selectedStateId && state.id == selectedStateId ? 'selected' : '';
                                    stateSelect.innerHTML += `
                                        <option value="${state.id}" ${isSelected}>${state.name}</option>
                                    `;
                                });
                            } else {
                                stateSelect.innerHTML = '<option value="" disabled>No states found</option>';
                            }
                        },
                        error: function() {
                            stateSelect.innerHTML = '<option value="" disabled>Error loading states</option>';
                        }
                    });
                }

                // Load states immediately on modal open with the original state selected
                if (originalCountryId) {
                    loadStates(originalCountryId, originalStateId);
                }

                // Handle country change
                countrySelect.addEventListener('change', function() {
                    const newCountryId = this.value;

                    // If country changed, load new states without pre-selection
                    if (newCountryId != originalCountryId) {
                        loadStates(newCountryId, null);
                    } else {
                        // If back to original country, restore original state
                        loadStates(newCountryId, originalStateId);
                    }
                });

                // Handle form submission
                const form = container.querySelector('#swalForm');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Clear previous errors
                    container.querySelectorAll('[id$="-error"]').forEach(el => el.textContent = '');

                    const formData = new FormData(this);

                    // Debug: Log form data
                    console.log('Form Data:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key + ': ' + value);
                    }

                    // Show loading
                    Swal.showLoading();

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message || 'LGA updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.hideLoading();

                        // Handle Laravel validation errors
                        if (error.errors) {
                            Object.keys(error.errors).forEach(field => {
                                const errorElement = container.querySelector(`#${field}-error`);
                                if (errorElement) {
                                    errorElement.textContent = error.errors[field][0];
                                }
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please fix the errors below',
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'An error occurred',
                                showConfirmButton: true
                            });
                        }
                    });
                });
            }
        });
    }
</script>
