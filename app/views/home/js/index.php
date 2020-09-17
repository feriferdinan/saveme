<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

<script>
    $(document).ready(function() {
        $(".js-cancel").hide()
        $('.select2').select2({
            theme: 'bootstrap4',
        });
        $("#accordionSidebar").addClass('toggled')
        $("#page-top").addClass('sidebar-toggled')
        toastr.options.closeMethod = 'fadeOut';
        toastr.options.closeDuration = 100;
        toastr.options.closeEasing = 'swing';

        let totalRowsLog = $(".total-rows-execute")
        let totalSuccessRowsLog = $(".total-success-rows-execute")
        let totalFailedRowsLog = $(".total-failed-rows-execute")

        var today = new Date();
        var dd = ("0" + (today.getDate())).slice(-2);
        var mm = ("0" + (today.getMonth() + 1)).slice(-2);
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        $(".default-todays-date").attr("value", today);
        let table = $('#tableData').DataTable({})

        function sendData(url, param) {
            var start_time = new Date().getTime();
            var datasend = JSON.parse(param.data)
            let promise = new Promise((resolve, reject) => {
                $.ajax({
                    'url': url,
                    'type': 'POST',
                    'data': param.data,
                    'success': res => {
                        var lastTotal = parseInt(totalRowsLog.text())
                        var lastTotalSuccess = parseInt(totalSuccessRowsLog.text())
                        totalRowsLog.text(lastTotal + 1)
                        totalSuccessRowsLog.text(lastTotalSuccess + 1)
                        var request_time = new Date().getTime() - start_time
                        // toastr.success('Success Send data ' + param.date, 'Success')
                        $("#tableLogBody").append(`<tr>
                           <td style="width: 18%">${param.date}</td>
                           <td style="width: 62%">${param.data}</td>
                           <td class="text-justify" style="width: 10%">${request_time}</td>
                           <td class="text-success text-justify" style="width: 10%"><i class="fas fa-check-circle"></i> Success</td>
                       </tr>`)
                        $("#tableLogBody").animate({
                            scrollTop: $("#tableLogBody")[0].scrollHeight
                        }, 1000);
                        resolve(res)
                    },
                    'error': err => {
                        var lastTotal = parseInt(totalRowsLog.text())
                        var lastTotalFailed = parseInt(totalFailedRowsLog.text())
                        totalRowsLog.text(lastTotal + 1)
                        totalFailedRowsLog.text(lastTotalFailed + 1)
                        var request_time = new Date().getTime() - start_time
                        // toastr.error('Failed Send data ' + param.date, 'Failed')
                        $("#tableLogBody").append(`<tr>
                            <td style="width: 18%">${param.date}</td>
                            <td style="width: 62%">${param.data}</td>
                            <td class="text-justify" style="width: 10%">${request_time}</td>
                            <td class="text-danger text-justify" style="width: 10%"><i class="icon-remove-circle"></i>  Failed</td>
                        </tr>`)
                        $("#tableLogBody").animate({
                            scrollTop: $("#tableLogBody")[0].scrollHeight
                        }, 1000);
                        reject(err)
                    }
                })
            })
            return promise
        }



        $(".js-filter-data").click(function(event) {
            $(this).prop("disabled", true)
            $(this).html("Loading...")

            let project_folder = JSON.parse($("#project_folder").val())
            let start_date = $("#start_date").val()
            let end_date = $("#end_date").val()
            let country = $("#country").val()
            $.post(`<?= BASEURL ?>home/generatedata`, {
                project_folder: project_folder.url,
                datetype: project_folder.datetype,
                prefix: project_folder.prefix,
                separator: project_folder.separator,
                start_date,
                end_date,
                country
            }).done(res => {
                $(this).prop("disabled", false)
                $(this).html("Show Data")
                let resJson = JSON.parse(res)
                $("#tableData").dataTable().fnDestroy()
                table = $('#tableData').DataTable({
                    "data": resJson.data,
                    "columns": [{
                            "data": "id",
                        },
                        {
                            "data": "date",
                            "width": "20%"
                        },
                        {
                            "data": "date_converted",
                            "width": "20%"
                        },
                        {
                            "data": "data",
                            "width": "60%"
                        },

                    ],
                    'select': {
                        'style': 'multi'
                    },
                    columnDefs: [{
                        orderable: false,
                        'checkboxes': {
                            'selectRow': true
                        },
                        targets: 0
                    }],
                });


            }).fail(err => {
                $(this).prop("disabled", false)
                $(this).html("Show Data")
                console.log(err)
            })
        })

        function clearLog() {
            $("#tableLogBody").html("")
            totalRowsLog.text(0)
            totalSuccessRowsLog.text(0)
            totalFailedRowsLog.text(0)
        }
        $(".js-clear-log").click(function(event) {
            clearLog()
        });

        $(".js-send").click(function(event) {
            $(this).prop("disabled", true)
            $(this).html("Sending...")
            // $(".js-cancel").show()
            $("html, body").animate({
                scrollTop: $(document).height()
            }, 2100);

            let send_url = $("#send_url").val()
            var data = table.rows().data();
            var rows_selected = table.column(0).checkboxes.selected()
            console.log(rows_selected, 'rows_selected');
            let promises = []
            var rowIds = []
            $.each(rows_selected, function(index, rowId) {
                rowIds.push(rowId)
            });
            rowIds = [...new Set(rowIds)];
            rowIds.forEach(e => {
                var req = sendData(send_url, data[e])
                promises.push(req)
            })

            $.when.apply(null, promises)
                .done(() => {
                    $(this).prop("disabled", false)
                    $(this).html("Send")
                    $(".js-cancel").hide()
                }).fail(() => {
                    $(this).prop("disabled", false)
                    $(this).html("Send")
                    $(".js-cancel").hide()
                })
            // $(".js-cancel").click(function() {
            //     console.log(promises);
            //     promises.forEach(e => e.cancel())
            //     $(this).prop("disabled", false)
            //     $(this).html("Send")
            //     $(".js-cancel").hide()
            // })

        });




    });
</script>