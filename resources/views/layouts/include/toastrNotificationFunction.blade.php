<script>
    // Configure toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>

{{-- @if (session('success'))
    <script>
        toastr.success("{{ session('success') }}", t('notifications.passwordDoNotMatch'));
    </script>
@elseif (session('error'))
    <script>
        toastr.error("{{ session('error') }}", t('notifications.error'));
    </script>
@elseif (session('info'))
    <script>
        toastr.info("{{ session('info') }}", t('notifications.info'));
    </script>
@elseif (session('warning'))
    <script>
        toastr.warning("{{ session('warning') }}", t('notifications.warning'));
    </script>
@endif --}}



@if (session('success'))
    <script>
        toastr.success(
            @json(session('success')),
            @json(t('notifications.success'))
        );
    </script>
@elseif (session('error'))
    <script>
        toastr.error(
            @json(session('error')),
            @json(t('notifications.error'))
        );
    </script>
@elseif (session('info'))
    <script>
        toastr.info(
            @json(session('info')),
            @json(t('notifications.info'))
        );
    </script>
@elseif (session('warning'))
    <script>
        toastr.warning(
            @json(session('warning')),
            @json(t('notifications.warning'))
        );
    </script>
@endif
