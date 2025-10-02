@extends('layouts.vertical', ['subtitle' => 'Dark Mode Layout'])

@section('html-attribute')
data-bs-theme="dark"
@endsection

@section('content')
@include('layouts.partials.page-title', ['title' => 'Layouts', 'subtitle' => 'Dark Mode Layout'])

<div class="row">
    <!-- Card 1 -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:globus-outline"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Clicks</p>
                        <h3 class="text-dark mt-2 mb-0">15,352</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 3.02%</span>
                        <span class="text-muted ms-1 fs-12">From last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:bag-check-outline"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Sales</p>
                        <h3 class="text-dark mt-2 mb-0">8,764</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i> 1.15%</span>
                        <span class="text-muted ms-1 fs-12">From last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:calendar-date-outline"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Events</p>
                        <h3 class="text-dark mt-2 mb-0">5,123</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 4.78%</span>
                        <span class="text-muted ms-1 fs-12">From last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:users-group-two-rounded-outline"
                                class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Users</p>
                        <h3 class="text-dark mt-2 mb-0">12,945</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 2.35%</span>
                        <span class="text-muted ms-1 fs-12">From last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-6">
        <div class="card card-height-100">
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <h4 class="card-title flex-grow-1">Top Pages</h4>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-light">ALL</button>
                    <button type="button" class="btn btn-sm btn-outline-light">1M</button>
                    <button type="button" class="btn btn-sm btn-outline-light">6M</button>
                    <button type="button" class="btn btn-sm btn-outline-light active">1Y</button>
                </div>
            </div>

            <div class="card-body pt-0">
                <div dir="ltr">
                    <div id="dash-performance-chart" class="apex-charts"></div>
                </div>
            </div>

        </div> <!-- end card-->
    </div> <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center border-bottom border-dashed">
                <h4 class="card-title">Sessions by Country</h4>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        View Data
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Download</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Export</a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">Import</a>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div id="world-map-markers" class="mt-3" style="height: 300px">
                        </div>
                    </div>
                    <div class="col-lg-5" dir="ltr">
                        <div class="p-3 pb-0">
                            <!-- Country Data -->
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-1">
                                    <iconify-icon icon="circle-flags:us"
                                        class="fs-16 align-middle me-1"></iconify-icon> <span
                                        class="align-middle">United States</span>
                                </p>
                            </div>
                            <div class="row align-items-center mb-3">
                                <div class="col">
                                    <div class="progress progress-soft progress-sm">
                                        <div class="progress-bar bg-secondary" role="progressbar"
                                            style="width: 82.05%" aria-valuenow="" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-0 fs-13 fw-semibold">659k</p>
                                </div>
                            </div>

                            <!-- Country Data -->
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-1">
                                    <iconify-icon icon="circle-flags:ru"
                                        class="fs-16 align-middle me-1"></iconify-icon> <span
                                        class="align-middle">Russia</span>
                                </p>
                            </div>
                            <div class="row align-items-center mb-3">
                                <div class="col">
                                    <div class="progress progress-soft progress-sm">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 70.5%"
                                            aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-0 fs-13 fw-semibold">485k</p>
                                </div>
                            </div>

                            <!-- Country Data -->
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-1">
                                    <iconify-icon icon="circle-flags:cn"
                                        class="fs-16 align-middle me-1"></iconify-icon> <span
                                        class="align-middle">China</span>
                                </p>
                            </div>
                            <div class="row align-items-center mb-3">
                                <div class="col">
                                    <div class="progress progress-soft progress-sm">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 65.8%"
                                            aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-0 fs-13 fw-semibold">355k</p>
                                </div>
                            </div>

                            <!-- Country Data -->
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-1">
                                    <iconify-icon icon="circle-flags:ca"
                                        class="fs-16 align-middle me-1"></iconify-icon> <span
                                        class="align-middle">Canada</span>
                                </p>
                            </div>
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="progress progress-soft progress-sm">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 55.8%"
                                            aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-0 fs-13 fw-semibold">204k</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body-->


        </div> <!-- end card-->
    </div> <!-- end col-->

</div> <!-- End row -->

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">New Users</h4>

                    <a href="#!" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus me-1"></i>Add New User
                    </a>
                </div>
            </div> <!-- end card body -->

            <div class="table-responsive table-centered">
                <table class="table mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <th class="border-0 py-2">Date</th>
                        <th class="border-0 py-2">User</th>
                        <th class="border-0 py-2">Account</th>
                        <th class="border-0 py-2">Username</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>24 April, 2024</td>
                            <td><img src="/images/users/avatar-2.jpg" alt="avatar-2"
                                    class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Dan
                                    Adrick</span></td>
                            <td><span class="badge badge-soft-success">Verified</span></td>
                            <td>@omions </td>
                        </tr>
                        <tr>
                            <td>24 April, 2024</td>
                            <td><img src="/images/users/avatar-3.jpg" alt="avatar-2"
                                    class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Daniel
                                    Olsen</span></td>
                            <td><span class="badge badge-soft-success">Verified</span></td>
                            <td>@alliates </td>
                        </tr>
                        <tr>
                            <td>20 April, 2024</td>
                            <td><img src="/images/users/avatar-4.jpg" alt="avatar-2"
                                    class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Jack
                                    Roldan</span></td>
                            <td><span class="badge badge-soft-warning">Pending</span></td>
                            <td>@griys </td>
                        </tr>
                        <tr>
                            <td>18 April, 2024</td>
                            <td><img src="/images/users/avatar-5.jpg" alt="avatar-2"
                                    class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Betty
                                    Cox</span></td>
                            <td><span class="badge badge-soft-success">Verified</span></td>
                            <td>@reffon </td>
                        </tr>
                        <tr>
                            <td>18 April, 2024</td>
                            <td><img src="/images/users/avatar-6.jpg" alt="avatar-2"
                                    class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Carlos
                                    Johnson</span></td>
                            <td><span class="badge badge-soft-danger">Blocked</span></td>
                            <td>@bebo </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- table responsive -->

            <div class="align-items-center justify-content-between row g-0 text-center text-sm-start p-3 border-top">
                <div class="col-sm">
                    <div class="text-muted">
                        Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">587</span> users
                    </div>
                </div>
                <div class="col-sm-auto mt-3 mt-sm-0">
                    <ul class="pagination pagination-rounded m-0">
                        <li class="page-item">
                            <a href="#" class="page-link"><i class='bx bx-left-arrow-alt'></i></a>
                        </li>
                        <li class="page-item active">
                            <a href="#" class="page-link">1</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">2</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">3</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link"><i class='bx bx-right-arrow-alt'></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Recent Orders</h4>

                    <a href="#!" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus me-1"></i>Create Order
                    </a>
                </div>
            </div> <!-- end card body -->
            <div class="table-responsive table-centered">
                <table class="table mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="border-0 py-2">Order ID.</th>
                            <th class="border-0 py-2">Date</th>
                            <th class="border-0 py-2">Customer Name</th>
                            <th class="border-0 py-2">Phone No.</th>
                            <th class="border-0 py-2">Address</th>
                            <th class="border-0 py-2">Payment Type</th>
                            <th class="border-0 py-2">Status</th>
                        </tr>
                    </thead> <!-- end thead-->
                    <tbody>
                        <tr>
                            <td><a href="#!">#TZ5625</a></td>
                            <td>29 April 2024</td>
                            <td><a href="#!">Anna M. Hines</a></td>
                            <td>(+1)-555-1564-261</td>
                            <td>Burr Ridge/Illinois</td>
                            <td>Credit Card</td>
                            <td><i class="bx bxs-circle text-success me-1"></i>Completed</td>
                        </tr>
                        <tr>
                            <td><a href="#!">#TZ9652</a></td>
                            <td>25 April 2024</td>
                            <td><a href="#!">Judith H. Fritsche</a></td>
                            <td>(+57)-305-5579-759</td>
                            <td>SULLIVAN/Kentucky</td>
                            <td>Credit Card</td>
                            <td><i class="bx bxs-circle text-success me-1"></i>Completed</td>
                        </tr>
                        <tr>
                            <td><a href="#!">#TZ5984</a></td>
                            <td>25 April 2024</td>
                            <td><a href="#!">Peter T. Smith</a></td>
                            <td>(+33)-655-5187-93</td>
                            <td>Yreka/California</td>
                            <td>Pay Pal</td>
                            <td><i class="bx bxs-circle text-success me-1"></i>Completed</td>
                        </tr>
                        <tr>
                            <td><a href="#!">#TZ3625</a></td>
                            <td>21 April 2024</td>
                            <td><a href="#!">Emmanuel J. Delcid</a></td>
                            <td>(+30)-693-5553-637</td>
                            <td>Atlanta/Georgia</td>
                            <td>Pay Pal</td>
                            <td><i class="bx bxs-circle text-primary me-1"></i>Processing</td>
                        </tr>
                        <tr>
                            <td><a href="#!">#TZ8652</a></td>
                            <td>18 April 2024</td>
                            <td><a href="#!">William J. Cook</a></td>
                            <td>(+91)-855-5446-150</td>
                            <td>Rosenberg/Texas</td>
                            <td>Credit Card</td>
                            <td><i class="bx bxs-circle text-primary me-1"></i>Processing</td>
                        </tr>
                    </tbody> <!-- end tbody -->
                </table> <!-- end table -->
            </div> <!-- table responsive -->
            <div class="align-items-center justify-content-between row g-0 text-center text-sm-start p-3 border-top">
                <div class="col-sm">
                    <div class="text-muted">
                        Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">90,521</span> orders
                    </div>
                </div>
                <div class="col-sm-auto mt-3 mt-sm-0">
                    <ul class="pagination pagination-rounded m-0">
                        <li class="page-item">
                            <a href="#" class="page-link"><i class='bx bx-left-arrow-alt'></i></a>
                        </li>
                        <li class="page-item active">
                            <a href="#" class="page-link">1</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">2</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link">3</a>
                        </li>
                        <li class="page-item">
                            <a href="#" class="page-link"><i class='bx bx-right-arrow-alt'></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('scripts')
@vite(['resources/js/pages/dashboard.js'])
@endsection