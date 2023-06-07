@extends('admin.layout.front')

@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Categories header) -->
        <div class="content-header ">
            <div class="container-fluid my-4">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <h1>Categories</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Categories</a></li>
                            <li class="breadcrumb-item active">Edit Category</li>
                        </ol>
                    </div>
                    <div class="col-md-12">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger my-4">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- jquery validation -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Category</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div style="padding: 15px">
                                    <form id="quickForm"
                                        action="{{ route('categories.update', ['category' => $category->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="card-body">
                                            <div class="row py-4">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            id="name" placeholder="Enter name"
                                                            value="{{ $category->name }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="slug">slug</label>
                                                        <input type="text" name="slug" class="form-control"
                                                            id="slug" placeholder="Enter slug" readonly
                                                            value="{{ $category->slug }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mt-4">
                                                        <input type="checkbox" id="status" name="status"
                                                            {{ $category->status == 1 ? 'checked' : null }} />
                                                        <label for="status">Active</label>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $("#name").keyup(function() {
                var Text = $(this).val();
                Text = Text.toLowerCase();
                Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
                $("#slug").val(Text);
            });

        })
    </script>
@endpush
