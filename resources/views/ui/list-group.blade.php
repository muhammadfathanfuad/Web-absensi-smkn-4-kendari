@extends('layouts.vertical', ['subtitle' => 'List Group'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'List Group'])

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Basic
                </h5>
                <p class="card-subtitle">The most basic list group is an unordered list with list items
                    and the proper classes. Build upon it with the options that follow, or with your own
                    CSS as needed.</p>

            </div>
            <div class="card-body">
                <div class="w-50">
                    <ul class="list-group">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                        <li class="list-group-item">A fourth item</li>
                        <li class="list-group-item">And a fifth one</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Active items
                </h5>
                <p class="card-subtitle">Add <code>.active</code> to a <code>.list-group-item</code> to
                    indicate the current active selection.</p>
            </div>
            <div class="card-body">
                <div class="w-50">
                    <ul class="list-group">
                        <li class="list-group-item active" aria-current="true">An active item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                        <li class="list-group-item">A fourth item</li>
                        <li class="list-group-item">And a fifth one</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Disabled items</h5>
                <p class="card-subtitle">Add <code>.disabled</code> to a <code>.list-group-item</code>
                    to make it <em>appear</em> disabled. Note that some elements with
                    <code>.disabled</code> will also require custom JavaScript to fully disable their
                    click events (e.g., links).
                </p>
            </div>
            <div class="card-body">
                <div class="w-50">
                    <ul class="list-group">
                        <li class="list-group-item disabled" aria-disabled="true">A disabled item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                        <li class="list-group-item">A fourth item</li>
                        <li class="list-group-item">And a fifth one</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Links and buttons
                </h5>
                <p class="card-subtitle">Use <code>&lt;a&gt;</code>s or <code>&lt;button&gt;</code>s to
                    create <em>actionable</em> list group items with hover, disabled, and active states
                    by adding <code>.list-group-item-action</code>. We separate these pseudo-classes to
                    ensure list groups made of non-interactive elements (like <code>&lt;li&gt;</code>s
                    or <code>&lt;div&gt;</code>s) don’t provide a click or tap affordance.</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                The current link item
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">A second link
                                item</a>
                            <a href="#" class="list-group-item list-group-item-action">A third link
                                item</a>
                            <a href="#" class="list-group-item list-group-item-action">A fourth link
                                item</a>
                            <a class="list-group-item list-group-item-action disabled">A disabled link
                                item</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action active"
                                aria-current="true">
                                The current button
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">A
                                second button item</button>
                            <button type="button" class="list-group-item list-group-item-action">A third
                                button item</button>
                            <button type="button" class="list-group-item list-group-item-action">A
                                fourth button item</button>
                            <button type="button" class="list-group-item list-group-item-action" disabled>A disabled
                                button item</button>
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
                    Flush
                </h5>
                <p class="card-subtitle">Add <code>.list-group-flush</code> to remove some borders and
                    rounded corners to render list group items edge-to-edge in a parent container (e.g.,
                    cards).</p>
            </div>
            <div class="card-body">
                <div class="w-50">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                        <li class="list-group-item">A fourth item</li>
                        <li class="list-group-item">And a fifth one</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Numbered
                </h5>
                <p class="card-subtitle">Add the <code>.list-group-numbered</code> modifier class (and
                    optionally use an <code>&lt;ol&gt;</code> element) to opt into numbered list group
                    items. Numbers are generated via CSS (as opposed to a <code>&lt;ol&gt;</code>s
                    default browser styling) for better placement inside list group items and to allow
                    for better customization.</p>
                <p class="card-subtitle">Numbers are generated by <code>counter-reset</code> on the
                    <code>&lt;ol&gt;</code>, and then styled and placed with a <code>::before</code>
                    pseudo-element on the <code>&lt;li&gt;</code> with <code>counter-increment</code>
                    and <code>content</code>.
                </p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item">A list item</li>
                            <li class="list-group-item">A list item</li>
                            <li class="list-group-item">A list item</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Subheading</div>
                                    Content for list item
                                </div>
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Subheading</div>
                                    Content for list item
                                </div>
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Subheading</div>
                                    Content for list item
                                </div>
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Horizontal
                </h5>
                <p class="card-subtitle">Add <code>.list-group-horizontal</code> to change the layout of
                    list group items from vertical to horizontal across all breakpoints. Alternatively,
                    choose a responsive variant <code>.list-group-horizontal-{sm|md|lg|xl|xxl}</code> to
                    make a list group horizontal starting at that breakpoint’s <code>min-width</code>.
                    Currently <strong>horizontal list groups cannot be combined with flush list
                        groups.</strong></p>
                <p class="card-subtitle mt-1"><strong>ProTip:</strong> Want equal-width list group items
                    when horizontal? Add <code>.flex-fill</code> to each list group item.</p>
            </div>
            <div class="card-body">
                <div class="w-50 mb-3 d-flex flex-column gap-2">
                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="list-group list-group-horizontal-sm">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="list-group list-group-horizontal-md">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="list-group list-group-horizontal-lg">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="list-group list-group-horizontal-xl">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="list-group list-group-horizontal-xxl">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Contextual classes
                </h5>
                <p class="card-subtitle">Use contextual classes to style list items with a stateful
                    background and color.</p>
            </div>
            <div class="card-body">

                <div class="w-50">
                    <ul class="list-group">
                        <li class="list-group-item">A simple default list group item</li>
                        <li class="list-group-item list-group-item-primary">A simple primary list group
                            item</li>
                        <li class="list-group-item list-group-item-secondary">A simple secondary list
                            group item</li>
                        <li class="list-group-item list-group-item-success">A simple success list group
                            item</li>
                        <li class="list-group-item list-group-item-danger">A simple danger list group
                            item</li>
                        <li class="list-group-item list-group-item-warning">A simple warning list group
                            item</li>
                        <li class="list-group-item list-group-item-info">A simple info list group item
                        </li>
                        <li class="list-group-item list-group-item-light">A simple light list group item
                        </li>
                        <li class="list-group-item list-group-item-dark">A simple dark list group item
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Custom content
                </h5>
                <p class="card-subtitle">Add nearly any HTML within, even for linked list groups like
                    the one below, with the help of <a href="/docs/5.2/utilities/flex/">flexbox
                        utilities</a>.</p>
            </div>
            <div class="card-body">
                <div class="w-50">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-reset">List group item heading</h5>
                                <small>3 days ago</small>
                            </div>
                            <p class="mb-1">Some placeholder content in a paragraph.</p>
                            <small>And some small print.</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">List group item heading</h5>
                                <small class="text-muted">3 days ago</small>
                            </div>
                            <p class="mb-1">Some placeholder content in a paragraph.</p>
                            <small class="text-muted">And some muted small print.</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">List group item heading</h5>
                                <small class="text-muted">3 days ago</small>
                            </div>
                            <p class="mb-1">Some placeholder content in a paragraph.</p>
                            <small class="text-muted">And some muted small print.</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Checkboxes and radios
                </h5>
                <p class="card-subtitle">Place Bootstrap’s checkboxes and radios within list group items
                    and customize as needed. You can use them without <code>&lt;label&gt;</code>s, but
                    please remember to include an <code>aria-label</code> attribute and value for
                    accessibility.</p>
            </div>
            <div class="card-body">
                <div class="w-50 ">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value="" id="firstCheckbox">
                            <label class="form-check-label" for="firstCheckbox">First checkbox</label>
                        </li>
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value="" id="secondCheckbox">
                            <label class="form-check-label" for="secondCheckbox">Second checkbox</label>
                        </li>
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value="" id="thirdCheckbox">
                            <label class="form-check-label" for="thirdCheckbox">Third checkbox</label>
                        </li>
                    </ul>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection