<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login POS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Parsley -->
    <link rel="stylesheet" href="https://parsleyjs.org/src/parsley.css">
    <script src="{{ asset('assets/vendors/parsleyjs/dist/parsley.min.js') }}"></script>


    <!-- notification js -->
    <link rel="stylesheet" href="{{ asset('assets/notif_js/dist/notifications.css') }}" />
    <script src="{{ asset('assets/notif_js/dist/notifications.js') }}"></script>

    <style>
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('login/bg-login_1.jpg') !important;
            /* background-color: #35353c; */
            background-size: cover !important;
            background-position: center !important;
        }

        a {
            text-decoration: none;
        }

        .login-page {
            width: 100%;
            height: 100vh;
            display: inline-block;
            display: flex;
            align-items: center;
        }

        .form-right i {
            font-size: 100px;
        }

        .custom-error {
            color: red !important;
            font-size: 0.875em !important;
            margin-top: 5px !important;
        }

        .rounded {
            border-radius: 0.5rem !important;
        }

        label {
            color: #0E7EF4 !important
        }
    </style>

</head>

<body>

    <div class="login-page ">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div style="background-color: #fcfdff !important;" class="shadow rounded">
                        <div class="row">
                            <div class="col-md-7 pe-0">
                                <h4>{{ session()->get('iduser'); }}</h4>
                                <div class="form-left h-100 py-5 px-5">
                                    <form id="formLogin" data-parsley-validate method="post" action="" class="row g-4">
                                        @csrf()
                                        <label style="font-weight: 600;font-style: italic;color: #35353c;" class="">Sign in To Your Account</label>
                                        <div class="col-md-12">
                                            <label style="font-size: 14px !important;font-style: italic;">Username<span class="text-danger">*</span></label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-text"><i class="bi bi-person-fill"></i></div>
                                                <input data-parsley-errors-container="#usernameError" required type="text" name="username" class="form-control-sm form-control" placeholder="Enter Username">
                                            </div>
                                            <div id="usernameError"></div> <!-- Custom error container -->

                                            <label style="font-size: 14px !important;font-style: italic;">Password<span class="text-danger">*</span></label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-text"><i class="bi bi-lock-fill"></i></div>
                                                <input required data-parsley-errors-container=" #passwordError" name="password" type="password" class="form-control-sm form-control" placeholder="Enter Password">
                                            </div>
                                            <div id="passwordError"></div> <!-- Custom error container -->

                                            <button id="" type="submit" class="btn btn-sm btn-primary px-4 float-end mt-4"><i class="fa fa-paper-plane"></i> LOGIN</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4 ps-0  d-none d-md-block">
                                <div class="col-md-12 mb-4"></div>
                                <div class="col-md-12 pt-5"></div>
                                <div class="form-right  text-center">
                                    <img style="width: 80%;" src="{{ asset('login/logo_pos.png') }}" alt="">
                                    <label style="font-weight: 600;font-style: italic;color: #fff;font-size: 13px;" for=""> Welcome To POS version 1.0</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div id="ErrorInfo">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            function doSuccess(msg, theme) {
                const myNotification = window.createNotification({
                    // options here
                    displayCloseButton: true,
                    theme: theme //success error , information , success
                });

                myNotification({
                    title: 'Notification',
                    message: msg
                });
            }

            $('#formLogin').parsley({
                errorsContainer: function(ParsleyField) {
                    return ParsleyField.$element.siblings('.custom-error');
                }
            });

            $('#formLogin').submit(function(e) {
                e.preventDefault();
                var f = $(this);
                f.parsley().validate();

                if (f.parsley().isValid()) {
                    var formData = new FormData($('#formLogin')[0]);
                    $.ajax({
                        url: "{{ url('auth') }}",
                        type: 'POST',
                        contentType: false,
                        processData: false,
                        data: formData,
                        async: false,
                        success: function(data) {
                            console.log(data)
                            if (data.msg == "success") {
                                doSuccess(data.text, "success")
                                setTimeout(function() {
                                    window.location.href = "{{ url('administrator/dashboard') }}"
                                }, 100)
                            } else {
                                doSuccess(data.msg, "warning")
                            }
                        },
                        error: function(xhr, desc, err) {
                            var respText = "";
                            try {
                                respText = eval(xhr.responseText);
                            } catch {
                                respText = xhr.responseText;
                            }

                            respText = unescape(respText).replaceAll("_n_", "<br/>")

                            var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2 alert-dismissible fade show" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></button></div></div>'
                            $('#ErrorInfo').html(errMsg);
                        },
                    });
                }
            })
        });
    </script>
</body>

</html>