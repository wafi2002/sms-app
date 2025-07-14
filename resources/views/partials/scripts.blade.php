<!-- BEGIN: Vendor JS-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


@vite(['resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js'])

@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'])
@vite(['resources/assets/vendor/js/menu.js'])

@vite(['resources/assets/js/main.js'])

<!-- Page Vendor JS-->
@yield('vendor-script')
<!-- END: Page Vendor JS-->

@vite(['resources/js/app.js'])

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

<!-- Place this tag before closing body tag for github widget button. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
