@extends('layouts.vertical', ['subtitle' => 'Nav Tabs'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Nav Tabs'])

<!-- start tabs -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Nav Tabs
                </h5>
                <p class="card-subtitle">Use the <code>.nav-tabs</code> class to generate a tabbed interface.</p>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#home" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                            <span class="d-none d-sm-block">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                            <span class="d-none d-sm-block">Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messages" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                            <span class="d-none d-sm-block">Messages</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content text-muted">
                    <div class="tab-pane" id="home">
                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                            doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                            vitae dicta sunt explicabo.
                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui
                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
                            sit amet, consectetur, adipisci
                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam
                            quaerat voluptatem?
                        </p>
                    </div>
                    <div class="tab-pane show active" id="profile">
                        <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es un
                            myth. Por scientie, musica, sport etc,
                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li
                            pronunciation e li plu commun vocabules.
                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi
                            traductores. At solmen va esser
                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues
                            coalesce, li grammatica del resultant
                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va
                            esser plu simplic e regulari quam
                            li existent Europan lingues.
                        </p>
                    </div>
                    <div class="tab-pane" id="messages">
                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                            doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                            vitae dicta sunt explicabo.
                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui
                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
                            sit amet, consectetur, adipisci
                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam
                            quaerat voluptatem?
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Tabs Justified
                </h5>
                <p class="card-subtitle">Using class <code>.nav-justified</code>, you can force your tabs menu items to
                    use the full available width.</p>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a href="#homeTabsJustified" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                            <span class="d-none d-sm-block">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profileTabsJustified" data-bs-toggle="tab" aria-expanded="true"
                            class="nav-link active">
                            <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                            <span class="d-none d-sm-block">Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messagesTabsJustified" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                            <span class="d-none d-sm-block">Messages</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-2 text-muted">
                    <div class="tab-pane" id="homeTabsJustified">
                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                            doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                            vitae dicta sunt explicabo.
                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui
                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
                            sit amet, consectetur, adipisci
                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam
                            quaerat voluptatem?
                        </p>
                    </div>
                    <div class="tab-pane show active" id="profileTabsJustified">
                        <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es un
                            myth. Por scientie, musica, sport etc,
                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li
                            pronunciation e li plu commun vocabules.
                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi
                            traductores. At solmen va esser
                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues
                            coalesce, li grammatica del resultant
                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va
                            esser plu simplic e regulari quam
                            li existent Europan lingues.
                        </p>
                    </div>
                    <div class="tab-pane" id="messagesTabsJustified">
                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                            doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                            vitae dicta sunt explicabo.
                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui
                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
                            sit amet, consectetur, adipisci
                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam
                            quaerat voluptatem?
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Nav Pills
                </h5>
                <p class="card-subtitle">Use the <code>.nav-pills</code> class to generate a pilled interface.</p>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="#homePill" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                            <span class="d-none d-sm-block">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profilePill" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                            <span class="d-none d-sm-block">Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messagesPill" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                            <span class="d-none d-sm-block">Messages</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-2 text-muted">
                    <div class="tab-pane" id="homePill">
                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                            doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                            vitae dicta sunt explicabo.
                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui
                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
                            sit amet, consectetur, adipisci
                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam
                            quaerat voluptatem?
                        </p>
                    </div>
                    <div class="tab-pane show active" id="profilePill">
                        <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es un
                            myth. Por scientie, musica, sport etc,
                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li
                            pronunciation e li plu commun vocabules.
                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi
                            traductores. At solmen va esser
                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues
                            coalesce, li grammatica del resultant
                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va
                            esser plu simplic e regulari quam
                            li existent Europan lingues.
                        </p>
                    </div>
                    <div class="tab-pane" id="messagesPill">
                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                            doloremque laudantium,
                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae
                            vitae dicta sunt explicabo.
                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui
                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor
                            sit amet, consectetur, adipisci
                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam
                            quaerat voluptatem?
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Pills Justified
                </h5>
                <p class="card-subtitle">Using class <code>.nav-justified</code>, you can force your pills menu items
                    to use the full available width.</p>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <ul class="nav nav-pills nav-justified p-1">
                        <li class="nav-item">
                            <a href="#homePillJustified" data-bs-toggle="tab" aria-expanded="false"
                                class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                                <span class="d-none d-sm-block">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#profilePillJustified" data-bs-toggle="tab" aria-expanded="true"
                                class="nav-link active">
                                <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                <span class="d-none d-sm-block">Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#messagesPillJustified" data-bs-toggle="tab" aria-expanded="false"
                                class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                <span class="d-none d-sm-block">Messages</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content pt-2 text-muted">
                        <div class="tab-pane" id="homePillJustified">
                            <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                accusantium doloremque laudantium,
                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto
                                beatae vitae dicta sunt explicabo.
                                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                                consequuntur magni dolores eos qui
                                ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia
                                dolor sit amet, consectetur, adipisci
                                velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam
                                aliquam quaerat voluptatem?
                            </p>
                        </div>
                        <div class="tab-pane show active" id="profilePillJustified">
                            <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es
                                un myth. Por scientie, musica, sport etc,
                                litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li
                                pronunciation e li plu commun vocabules.
                                Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar
                                custosi traductores. At solmen va esser
                                necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues
                                coalesce, li grammatica del resultant
                                lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va
                                esser plu simplic e regulari quam
                                li existent Europan lingues.
                            </p>
                        </div>
                        <div class="tab-pane" id="messagesPillJustified">
                            <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                accusantium doloremque laudantium,
                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto
                                beatae vitae dicta sunt explicabo.
                                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                                consequuntur magni dolores eos qui
                                ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia
                                dolor sit amet, consectetur, adipisci
                                velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam
                                aliquam quaerat voluptatem?
                            </p>
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
                    Tabs Vertical Left
                </h5>
                <p class="card-subtitle"> You can stack your navigation by changing the flex item direction with the
                    <code>.flex-column</code> utility.
                </p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 mb-2 mb-sm-0">
                        <div class="nav flex-column nav-pills" id="vl-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active show" id="vl-pills-home-tab" data-bs-toggle="pill"
                                href="#vl-pills-home" role="tab" aria-controls="vl-pills-home"
                                aria-selected="true">
                                <span>Home</span>
                            </a>
                            <a class="nav-link" id="vl-pills-profile-tab" data-bs-toggle="pill"
                                href="#vl-pills-profile" role="tab" aria-controls="vl-pills-profile"
                                aria-selected="false">
                                <span>Profile</span>
                            </a>
                            <a class="nav-link" id="vl-pills-settings-tab" data-bs-toggle="pill"
                                href="#vl-pills-settings" role="tab" aria-controls="vl-pills-settings"
                                aria-selected="false">
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-9">
                        <div class="tab-content pt-0" id="vl-pills-tabContent">
                            <div class="tab-pane fade active show" id="vl-pills-home" role="tabpanel"
                                aria-labelledby="vl-pills-home-tab">
                                <p class="mb-0">
                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                    doloremque
                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia
                                    voluptas
                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                    ratione
                                    voluptatem sequi nesciunt.
                                </p>
                            </div>
                            <div class="tab-pane fade" id="vl-pills-profile" role="tabpanel"
                                aria-labelledby="vl-pills-profile-tab">
                                <p class="mb-0">
                                    Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                                    Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues
                                    differe
                                    solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe
                                    al desirabilite de un nov lingua franca: On refusa continuar payar custosi
                                    traductores.
                                    At solmen va esser necessi far uniform.
                                </p>
                            </div>
                            <div class="tab-pane fade" id="vl-pills-settings" role="tabpanel"
                                aria-labelledby="vl-pills-settings-tab">
                                <p class="mb-0">
                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                    doloremque
                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia
                                    voluptas
                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                    ratione
                                    voluptatem sequi nesciunt.
                                </p>
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
                    Tabs Vertical Right
                </h5>
                <p class="card-subtitle"> You can stack your navigation by changing the flex item direction with the
                    <code>.flex-column</code> utility.
                </p>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-9 mb-2 mb-sm-0">
                        <div class="tab-content pt-0" id="vr-pills-tabContent">
                            <div class="tab-pane fade active show" id="vr-pills-home" role="tabpanel"
                                aria-labelledby="vr-pills-home-tab">
                                <p class="mb-0">
                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                    doloremque
                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia
                                    voluptas
                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                    ratione
                                    voluptatem sequi nesciunt.
                                </p>
                            </div>
                            <div class="tab-pane fade" id="vr-pills-profile" role="tabpanel"
                                aria-labelledby="vr-pills-profile-tab">
                                <p class="mb-0">
                                    Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                                    Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues
                                    differe
                                    solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe
                                    al desirabilite de un nov lingua franca: On refusa continuar payar custosi
                                    traductores.
                                    At solmen va esser necessi far uniform.
                                </p>
                            </div>
                            <div class="tab-pane fade" id="vr-pills-settings" role="tabpanel"
                                aria-labelledby="vr-pills-settings-tab">
                                <p class="mb-0">
                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                    doloremque
                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia
                                    voluptas
                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                    ratione
                                    voluptatem sequi nesciunt.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="nav flex-column nav-pills" id="vr-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active show" id="vr-pills-home-tab" data-bs-toggle="pill"
                                href="#vr-pills-home" role="tab" aria-controls="vr-pills-home"
                                aria-selected="true">
                                <span>Home</span>
                            </a>
                            <a class="nav-link" id="vr-pills-profile-tab" data-bs-toggle="pill"
                                href="#vr-pills-profile" role="tab" aria-controls="vr-pills-profile"
                                aria-selected="false">
                                <span>Profile</span>
                            </a>
                            <a class="nav-link" id="vr-pills-settings-tab" data-bs-toggle="pill"
                                href="#vr-pills-settings" role="tab" aria-controls="vr-pills-settings"
                                aria-selected="false">
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
<!-- end tabs -->
@endsection