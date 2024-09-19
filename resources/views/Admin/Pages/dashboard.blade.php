@extends('admin')

@section('content')
    <div class="content-wrapper">
        <h1 class="mb-4">Dashboard</h1>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text">1500</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Active Users</h5>
                        <p class="card-text">1200</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pending Requests</h5>
                        <p class="card-text">45</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Example -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Recent Users</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td>Admin</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Doe</td>
                            <td>jane@example.com</td>
                            <td>User</td>
                            <td>Inactive</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bob Smith</td>
                            <td>bob@example.com</td>
                            <td>Editor</td>
                            <td>Active</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
