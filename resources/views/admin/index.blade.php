@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content_header')
    <x-admin.title
        text="Dashboard"
    />
@endsection

@section('content')
    {{--
    <div class="tab-pane active" id="fa-icons">
        <section id="new">
        <h4 class="page-header">66 New Icons in 4.4</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-500px"></i> fa-500px</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-amazon"></i> fa-amazon</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-balance-scale"></i> fa-balance-scale</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-0"></i> fa-battery-0
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-1"></i> fa-battery-1
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-2"></i> fa-battery-2
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-3"></i> fa-battery-3
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-4"></i> fa-battery-4
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-empty"></i> fa-battery-empty</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-full"></i> fa-battery-full</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-half"></i> fa-battery-half</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-quarter"></i> fa-battery-quarter</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-three-quarters"></i>
        fa-battery-three-quarters
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-black-tie"></i> fa-black-tie</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-check-o"></i> fa-calendar-check-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-minus-o"></i> fa-calendar-minus-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-plus-o"></i> fa-calendar-plus-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-times-o"></i> fa-calendar-times-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-diners-club"></i> fa-cc-diners-club</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-jcb"></i> fa-cc-jcb</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chrome"></i> fa-chrome</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-clone"></i> fa-clone</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-commenting"></i> fa-commenting</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-commenting-o"></i> fa-commenting-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-contao"></i> fa-contao</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-creative-commons"></i> fa-creative-commons
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-expeditedssl"></i> fa-expeditedssl</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-firefox"></i> fa-firefox</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fonticons"></i> fa-fonticons</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-genderless"></i> fa-genderless</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-get-pocket"></i> fa-get-pocket</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gg"></i> fa-gg</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gg-circle"></i> fa-gg-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-grab-o"></i> fa-hand-grab-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-lizard-o"></i> fa-hand-lizard-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-paper-o"></i> fa-hand-paper-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-peace-o"></i> fa-hand-peace-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-pointer-o"></i> fa-hand-pointer-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-rock-o"></i> fa-hand-rock-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-scissors-o"></i> fa-hand-scissors-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-spock-o"></i> fa-hand-spock-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-stop-o"></i> fa-hand-stop-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass"></i> fa-hourglass</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-1"></i> fa-hourglass-1
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-2"></i> fa-hourglass-2
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-3"></i> fa-hourglass-3
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-end"></i> fa-hourglass-end</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-half"></i> fa-hourglass-half</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-o"></i> fa-hourglass-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-start"></i> fa-hourglass-start</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-houzz"></i> fa-houzz</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-i-cursor"></i> fa-i-cursor</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-industry"></i> fa-industry</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-internet-explorer"></i> fa-internet-explorer
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map"></i> fa-map</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map-o"></i> fa-map-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map-pin"></i> fa-map-pin</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map-signs"></i> fa-map-signs</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mouse-pointer"></i> fa-mouse-pointer</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-object-group"></i> fa-object-group</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-object-ungroup"></i> fa-object-ungroup</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-odnoklassniki"></i> fa-odnoklassniki</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-odnoklassniki-square"></i>
        fa-odnoklassniki-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-opencart"></i> fa-opencart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-opera"></i> fa-opera</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-optin-monster"></i> fa-optin-monster</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-registered"></i> fa-registered</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-safari"></i> fa-safari</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sticky-note"></i> fa-sticky-note</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sticky-note-o"></i> fa-sticky-note-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-television"></i> fa-television</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-trademark"></i> fa-trademark</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tripadvisor"></i> fa-tripadvisor</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tv"></i> fa-tv
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-vimeo"></i> fa-vimeo</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wikipedia-w"></i> fa-wikipedia-w</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-y-combinator"></i> fa-y-combinator</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-yc"></i> fa-yc
        <span class="text-muted">(alias)</span></div>
        </div>
        </section>
        <section id="web-application">
        <h4 class="page-header">Web Application Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-adjust"></i> fa-adjust</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-anchor"></i> fa-anchor</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-archive"></i> fa-archive</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-area-chart"></i> fa-area-chart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows"></i> fa-arrows</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows-h"></i> fa-arrows-h</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows-v"></i> fa-arrows-v</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-asterisk"></i> fa-asterisk</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-at"></i> fa-at</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-automobile"></i> fa-automobile
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-balance-scale"></i> fa-balance-scale</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ban"></i> fa-ban</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bank"></i> fa-bank <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bar-chart"></i> fa-bar-chart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bar-chart-o"></i> fa-bar-chart-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-barcode"></i> fa-barcode</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bars"></i> fa-bars</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-0"></i> fa-battery-0
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-1"></i> fa-battery-1
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-2"></i> fa-battery-2
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-3"></i> fa-battery-3
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-4"></i> fa-battery-4
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-empty"></i> fa-battery-empty</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-full"></i> fa-battery-full</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-half"></i> fa-battery-half</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-quarter"></i> fa-battery-quarter</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-battery-three-quarters"></i>
        fa-battery-three-quarters
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bed"></i> fa-bed</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-beer"></i> fa-beer</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bell"></i> fa-bell</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bell-o"></i> fa-bell-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bell-slash"></i> fa-bell-slash</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bell-slash-o"></i> fa-bell-slash-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bicycle"></i> fa-bicycle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-binoculars"></i> fa-binoculars</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-birthday-cake"></i> fa-birthday-cake</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bolt"></i> fa-bolt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bomb"></i> fa-bomb</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-book"></i> fa-book</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bookmark"></i> fa-bookmark</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bookmark-o"></i> fa-bookmark-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-briefcase"></i> fa-briefcase</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bug"></i> fa-bug</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-building"></i> fa-building</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-building-o"></i> fa-building-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bullhorn"></i> fa-bullhorn</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bullseye"></i> fa-bullseye</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bus"></i> fa-bus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cab"></i> fa-cab <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calculator"></i> fa-calculator</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar"></i> fa-calendar</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-check-o"></i> fa-calendar-check-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-minus-o"></i> fa-calendar-minus-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-o"></i> fa-calendar-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-plus-o"></i> fa-calendar-plus-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-calendar-times-o"></i> fa-calendar-times-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-camera"></i> fa-camera</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-camera-retro"></i> fa-camera-retro</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-car"></i> fa-car</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-down"></i>
        fa-caret-square-o-down
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-left"></i>
        fa-caret-square-o-left
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-right"></i>
        fa-caret-square-o-right
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-up"></i> fa-caret-square-o-up
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cart-arrow-down"></i> fa-cart-arrow-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cart-plus"></i> fa-cart-plus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc"></i> fa-cc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-certificate"></i> fa-certificate</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-check"></i> fa-check</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-check-circle"></i> fa-check-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-check-circle-o"></i> fa-check-circle-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-check-square"></i> fa-check-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-check-square-o"></i> fa-check-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-child"></i> fa-child</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-circle"></i> fa-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-circle-o"></i> fa-circle-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-circle-o-notch"></i> fa-circle-o-notch</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-circle-thin"></i> fa-circle-thin</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-clock-o"></i> fa-clock-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-clone"></i> fa-clone</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-close"></i> fa-close <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cloud"></i> fa-cloud</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cloud-download"></i> fa-cloud-download</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cloud-upload"></i> fa-cloud-upload</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-code"></i> fa-code</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-code-fork"></i> fa-code-fork</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-coffee"></i> fa-coffee</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cog"></i> fa-cog</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cogs"></i> fa-cogs</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-comment"></i> fa-comment</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-comment-o"></i> fa-comment-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-commenting"></i> fa-commenting</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-commenting-o"></i> fa-commenting-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-comments"></i> fa-comments</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-comments-o"></i> fa-comments-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-compass"></i> fa-compass</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-copyright"></i> fa-copyright</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-creative-commons"></i> fa-creative-commons
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-credit-card"></i> fa-credit-card</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-crop"></i> fa-crop</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-crosshairs"></i> fa-crosshairs</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cube"></i> fa-cube</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cubes"></i> fa-cubes</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cutlery"></i> fa-cutlery</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dashboard"></i> fa-dashboard
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-database"></i> fa-database</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-desktop"></i> fa-desktop</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-diamond"></i> fa-diamond</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dot-circle-o"></i> fa-dot-circle-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-download"></i> fa-download</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-edit"></i> fa-edit <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ellipsis-h"></i> fa-ellipsis-h</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ellipsis-v"></i> fa-ellipsis-v</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-envelope"></i> fa-envelope</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-envelope-o"></i> fa-envelope-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-envelope-square"></i> fa-envelope-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-eraser"></i> fa-eraser</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-exchange"></i> fa-exchange</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-exclamation"></i> fa-exclamation</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-exclamation-circle"></i> fa-exclamation-circle
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-exclamation-triangle"></i>
        fa-exclamation-triangle
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-external-link"></i> fa-external-link</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-external-link-square"></i>
        fa-external-link-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-eye"></i> fa-eye</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-eye-slash"></i> fa-eye-slash</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-eyedropper"></i> fa-eyedropper</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fax"></i> fa-fax</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-feed"></i> fa-feed <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-female"></i> fa-female</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fighter-jet"></i> fa-fighter-jet</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-archive-o"></i> fa-file-archive-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-audio-o"></i> fa-file-audio-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-code-o"></i> fa-file-code-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-excel-o"></i> fa-file-excel-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-image-o"></i> fa-file-image-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-movie-o"></i> fa-file-movie-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-pdf-o"></i> fa-file-pdf-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-photo-o"></i> fa-file-photo-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-picture-o"></i> fa-file-picture-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-powerpoint-o"></i> fa-file-powerpoint-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-sound-o"></i> fa-file-sound-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-video-o"></i> fa-file-video-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-word-o"></i> fa-file-word-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-zip-o"></i> fa-file-zip-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-film"></i> fa-film</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-filter"></i> fa-filter</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fire"></i> fa-fire</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fire-extinguisher"></i> fa-fire-extinguisher
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-flag"></i> fa-flag</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-flag-checkered"></i> fa-flag-checkered</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-flag-o"></i> fa-flag-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-flash"></i> fa-flash <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-flask"></i> fa-flask</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-folder"></i> fa-folder</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-folder-o"></i> fa-folder-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-folder-open"></i> fa-folder-open</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-folder-open-o"></i> fa-folder-open-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-frown-o"></i> fa-frown-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-futbol-o"></i> fa-futbol-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gamepad"></i> fa-gamepad</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gavel"></i> fa-gavel</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gear"></i> fa-gear <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gears"></i> fa-gears <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gift"></i> fa-gift</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-glass"></i> fa-glass</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-globe"></i> fa-globe</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-graduation-cap"></i> fa-graduation-cap</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-group"></i> fa-group <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-grab-o"></i> fa-hand-grab-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-lizard-o"></i> fa-hand-lizard-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-paper-o"></i> fa-hand-paper-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-peace-o"></i> fa-hand-peace-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-pointer-o"></i> fa-hand-pointer-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-rock-o"></i> fa-hand-rock-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-scissors-o"></i> fa-hand-scissors-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-spock-o"></i> fa-hand-spock-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-stop-o"></i> fa-hand-stop-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hdd-o"></i> fa-hdd-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-headphones"></i> fa-headphones</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-heart"></i> fa-heart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-heart-o"></i> fa-heart-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-heartbeat"></i> fa-heartbeat</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-history"></i> fa-history</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-home"></i> fa-home</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hotel"></i> fa-hotel <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass"></i> fa-hourglass</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-1"></i> fa-hourglass-1
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-2"></i> fa-hourglass-2
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-3"></i> fa-hourglass-3
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-end"></i> fa-hourglass-end</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-half"></i> fa-hourglass-half</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-o"></i> fa-hourglass-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hourglass-start"></i> fa-hourglass-start</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-i-cursor"></i> fa-i-cursor</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-image"></i> fa-image <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-inbox"></i> fa-inbox</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-industry"></i> fa-industry</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-info"></i> fa-info</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-info-circle"></i> fa-info-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-institution"></i> fa-institution
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-key"></i> fa-key</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-keyboard-o"></i> fa-keyboard-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-language"></i> fa-language</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-laptop"></i> fa-laptop</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-leaf"></i> fa-leaf</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-legal"></i> fa-legal <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-lemon-o"></i> fa-lemon-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-level-down"></i> fa-level-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-level-up"></i> fa-level-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-life-bouy"></i> fa-life-bouy
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-life-buoy"></i> fa-life-buoy
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-life-ring"></i> fa-life-ring</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-life-saver"></i> fa-life-saver
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-lightbulb-o"></i> fa-lightbulb-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-line-chart"></i> fa-line-chart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-location-arrow"></i> fa-location-arrow</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-lock"></i> fa-lock</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-magic"></i> fa-magic</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-magnet"></i> fa-magnet</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mail-forward"></i> fa-mail-forward
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mail-reply"></i> fa-mail-reply
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mail-reply-all"></i> fa-mail-reply-all
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-male"></i> fa-male</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map"></i> fa-map</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map-marker"></i> fa-map-marker</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map-o"></i> fa-map-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map-pin"></i> fa-map-pin</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-map-signs"></i> fa-map-signs</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-meh-o"></i> fa-meh-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-microphone"></i> fa-microphone</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-microphone-slash"></i> fa-microphone-slash
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-minus"></i> fa-minus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-minus-circle"></i> fa-minus-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-minus-square"></i> fa-minus-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-minus-square-o"></i> fa-minus-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mobile"></i> fa-mobile</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mobile-phone"></i> fa-mobile-phone
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-money"></i> fa-money</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-moon-o"></i> fa-moon-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mortar-board"></i> fa-mortar-board
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-motorcycle"></i> fa-motorcycle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mouse-pointer"></i> fa-mouse-pointer</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-music"></i> fa-music</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-navicon"></i> fa-navicon
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-newspaper-o"></i> fa-newspaper-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-object-group"></i> fa-object-group</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-object-ungroup"></i> fa-object-ungroup</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paint-brush"></i> fa-paint-brush</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paper-plane"></i> fa-paper-plane</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paper-plane-o"></i> fa-paper-plane-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paw"></i> fa-paw</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pencil"></i> fa-pencil</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pencil-square"></i> fa-pencil-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pencil-square-o"></i> fa-pencil-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-phone"></i> fa-phone</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-phone-square"></i> fa-phone-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-photo"></i> fa-photo <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-picture-o"></i> fa-picture-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pie-chart"></i> fa-pie-chart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plane"></i> fa-plane</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plug"></i> fa-plug</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plus"></i> fa-plus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plus-circle"></i> fa-plus-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plus-square"></i> fa-plus-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plus-square-o"></i> fa-plus-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-power-off"></i> fa-power-off</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-print"></i> fa-print</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-puzzle-piece"></i> fa-puzzle-piece</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-qrcode"></i> fa-qrcode</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-question"></i> fa-question</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-question-circle"></i> fa-question-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-quote-left"></i> fa-quote-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-quote-right"></i> fa-quote-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-random"></i> fa-random</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-recycle"></i> fa-recycle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-refresh"></i> fa-refresh</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-registered"></i> fa-registered</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-remove"></i> fa-remove
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-reorder"></i> fa-reorder
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-reply"></i> fa-reply</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-reply-all"></i> fa-reply-all</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-retweet"></i> fa-retweet</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-road"></i> fa-road</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rocket"></i> fa-rocket</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rss"></i> fa-rss</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rss-square"></i> fa-rss-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-search"></i> fa-search</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-search-minus"></i> fa-search-minus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-search-plus"></i> fa-search-plus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-send"></i> fa-send <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-send-o"></i> fa-send-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-server"></i> fa-server</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-share"></i> fa-share</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-share-alt"></i> fa-share-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-share-alt-square"></i> fa-share-alt-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-share-square"></i> fa-share-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-share-square-o"></i> fa-share-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-shield"></i> fa-shield</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ship"></i> fa-ship</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-shopping-cart"></i> fa-shopping-cart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sign-in"></i> fa-sign-in</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sign-out"></i> fa-sign-out</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-signal"></i> fa-signal</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sitemap"></i> fa-sitemap</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sliders"></i> fa-sliders</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-smile-o"></i> fa-smile-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-soccer-ball-o"></i> fa-soccer-ball-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort"></i> fa-sort</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-alpha-asc"></i> fa-sort-alpha-asc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-alpha-desc"></i> fa-sort-alpha-desc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-amount-asc"></i> fa-sort-amount-asc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-amount-desc"></i> fa-sort-amount-desc
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-asc"></i> fa-sort-asc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-desc"></i> fa-sort-desc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-down"></i> fa-sort-down
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-numeric-asc"></i> fa-sort-numeric-asc
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-numeric-desc"></i> fa-sort-numeric-desc
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sort-up"></i> fa-sort-up
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-space-shuttle"></i> fa-space-shuttle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-spinner"></i> fa-spinner</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-spoon"></i> fa-spoon</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-square"></i> fa-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-square-o"></i> fa-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-star"></i> fa-star</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-star-half"></i> fa-star-half</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-star-half-empty"></i> fa-star-half-empty
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-star-half-full"></i> fa-star-half-full
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-star-half-o"></i> fa-star-half-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-star-o"></i> fa-star-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sticky-note"></i> fa-sticky-note</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sticky-note-o"></i> fa-sticky-note-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-street-view"></i> fa-street-view</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-suitcase"></i> fa-suitcase</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sun-o"></i> fa-sun-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-support"></i> fa-support
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tablet"></i> fa-tablet</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tachometer"></i> fa-tachometer</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tag"></i> fa-tag</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tags"></i> fa-tags</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tasks"></i> fa-tasks</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-taxi"></i> fa-taxi</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-television"></i> fa-television</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-terminal"></i> fa-terminal</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumb-tack"></i> fa-thumb-tack</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-down"></i> fa-thumbs-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-o-down"></i> fa-thumbs-o-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-o-up"></i> fa-thumbs-o-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-up"></i> fa-thumbs-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ticket"></i> fa-ticket</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-times"></i> fa-times</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-times-circle"></i> fa-times-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-times-circle-o"></i> fa-times-circle-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tint"></i> fa-tint</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-down"></i> fa-toggle-down
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-left"></i> fa-toggle-left
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-off"></i> fa-toggle-off</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-on"></i> fa-toggle-on</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-right"></i> fa-toggle-right
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-up"></i> fa-toggle-up
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-trademark"></i> fa-trademark</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-trash"></i> fa-trash</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-trash-o"></i> fa-trash-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tree"></i> fa-tree</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-trophy"></i> fa-trophy</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-truck"></i> fa-truck</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tty"></i> fa-tty</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tv"></i> fa-tv
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-umbrella"></i> fa-umbrella</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-university"></i> fa-university</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-unlock"></i> fa-unlock</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-unlock-alt"></i> fa-unlock-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-unsorted"></i> fa-unsorted
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-upload"></i> fa-upload</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-user"></i> fa-user</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-user-plus"></i> fa-user-plus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-user-secret"></i> fa-user-secret</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-user-times"></i> fa-user-times</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-users"></i> fa-users</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-video-camera"></i> fa-video-camera</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-volume-down"></i> fa-volume-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-volume-off"></i> fa-volume-off</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-volume-up"></i> fa-volume-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-warning"></i> fa-warning
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wheelchair"></i> fa-wheelchair</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wifi"></i> fa-wifi</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wrench"></i> fa-wrench</div>
        </div>
        </section>
        <section id="hand">
        <h4 class="page-header">Hand Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-grab-o"></i> fa-hand-grab-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-lizard-o"></i> fa-hand-lizard-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-down"></i> fa-hand-o-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-left"></i> fa-hand-o-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-right"></i> fa-hand-o-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-up"></i> fa-hand-o-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-paper-o"></i> fa-hand-paper-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-peace-o"></i> fa-hand-peace-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-pointer-o"></i> fa-hand-pointer-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-rock-o"></i> fa-hand-rock-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-scissors-o"></i> fa-hand-scissors-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-spock-o"></i> fa-hand-spock-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-stop-o"></i> fa-hand-stop-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-down"></i> fa-thumbs-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-o-down"></i> fa-thumbs-o-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-o-up"></i> fa-thumbs-o-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-thumbs-up"></i> fa-thumbs-up</div>
        </div>
        </section>
        <section id="transportation">
        <h4 class="page-header">Transportation Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ambulance"></i> fa-ambulance</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-automobile"></i> fa-automobile
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bicycle"></i> fa-bicycle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bus"></i> fa-bus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cab"></i> fa-cab <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-car"></i> fa-car</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fighter-jet"></i> fa-fighter-jet</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-motorcycle"></i> fa-motorcycle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plane"></i> fa-plane</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rocket"></i> fa-rocket</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ship"></i> fa-ship</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-space-shuttle"></i> fa-space-shuttle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-subway"></i> fa-subway</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-taxi"></i> fa-taxi</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-train"></i> fa-train</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-truck"></i> fa-truck</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wheelchair"></i> fa-wheelchair</div>
        </div>
        </section>
        <section id="gender">
        <h4 class="page-header">Gender Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-genderless"></i> fa-genderless</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-intersex"></i> fa-intersex
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mars"></i> fa-mars</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mars-double"></i> fa-mars-double</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mars-stroke"></i> fa-mars-stroke</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mars-stroke-h"></i> fa-mars-stroke-h</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mars-stroke-v"></i> fa-mars-stroke-v</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-mercury"></i> fa-mercury</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-neuter"></i> fa-neuter</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-transgender"></i> fa-transgender</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-transgender-alt"></i> fa-transgender-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-venus"></i> fa-venus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-venus-double"></i> fa-venus-double</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-venus-mars"></i> fa-venus-mars</div>
        </div>
        </section>
        <section id="file-type">
        <h2 class="page-header">File Type Icons</h2>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file"></i> fa-file</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-archive-o"></i> fa-file-archive-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-audio-o"></i> fa-file-audio-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-code-o"></i> fa-file-code-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-excel-o"></i> fa-file-excel-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-image-o"></i> fa-file-image-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-movie-o"></i> fa-file-movie-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-o"></i> fa-file-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-pdf-o"></i> fa-file-pdf-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-photo-o"></i> fa-file-photo-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-picture-o"></i> fa-file-picture-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-powerpoint-o"></i> fa-file-powerpoint-o
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-sound-o"></i> fa-file-sound-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-text"></i> fa-file-text</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-text-o"></i> fa-file-text-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-video-o"></i> fa-file-video-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-word-o"></i> fa-file-word-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-zip-o"></i> fa-file-zip-o
        <span class="text-muted">(alias)</span></div>
        </div>
        </section>
        <section id="spinner">
        <h2 class="page-header">Spinner Icons</h2>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-circle-o-notch"></i> fa-circle-o-notch</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cog"></i> fa-cog</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gear"></i> fa-gear <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-refresh"></i> fa-refresh</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-spinner"></i> fa-spinner</div>
        </div>
        </section>
        <section id="form-control">
        <h4 class="page-header">Form Control Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-check-square"></i> fa-check-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-check-square-o"></i> fa-check-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-circle"></i> fa-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-circle-o"></i> fa-circle-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dot-circle-o"></i> fa-dot-circle-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-minus-square"></i> fa-minus-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-minus-square-o"></i> fa-minus-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plus-square"></i> fa-plus-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plus-square-o"></i> fa-plus-square-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-square"></i> fa-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-square-o"></i> fa-square-o</div>
        </div>
        </section>
        <section id="payment">
        <h4 class="page-header">Payment Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-amex"></i> fa-cc-amex</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-diners-club"></i> fa-cc-diners-club</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-discover"></i> fa-cc-discover</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-jcb"></i> fa-cc-jcb</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-mastercard"></i> fa-cc-mastercard</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-paypal"></i> fa-cc-paypal</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-stripe"></i> fa-cc-stripe</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-visa"></i> fa-cc-visa</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-credit-card"></i> fa-credit-card</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-google-wallet"></i> fa-google-wallet</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paypal"></i> fa-paypal</div>
        </div>
        </section>
        <section id="chart">
        <h4 class="page-header">Chart Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-area-chart"></i> fa-area-chart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bar-chart"></i> fa-bar-chart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bar-chart-o"></i> fa-bar-chart-o
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-line-chart"></i> fa-line-chart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pie-chart"></i> fa-pie-chart</div>
        </div>
        </section>
        <section id="currency">
        <h4 class="page-header">Currency Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bitcoin"></i> fa-bitcoin
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-btc"></i> fa-btc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cny"></i> fa-cny <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dollar"></i> fa-dollar
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-eur"></i> fa-eur</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-euro"></i> fa-euro <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gbp"></i> fa-gbp</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gg"></i> fa-gg</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gg-circle"></i> fa-gg-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ils"></i> fa-ils</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-inr"></i> fa-inr</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-jpy"></i> fa-jpy</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-krw"></i> fa-krw</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-money"></i> fa-money</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rmb"></i> fa-rmb <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rouble"></i> fa-rouble
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rub"></i> fa-rub</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ruble"></i> fa-ruble <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rupee"></i> fa-rupee <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-shekel"></i> fa-shekel
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sheqel"></i> fa-sheqel
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-try"></i> fa-try</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-turkish-lira"></i> fa-turkish-lira
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-usd"></i> fa-usd</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-won"></i> fa-won <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-yen"></i> fa-yen <span class="text-muted">(alias)</span>
        </div>
        </div>
        </section>
        <section id="text-editor">
        <h4 class="page-header">Text Editor Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-align-center"></i> fa-align-center</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-align-justify"></i> fa-align-justify</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-align-left"></i> fa-align-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-align-right"></i> fa-align-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bold"></i> fa-bold</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chain"></i> fa-chain <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chain-broken"></i> fa-chain-broken</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-clipboard"></i> fa-clipboard</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-columns"></i> fa-columns</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-copy"></i> fa-copy <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cut"></i> fa-cut <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dedent"></i> fa-dedent
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-eraser"></i> fa-eraser</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file"></i> fa-file</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-o"></i> fa-file-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-text"></i> fa-file-text</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-file-text-o"></i> fa-file-text-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-files-o"></i> fa-files-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-floppy-o"></i> fa-floppy-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-font"></i> fa-font</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-header"></i> fa-header</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-indent"></i> fa-indent</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-italic"></i> fa-italic</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-link"></i> fa-link</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-list"></i> fa-list</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-list-alt"></i> fa-list-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-list-ol"></i> fa-list-ol</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-list-ul"></i> fa-list-ul</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-outdent"></i> fa-outdent</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paperclip"></i> fa-paperclip</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paragraph"></i> fa-paragraph</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paste"></i> fa-paste <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-repeat"></i> fa-repeat</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rotate-left"></i> fa-rotate-left
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rotate-right"></i> fa-rotate-right
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-save"></i> fa-save <span class="text-muted">(alias)</span>
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-scissors"></i> fa-scissors</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-strikethrough"></i> fa-strikethrough</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-subscript"></i> fa-subscript</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-superscript"></i> fa-superscript</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-table"></i> fa-table</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-text-height"></i> fa-text-height</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-text-width"></i> fa-text-width</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-th"></i> fa-th</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-th-large"></i> fa-th-large</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-th-list"></i> fa-th-list</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-underline"></i> fa-underline</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-undo"></i> fa-undo</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-unlink"></i> fa-unlink
        <span class="text-muted">(alias)</span></div>
        </div>
        </section>
        <section id="directional">
        <h4 class="page-header">Directional Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-double-down"></i> fa-angle-double-down
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-double-left"></i> fa-angle-double-left
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-double-right"></i> fa-angle-double-right
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-double-up"></i> fa-angle-double-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-down"></i> fa-angle-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-left"></i> fa-angle-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-right"></i> fa-angle-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angle-up"></i> fa-angle-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-down"></i> fa-arrow-circle-down
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-left"></i> fa-arrow-circle-left
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-o-down"></i>
        fa-arrow-circle-o-down
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-o-left"></i>
        fa-arrow-circle-o-left
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-o-right"></i>
        fa-arrow-circle-o-right
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-o-up"></i> fa-arrow-circle-o-up
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-right"></i> fa-arrow-circle-right
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-circle-up"></i> fa-arrow-circle-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-down"></i> fa-arrow-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-left"></i> fa-arrow-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-right"></i> fa-arrow-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrow-up"></i> fa-arrow-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows"></i> fa-arrows</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows-alt"></i> fa-arrows-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows-h"></i> fa-arrows-h</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows-v"></i> fa-arrows-v</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-down"></i> fa-caret-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-left"></i> fa-caret-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-right"></i> fa-caret-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-down"></i>
        fa-caret-square-o-down
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-left"></i>
        fa-caret-square-o-left
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-right"></i>
        fa-caret-square-o-right
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-square-o-up"></i> fa-caret-square-o-up
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-caret-up"></i> fa-caret-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-circle-down"></i>
        fa-chevron-circle-down
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-circle-left"></i>
        fa-chevron-circle-left
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-circle-right"></i>
        fa-chevron-circle-right
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-circle-up"></i> fa-chevron-circle-up
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-down"></i> fa-chevron-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-left"></i> fa-chevron-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-right"></i> fa-chevron-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chevron-up"></i> fa-chevron-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-exchange"></i> fa-exchange</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-down"></i> fa-hand-o-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-left"></i> fa-hand-o-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-right"></i> fa-hand-o-right</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hand-o-up"></i> fa-hand-o-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-long-arrow-down"></i> fa-long-arrow-down</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-long-arrow-left"></i> fa-long-arrow-left</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-long-arrow-right"></i> fa-long-arrow-right
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-long-arrow-up"></i> fa-long-arrow-up</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-down"></i> fa-toggle-down
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-left"></i> fa-toggle-left
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-right"></i> fa-toggle-right
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-toggle-up"></i> fa-toggle-up
        <span class="text-muted">(alias)</span></div>
        </div>
        </section>
        <section id="video-player">
        <h4 class="page-header">Video Player Icons</h4>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-arrows-alt"></i> fa-arrows-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-backward"></i> fa-backward</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-compress"></i> fa-compress</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-eject"></i> fa-eject</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-expand"></i> fa-expand</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fast-backward"></i> fa-fast-backward</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fast-forward"></i> fa-fast-forward</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-forward"></i> fa-forward</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pause"></i> fa-pause</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-play"></i> fa-play</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-play-circle"></i> fa-play-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-play-circle-o"></i> fa-play-circle-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-random"></i> fa-random</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-step-backward"></i> fa-step-backward</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-step-forward"></i> fa-step-forward</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-stop"></i> fa-stop</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-youtube-play"></i> fa-youtube-play</div>
        </div>
        </section>
        <section id="brand">
        <h4 class="page-header">Brand Icons</h4>
        <div class="alert alert-info">
        <ul class="margin-bottom-none padding-left-lg">
        <li>All brand icons are trademarks of their respective owners.</li>
        <li>The use of these trademarks does not indicate endorsement of the trademark holder by Font
        Awesome, nor vice versa.
        </li>
        </ul>
        </div>
        <div class="row fontawesome-icon-list">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-500px"></i> fa-500px</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-adn"></i> fa-adn</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-amazon"></i> fa-amazon</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-android"></i> fa-android</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-angellist"></i> fa-angellist</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-apple"></i> fa-apple</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-behance"></i> fa-behance</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-behance-square"></i> fa-behance-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bitbucket"></i> fa-bitbucket</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bitbucket-square"></i> fa-bitbucket-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-bitcoin"></i> fa-bitcoin
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-black-tie"></i> fa-black-tie</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-btc"></i> fa-btc</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-buysellads"></i> fa-buysellads</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-amex"></i> fa-cc-amex</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-diners-club"></i> fa-cc-diners-club</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-discover"></i> fa-cc-discover</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-jcb"></i> fa-cc-jcb</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-mastercard"></i> fa-cc-mastercard</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-paypal"></i> fa-cc-paypal</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-stripe"></i> fa-cc-stripe</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-cc-visa"></i> fa-cc-visa</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-chrome"></i> fa-chrome</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-codepen"></i> fa-codepen</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-connectdevelop"></i> fa-connectdevelop</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-contao"></i> fa-contao</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-css3"></i> fa-css3</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dashcube"></i> fa-dashcube</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-delicious"></i> fa-delicious</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-deviantart"></i> fa-deviantart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-digg"></i> fa-digg</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dribbble"></i> fa-dribbble</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-dropbox"></i> fa-dropbox</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-drupal"></i> fa-drupal</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-empire"></i> fa-empire</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-expeditedssl"></i> fa-expeditedssl</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-facebook"></i> fa-facebook</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-facebook-f"></i> fa-facebook-f
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-facebook-official"></i> fa-facebook-official
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-facebook-square"></i> fa-facebook-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-firefox"></i> fa-firefox</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-flickr"></i> fa-flickr</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-fonticons"></i> fa-fonticons</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-forumbee"></i> fa-forumbee</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-foursquare"></i> fa-foursquare</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ge"></i> fa-ge
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-get-pocket"></i> fa-get-pocket</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gg"></i> fa-gg</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gg-circle"></i> fa-gg-circle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-git"></i> fa-git</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-git-square"></i> fa-git-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-github"></i> fa-github</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-github-alt"></i> fa-github-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-github-square"></i> fa-github-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gittip"></i> fa-gittip
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-google"></i> fa-google</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-google-plus"></i> fa-google-plus</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-google-plus-square"></i> fa-google-plus-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-google-wallet"></i> fa-google-wallet</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-gratipay"></i> fa-gratipay</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hacker-news"></i> fa-hacker-news</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-houzz"></i> fa-houzz</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-html5"></i> fa-html5</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-instagram"></i> fa-instagram</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-internet-explorer"></i> fa-internet-explorer
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ioxhost"></i> fa-ioxhost</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-joomla"></i> fa-joomla</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-jsfiddle"></i> fa-jsfiddle</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-lastfm"></i> fa-lastfm</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-lastfm-square"></i> fa-lastfm-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-leanpub"></i> fa-leanpub</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-linkedin"></i> fa-linkedin</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-linkedin-square"></i> fa-linkedin-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-linux"></i> fa-linux</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-maxcdn"></i> fa-maxcdn</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-meanpath"></i> fa-meanpath</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-medium"></i> fa-medium</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-odnoklassniki"></i> fa-odnoklassniki</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-odnoklassniki-square"></i>
        fa-odnoklassniki-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-opencart"></i> fa-opencart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-openid"></i> fa-openid</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-opera"></i> fa-opera</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-optin-monster"></i> fa-optin-monster</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pagelines"></i> fa-pagelines</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-paypal"></i> fa-paypal</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pied-piper"></i> fa-pied-piper</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pied-piper-alt"></i> fa-pied-piper-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pinterest"></i> fa-pinterest</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pinterest-p"></i> fa-pinterest-p</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-pinterest-square"></i> fa-pinterest-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-qq"></i> fa-qq</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ra"></i> fa-ra
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-rebel"></i> fa-rebel</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-reddit"></i> fa-reddit</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-reddit-square"></i> fa-reddit-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-renren"></i> fa-renren</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-safari"></i> fa-safari</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-sellsy"></i> fa-sellsy</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-share-alt"></i> fa-share-alt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-share-alt-square"></i> fa-share-alt-square
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-shirtsinbulk"></i> fa-shirtsinbulk</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-simplybuilt"></i> fa-simplybuilt</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-skyatlas"></i> fa-skyatlas</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-skype"></i> fa-skype</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-slack"></i> fa-slack</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-slideshare"></i> fa-slideshare</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-soundcloud"></i> fa-soundcloud</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-spotify"></i> fa-spotify</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-stack-exchange"></i> fa-stack-exchange</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-stack-overflow"></i> fa-stack-overflow</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-steam"></i> fa-steam</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-steam-square"></i> fa-steam-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-stumbleupon"></i> fa-stumbleupon</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-stumbleupon-circle"></i> fa-stumbleupon-circle
        </div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tencent-weibo"></i> fa-tencent-weibo</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-trello"></i> fa-trello</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tripadvisor"></i> fa-tripadvisor</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tumblr"></i> fa-tumblr</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-tumblr-square"></i> fa-tumblr-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-twitch"></i> fa-twitch</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-twitter"></i> fa-twitter</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-twitter-square"></i> fa-twitter-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-viacoin"></i> fa-viacoin</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-vimeo"></i> fa-vimeo</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-vimeo-square"></i> fa-vimeo-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-vine"></i> fa-vine</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-vk"></i> fa-vk</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wechat"></i> fa-wechat
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-weibo"></i> fa-weibo</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-weixin"></i> fa-weixin</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-whatsapp"></i> fa-whatsapp</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wikipedia-w"></i> fa-wikipedia-w</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-windows"></i> fa-windows</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wordpress"></i> fa-wordpress</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-xing"></i> fa-xing</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-xing-square"></i> fa-xing-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-y-combinator"></i> fa-y-combinator</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-y-combinator-square"></i>
        fa-y-combinator-square <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-yahoo"></i> fa-yahoo</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-yc"></i> fa-yc
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-yc-square"></i> fa-yc-square
        <span class="text-muted">(alias)</span></div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-yelp"></i> fa-yelp</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-youtube"></i> fa-youtube</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-youtube-play"></i> fa-youtube-play</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-youtube-square"></i> fa-youtube-square</div>
        </div>
        </section>
        <section id="medical">
        <h4 class="page-header">Medical Icons</h4>
        <div class="row">
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-ambulance"></i> fa-ambulance</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-h-square"></i> fa-h-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-heart"></i> fa-heart</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-heart-o"></i> fa-heart-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-heartbeat"></i> fa-heartbeat</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-hospital-o"></i> fa-hospital-o</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-medkit"></i> fa-medkit</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-plus-square"></i> fa-plus-square</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-stethoscope"></i> fa-stethoscope</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-user-md"></i> fa-user-md</div>
        <div class="col-md-3 col-sm-4"><i class="fa fa-fw fa-wheelchair"></i> fa-wheelchair</div>
        </div>
        </section>
    </div>
    --}}
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #1AA7F6 1.91%, #57C3FF 95.64%);">
                <div class="inner">
                    <h3>Users: {{$usersNumbers['total']}}</h3>
                    <p>Online: <b>{{$usersNumbers['online']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$usersNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$usersNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$usersNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$usersNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$usersNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$usersNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 47.5C5 42 9.5 37.5 15 37.5H25C30.5 37.5 35 42 35 47.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M26.2499 14.9999C29.7499 18.4999 29.7499 23.9999 26.2499 27.2499C22.7499 30.4999 17.2499 30.7499 13.9999 27.2499C10.7499 23.7499 10.4999 18.4999 13.7499 14.9999C16.9999 11.4999 22.7499 11.7499 26.2499 14.9999" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M40 35H47.5C51.75 35 55 38.25 55 42.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M48.25 16.75C50.75 19.25 50.75 23.25 48.25 25.5C45.75 27.75 41.75 28 39.5 25.5C37.25 23 37 19 39.5 16.75C41.75 14.5 45.75 14.5 48.25 16.75" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #1DE183 1.91%, #5CECA7 95.64%);">
                <div class="inner">
                    <h3>Notifications: {{$notificationViewsNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$notificationViewsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$notificationViewsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$notificationViewsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$notificationViewsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$notificationViewsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$notificationViewsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #1DE183 1.91%, #5CECA7 95.64%);">
                <div class="inner">
                    <h3>Posts: {{$postsNumbers['total']}}</h3>
                    <p>Inactive: <b>{{$postsNumbers['inactive']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$postsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$postsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$postsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$postsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$postsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$postsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #1DE183 1.91%, #5CECA7 95.64%);">
                <div class="inner">
                    <h3>Posts Views: {{$postViewsNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$postViewsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$postViewsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$postViewsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$postViewsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$postViewsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$postViewsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #1DE183 1.91%, #5CECA7 95.64%);">
                <div class="inner">
                    <h3>Blog Views: {{$blogViewsNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$blogViewsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$blogViewsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$blogViewsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$blogViewsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$blogViewsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$blogViewsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #e1bd1d 1.91%, #ece25c 95.64%);">
                <div class="inner">
                    <h3>Mailers: {{$mailersNumbers['total']}}</h3>
                    <p>Inactive: <b>{{$mailersNumbers['inactive']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$mailersNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$mailersNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$mailersNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$mailersNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$mailersNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$mailersNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #2d1de1 1.91%, #5e5cec 95.64%);">
                <div class="inner">
                    <h3>Imports: {{$importsNumbers['total']}}</h3>
                    <p>Success: <b>{{$importsNumbers['success']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$importsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$importsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$importsNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$importsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$importsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$importsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #e1371d 1.91%, #ec6f5c 95.64%);">
                <div class="inner">
                    <h3>Feedbacks: {{$feedbacksNumbers['total']}}</h3>
                    <p>From users: <b>{{$feedbacksNumbers['from-users']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$feedbacksNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$feedbacksNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$feedbacksNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$feedbacksNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$feedbacksNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$feedbacksNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #1de1de 1.91%, #5ce9ec 95.64%);">
                <div class="inner">
                    <h3>Subscriptions: ?</h3>
                    <p>-<b></b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>?</b>
                            <br>
                            Last 2d: <b>?</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>?</b>
                            <br>
                            Last 2w: <b>?</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>?</b>
                            <br>
                            Last 2m: <b>?</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- models created chart --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Models Created</h3>
                    <div class="card-tools" style="width: 185px">
                        <div class="input-group">
                            <input type="text" name="period" class="form-control daterangepicker-mult">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="models-created" data-url="{{route('admin.charts.models-created')}}"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- Users per country chart --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users per country</h3>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- Posts per origin locale chart --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Posts per origin locale</h3>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Global post views --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Global post views</h3>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- Mailer created chart --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mailer Created</h3>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Mailer emails chart --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mailer emails</h3>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- Feedbacks created chart --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Feedbacks Created</h3>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by posts count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Posts</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                                <th class="actions-column-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by mailers count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Posts</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                                <th class="actions-column-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by imports count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Posts</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                                <th class="actions-column-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top posts by views count</h3>
                </div>
                <div class="card-body">
                    <table id="posts-table" class="table table-bordered table-striped">
                        <x-admin.posts-table />
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top categories by posts count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Sub-categories</th>
                                <th>Posts</th>
                                <th>Is Active</th>
                                <th>Created_at</th>
                                <th class="actions-column-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top useless mailers <small>(3d without mails since last update)</small></h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>User</th>
                                <th>Title</th>
                                <th>Active</th>
                                <th>Posts Emailed #</th>
                                <th>Last Mail At</th>
                                <th>Created_at</th>
                                <th class="actions-column-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top useless posts <small>(3d without views since last update)</small></h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <x-admin.posts-table />
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/dashboard.js')}}"></script>
@endpush
