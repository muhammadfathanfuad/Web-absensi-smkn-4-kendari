@extends('layouts.vertical', ['subtitle' => 'Modal'])


@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Modal'])

<!-- start modal -->
<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Default Modals
                </h5>
                <p class="card-subtitle">Toggle a working modal demo by clicking the button below. It
                    will slide down and fade in from the top of the page.</p>
            </div>
            <div class="card-body">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Launch demo modal
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Woo-hoo, you're reading this text in a modal!</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
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
                <p class="card-subtitle">When backdrop is set to static, the modal will not close when
                    clicking outside of it. Click the button below to try it.</p>
            </div>
            <div class="card-body">

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Launch static backdrop modal
                </button>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>I will not close if you click outside of me. Don't even try to press
                                    escape key.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Understood</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title ">
                    Scrolling Long Content
                </h5>
                <p class="card-subtitle">When modals become too long for the user&rsquo;s viewport or
                    device, they scroll independent of the page itself. Try the demo below to see what
                    we mean.</p>
            </div>
            <div class="card-body">

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#exampleModalLong">
                    Launch demo modal
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalLong" tabindex="-1" aria-labelledby="exampleModalLongTitle"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="min-height: 1500px">
                                <p>This is some placeholder content to show the scrolling behavior for
                                    modals. Instead of repeating
                                    the text the modal, we use an inline style set a minimum height,
                                    thereby extending the length of
                                    the overall modal and demonstrating the overflow scrolling. When
                                    content becomes longer than the
                                    height of the viewport, scrolling will move the modal as needed.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>



                <p class="text-muted mt-3">You can also create a scrollable modal that allows scroll the
                    modal body by adding <code>.modal-dialog-scrollable</code> to
                    <code>.modal-dialog</code>.
                </p>


                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#exampleModalScrollable">
                    Launch demo modal
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalScrollable" tabindex="-1"
                    aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalScrollableTitle">Modal title
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>This is some placeholder content to show the scrolling behavior for
                                    modals. We use repeated line
                                    breaks to demonstrate how content can exceed minimum inner height,
                                    thereby showing inner
                                    scrolling. When content becomes longer than the predefined
                                    max-height of modal, content will be
                                    cropped and scrollable within the modal.</p>
                                <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                <p>This content should appear at the bottom after you scroll.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
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
                    Modal Position
                </h5>
                <p class="card-subtitle">Add <code>.modal-dialog-centered</code> to
                    <code>.modal-dialog</code> to vertically center the modal.
                </p>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalCenter">
                        Vertically centered modal
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Modal title
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>This is a vertically centered modal.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalCenteredScrollable">
                        Vertically centered scrollable modal
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModalCenteredScrollable" tabindex="-1"
                        aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">
                                        Modal title</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>This is some placeholder content to show a vertically centered
                                        modal. We've added some extra
                                        copy
                                        here to show how vertically centering the modal works when
                                        combined with scrollable modals.
                                        We
                                        also use some repeated line breaks to quickly extend the height
                                        of the content, thereby
                                        triggering the scrolling. When content becomes longer than the
                                        predefined max-height of
                                        modal,
                                        content will be cropped and scrollable within the modal.</p>
                                    <br><br><br><br><br><br><br><br><br><br>
                                    <p>Just like that.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <p class="text-muted mt-3">
                    Specify the position for the modal. You can display modal at top, bottom of page
                    by specifying classes <code>modal-top</code> and <code>modal-bottom</code>
                    respectively.
                </p>


                <div class="d-flex flex-wrap gap-2">
                    <!-- Button trigger modal -->
                    <div class="hstack gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#topModal">Top Modal</button>
                        <button type="button" class="btn btn-success " data-bs-toggle="modal"
                            data-bs-target="#bottomModal">Bottom Modal</button>
                    </div>

                    <!-- top modal -->
                    <div id="topModal" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="topModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-top">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="topModalLabel">Modal Heading</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Text in a modal</h6>
                                    <p class="mb-0">Duis mollis, est non commodo luctus, nisi erat
                                        porttitor ligula.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div> <!-- end modal content -->
                        </div> <!-- end modal dialog -->
                    </div> <!-- end modal -->

                    <!-- bottom modal -->
                    <div id="bottomModal" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="bottomModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-bottom">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bottomModalLabel">Modal Heading</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Text in a modal</h6>
                                    <p class="mb-0">Duis mollis, est non commodo luctus, nisi erat
                                        porttitor ligula.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div> <!-- end modal content -->
                        </div> <!-- end modal dialog -->
                    </div> <!-- end modal -->
                </div>


            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Toggle Between Modals
                </h5>
                <p class="card-subtitle"> Toggle between multiple modals with some clever placement of
                    the <code>data-bs-target</code> and
                    <code>data-bs-toggle</code> attributes. For example, you could toggle a password
                    reset modal from within an already
                    open sign in modal. <strong>Please note multiple modals cannot be open at the same
                        time</strong>—this method simply
                    toggles between two separate modals.
                </p>
            </div>
            <div class="card-body">
                <!-- Button trigger modal -->
                <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open
                    first modal</a>

                <!-- First Modal -->
                <div class="modal fade" id="exampleModalToggle" aria-hidden="true"
                    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Modal 1</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Show a second modal and hide this one with the button below.
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-bs-target="#exampleModalToggle2"
                                    data-bs-toggle="modal">Open
                                    second modal</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Modal -->
                <div class="modal fade" id="exampleModalToggle2" aria-hidden="true"
                    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel2">Modal 2</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Hide this modal and show the first with the button below.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" data-bs-target="#exampleModalToggle"
                                    data-bs-toggle="modal">Back to
                                    first</button>
                            </div>
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
                    Optional Sizes
                </h5>
                <p class="card-subtitle">Modals have three optional sizes, available via modifier
                    classes to be placed on a <code>.modal-dialog</code>. These sizes kick in at certain
                    breakpoints to avoid horizontal scrollbars on narrower viewports.</p>
            </div>
            <div class="card-body">
                <div class="hstack gap-2">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalXl">Extra large modal</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalLg">Large modal</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalSm">Small modal</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalXl" tabindex="-1" aria-labelledby="exampleModalXlLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalXlLabel">Extra large modal
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModalLg" tabindex="-1" aria-labelledby="exampleModalLgLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalLgLabel">Large modal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModalSm" tabindex="-1" aria-labelledby="exampleModalSmLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalSmLabel">Small modal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
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
                    Fullscreen Modal
                </h5>
                <p class="card-subtitle">Modals have three optional sizes, available via modifier
                    classes to be placed on a <code>.modal-dialog</code>. These sizes kick in at certain
                    breakpoints to avoid horizontal scrollbars on narrower viewports.</p>
            </div>
            <div class="card-body">


                <div class="hstack flex-wrap gap-2">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalFullscreen">Full screen</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalFullscreenSm">Full screen below sm</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalFullscreenMd">Full screen below md</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalFullscreenLg">Full screen below lg</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalFullscreenXl">Full screen below xl</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalFullscreenXxl">Full screen below xxl</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalFullscreen" tabindex="-1"
                    aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalFullscreenLabel">Full screen
                                    modal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModalFullscreenSm" tabindex="-1"
                    aria-labelledby="exampleModalFullscreenSmLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen-sm-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalFullscreenSmLabel">Full
                                    screen below sm</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModalFullscreenMd" tabindex="-1"
                    aria-labelledby="exampleModalFullscreenMdLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalFullscreenMdLabel">Full
                                    screen below md</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModalFullscreenLg" tabindex="-1"
                    aria-labelledby="exampleModalFullscreenLgLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen-lg-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalFullscreenLgLabel">Full
                                    screen below lg</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModalFullscreenXl" tabindex="-1"
                    aria-labelledby="exampleModalFullscreenXlLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen-xl-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalFullscreenXlLabel">Full
                                    screen below xl</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModalFullscreenXxl" tabindex="-1"
                    aria-labelledby="exampleModalFullscreenXxlLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen-xxl-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="exampleModalFullscreenXxlLabel">Full
                                    screen below xxl</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end card -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Modal Based Alerts
                </h5>
                <p class="card-subtitle">Modals have three optional sizes, available via modifier
                    classes to be placed on a <code>.modal-dialog</code>. These sizes kick in at certain
                    breakpoints to avoid horizontal scrollbars on narrower viewports.</p>
            </div>
            <div class="card-body">

                <div class="hstack flex-wrap gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#primaryAlertModal">Primary Alert</button>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#secondaryAlertModal">Secondary Alert</button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#successAlertModal">Success Alert</button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal"
                        data-bs-target="#infoALertModal">Info Alert</button>
                </div>

                <!-- Primary Alert Modal -->
                <div id="primaryAlertModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content modal-filled bg-primary">
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="bx bx-check-double display-6 mt-0 text-white"></i>
                                    <h4 class="mt-3 text-white">Well Done!</h4>
                                    <p class="mt-3">Cras mattis consectetur purus sit amet fermentum.
                                        Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                                    <button type="button" class="btn btn-light mt-3"
                                        data-bs-dismiss="modal">Continue</button>
                                </div>
                            </div> <!-- end modal body -->
                        </div><!-- end modal content -->
                    </div><!-- end modal dialog -->
                </div> <!-- end modal -->

                <!-- Secondary Alert Modal -->
                <div id="secondaryAlertModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content modal-filled bg-secondary">
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="bx bx-check-double display-6 mt-0 text-white"></i>
                                    <h4 class="mt-3 text-white">Well Done!</h4>
                                    <p class="mt-3">Cras mattis consectetur purus sit amet fermentum.
                                        Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                                    <button type="button" class="btn btn-light mt-3"
                                        data-bs-dismiss="modal">Continue</button>
                                </div>
                            </div> <!-- end modal body -->
                        </div><!-- end modal content -->
                    </div><!-- end modal dialog -->
                </div> <!-- end modal -->

                <!-- Success Alert Modal -->
                <div id="successAlertModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content modal-filled bg-success">
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="bx bx-check-double display-6 mt-0 text-white"></i>
                                    <h4 class="mt-3 text-white">Well Done!</h4>
                                    <p class="mt-3">Cras mattis consectetur purus sit amet fermentum.
                                        Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                                    <button type="button" class="btn btn-light mt-3"
                                        data-bs-dismiss="modal">Continue</button>
                                </div>
                            </div> <!-- end modal body -->
                        </div><!-- end modal content -->
                    </div><!-- end modal dialog -->
                </div> <!-- end modal -->

                <!-- Info Alert Modal -->
                <div id="infoALertModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content modal-filled bg-info">
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="bx bx-check-double display-6 mt-0 text-white"></i>
                                    <h4 class="mt-3 text-white">Well Done!</h4>
                                    <p class="mt-3">Cras mattis consectetur purus sit amet fermentum.
                                        Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                                    <button type="button" class="btn btn-light mt-3"
                                        data-bs-dismiss="modal">Continue</button>
                                </div>
                            </div> <!-- end modal body -->
                        </div><!-- end modal content -->
                    </div><!-- end modal dialog -->
                </div> <!-- end modal -->

            </div>

        </div> <!-- end card body -->
    </div>
</div> <!-- end row -->
@endsection