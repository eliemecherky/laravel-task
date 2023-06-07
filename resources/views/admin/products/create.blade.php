@extends('admin.layout.front')

@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <div class="content-header ">
            <div class="container-fluid my-4">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <h1>Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Product</a></li>
                            <li class="breadcrumb-item active">Add Product</li>
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
                                    <h3 class="card-title">Create Product</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div style="padding: 15px">
                                    <form id="quickForm" action="{{ route('products.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="tab-content" id="nav-tabContent">
                                            <!--Start First Tab-->
                                            <div class="tab-pane fade show active" id="general" role="tabpanel"
                                                aria-labelledby="general-tab">
                                                <div class="row py-4">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                id="name" placeholder="Enter name">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="slug">slug</label>
                                                            <input type="text" name="slug" class="form-control"
                                                                id="slug" placeholder="Enter slug" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6 mb-3">
                                                        <label for="price" class="form-label">Price</label>
                                                        <input type="number" class="form-control" id="price"
                                                            name="price" />
                                                    </div>


                                                    <div class="col-md-6 mb-3">
                                                        <label for="select_category" class="form-label">Select
                                                            category</label>

                                                        <select class="form-control" name="product_category_id"
                                                            id="">
                                                            <option value="">Select category</option>
                                                            @forelse ($categories as $c)
                                                                <option value="{{ $c->id }}">{{ $c->name }}
                                                                </option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group mt-4">
                                                            <input type="checkbox" id="status" name="status" />
                                                            <label for="status">Active</label>
                                                        </div>
                                                    </div>


                                                </div>

                                            </div>
                                            <!--End First Tab-->
                                        </div>
                                        <div class="card-body">

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
