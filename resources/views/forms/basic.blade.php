@extends('layouts.vertical', ['subtitle' => 'Form Basics'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Form', 'subtitle' => 'Form Basics'])

<div class="row row-cols-lg-2 gx-3">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Example</h5>
            </div>

            <div class="card-body">
                <div>
                    <div>
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Text</label>
                            <input type="text" id="simpleinput" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="example-email" class="form-label">Email</label>
                            <input type="email" id="example-email" name="example-email" class="form-control"
                                placeholder="Email">
                        </div>

                        <div class="mb-3">
                            <label for="example-password" class="form-label">Password</label>
                            <input type="password" id="example-password" class="form-control" value="password">
                        </div>

                        <div class="mb-3">
                            <label for="example-palaceholder" class="form-label">Placeholder</label>
                            <input type="text" id="example-palaceholder" class="form-control"
                                placeholder="placeholder">
                        </div>

                        <div class="mb-3">
                            <label for="example-textarea" class="form-label">Text
                                area</label>
                            <textarea class="form-control" id="example-textarea" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Input Sizing</h5>
            </div>

            <div class="card-body">
                <div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg"
                            aria-label=".form-control-lg example">
                        <input class="form-control" type="text" placeholder="Default input"
                            aria-label="default input example">
                        <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm"
                            aria-label=".form-control-sm example">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Disabled Input</h5>
            </div>

            <div class="card-body">
                <div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <input class="form-control" type="text" placeholder="Disabled input"
                            aria-label="Disabled input example" disabled>
                        <input class="form-control" type="text" value="Disabled readonly input"
                            aria-label="Disabled input example" disabled readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Checkbox</h5>
            </div>

            <div class="card-body">
                <h5>Default</h5>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="customCheck1">
                        <label class="form-check-label" for="customCheck1">Check this custom
                            checkbox</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="customCheck2">
                        <label class="form-check-label" for="customCheck2">Check this custom
                            checkbox</label>
                    </div>
                </div>

                <h5>Inline</h5>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="customCheck3">
                        <label class="form-check-label" for="customCheck3">Check this custom
                            checkbox</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="customCheck4">
                        <label class="form-check-label" for="customCheck4">Check this custom
                            checkbox</label>
                    </div>
                </div>

                <!-- Colors-->
                <h5>Colors</h5>
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor1" checked="">
                    <label class="form-check-label" for="customCheckcolor1">Default
                        Checkbox</label>
                </div>
                <div class="form-check form-checkbox-success mb-2">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor2" checked="">
                    <label class="form-check-label" for="customCheckcolor2">Success
                        Checkbox</label>
                </div>
                <div class="form-check form-checkbox-info mb-2">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor3" checked="">
                    <label class="form-check-label" for="customCheckcolor3">Info
                        Checkbox</label>
                </div>
                <div class="form-check form-checkbox-secondary mb-2">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor6" checked="">
                    <label class="form-check-label" for="customCheckcolor6">Secondary
                        Checkbox</label>
                </div>
                <div class="form-check form-checkbox-warning mb-2">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor4" checked="">
                    <label class="form-check-label" for="customCheckcolor4">Warning
                        Checkbox</label>
                </div>
                <div class="form-check form-checkbox-danger mb-2">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor5" checked="">
                    <label class="form-check-label" for="customCheckcolor5">Danger
                        Checkbox</label>
                </div>
                <div class="form-check form-checkbox-dark">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor7" checked="">
                    <label class="form-check-label" for="customCheckcolor7">Dark
                        Checkbox</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Readonly Input</h5>
            </div>

            <div class="card-body">
                <div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <input class="form-control" type="text" value="Readonly input here..."
                            aria-label="readonly input example" readonly>
                        <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                            value="email@example.com">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Datalists input</h5>
            </div>
            <div class="card-body">
                <div>
                    <div class="mb-3">
                        <label for="exampleDataList" class="form-label">Datalist
                            example</label>
                        <input class="form-control" list="datalistOptions" id="exampleDataList"
                            placeholder="Type to search...">
                        <datalist id="datalistOptions">
                            <option value="San Francisco">
                            <option value="New York">
                            <option value="Seattle">
                            <option value="Los Angeles">
                            <option value="Chicago">
                        </datalist>
                    </div>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Select</h5>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <div class="mb-3">
                        <label for="example-select" class="form-label">Default Input
                            Select</label>
                        <select class="form-select" id="example-select">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>

                    <p class="text-muted">The <code>multiple</code> attribute is also
                        supported:</p>

                    <div class="mb-3">
                        <label for="example-multiselect" class="form-label">Multiple
                            Select</label>
                        <select id="example-multiselect" multiple="" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>

                    <p class="text-muted">As is the <code>size</code> attribute:</p>

                    <label for="example-multiselectsize" class="form-label">Multiple Select
                        Size</label>
                    <select id="example-multiselectsize" class="form-select" size="3"
                        aria-label="size 3 select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Radio</h5>
            </div>

            <div class="card-body">
                <h5>Default</h5>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                            id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Default radio
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                            id="flexRadioDefault2" checked="">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Default checked radio
                        </label>
                    </div>
                </div>

                <h5>Inline</h5>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                            value="option1">
                        <label class="form-check-label" for="inlineRadio1">Check this custom
                            checkbox</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                            value="option2">
                        <label class="form-check-label" for="inlineRadio2">Check this custom
                            checkbox</label>
                    </div>
                </div>

                <div>
                    <h5>Colors</h5>
                    <div class="form-check mb-2">
                        <input type="radio" id="customRadiocolor1" name="customRadiocolor1"
                            class="form-check-input" checked="">
                        <label class="form-check-label" for="customRadiocolor1">Default
                            Radio</label>
                    </div>
                    <div class="form-check form-radio-success mb-2">
                        <input type="radio" id="customRadiocolor2" name="customRadiocolor2"
                            class="form-check-input" checked="">
                        <label class="form-check-label" for="customRadiocolor2">Success
                            Radio</label>
                    </div>
                    <div class="form-check form-radio-info mb-2">
                        <input type="radio" id="customRadiocolor3" name="customRadiocolor3"
                            class="form-check-input" checked="">
                        <label class="form-check-label" for="customRadiocolor3">Info
                            Radio</label>
                    </div>
                    <div class="form-check form-radio-secondary mb-2">
                        <input type="radio" id="customRadiocolor6" name="customRadiocolor6"
                            class="form-check-input" checked="">
                        <label class="form-check-label" for="customRadiocolor6">Secondary
                            Radio</label>
                    </div>
                    <div class="form-check form-radio-warning mb-2">
                        <input type="radio" id="customRadiocolor4" name="customRadiocolor4"
                            class="form-check-input" checked="">
                        <label class="form-check-label" for="customRadiocolor4">Warning
                            Radio</label>
                    </div>
                    <div class="form-check form-radio-danger mb-2">
                        <input type="radio" id="customRadiocolor5" name="customRadiocolor5"
                            class="form-check-input" checked="">
                        <label class="form-check-label" for="customRadiocolor5">Danger
                            Radio</label>
                    </div>
                    <div class="form-check form-radio-dark">
                        <input type="radio" id="customRadiocolor7" name="customRadiocolor7"
                            class="form-check-input" checked="">
                        <label class="form-check-label" for="customRadiocolor7">Dark
                            Radio</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Switch</h5>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Default
                            switch checkbox
                            input</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                            checked="">
                        <label class="form-check-label" for="flexSwitchCheckChecked">Checked
                            switch checkbox
                            input</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled"
                            disabled="">
                        <label class="form-check-label" for="flexSwitchCheckDisabled">Disabled
                            switch checkbox
                            input</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                            id="flexSwitchCheckCheckedDisabled" checked="" disabled="">
                        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Disabled checked switch
                            checkbox input</label>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection