<!-- jquery -->
<script src="{{ asset('public/frontend/js/jquery-3.6.0.js') }}"></script>
<!-- bootstrap js -->
<script src="{{ asset('public/frontend/js/bootstrap.bundle.js') }}"></script>
<!-- swipper js -->
<script src="{{ asset('public/frontend/js/swiper.js') }}"></script>
<!-- lightcase js-->
<script src="{{ asset('public/frontend/js/lightcase.js') }}"></script>
<!-- odometer js -->
<script src="{{ asset('public/frontend/js/odometer.js') }}"></script>
<!-- viewport js -->
<script src="{{ asset('public/frontend/js/viewport.jquery.js') }}"></script>
<!-- AOS js -->
<script src="{{ asset('public/frontend/js/aos.js') }}"></script>
<!-- nice select js -->
<script src="{{ asset('public/frontend/js/jquery.nice-select.js') }}"></script>
<!-- select2 -->
<script src="{{ asset('public/frontend/js/select2.js') }}"></script>

<!-- Popup -->
<script src="{{ asset('public/backend/library/popup/jquery.magnific-popup.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function laravelCsrf() {
        return $("head meta[name=csrf-token]").attr("content");
    }
</script>
<!-- main -->
<script src="{{ asset('public/frontend/js/main.js') }}"></script>
@include('admin.partials.notify')
