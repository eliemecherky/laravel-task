@extends('admin.layout.front')

@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Users Management</h2>
                        </div>
                        @can('Users Add')
                            <div class="pull-right">
                                <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
                            </div>
                        @endcan
                    </div>
                </div>

            </div>

        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table id="users-datatable" class="table table-striped users-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th width="280px">Action</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </section>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#users-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'roles',
                        name: 'roles'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });

            $('body').on('click', '.deleteUser', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure you want to delete this User?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr(
                                    'content')
                            }
                        });

                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('delete.user') }}",
                            data: {
                                "id": id
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                if (data.status == "success") {
                                    table.ajax.reload();
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'User has been deleted successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                }
                            },
                            error: function(data) {
                                console.log(data);
                            }
                        });
                    }
                })

            })

        })
    </script>

    @if (Session::has('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session()->get('success') }}',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session()->get('error') }}',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif
@endpush
