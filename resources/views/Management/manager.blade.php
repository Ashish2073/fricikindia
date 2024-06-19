@extends('management.main')
@section('title', 'Product List')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Form</h4>
                    </div>
                    <div class="card-body">
                        <form id="employee-form" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter your name">
                                <span id="errorname" style="color:red;"></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>

                                <input type="hidden" id="parent_id" name="parent_id"
                                    value="{{ request()->query('id', '0') }}" />
                                <input type="hidden" id="second_segemt" value="{{ request()->segment(2) }}" />



                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Enter your email">
                                <span id="erroremail" style="color:red;"></span>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" name="phone_no" id="phone"
                                    placeholder="Enter your phone number">
                                <span id="errorphone_no" style="color:red;"></span>
                            </div>
                            <div class="form-group">
                                <label for="position">Position</label>
                                <select class="form-control" id="position" name="position">



                                    @if (
                                        (request()->segment(2) == 'manager' || request()->segment(2) == 'assist-manager') &&
                                            request()->segment(3) != 'teamlead')
                                        <option selected disabled>Please Choose Option</option>
                                        <option value="3">Team Lead</option>
                                    @elseif (request()->segment(3) == 'teamlead')
                                        <option selected disabled>Please Choose Option</option>
                                        <option value="4">coller</option>
                                    @else
                                        <option selected disabled>Please Choose Option</option>
                                        <option value="1">Manager</option>
                                        <option value="2">Assistance manager</option>
                                    @endif

                                </select>
                                <span id="errorpostion" style="color:red;"></span>
                            </div>
                            <button type="submit" id="employe-savebutton" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Table</h4>
                    </div>
                    <div class="card-body">
                        <div style="overflow-x: auto; ">
                            <table class="table data-table-management">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone Number</th>
                                        <th scope="col">Position</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('page-script')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            employeeDetailsDataTable();
            $('#employe-savebutton').click(function(event) {
                event.preventDefault();

                var formData = new FormData($("#employee-form")[0]);



                $.ajax({
                    url: "{{ route('cm.employe.save') }}",
                    type: "POST",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },

                    beforeSend: function() {




                        $(`[id="name"]`).html(" ");
                        $(`[id="email"]`).html(" ");
                        $(`[id="phone"]`).html(" ");



                    },
                    success: (data) => {
                        employeeDetailsDataTable();
                        toastr.success(
                            "Employee  Added Sucessfully"
                        );



                    },
                    error: function(xhr, status, error) {

                        if (xhr.status == 422) {







                            errorMessage = xhr.responseJSON.errormessage;

                            console.log(errorMessage);


                            for (var fieldName in errorMessage) {

                                if (errorMessage.hasOwnProperty(fieldName)) {
                                    $(`[id="error${fieldName}"]`).html(errorMessage[fieldName][
                                        0
                                    ]);
                                }

                            }

                            toastr.error(
                                "Somthing get wroung"
                            );


                            $('#loader').html('');
                            $('#main_content').removeAttr('class', 'demo');


                        }













                    }
                });




            });

            function employeeDetailsDataTable() {
                if ($.fn.DataTable.isDataTable('.data-table-management')) {
                    $('.data-table-management').DataTable().clear().destroy();
                }

                var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                var table = $('.data-table-management').DataTable({
                    dom: '<"top"lfB>rt<"bottom"ip><"clear">',
                    buttons: [{
                            extend: 'copy',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        }
                    ],
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    fixedHeader: true,
                    ajax: {
                        url: "{{ route('cm.employeedata') }}",
                        type: "get",
                        data: {
                            _token: "{{ csrf_token() }}",
                            parent_id: `${$("#parent_id").val()}`,
                            second_segemt: `${$("#second_segemt").val()}`
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'serial_number',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'phone_number',
                            name: 'phone_number',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'position',
                            name: 'position',
                            searchable: true
                        },
                        {
                            data: 'created_date',
                            name: 'created_date',
                            orderable: true,
                            searchable: false
                        },

                        {
                            data: 'user_details',
                            name: 'user_details',
                            orderable: true,
                            searchable: true
                        }
                    ],
                    language: {
                        lengthMenu: "Show _MENU_ Entries per Page"
                    }
                });
            };


        });
    </script>


@endsection
