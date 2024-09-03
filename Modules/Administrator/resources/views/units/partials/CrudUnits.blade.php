<div class="modal fade" id="modalCrudUnits" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class=" modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" data-parsley-validate id="formCrudUnits">
                @csrf()
                <div class="modal-body">
                    <div class="row">


                        <div class="col-lg-12">
                            <input type="text" hidden name="action" id="CrudUnitsAction" />
                            <input type="text" hidden name="id" id="id" />
                            <div class="form-group">
                                <label for="unit_name">Name Unit* :</label>
                                <input type="text" id="unit_name" class="form-control" name="unit_name" required />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="unit_code">Code Unit* :</label>
                                <input type="text" id="unit_code" class="form-control" name="unit_code" required />
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="remarks">Remarks * :</label>
                                <textarea type="text" id="remarks" class="form-control" name="remarks"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="status_unit">Status Unit* :</label>
                                <input type="checkbox" checked id="status_unit" class="" name="status_unit" />
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary btn-sm btn-title"></button>
                    </div>
                    <div class="loader" style="display: none;">
                        <div class="col-md-12">
                            <div style="background-color: rgba(132, 122, 42, 0.63) !important;" class="alert alert-info mt-2" role="alert">
                                <span style="font-style: italic;">Please Wait Send Data . . .</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div id="CrudUnitsError"></div>
            <div id="CrudUnitsAlertDelete"></div>
        </div>
    </div>
</div>

<script>
    function doSuccess(act, msg, theme) {
        const myNotification = window.createNotification({
            // options here
            displayCloseButton: true,
            theme: theme //success error , information , success
        });

        myNotification({
            title: 'Information',
            message: msg
        });
    }
    $("#formCrudUnits").parsley();
    $('#formCrudUnits').submit(function(e) {
        e.preventDefault();
        var f = $(this);
        f.parsley().validate();

        if (f.parsley().isValid()) {
            var formData = new FormData($('#formCrudUnits')[0]);
            var actions = $("#CrudUnitsAction").val();
            var url = '';
            if (actions == "create") {
                url = '{{ url("administrator/jsonCreateUnits") }}';
            } else if (actions == "update") {
                url = '{{ url("administrator/jsonUpdateUnits") }}';
            } else if (actions == "delete") {
                url = '{{ url("administrator/jsonDeleteUnits") }}';
            }

            $.ajax({
                url: url,
                type: 'POST',
                contentType: false,
                processData: false,
                data: formData,
                async: false,
                beforeSend: function() {
                    document.querySelector(".loader").style.display = "block";
                },
                complete: function() {
                    document.querySelector(".loader").style.display = "none";
                },
                success: function(data) {
                    if (data.msg == "success") {
                        $('#modalCrudUnits').modal('hide');
                        var act = $("#CrudUnitsAction").val();
                        act = act.toLowerCase();
                        ReloadBarang();
                        doSuccess('create', 'success ' + act + ' data', 'success')
                    } else {
                        var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                        $('#CrudUnitsError').html(errMsg);
                    }
                },
                error: function(xhr, desc, err) {
                    document.querySelector(".loader").style.display = "none";
                    var respText = "";
                    try {
                        respText = eval(xhr.responseText);
                    } catch {
                        respText = xhr.responseText;
                    }

                    respText = unescape(respText).replaceAll("_n_", "<br/>")

                    var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error ' + xhr.status + '!</b><br/>' + respText + '</small></div></div>'
                    $('#CrudUnitsError').html(errMsg);
                },
            });
        } else {
            alert("form invalid");
        }
    })
</script>