function custom_datatable(grid, table, ajax_url, unsortable_fields) {
    $(".date-picker").datepicker({autoclose:!0});

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    grid.init({
        src: table,
        onSuccess: function (grid, response) {
        },
        onError: function (grid) {
        },
        onDataLoad: function (grid) {
        },
        loadingMessage: 'Loading...',
        dataTable: {
            "bStateSave": false,

            "lengthMenu": [
                [15, 30, 50, 100, 200, -1],
                [15, 30, 50, 100, 200, "All"]
            ],
            "pageLength": 15,
            "ajax": {
                "url": ajax_url
            },
            // "order": [
            //     [0, "asc"]
            // ],
            "columnDefs": [{
                className: "no-sort",
                "targets": unsortable_fields,/*[0,3,4,5],*/
            }],
            buttons: [
                {extend: 'print', className: 'btn default'},
                {extend: 'copy', className: 'btn default'},
                {extend: 'pdf', className: 'btn default'},
                {extend: 'excel', className: 'btn default'},
                {extend: 'csv', className: 'btn default'},
                {
                    text: 'Reload',
                    className: 'btn default',
                    action: function (e, dt, node, config) {
                        dt.ajax.reload();
                        alert('Datatable reloaded!');
                    }
                }
            ]
        }
    });

    // handle group actionsubmit button click
    grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
        e.preventDefault();
        var action = $(".table-group-action-input", grid.getTableWrapper());
        if (action.val() !== "" && grid.getSelectedRowsCount() > 0) {
            grid.setAjaxParam("customActionType", "group_action");
            grid.setAjaxParam("customActionName", action.val());
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
            grid.clearAjaxParams();
        } else if (action.val() === "") {
            App.alert({
                type: 'danger',
                icon: 'warning',
                message: 'Please select an action',
                container: grid.getTableWrapper(),
                place: 'prepend'
            });
        } else if (grid.getSelectedRowsCount() === 0) {
            App.alert({
                type: 'danger',
                icon: 'warning',
                message: 'No record selected',
                container: grid.getTableWrapper(),
                place: 'prepend'
            });
        }
    });

    grid.getTableWrapper().on('click', '.table-row-delete', function (e) {
        e.preventDefault();
        var record_id = $(this).data('id');
        if (record_id) {
            var action = confirm("Do you want to delete this?");
            if (action) {
                grid.setAjaxParam("actionType", "delete_action");
                grid.setAjaxParam("record_id", record_id);
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            }
        }
    });

    $('#datatable_ajax_tools > li > a.tool-action').on('click', function () {
        var action = $(this).attr('data-action');
        grid.getDataTable().button(action).trigger();
    });
}

function generateArray(str){
    var arr = str.split(",").map(Number);
    return arr;
}

/* This function basically updates the status ["is_seen"]
 * which manages the  "new" tag for specific users in datatable
 */
$("#table_ajax_datatable").on('click', '.btn-detail', function(){
    var data_id     = $(this).data('id');
    var data_seen   = $(this).data('is-seen');
    var data_url    = $(this).data('url');
    var data_url_prefix = $(this).data('url-prefix');
    var url_prefix_arr  = ['certificates', 'permissions'];
    if(jQuery.inArray(data_url_prefix, url_prefix_arr) == -1){
        App.alert({
            type: 'danger',
            icon: 'warning',
            message: "Wrong URL Prefix!",
            container: grid.getTableWrapper(),
            place: 'prepend'
        });
        return;
    }
    $.ajax({
        url: '/'+data_url_prefix+'/seen',
        data: {'data_id':data_id, 'is_seen_val':data_seen},
        method: 'POST',
    }).done(function(response) {
        if(typeof response.success != 'undefined'){
            if(response.success){
                $('#new-notifier-'+data_id).remove();
                window.location.href = data_url;
            }
        }
    }).fail(function (response) {
        var message = "Something Went Wrong!"
        if(typeof response.message != 'undefined'){
            if(response.message.length > 0){
                message = response.message;
            }
        }
        App.alert({
            type: 'danger',
            icon: 'warning',
            message: message,
            container: grid.getTableWrapper(),
            place: 'prepend'
        });
    });
});


$("#table_ajax_datatable").on("click",'.btn-print',function(){
    $(this).hide();
});


$(document).ready(function () {
    if ($("#table_ajax_datatable").length && $("#table_ajax_url").length) {
        var table = $("#table_ajax_datatable");
        var ajax_url = $("#table_ajax_url").val();
        var unsortable_fields = generateArray($("#table_ajax_unsortable").val());
        window.grid = new Datatable();
        custom_datatable(window.grid, table, ajax_url, unsortable_fields);
    }
});
