<div class="modal fade" id="modalCrudPajak" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" data-parsley-validate id="formCrudPajak">
                @csrf()
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" hidden name="action" id="CrudPajakAction" />
                            <input type="text" hidden name="id" id="id" />
                            <div class="form-group">
                                <label for="name">Name * :</label>
                                <input type="text" id="name" class="form-control" name="name" required />
                            </div>

                            <div class="form-group">
                                <label for="code_pajak">Kode Pajak * :</label>
                                <input type="text" id="code_pajak" class="form-control" name="code_pajak" required />
                            </div>
                            <div class="form-group">
                                <label for="persentase">Nilai Pajak(%) * :</label>
                                <input type="text" id="persentase" class="form-control" name="persentase" required />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" value="1" id="status_pajak" name="status_pajak" class=" " checked="checked" /> <label for="status_pajak"> Status *</label>
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


            <div id="CrudPajakError">
            </div>
            <div id="CrudPajakAlertDelete"></div>
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
    $("#formCrudPajak").parsley();
    $('#formCrudPajak').submit(function(e) {
        e.preventDefault();
        var f = $(this);
        f.parsley().validate();


        if (f.parsley().isValid()) {

            var formData = new FormData($('#formCrudPajak')[0]);
            var actions = $("#CrudPajakAction").val();
            var url = '';
            if (actions == "create") {
                url = '{{ url("administrator/jsonCreatePajak") }}';
            } else if (actions == "update") {
                url = '{{ url("administrator/jsonUpdatePajak") }}';
            } else if (actions == "delete") {
                url = '{{ url("administrator/jsonDeletePajak") }}';
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
                        $('#modalCrudPajak').modal('hide');
                        var act = $("#CrudPajakAction").val();
                        act = act.toLowerCase();
                        ReloadBarang();
                        doSuccess('create', 'success ' + act + ' data', 'success')
                    } else {
                        var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                        $('#CrudPajakError').html(errMsg);
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
                    $('#CrudPajakError').html(errMsg);
                },
            });
        } else {
            alert("form invalid");
        }
    })
</script>