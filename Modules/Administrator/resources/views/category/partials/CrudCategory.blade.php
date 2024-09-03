<div class="modal fade" id="modalCrudCategory" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-lg modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="titleModal"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" data-parsley-validate id="formCrudCategory">
                @csrf()
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" hidden name="action" id="CrudCategoryAction" />
                            <input type="text" hidden name="id" id="id" />
                            <div class="form-group">
                                <label for="name_categories">Name Category* :</label>
                                <input type="text" id="name_categories" class="form-control" name="name_categories" required />
                            </div>

                            <div class="form-group">
                                <label for="code_categories">Code Category * :</label>
                                <input type="text" id="code_categories" class="form-control" name="code_categories" required />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" value="1" id="status_categories" name="status_categories" class=" " checked="checked" /> <label for="status_categories"> Status *</label>
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="remarks">Remarks * :</label>
                                <textarea type="text" id="remarks" class="form-control" name="remarks" required></textarea>
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


            <div id="CrudCategoryError">
            </div>
            <div id="CrudCategoryAlertDelete"></div>
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
    $("#formCrudCategory").parsley();
    $('#formCrudCategory').submit(function(e) {
        e.preventDefault();
        var f = $(this);
        f.parsley().validate();


        if (f.parsley().isValid()) {

            var formData = new FormData($('#formCrudCategory')[0]);
            var actions = $("#CrudCategoryAction").val();
            var url = '';
            if (actions == "create") {
                url = '{{ url("administrator/jsonCreateCategory") }}';
            } else if (actions == "update") {
                url = '{{ url("administrator/jsonUpdateCategory") }}';
            } else if (actions == "delete") {
                url = '{{ url("administrator/jsonDeleteCategory") }}';
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
                        $('#modalCrudCategory').modal('hide');
                        var act = $("#CrudCategoryAction").val();
                        act = act.toLowerCase();
                        ReloadBarang();
                        doSuccess('create', 'success ' + act + ' data', 'success')
                    } else {
                        var errMsg = '<div class="col-md-12"><div class="alert alert-warning mt-2" role="alert"><small><b> Error !</b><br/>' + data.msg + '</small></div></div>'
                        $('#CrudCategoryError').html(errMsg);
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
                    $('#CrudCategoryError').html(errMsg);
                },
            });
        } else {
            alert("form invalid");
        }
    })
</script>