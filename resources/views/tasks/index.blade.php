@extends('layouts.app')
@section('content')

    {{-- Modal for add the task --}}
    <div class="modal fade" id="AddTaskModal" tabindex="-1" aria-labelledby="AddTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <ul id="save_msgList"></ul>

                    <div class="form-group mb-3">
                        <label for="">Title</label>
                        <input type="text" required class="title form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Description</label>
                        <input type="text" required class="description form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_task">Save</button>
                </div>

            </div>
        </div>
    </div>
    {{-- End - Add Modal --}}


    {{-- Modal for Edit and Update the task --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit & Update Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <ul id="update_msgList"></ul>

                    <input type="hidden" id="task_id" />

                    <div class="form-group mb-3">
                        <label for="">Title</label>
                        <input type="text" id="title" required class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Description</label>
                        <input type="text" id="description" required class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update_task">Update</button>
                </div>

            </div>
        </div>
    </div>
    {{-- End- Edit Modal --}}


    {{-- Modal for delete the task --}}
    <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Confirm to Delete Task ?</h4>
                    <input type="hidden" id="deleteing_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary delete_task">Yes Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End - Delete Modal --}}


    {{-- Table for showing all the tasks --}}
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">

                <div id="success_message"></div>

                <div class="card">
                    <div class="card-header">
                        <h4>
                            Tasks
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#AddTaskModal">Add Task</button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>

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
    {{-- End - Table --}}
@endsection

{{-- AJAX --}}
@section('scripts')

    <script>
        $(document).ready(function () {

            fetchtask();

            function fetchtask() {
                $.ajax({
                    type: "GET",
                    url: "/get_tasks",
                    dataType: "json",
                    success: function (response) {

                        $('tbody').html("");
                        $.each(response.tasks, function (key, item) {
                            $('tbody').append('<tr>\
                                <td>' + item.id + '</td>\
                                <td>' + item.title + '</td>\
                                <td>' + item.description + '</td>\
                                <td><button type="button" value="' + item.id + '" class="btn btn-primary editbtn btn-sm">Edit</button>\
                                <button type="button" value="' + item.id + '" class="btn btn-danger deletebtn btn-sm">Delete</button></td>\
                            \</tr>');
                        });
                    }
                });
            }

            $(document).on('click', '.add_task', function (e) {
                e.preventDefault();

                $(this).text('Sending..');

                var data = {
                    'title': $('.title').val(),
                    'description': $('.description').val(),
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/store",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 400) {
                            $('#save_msgList').html("");
                            $('#save_msgList').addClass('alert alert-danger');
                            $.each(response.errors, function (key, err_value) {
                                $('#save_msgList').append('<li>' + err_value + '</li>');
                            });
                            $('.add_task').text('Save');
                        } else {
                            $('#save_msgList').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('#AddTaskModal').find('input').val('');
                            $('.add_task').text('Save');
                            $('#AddTaskModal').modal('hide');
                            fetchtask();
                        }
                    }
                });

            });

            $(document).on('click', '.editbtn', function (e) {
                e.preventDefault();
                var task_id = $(this).val();

                $('#editModal').modal('show');
                $.ajax({
                    type: "GET",
                    url: "/edit_task/" + task_id,
                    success: function (response) {
                        if (response.status == 404) {
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('#editModal').modal('hide');
                        } else {

                            $('#title').val(response.task.title);
                            $('#description').val(response.task.description);
                            $('#task_id').val(task_id);
                        }
                    }
                });
                $('.btn-close').find('input').val('');

            });

            $(document).on('click', '.update_task', function (e) {
                e.preventDefault();

                $(this).text('Updating..');
                var id = $('#task_id').val();

                var data = {
                    'title': $('#title').val(),
                    'description': $('#description').val(),

                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: "/update_task/" + id,
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 400) {
                            $('#update_msgList').html("");
                            $('#update_msgList').addClass('alert alert-danger');
                            $.each(response.errors, function (key, err_value) {
                                $('#update_msgList').append('<li>' + err_value +
                                    '</li>');
                            });
                            $('.update_task').text('Update');
                        } else {
                            $('#update_msgList').html("");

                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('#editModal').find('input').val('');
                            $('.update_task').text('Update');
                            $('#editModal').modal('hide');
                            fetchtask();
                        }
                    }
                });

            });

            $(document).on('click', '.deletebtn', function () {
                var task_id = $(this).val();
                $('#DeleteModal').modal('show');
                $('#deleteing_id').val(task_id);
            });

            $(document).on('click', '.delete_task', function (e) {
                e.preventDefault();

                $(this).text('Deleting..');
                var id = $('#deleteing_id').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "DELETE",
                    url: "/delete_task/" + id,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 404) {
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('.delete_task').text('Yes Delete');
                        } else {
                            $('#success_message').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('.delete_task').text('Yes Delete');
                            $('#DeleteModal').modal('hide');
                            fetchtask();
                        }
                    }
                });
            });

        });

    </script>

@endsection
