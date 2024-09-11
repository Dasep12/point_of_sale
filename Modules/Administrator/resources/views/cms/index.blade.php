@extends('administrator::layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h2>CMS </h2>
                <div class="nav navbar-right panel_toolbox">
                    <div class="input-group">


                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <form action="{{ url('administrator/updateCms') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="text" hidden name="id" id="id" value="{{$data->id}}">
                <div class="x_content">
                    <div class="col-md-3 col-sm-3  profile_left">
                        <div class="profile_img">
                            <div id="crop-avatar">
                                <!-- Current avatar -->
                                <img class="img-responsive avatar-view" height="150" width="150" id="image_logo" src="{{ asset('assets/images/' . $data->logo) }}" alt="Avatar" title="Change the avatar">
                            </div>
                        </div>

                        <ul class="list-unstyled user_data">
                            <li><input type="file" name="logo" id="logo">
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-9 col-sm-9 ">

                        <div class="profile_title">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_company">Company</label>
                                    <input value="{{ $data->name_company }}" type="text" class="form-control" name="name_company" id="name_company">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" value="{{ $data->address }}" class="form-control" name="address" id="address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" value="{{ $data->phone }}" class="form-control" name="phone" id="phone">
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end">
                            <button class="btn-sm mt-4 btn btn-primary"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            @if (session('error'))
            <p>{{ session('error') }}</p>
            @endif
        </div>
    </div>
</div>
</div>


<script>
    document.getElementById('logo').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image_logo');
                preview.src = e.target.result; // Set the image src to the file's data URL
                //preview.style.display = 'block'; // Display the image
            }
            reader.readAsDataURL(file); // Convert the file to a data URL
        }
    });
</script>
@endsection