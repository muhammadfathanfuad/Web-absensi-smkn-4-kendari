@extends('layouts.vertical', ['subtitle' => 'Offcanvas'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Offcanvas'])

<!-- start offcanvas -->
<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Default Buttons
                </h5>
                <p class="card-subtitle">
                    You can use a link with the href attribute, or a button with the <code>data-bs-target</code>
                    attribute.
                    In both cases, the <code>data-bs-toggle="offcanvas"</code> is required.
                </p>
            </div>
            <div class="card-body">
                <div class="button-list">
                    <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                        aria-controls="offcanvasExample">
                        Link with href
                    </a>
                    <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                        Button with data-bs-target
                    </button>
                </div>
                <!-- default offcanvas -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
                    aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="offcanvasExampleLabel">Offcanvas</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="defaultDropdownMenuButton" data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="defaultDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Static Backdrop
                </h5>
                <p class="card-subtitle">
                    Scrolling the <code>&lt;body&gt;</code> element is disabled when an offcanvas and its backdrop are
                    visible.
                    Use the <code>data-bs-scroll</code> attribute to toggle <code>&lt;body&gt;</code> scrolling and
                    <code>data-bs-backdrop</code> to toggle the backdrop.
                </p>
            </div>
            <div class="card-body">

                <div class="button-list">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Enable Body
                        Scrolling</button>
                    <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasWithBackdrop" aria-controls="offcanvasWithBackdrop">Enable Backdrop
                        (Default)</button>
                    <button class="btn btn-success" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">Enable Both
                        Scrolling & Backdrop</button>
                </div>

                <!-- scrolling offcanvas -->
                <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
                    id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="offcanvasScrollingLabel">Colored with scrolling</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="scrollingDropdownMenuButton" data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="scrollingDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- backdrop offcanvas -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop"
                    aria-labelledby="offcanvasWithBackdropLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="offcanvasWithBackdropLabel">Offcanvas with backdrop</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="backdropDropdownMenuButton" data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="backdropDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- both scrolling & backdrop offcanvas -->
                <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1"
                    id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="offcanvasWithBothOptionsLabel">Backdroped with scrolling
                        </h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="scrollingBackdropDropdownMenuButton" data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="scrollingBackdropDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Offcanvas Position
                </h5>
                <p class="card-subtitle">
                    Try the top, right, bottom and left examples out below.
                </p>
            </div>
            <div class="card-body">
                <ul>
                    <li><code>.offcanvas-top</code> places offcanvas on the top of the viewport</li>
                    <li><code>.offcanvas-end</code> places offcanvas on the right of the viewport</li>
                    <li><code>.offcanvas-bottom</code> places offcanvas on the bottom of the viewport</li>
                    <li><code>.offcanvas-start</code> places offcanvas on the left of the viewport</li>
                </ul>

                <div class="button-list">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#leftOffcanvas" aria-controls="leftOffcanvas">Left Offcanvas</button>
                    <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#rightOffcanvas" aria-controls="rightOffcanvas">Right Offcanvas</button>
                    <button class="btn btn-success" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#topOffcanvas" aria-controls="topOffcanvas">Top Offcanvas</button>
                    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#bottomOffcanvas" aria-controls="bottomOffcanvas">Bottom Offcanvas</button>
                </div>

                <!-- left offcanvas -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="leftOffcanvas"
                    aria-labelledby="leftOffcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="leftOffcanvasLabel">Offcanvas</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="leftDropdownMenuButton" data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="leftDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- right offcanvas -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="rightOffcanvas"
                    aria-labelledby="rightOffcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="rightOffcanvasLabel">Offcanvas</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="rightDropdownMenuButton" data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="rightDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- top offcanvas -->
                <div class="offcanvas offcanvas-top" tabindex="-1" id="topOffcanvas"
                    aria-labelledby="topOffcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="topOffcanvasLabel">Offcanvas</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="topDropdownMenuButton"
                                data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="topDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- bottom offcanvas -->
                <div class="offcanvas offcanvas-bottom" tabindex="-1" id="bottomOffcanvas"
                    aria-labelledby="bottomOffcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title mt-0" id="bottomOffcanvasLabel">Offcanvas</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <p>
                            Some text as placeholder. In real life you can have the elements you have chosen. Like,
                            text, images, lists, etc.
                        </p>
                        <div class="dropdown mt-3">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="bottomDropdownMenuButton" data-bs-toggle="dropdown">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="bottomDropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection