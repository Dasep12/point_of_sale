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
        var cleanValue = value.replace(/[^,\d]/g, '').toString();
        var split = cleanValue.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        inputField.value = rupiah ? 'Rp ' + rupiah : '';

        // Update the raw input field with the unformatted value
        var rawValue = cleanValue.replace(/\./g, '');
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

    $(window).on('load', function() {
        // Hide the loader
        $('#fullPageLoader').fadeOut('slow', function() {

        });
    });
</script>