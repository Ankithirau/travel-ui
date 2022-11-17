

$(document).ready(function () {

    setTimeout(function () {
        var table = $("#datatable").DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20, 30],
                [5, 10, 20, 30],
            ],
            dom:
                '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2',
                    text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
                            className: 'dropdown-item',
                            // exportOptions: { columns: [3, 4, 5, 6, 7] }
                        },
                        {
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                            className: 'dropdown-item',
                            // exportOptions: { columns: [3, 4, 5, 6, 7] }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: { columns: [3, 4, 5, 6, 7] }
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
                            className: 'dropdown-item',
                            // exportOptions: { columns: [3, 4, 5, 6, 7] }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                            className: 'dropdown-item',
                            // exportOptions: { columns: [3, 4, 5, 6, 7] }
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                },
            ]
            // columnDefs: [
            //     { width: 10, targets: 0 },
            //     { width: 10, targets: 2 },
            //     { width: 10, targets: 3 },

            // ],
        });

        $("#show_table").show();

        // or fade, css display however you'd like.

        $('#insert_btn').append(
            '<input type="submit" id="form-button" value="Send Request" class="btn btn-primary mt-1" data-action="{{route(product.add_variation,isset($prices->id)?$prices->id:0)}}">'
        );

    }, 500);

    var pick_date = [];
    var pick_id = [];
    $(document).on('click', '.groupBtn', function (event) {
        event.preventDefault();
        $(".has_error").remove();
        var value = $(this).parent().closest(".getInput");
        var url = $(".invoice-repeater").attr('action')
        var group_name = value.find("input.group_name").val();
        var total_seat = value.find("input.total_seat").val();
        var pickup_point = value.find("select.pickup_point").val();
        pickup_point.forEach(element => {
            pick_id.push(element.split('#')[0])
            pick_date.push(element.split('#')[1])
        });
        var product_id = $('.product_id').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                '_token': $('meta[name=csrf-token]').attr('content'),
                'group_name': group_name,
                'total_seat': total_seat,
                'pickup_point': pick_id,
                'product_id': product_id,
                'date_concert': pick_date
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.status === 200) {
                    showSuccess(data.msg);
                    value.next('hr').remove();
                    value.remove();
                } else if (data.status === 500) {
                    showError(data.msg);
                }
            }
        }).fail(function (response, status, error) {
            var data = response.responseJSON;
            if (status === "error") {
                $.each(data.errors, function (i, val) {
                    if (i == 'group_name') {
                        value.find("input.group_name").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                    }

                    if (i == 'total_seat') {
                        value.find("input.total_seat").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                    }

                    if (i == 'pickup_point') {
                        value.find("div.position-relative").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                    }

                });
            }
        });

    })

    $(document).on('change', '#eventSelect', function (params) {
        $('#operatorSelect').prop('selectedIndex', 0);
        $('#busSelect').html('<option value="" selected="">Select Bus</option>');
    });

    $(document).on('change', '#filter_events', function (e) {
        var id = $(this).val();
        var url = $(this).data('url');
        $.ajax({
            type: "GET",
            url: url,
            data: { id: id },
            success: function (response) {
                location.reload();
            }
        });
    });

    $(document).on('click', '#send_request', function (e) {
        var status = $(this).val();
        var url = $(this).data('url');

        Swal.fire({
            title: "Are you sure?",
            text: "want to change status of a request?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-outline-secondary ml-1",
            },
            buttonsStyling: false,
        }).then(function (isConfirm) {
            if (isConfirm.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    data: { 'status': status },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 200) {
                            showSuccess(data.msg);
                            location.reload();
                        } else if (data.status === 201) {
                            showInfo(data.msg);
                        } else {
                            showError(data.msg);
                        }
                    }
                });
            } else {

            }
        });
    });

    $(document).on('keyup', '#set_val', function () {
        var mst_val = $(this).val();
        $('#set_variation input[type="text"]').val(mst_val);
    });

    // operatorSelect
    $(document).on("change", "#operatorSelect", function () {
        var id = $(this).val();
        var url = $(this).data('url');
        $.ajax({
            type: "get",
            url: url,
            data: {
                id: id
            },
            success: function (response) {
                var res = JSON.parse(response);
                var list = '';
                if (res.data.length > 0) {
                    $.each(res.data, function (k, v) {
                        list += '<option value="' + v.id + '">' + v.bus_number + '</option>';
                    });
                } else {
                    list += '<option value="">No Record Found</option>';
                }
                $("#busSelect").html(list);
            }
        });
    });

    // reset modal form
    if ($(".create-new").length) {
        $(document).on("click", ".create-new", function () {
            $(".modal").find('form').trigger('reset');
            $("option:selected").removeAttr("selected");
            $(".select2-selection__rendered").empty();
            $("#pointSelect").empty();
            // $(".media-left").empty();
            // $('.modal-content').trigger("reset");
            // $(this).closest('form').trigger("reset");
        });
    }

    //get pickup-point
    $(document).on("change", ".countySelect", function () {
        var id = $(this).val();
        var url = $(this).data('route');
        $("#pointSelect").empty();
        $.ajax({
            type: "get",
            url: url,
            data: {
                id: id
            },
            success: function (response) {
                var res = JSON.parse(response);
                var list = '';
                if (res.data) {
                    $.each(res.data, function (k, v) {
                        for (let j = 0; j < v.length; j++) {
                            list += '<option value="' + v[j].id + '">' + v[j].name.substr(0, 40) + '.....' + '</option>';

                        }

                    });
                } else {
                    list += '<option value="">Select Pickup point</option>';
                }
                $("#pointSelect").html(list);
            }
        });
    });

    var maxLength = 40;

    $('.trim_select > option').text(function (i, text) {
        if (text.length > maxLength) {
            return text.substr(0, maxLength) + '...';
        }
    });

    $(document).on("change", ".buses", function (e) {
        $(this).closest("tr").find(".checkhour").attr("checked", true);
    });

    $(document).on('click', '#chkall', function () {
        if ($("#chkall").is(':checked')) {
            $("#counties_id > option").prop("selected", "selected");
            $("#counties_id").trigger("change");
        } else {
            $("#counties_id > option").removeAttr("selected");
            $("#counties_id").val(null).trigger("change");
        }
    });

    $(document).on('click', '#chkalls', function () {
        if ($("#chkalls").is(':checked')) {
            $("#testSelect4 > option").prop("selected", "selected");
            $("#testSelect4").trigger("change");
        } else {
            $("#testSelect4 > option").removeAttr("selected");
            $("#testSelect4").val(null).trigger("change");
        }
    });

    $(document).on("change", "#route", function () {
        let route_id = $("#route").val();
        let product_id = $("#product_id").val();
        var url = $("#product_id").data("url");
        $.ajax({
            type: "GET",
            url: url,
            data: { route_id: route_id, id: product_id },
            success: function (res) {
                if (res.status) {
                    showWarning(res.status);
                } else {
                    if (res) {
                        $("#get_val").find("tbody").html(res);
                        $('#set_total').text($("#total_booking").val());
                        if (route_id === 'all') {
                            $("#add_th").after(
                                '<th id="route_head">Route</th>'
                            );
                        } else {
                            $("#route_head").remove();
                        }
                    }
                }
            },
        });
    });

    //Check Image Size
    $("#image").bind("change", function () {
        $(".has_error").remove();
        $("#form-button").attr("type", "submit");
        var a = this.files[0].size;
        if (a > 1000000) {
            $(this).after(
                '<div class="text-danger has_error">Image size should be < 1MB</div>'
            );
            $("#form-button").attr("type", "button");
        }
    });

    $(document).on("click", ".update_variation", function () {
        var tr = $(this).closest("tr");
        $(".has_error").remove();

        var date = tr.find("input.date_concert").val();
        var price = tr.find("input.price").val();
        var county_id = tr.find("input.county_id").val();
        var pickup_id = tr.find("input.pickup_id").val();
        var product = tr.find("input.product").val();
        var stock_quantity = tr.find("input.stock_quantity").val();
        var formData = {
            date: date,
            price: price,
            county_id: county_id,
            pickup_id: pickup_id,
            product: product,
            stock_quantity: stock_quantity,
        };
        $.ajax({
            method: "GET",
            url: $(this).data("action"),
            data: formData,
            success: function (response) {
                var data = JSON.parse(response);
                if (data.status === 400) {
                    if (data.stock_quantity) {
                        $("#stock_" + data.pickup_id).after(
                            '<div class="text-danger has_error">' +
                            data.stock_quantity +
                            "</div>"
                        );
                    }
                    if (data.price) {
                        $("#price_" + data.pickup_id).after(
                            '<div class="text-danger has_error">' +
                            data.price +
                            "</div>"
                        );
                    }
                } else if (data.status === 500) {

                    showError(data.msg);
                } else {
                    //play();
                    showSuccess(data.msg);
                    if (data.redirect == true) {
                        window.location.href = data.url;
                    } else {
                        location.reload();
                    }
                }
                //$("#ajax-loader").hide();
            },
        }).fail(function (response, status, error) {
            var data = response.responseJSON;
            if (status === "error") {
                $.each(data.errors, function (i, val) {
                    $("input[name=" + i + "]").after(
                        '<div class="text-danger has_error">' + val + "</div>"
                    );
                    if ($("textarea[name=" + i + "]").length) {
                        $("textarea[name=" + i + "]").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                    }
                    if ($("select[name=" + i + "]").length) {
                        $("select[name=" + i + "]").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                    }
                    // if (i == "pick_point_id") {
                    //   $(".pick_point_id_error").text(val);
                    // }
                    if (i == "pick_point_id" || i == "counties_id") {
                        $(".error_" + i).text(val);
                    }
                });
            }
        });
    });

    $(document).on("click", ".schedule_update", function () {
        var url = $(this).data("action");
        var tr = $(this).closest("tr");
        $(".has_error").remove();
        var row = $(this).parent();

        var route_name = tr.find("select.routes option:selected").text();
        if (route_name == "Select Route") {
            route_name = "";
        }
        var route_id = tr.find("select.routes").val();
        var product_id = tr.find("input.product_id").val();
        var booked_seat = tr.find("input.seat_count").val();
        var schedule_date = tr.find("input.date_concert").val();
        var pickup_point_id = tr.find("input.pickup_point_id").val();
        var buses = tr.find("select.buses").val();

        var formData = {
            route_name: route_name.trim(),
            route_id: route_id,
            product_id: product_id,
            booked_seat: booked_seat,
            schedule_date: schedule_date,
            pickup_point_id: pickup_point_id,
            product_id: product_id,
            buses: buses,
        };

        $.ajax({
            method: "GET",
            url: url,
            data: formData,
            success: function (response) {
                var data = JSON.parse(response);
                if (data.status === 400) {
                    if (data.route_name) {
                        $("#route_name_" + data.pickup_id).after(
                            '<div class="text-danger has_error text-center"><small>' +
                            data.route_name +
                            "</small></div>"
                        );
                    }
                    if (data.buses) {
                        $("#buses_" + data.pickup_id).after(
                            '<div class="text-danger has_error text-center"><small>' +
                            data.buses +
                            "</small></div>"
                        );
                    }
                } else if (data.status === 500) {
                    showError(data.msg);
                } else {
                    showSuccess(data.msg);
                    row.slideUp(300, function () {
                        row.closest("tr").remove();
                        $("table#get_routes tbody tr").each(function (index) {
                            $(this)
                                .children("td")
                                .eq(0)
                                .text(index + 1);
                        });
                    });
                    // if (data.redirect == true) {
                    //   window.location.href = data.url;
                    // } else {
                    //   location.reload();
                    // }
                }
            },
        }).fail(function (response, status, error) {
            var data = response.responseJSON;
            if (status === "error") {
                $.each(data.errors, function (i, val) {
                    $("input[name=" + i + "]").after(
                        '<div class="text-danger has_error">' + val + "</div>"
                    );
                    if ($("textarea[name=" + i + "]").length) {
                        $("textarea[name=" + i + "]").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                    }
                    if ($("select[name=" + i + "]").length) {
                        $("select[name=" + i + "]").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                    }
                    if (i == "pick_point_id" || i == "counties_id") {
                        $(".error_" + i).text(val);
                    }
                });
            }
        });
    });

    $(document).on('click', '#edit_info', function () {
        var btn_val = $(this).val();
        if (btn_val == 1) {
            $("#form_edit :input").attr("disabled", false);
            $(this).removeClass("btn-success").addClass("btn-primary").val(0);
        } else {
            $("#form_edit :input").attr("disabled", true);
            $(this).removeClass("btn-primary").addClass("btn-success").val(1);
        }
    })

    $(document).on("submit", "form[name=ajax_form]", function (e) {

        if ($(".editor")[0] && $(".editors")[0]) {
            var myEditor = document.querySelector('.editor');
            var myEditors = document.querySelector('.editors');
            var html = myEditor.children[0].innerHTML;
            var htmls = myEditors.children[0].innerHTML;
            $("#product_desc").val(html);
            $("#shortdesc").val(htmls);
        }

        var formData = new FormData(this);
        var $this = this;
        var pervious_action = $(this).data('pervious_action')

        e.preventDefault();
        $(".has_error").remove();

        $(".error-inline").text("");
        var fail = false;

        $(this)
            .find("select, textarea, input")
            .each(function () {
                if (!$(this).prop("required")) {
                } else {
                    if (!$(this).val()) {
                        fail = true;
                        name = $(this).attr("name");
                        $(this).after(
                            '<div class="text-danger has_error">' +
                            name +
                            " is required</div>"
                        );
                    }
                }
            });
        if (!fail) {
            $.ajax({
                type: $(this).attr("method"),
                url: $(this).attr("action"),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                // beforeSend: function () {
                //   $("#ajax-loader").show();
                // },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 400) {
                        $.each(data.error, function (i, val) {
                            $("input[name=" + i + "]").after(
                                '<div class="text-danger has_error">' +
                                val +
                                "</div>"
                            );
                            if ($("textarea[name=" + i + "]").length) {
                                $("textarea[name=" + i + "]").after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                            }
                            if ($("select[name=" + i + "]").length) {
                                $("select[name=" + i + "]").after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                            }
                        });
                    } else if (data.status === 500) {
                        $('.errors').html('<span class="text-danger has_error"><small>' + data.msg + '</small></span>');
                        showError(data.msg);
                        // swal("Sorry!", data.msg, "error");
                    } else {
                        if ($(".modal").length) {
                            $(".modal").modal("hide");
                        }
                        //set previous url to action attr in form
                        $($this).prop('action', pervious_action);

                        showSuccess(data.msg);

                        setTimeout(() => {
                            if (data.redirect == true) {
                                window.location.href = data.url;
                            } else {
                                location.reload();
                            }
                        }, 1000);
                    }
                },
            }).fail(function (response, status, error) {
                var data = response.responseJSON;

                if (status === "error") {
                    $.each(data.errors, function (i, val) {

                        if (
                            val == "Concert Date is required." ||
                            val == "The price is required." ||
                            val == "The stock quantity is required." ||
                            val == "this is field is required."
                        ) {
                            $("." + i).text(val);
                        } else {

                            $("input[name=" + i + "]").after(
                                '<div class="text-danger has_error">' +
                                val +
                                "</div>"
                            );
                            if ($("textarea[name=" + i + "]").length) {
                                $("textarea[name=" + i + "]").after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                            }
                            if ($("select[name=" + i + "]").length) {
                                $("select[name=" + i + "]").after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                            }
                            if (i == "pickup_point_id") {
                                $("#pickup_point").find('.select2-container--default').after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                                if (i !== "counties_id") {
                                    $("#event_county").find('.select2-container--default').after(
                                        '<div class="text-danger has_error">' +
                                        val +
                                        "</div>"
                                    );
                                }
                            }
                            if (i == "counties_id") {
                                $("#event_county").find('.select2-container--default').after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                            }

                            // if (i == "pickup_point_id" || i == "counties_id") {
                            //     $(".error_" + i).text(val);
                            // }
                        }
                    });
                }
            });
        }
    });

    //Submit System Form
    $(document).on("submit", "form[name=ajax_form_system]", function (e) {
        var formData = new FormData(this);
        e.preventDefault();
        $(".has_error").hide();
        var fail = false;
        $(this)
            .find("select, textarea, input")
            .each(function () {
                if (!$(this).prop("required")) {
                } else {
                    if (!$(this).val()) {
                        fail = true;
                        name = $(this).attr("name");
                        $(this).after(
                            '<div class="text-danger has_error">' +
                            name +
                            " is required</div>"
                        );
                    }
                }
            });
        if (!fail) {
            $.ajax({
                type: $(this).attr("method"),
                url: $(this).attr("action"),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                // beforeSend: function () {
                //   $("#ajax-loader").show();
                // },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 400) {
                        $.each(data.error, function (i, val) {
                            $("input[name=" + i + "]").after(
                                '<div class="text-danger has_error">' +
                                val +
                                "</div>"
                            );
                            if ($("textarea[name=" + i + "]").length) {
                                $("textarea[name=" + i + "]").after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                            }
                            if ($("select[name=" + i + "]").length) {
                                $("select[name=" + i + "]").after(
                                    '<div class="text-danger has_error">' +
                                    val +
                                    "</div>"
                                );
                            }
                        });
                    } else if (data.status === 500) {
                        swal("Sorry!", data.msg, "error");
                    } else {
                        //play();
                        swal("Great!", data.msg, "success").then(function () {
                            if (data.redirect == true) {
                                window.location.href = data.url;
                            } else {
                                location.reload();
                            }
                            // $("#form-button").prop('disabled', true);
                            // $('.modal').modal('hide');
                        });
                    }
                    //$("#ajax-loader").hide();
                },
            }).fail(function (response, status, error) {
                var data = response.responseJSON;
                if (status === "error") {
                    $.each(data.errors, function (i, val) {
                        $("input[name=" + i + "]").after(
                            '<div class="text-danger has_error">' +
                            val +
                            "</div>"
                        );
                        if ($("textarea[name=" + i + "]").length) {
                            $("textarea[name=" + i + "]").after(
                                '<div class="text-danger has_error">' +
                                val +
                                "</div>"
                            );
                        }
                        if ($("select[name=" + i + "]").length) {
                            $("select[name=" + i + "]").after(
                                '<div class="text-danger has_error">' +
                                val +
                                "</div>"
                            );
                        }
                    });
                }
            });
        }
    });

    //Append Selected text to input
    if ($(".append_selected_text").length) {
        $(".append_selected_text").change(function () {
            var text = $(this).find("option:selected").text();
            $(this).parent("div").find("input").val(text);
        });
    }

    // View Record
    $(document).on("click", ".viewRecord", function (e) {
        $(".has_error").hide();
        var url = $(this).data("url");
        var title = $(this).data("title");
        var row = $(this).parent();
        $.ajax({
            type: "get",
            url: url,
            success: function (response) {
                $(".modal-content").html(response);
            },
        });
    });

    // Edit Record
    $(document).on("click", ".editRecord", function (e) {
        var url = $(this).data("url");

        var updateAction = $(this).data("action");

        var title = $(this).data("title");

        var row = $(this).parent();
        //get previus action url
        var previous_action = $("#modal" + title).find("form").attr("action");

        $.ajax({
            type: "get",
            url: url,
            success: function (response) {
                var data = JSON.parse(response);
                if (data.status === 200) {
                    $("#modal" + title).modal("show");
                    $("#modal" + title)
                        .find("form")
                        .attr("action", updateAction);

                    //set previus action in data previous_action attr
                    $("#modal" + title)
                        .find("form")
                        .attr("data-previous_action", previous_action);

                    $("#modal" + title)
                        .find("form")
                        .append(
                            '<input name="_method" type="hidden" value="PUT">'
                        );
                    $(".modal-title").text("Edit " + title);
                    $("#form-button").text("Update");

                    $.each(data.result, function (i, val) {
                        const site_url = window.location.origin + '/images/uploads/';
                        if (i == 'photo') {
                            $('#banner_image').html('<img src="' + site_url + '/slider/' + val + '" alt="avatar" height="64" width="100" class="cursor-pointer">');
                        }
                        if (i == 'category_image') {
                            if (val) {
                                $('#banner_image').html('<img src="' + site_url + '/category_image/' + val + '" alt="avatar" height="64" width="100" class="cursor-pointer">');
                            } else {
                                $('#banner_image').empty();
                            }
                        }
                        if (
                            i != "image" &&
                            i != "image1" &&
                            i != "image2" &&
                            i != "image3" &&
                            i != "image4" &&
                            i != "image5" &&
                            i != "image6" &&
                            i != "file"
                        ) {
                            $("input[name=" + i + "]").val(val);
                        }
                        if ($("textarea[name=" + i + "]").length) {
                            $("textarea[name=" + i + "]").val(val);
                        }
                        if (i == "role_id") {
                            $("select[name=role]").val(val);
                        } else {

                            if (i == "discount_type") {
                                var d_type = 1;
                                if (val == "value") {
                                    d_type = 2;
                                }
                                $("select[name=" + i + "]").val(d_type);
                            } else {
                                if (i == "pickup_point_id") {
                                    var list = "";
                                    list += '<option value="">Select Pickup point</option>';
                                    for (let p = 0; p < val.length; p++) {
                                        list += '<option value="' + val[p].id + '" selected>' + val[p].name.substr(0, 40) + '.....' + '</option>';

                                    }

                                    $(".name_s").html(list);
                                    // let point = val.split(",");

                                    // for (let p = 0; p < point.length; p++) {
                                    //     $(
                                    //         ".name_s option[value=" +
                                    //             point[p].trim() +
                                    //             "]"
                                    //     ).attr("selected", true);

                                    //     $("#testSelect1_inputCount")
                                    //         .css("visibility", "visible")
                                    //         .text(point.length);

                                    //     $(".multiselect-list ul li")
                                    //         .find(
                                    //             "[data-val='" +
                                    //                 point[p].trim() +
                                    //                 "']"
                                    //         )
                                    //         .prop("checked", true)
                                    //         .addClass("active");

                                    //     var value = $(
                                    //         ".name_s option[value=" +
                                    //             point[p].trim() +
                                    //             "]"
                                    //     ).text();
                                    //     $(".multiselect-input").attr(
                                    //         "value",
                                    //         ""
                                    //     );
                                    //     $(".multiselect-input").val(value);
                                    // }
                                } else {
                                    $("select[name=" + i + "]").val(val);
                                }

                                if (i == 'counties_id') {

                                    var county_list = "";
                                    for (let p = 0; p < val.length; p++) {

                                        $('.countySelect option[value=' + val[p].id + ']').attr("selected", true);

                                        county_list += '<li class="select2-selection__choice"><span class="select2-selection__choice__remove" role="presentation">×</span>' + val[p].name + '</li>';
                                    }
                                    $(".select2-selection__rendered").html(county_list);
                                }
                            }
                        }
                        if (i == "state_id") {
                            setTimeout(function () {
                                getAjaxDataPlace(
                                    val,
                                    $(".editRecord").data("place_list")
                                );
                            }, 500);
                        }
                        if (i == "place_id") {
                            setTimeout(function () {
                                $("select[name=" + i + "]").val(val);
                            }, 1000);
                        }

                        if (
                            i == "image" ||
                            i == "image1" ||
                            i == "image2" ||
                            i == "image3" ||
                            i == "image4" ||
                            i == "image5" ||
                            i == "image6"
                        ) {
                            if (val != null) {
                                $("input[name=" + i + "]").prop(
                                    "required",
                                    false
                                );
                                $("input[name=" + i + "]").after(
                                    '<div class="text-center mt-2"><img src="' +
                                    val +
                                    '" alt="Image" width="100px"> <a href="' +
                                    val +
                                    '" download title="Download" class="text-primary"><img src="" alt="Download"></a></div>'
                                );
                            }
                            $("input[name=" + i + "]").after(
                                '<input name="old_image" value="' +
                                val +
                                '" type="hidden">'
                            );
                        } else if (i == "file") {
                            if ($(".uploaded-file").length) {
                                $(".uploaded-file").html("");
                            }
                            if (val != "") {
                                $("input[name=" + i + "]").after(
                                    '<a href="' +
                                    val +
                                    '" taget="_blank" class="uploaded-file"><i class="fa fa-file-pdf-o"></i></a>'
                                );
                            }
                            $("input[name=" + i + "]").after(
                                '<input name="old_file" value="' +
                                val +
                                '" type="hidden">'
                            );
                        }
                    });
                } else {
                    swal("Sorry!", data.msg, "error");
                }
            },
        }).fail(function (params) {
        });
    })

    //Change  Record satus
    $(document).on("click", ".updateStatus", function (e) {

        var url = $(this).data("url");

        var id = $(this).data("status_id");

        Swal.fire({
            title: "Are you sure?",
            text: "want to change status?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-outline-secondary ml-1",
            },
            buttonsStyling: false,
        }).then(function (isConfirm) {
            if (isConfirm.isConfirmed) {
                $.ajax({
                    type: "get",
                    url: url,
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 200) {
                            //play();
                            showSuccess(data.msg);
                            // swal("Success", data.msg, "success").then(function () {
                            location.reload();
                            // });
                        } else {
                            showError(data.msg);
                            // swal("Sorry!", data.msg, "error");
                        }
                    },
                });
            } else {
                if (isConfirm.isConfirmed == false) {
                    if (status == 1) {
                        $("#customSwitch" + id).prop("checked", false);
                    } else {
                        $("#customSwitch" + id).prop("checked", true);
                    }
                }
            }
        });

    });

    //Delete Record
    $(document).on("click", ".deleteRecord", function (e) {
        var url = $(this).data("url");
        var row = $(this).parent();

        Swal.fire({
            title: "Are you sure?",
            text: "want to delete?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-outline-secondary ml-1",
            },
            buttonsStyling: false,
        }).then(function (isConfirm) {
            if (isConfirm.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        _method: "DELETE",
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 200) {
                            // swal("Success", data.msg, "success").then(function () {
                            showSuccess(data.msg);
                            row.slideUp(300, function () {
                                row.closest("tr").remove();
                                $("table#datatable tbody tr").each(function (index) {
                                    $(this).children("td").first().text(index + 1);
                                });
                            });
                            // });
                        } else {
                            showError(data.msg);
                        }
                    },
                });
            }
        });
    });

    //Remove Image
    $(document).on("click", ".removeImage", function (e) {
        var url = $(this).data("url");
        var src = $(this).data("src");
        var row = $(this).parent();
        swal("Are you sure", "want to delete?", "warning", {
            dangerMode: true,
            buttons: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                        src: src,
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 200) {
                            //
                            swal("Success", data.msg, "success").then(
                                function () {
                                    row.closest("div").remove();
                                }
                            );
                        } else {
                            swal("Sorry!", data.msg, "error");
                        }
                    },
                });
            }
        });
    });

    //Change  Record satus
    $(document).on("click", ".updateStatus", function (e) {

        var url = $(this).data("url");

        var id = $(this).data("status_id");

        var status = $(this).prop('checked') == true ? 1 : 0;

        Swal.fire({
            title: "Are you sure?",
            text: "want to change status?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-outline-secondary ml-1",
            },
            buttonsStyling: false,
        }).then(function (isConfirm) {
            if (isConfirm.isConfirmed) {
                $.ajax({
                    type: "get",
                    url: url,
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 200) {
                            //play();
                            showSuccess(data.msg);
                            // swal("Success", data.msg, "success").then(function () {
                            location.reload();
                            // });
                        } else {
                            showError(data.msg);
                            // swal("Sorry!", data.msg, "error");
                        }
                    },
                });
            } else {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Status not updated',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
                if (isConfirm.isConfirmed == false) {
                    if (status == 1) {
                        $("#customSwitch" + id).prop("checked", false);
                    } else {
                        $("#customSwitch" + id).prop("checked", true);
                    }
                }
            }
        });

    });

    //Convert kyc client to non-kyc client
    $(document).on("click", ".updateKycStatus", function (e) {
        var url = $(this).data("url");
        swal("Are you sure", "want to convert?", "warning", {
            dangerMode: true,
            buttons: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "get",
                    url: url,
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 200) {
                            //play();
                            swal("Success", data.msg, "success").then(
                                function () {
                                    location.reload();
                                }
                            );
                        } else {
                            swal("Sorry!", data.msg, "error");
                        }
                    },
                });
            }
        });
    });

    $("#get_point").click(function () {
        var selected = $(".counties_id").val();
        var url = $(this).data("url");
        $("#testSelect2").remove();
        $(".pick_label").remove();

        $.ajax({
            type: "post",
            url: url,
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                county: selected,
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.status == 200) {

                    var list = "";
                    list +=
                        '<label for="pick_point_id">Pick up Points & Departure Times</label></br><select name="pickup_point_id[]" class="form-control pickup_point_id select2 pointAll" multiple id="testSelect4">';
                    $.each(data.result, function (k, v) {
                        if (v.length > 0) {
                            for (let index = 0; index < v.length; index++) {
                                list +=
                                    '<option value="' +
                                    v[index].id +
                                    '" class="checkpoint">' +
                                    v[index].name +
                                    "</option>";
                            }
                        } else {
                            list += '<option value="">No Place Found!</option>';
                        }
                    });
                    list +=
                        '</select><div class="custom-control custom-checkbox mb-2 mt-1"><input type="checkbox" class="custom-control-input" id="chkalls"><label class="custom-control-label" for="chkalls">Select All</label></div><div class="text-danger error_pick_point_id error-inline"></div>';

                    $("#pickup_point").html(list);
                    $('.select2').each(function () {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>');
                        $this.select2({
                            dropdownAutoWidth: true,
                            width: '100%',
                            dropdownParent: $this.parent()
                        });
                    });
                    $("#countySelect").empty();
                } else {
                    $("#countySelect").html(
                        '<div class="text-danger has_error">please select county</div>'
                    );
                }

            },
        });
    });

    //Inline form submit
});

function getCategoryData(type, url) {
    $.ajax({
        type: "get",
        url: url,
        data: {
            type: type,
        },
        success: function (response) {
            var data = JSON.parse(response);
            var list = "";
            if (data.length > 0) {
                list += '<option value="">Select</option>';
                $.each(data, function (k, v) {
                    list +=
                        '<option value="' +
                        v.id +
                        '">' +
                        v.category +
                        "</option>";
                });
            } else {
                list += '<option value="">No Category Found!</option>';
            }
            $("#category").html(list);
        },
    });
}

function getAjaxData(id, url) {
    $.ajax({
        type: "get",
        url: url,
        data: {
            id: id,
        },
        success: function (response) {
            var data = JSON.parse(response);
            var list = "";
            if (data.length > 0) {
                list += '<option value="">Select</option>';
                $.each(data, function (k, v) {
                    list +=
                        '<option value="' +
                        v.id +
                        '">' +
                        v.category +
                        "</option>";
                });
            } else {
                list += '<option value="">No Category Found!</option>';
            }
            $("#append_list").html(list);
        },
    });
}

//Get City List
function getAjaxDataCity(id, url) {
    $.ajax({
        type: "get",
        url: url,
        data: {
            id: id,
        },
        success: function (response) {
            var data = JSON.parse(response);
            var list = "";
            if (data.length > 0) {
                list += '<option value="">Select</option>';
                $.each(data, function (k, v) {
                    list +=
                        '<option value="' + v.id + '">' + v.name + "</option>";
                });
            } else {
                list += '<option value="">No City Found!</option>';
            }
            $("#append_list").html(list);
        },
    });
}

//Get Place List
function getAjaxDataPlace(id, url) {
    $.ajax({
        type: "get",
        url: url,
        data: {
            id: id,
        },
        success: function (response) {
            var data = JSON.parse(response);
            var list = "";
            if (data.length > 0) {
                list += '<option value="">Select</option>';
                $.each(data, function (k, v) {
                    list +=
                        '<option value="' + v.id + '">' + v.name + "</option>";
                });
            } else {
                list += '<option value="">No Place Found!</option>';
            }
            $("#place_id").html(list);
        },
    });
}

//Play alert sound
function play() {
    var audio = document.getElementById("audio");
    audio.play();
}

function playAbeYaar() {
    var audio = document.getElementById("abe-yaar");
    audio.play();
}

//Logout function
function logout() {

    Swal.fire({
        title: "Are you sure?",
        text: "want to logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "OK",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-outline-secondary ml-1",
        },
        buttonsStyling: false,
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {

            $(".logout-form").submit();
        }
    });
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var num = document.getElementById('someid').value;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) || (num > 10)) {
        if (num >= 10) {

            $("#checkIn").html(
                '<div class="text-danger has_error">choose Ticket checkIn from 1-10</div>'
            );
            $(".has_error").delay(1000);
            document.getElementById('someid').value = "";
        }
        return false;
    } else {
        return true;
    }

}

const isRtl = $("html").attr("data-textdirection") === "rtl";

function showSuccess(msg) {
    toastr["success"](msg, "Success!", {
        closeButton: true,
        tapToDismiss: false,
        rtl: isRtl,
    });
}
function showInfo(msg) {
    toastr["info"](msg, "Info!", {
        closeButton: true,
        tapToDismiss: false,
        rtl: isRtl,
    });
}
function showError(msg) {
    toastr["error"](msg, "Error!", {
        closeButton: true,
        tapToDismiss: false,
        rtl: isRtl,
    });
}
function showWarning(msg) {
    toastr["warning"](msg, "Warning!", {
        closeButton: true,
        tapToDismiss: false,
        rtl: isRtl,
    });
}

/**
 * @license
 * Copyright 2021 Google LLC.
 * SPDX-License-Identifier: Apache-2.0
 */
// The following example creates five accessible and
// focusable markers.
function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: 51.54879, lng: -9.2637 },
    });
    // Set LatLng and title text for the markers. The first marker (Boynton Pass)
    // receives the initial focus when tab is pressed. Use arrow keys to
    // move between markers; press tab again to cycle through the map controls.
    const tourStops = [
        [{ lat: 51.54879, lng: -9.26374 }, "Boynton Pass"],
        [{ lat: 51.53879, lng: -9.25374 }, "Airport Mesa"],
        [{ lat: 51.52879, lng: -9.24374 }, "Chapel of the Holy Cross"],
        [{ lat: 51.51879, lng: -9.23374 }, "Red Rock Crossing"],
        [{ lat: 51.50879, lng: -9.22374 }, "Bell Rock"],
    ];
    const tourStopss = [{ lat: 51.54879, lng: -9.26374 }];
    const flightPath = new google.maps.Polyline({
        path: tourStopss,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 2,
    });

    flightPath.setMap(map);
    // Create an info window to share between markers.
    const infoWindow = new google.maps.InfoWindow();

    // Create the markers.
    const image = "https://gcdnb.pbrd.co/images/EsjrYbyrkSq7.png?o=1";

    tourStops.forEach(([position, title], i) => {
        const marker = new google.maps.Marker({
            position,
            map,
            icon: image,
            title: `${i + 1}. ${title}`,
            label: {
                text: `${i + 1}`, // codepoint from https://fonts.google.com/icons
                fontFamily: "Material Icons",
                color: "#0d0c0a",
                fontSize: "0px",
            },
            optimized: false,
        });

        // Add a click listener for each marker, and set up the info window.
        marker.addListener("click", () => {
            infoWindow.close();
            infoWindow.setContent(marker.getTitle());
            infoWindow.open(marker.getMap(), marker);
        });
    });
}

window.initMap = initMap;

    // $(document).on("click", ".get_variation", function () {
    //     var id=$('.variation').val();
    //     var url = $(this).data('url');
    //     if (id) {
    //     $.ajax({
    //         type: "get",
    //         url: url,
    //         data: {
    //             id: id
    //         },
    //         success: function (response) {
    //             $("#set_variation").html(response);
    //         }
    //     });
    //     }
    // });
    // var clicked = false;
    // $(document).on('change', ".countyAll", function () {
    //     $('.checkhour').prop('selected', true);
    //     $list="";
    //     $(".countyAll option").each(function(){
    //         $id=$(this).data("select2-id");
    //         $val=$(this).text();
    //         if ($id!=='checkall') {
    //             $list+='<li class="select2-selection__choice" title="'+$val+'" data-select2-id="'+$id+'"><span class="select2-selection__choice__remove" role="presentation">×</span>'+$val+'</li>';
    //         }
    //     });
    //     $("#event_county").find(".select2-selection__rendered").html($list);
    //     $('.checkhour option').prop('selected', true);
    //     $(".checkhour").prop("checked", !clicked);
    //     clicked = !clicked;
    //     this.innerHTML = clicked ? "Select" : "Deselect";
    // });
    // $(document).on('change', ".countyAll", function () {
    //     var btn_checker=$id=$(this).data("select2-id");
    //     if (btn_checker==='Select All') {
    //     $('.checkcounty').prop('selected', true);
    //     $list="";
    //     $(".countyAll option").each(function(){
    //         var $id=$(this).data("select2-id");
    //         var $val=$(this).text();
    //         if ($val=="Select All") {
    //         if ($id!=='checkall') {
    //             $list+='<li class="select2-selection__choice" title="'+$val+'" data-select2-id="'+$id+'"><span class="select2-selection__choice__remove" role="presentation">×</span>'+$val+'</li>';
    //         }
    //     }
    //     });
    //     $("#event_county").find(".select2-selection__rendered").html($list);
    //     }
    //     $(".select2-selection__rendered").html($list);
    //     $('.checkhour option').prop('selected', true);
    //     $(".checkhour").prop("checked", !clicked);
    //     clicked = !clicked;
    //     this.innerHTML = clicked ? "Select" : "Deselect";
    // });
    //      $(document).on('change', ".pointAll", function () {
    //     $('.checkpoint').prop('selected', true);
    //     $list="";
    //     $(".pointAll option").each(function(){
    //         $id=$(this).data("select2-id");
    //         $val=$(this).text();
    //         if ($id!=='checkall' && $val!=='No Place Found!') {
    //             $list+='<li class="select2-selection__choice" title="'+$val+'" data-select2-id="'+$id+'"><span class="select2-selection__choice__remove" role="presentation">×</span>'+$val+'</li>';
    //         }
    //     });
    //     $("#pickup_point").find(".select2-selection__rendered").html($list);
    //     $(".select2-selection__rendered").html($list);
    //     $('.checkhour option').prop('selected', true);
    //     $(".checkhour").prop("checked", !clicked);
    //     clicked = !clicked;
    //     this.innerHTML = clicked ? "Select" : "Deselect";
    // });