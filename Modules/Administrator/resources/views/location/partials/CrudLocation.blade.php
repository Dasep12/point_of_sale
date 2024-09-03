<div class="modal fade" id="modalCrudLocation" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class=" modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" data-parsley-validate id="formCrudLocation">
                @csrf()
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="warehouse_id">Warehouse* :</label>
                                <select name="warehouse_id" class="form-control custom-select" id="warehouse_id">
                                    <?php
                                    foreach ($warehouse as $whs) { ?>
                                        <option value="{{ $whs->id }}">{{ $whs->NameWarehouse }}</option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>



                        <div class="col-lg-12">
                            <input type="text" hidden name="action" id="CrudLocationAction" />
                            <input type="text" hidden name="id" id="id" />
                            <div class="form-group">
                                <label for="location">Location* :</label>
                                <input type="text" id="location" class="form-control" name="location" required />
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
                                <label for="status_location">Status Location* :</label>
                                <input type="checkbox" checked id="status_location" class="" name="status_location" />
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

            <div id="CrudLocationError"></div>
            <div id="CrudLocationAlertDelete"></div>
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
    $("#formCrudLocation").parsley();
    $('#formCrudLocation').submit(function(e) {
        e.preventDefault();
        var f = $(this);
        f.parsley().validate();

        if (f.parsley().isValid()) {
            var formData = new FormData($('#formCrudLocation')[0]);
            var actions = $("#CrudLocationAction").val();
            var url = '';
            if (actions == "create") {
                url = '{{ url("administrator/jsonCreateLocation") }}';
            } else if (actions == "update") {
                url = '{{ url("administrator/jsonUpdateLocation") }}';
            } else if (actions == "delete") {
                url = '{{ url("administrator/jsonDeleteLocation") }}';
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
                        $('#modalCrudLocation').modal('hide');
                        var act = $("#CrudLocationAction").val();
                        act = act.toLowerCase();
                        ReloadBarang();
                        doSuccess('create', 'success ' + act + ' data', 'success')
                    } else {
                        var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                        $('#CrudLocationError').html(errMsg);
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
                    $('#CrudLocationError').html(errMsg);
                },
            });
        } else {
            alert("form invalid");
        }
    })
</script>