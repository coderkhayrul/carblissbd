<!-- Core JS -->
<script src="{{ asset('backend') }}/assets/vendor/libs/jquery/jquery.js"></script>

<script src="{{ asset('backend') }}/assets/vendor/libs/popper/popper.js"></script>
<script src="{{ asset('backend') }}/assets/vendor/js/bootstrap.js"></script>

<script src="{{ asset('backend') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="{{ asset('backend') }}/assets/vendor/libs/hammer/hammer.js"></script>

<script src="{{ asset('backend') }}/assets/vendor/js/menu.js"></script>
<!-- Vendors JS -->
<script src="{{ asset('backend') }}/assets/vendor/libs/notyf/notyf.js"></script>


<script>
    // CORE CONFIG DO NOT TOUCH
    window.assetsPath = "{{ asset('backend/assets/') }}/";
    window.templateName = 'carblissbd-admin';

    // SHOW GLOBAL NOTIFICATION USING NOTYF
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Notyf
        const notyf = new Notyf({
            duration: 4000, // Notification duration in milliseconds
            position: {
                x: 'right',
                y: 'top',
            },
            dismissible: true
        });

        // Check for Laravel session 'success'
        @if (session()->has('success'))
            notyf.success("{{ session()->get('success') }}");
        @endif

        // Check for Laravel session 'error'
        @if (session()->has('error'))
            notyf.error("{{ session()->get('error') }}");
        @endif

        // Check for Laravel session 'info'
        @if (session()->has('info'))
            notyf.Info("{{ session()->get('info') }}");
        @endif

        // Check for Laravel session 'warning'
        @if (session()->has('warning'))
            notyf.Warning("{{ session()->get('warning') }}");
        @endif
    });
</script>

<!-- Main JS -->
<script src="{{ asset('backend') }}/assets/js/main.js"></script>

<script src="{{ asset('backend') }}/js/custom.js"></script>
</body>

</html>
