<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>POS </title>
    <!-- 
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/rim_logo_rev.png') }}">
    <link rel=" icon" type="image/x-icon" href="{{ asset('assets/images/rim_logo_rev.png') }}"> -->


    <!-- Font Awesome -->
    <link href=" {{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="{{ asset('assets/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet" />

    <!-- http://localhost:62107/ -->



    <!-- jQuery -->
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Select2 -->
    <link href="{{ asset('assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/dist/css/select2-bootstrap.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/vendors/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- jQGrid -->
    <script src="{{ asset('assets/jqgrid/js/jquery.jqGrid.min.js') }}"></script>
    <script src="{{ asset('assets/jqgrid/js/i18n/grid.locale-en.js') }}"></script>
    <link href="{{ asset('assets/jqgrid/css/ui.jqgrid.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/jqgrid/css/ui.jqgrid-bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">

    <!-- Switchery -->
    <script src="{{ asset('assets/vendors/iCheck/icheck.min.js') }}"></script>
    <!-- Switchery -->
    <link href="{{ asset('assets/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/build/css/custom-web.css') }}" rel="stylesheet">
    <!-- notification js -->
    <link rel="stylesheet" href="{{ asset('assets/notif_js/dist/notifications.css') }}" />
    <script src="{{ asset('assets/notif_js/dist/notifications.js') }}"></script>
    <!-- Parsley -->
    <script src="{{ asset('assets/vendors/parsleyjs/dist/parsley.min.js') }}"></script>


    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="{{ asset('assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">

    <!-- bootstrap-daterangepicker -->
    <script src="{{ asset('assets/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="{{ asset('assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>


    <!-- Load pdfmake lib files -->
    <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.26/build/pdfmake.min.js"> </script>
    <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.26/build/vfs_fonts.js"></script>

    <!-- jquery confirm -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <style>
        .loadering {
            width: 45px;
            aspect-ratio: 1;
            --c: conic-gradient(from -90deg, #FFF 90deg, #0000 0);
            background: var(--c), var(--c);
            background-size: 40% 40%;
            animation: l19 1s infinite alternate;
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 9999;
        }

        @keyframes l19 {

            0%,
            10% {
                background-position: 0 0, 0 calc(100%/3)
            }

            50% {
                background-position: 0 0, calc(100%/3) calc(100%/3)
            }

            90%,
            100% {
                background-position: 0 0, calc(100%/3) 0
            }
        }

        /* .ui-jqgrid tr.jqgrow td {
            white-space: normal !important;
            word-wrap: break-word !important;
        } */
        /* Full Page Loader Styles */
        /* Absolute Center Spinner */

        /* Center the loader */
        #fullPageLoader {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(55, 55, 55, 0.83);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            display: block;
        }

        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 9999;
            width: 90px;
            height: 90px;
            margin: -76px 0 0 -76px;
            border: 16px solid #f6efee;
            border-radius: 50%;
            border-top: 16px solid #137b14;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0;
                opacity: 1
            }
        }


        .custom-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 1px;
            background-color: #fefefe;
            font-size: 16px;
            color: #555;
            box-shadow: inset 0 1px 3px rgba(255, 255, 255, 0.1);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Add arrow inside select box */
        .custom-select:after {
            content: '\25BC';
            /* Unicode character for down arrow */
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        /* Wrapper for positioning the arrow */
        .select-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        /* Add styling to the first option (placeholder) */
        .custom-select option:first-child {
            color: #999;
        }
    </style>
</head>