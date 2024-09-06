<!-- Bootstrap -->
<script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('assets/vendors/nprogress/nprogress.js') }}"></script>
<!-- jQuery custom content scroller -->
<script src="{{ asset('assets/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>


<!-- Custom Theme Scripts -->
<script src="{{ asset('assets/build/js/custom.min.js') }}"></script>


<script>
    function formatRupiah(value, inputField, rawField) {
        // Remove non-numeric characters, except for commas and dots
        var cleanValue = value.replace(/[^,\d.]/g, '').toString();

        // Split the value into integer and fractional parts
        var split = cleanValue.split('.');
        var integerPart = split[0];
        var fractionalPart = split[1] !== undefined ? split[1] : '';

        // Handle the case where the integer part is empty
        if (integerPart === '') {
            integerPart = '0';
        }

        // Add thousand separators
        var sisa = integerPart.length % 3;
        var rupiah = integerPart.substr(0, sisa);
        var ribuan = integerPart.substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? ',' : '';
            rupiah += separator + ribuan.join(',');
        }

        // Ensure the fractional part has two digits
        fractionalPart = fractionalPart.length > 2 ? fractionalPart.substring(0, 2) : fractionalPart;
        if (fractionalPart.length === 1) {
            fractionalPart += '0'; // Add trailing zero if needed
        }

        // Combine integer and fractional parts with dot as the decimal separator
        rupiah = fractionalPart ? rupiah + '.' + fractionalPart : rupiah;

        // Update the input field with the formatted value
        inputField.value = rupiah ? 'Rp ' + rupiah : '';

        // Update the raw input field with the unformatted value (remove commas and dots)
        var rawValue = cleanValue.replace(/,/g, '').replace(/\./g, '');
        rawField.value = rawValue;
    }
    // only number and decimal
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function formatRpDashboard(value) {
        // Remove non-numeric characters, except for commas and dots
        var cleanValue = value.replace(/[^,\d.]/g, '').toString();

        // Split the value into integer and fractional parts
        var split = cleanValue.split('.');
        var integerPart = split[0];
        var fractionalPart = split[1] !== undefined ? split[1] : '';

        // Handle the case where the integer part is empty
        if (integerPart === '') {
            integerPart = '0';
        }

        // Add thousand separators
        var sisa = integerPart.length % 3;
        var rupiah = integerPart.substr(0, sisa);
        var ribuan = integerPart.substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? ',' : '';
            rupiah += separator + ribuan.join(',');
        }

        // Ensure the fractional part has two digits
        fractionalPart = fractionalPart.length > 2 ? fractionalPart.substring(0, 2) : fractionalPart;
        if (fractionalPart.length === 1) {
            fractionalPart += '0'; // Add trailing zero if needed
        }

        // Combine integer and fractional parts with dot as the decimal separator
        rupiah = fractionalPart ? rupiah + '.' + fractionalPart : rupiah;
        return rupiah ? '' + rupiah : '';
    }

    $(window).on('load', function() {
        // Hide the loader
        $('#fullPageLoader').fadeOut('slow', function() {

        });
    });
</script>