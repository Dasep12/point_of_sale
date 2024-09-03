<!-- header menu -->
@include('administrator::layouts.header')
<!-- header menu -->
<?php
$MenuUrl = "";
?>

<div id="fullPageLoader">
    <!-- <div id="loader"></div> -->
    <div class="loadering"></div>
</div>
<script>
    //document.getElementById("fullPageLoader").style.display = "none";
</script>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col menu_fixed">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title justify-content-center" style="border: 0;">
                        <img style="width: 20%;height: 40px !important;" src="{{ asset('login/logo_pos.png') }}" alt="..." class=" profile_img">
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="">
                        <!-- <div class="profile_pic">
                            <img src="{{ asset('assets/images/img.jpg') }}" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2>John Doe</h2>
                        </div> -->
                        <!-- <a href="#" class="site_title"><i class="fa fa-cubes"></i> <span>WMS</span></a> -->
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    @include('administrator::layouts.sidebar')
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <!-- <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a> -->
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <nav class="nav navbar-nav">
                        <ul class=" navbar-right">
                            <li class="nav-item dropdown open" style="padding-left: 15px;">
                                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('assets/images/user_2.png') }}" alt="">{{ ucwords(strtolower(session()->get("fullname"))) }}
                                </a>
                                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                                    <!-- <a class="dropdown-item" href="#"> Profile</a> -->
                                    <a class="dropdown-item" href="{{ url('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                </div>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    @yield('content')
                </div>
            </div>
            <!-- /page content -->

            <!-- footer content -->
            <footer>
                <div class="pull-right">
                    POINT OF SALE || <a href="#"></a>
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>


</body>
<!-- sidebar menu -->
@include('administrator::layouts.footer')
<!-- /sidebar menu -->

<!-- Created by  -->
<!-- DASEP DEPIYAWAN -->
<!-- 6283821619460 -->

</html>