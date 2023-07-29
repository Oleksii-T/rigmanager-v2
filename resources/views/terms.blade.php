@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.tos')}}</title>
    <meta name="description" content="{{__('meta.description.info.tos')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
	<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<span itemprop="name">{{__('ui.footerTerms')}}</span>
		<meta itemprop="position" content="2" />
	</li>
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='terms'/>

		<div class="content">
			<h1>{{__('ui.footerTerms')}}</h1>
            <div class="content-top-text">{{__('ui.content')}}:

                1. <a href="#terms-p1">{{__('terms.P1')}}</a>
                2. <a href="#terms-p2">{{__('terms.P2')}}</a>
                3. <a href="#terms-p3">{{__('terms.P3')}}</a>
                4. <a href="#terms-p4">{{__('terms.P4')}}</a>
                5. <a href="#terms-p5">{{__('terms.P5')}}</a>
                6. <a href="#terms-p6">{{__('terms.P6')}}</a>
                7. <a href="#terms-p7">{{__('terms.P7')}}</a>
                8. <a href="#terms-p8">{{__('terms.P8')}}</a>
                9. <a href="#terms-p9">{{__('terms.P9')}}</a>
                10. <a href="#terms-p10">{{__('terms.P10')}}</a>
                11. <a href="#terms-p11">{{__('terms.P11')}}</a>
            </div>
			<article class="policy">
				<ol class="policy-list">
					<li>
						<a id="terms-p1">{{__('terms.P1')}}</a>
						<ol>
							<li>{{__('terms.P1.1')}}</li>
							<li>{{__('terms.P1.2')}}</li>
							<li>{{__('terms.P1.3')}}
                                <ul>
                                    <li>{{__('terms.P1.3.a')}}</li>
                                    <li>{{__('terms.P1.3.b')}}</li>
                                    <li>{{__('terms.P1.3.c')}}</li>
                                    <li>{{__('terms.P1.3.d')}}</li>
                                    <li>{{__('terms.P1.3.e')}}</li>
                                    <li>{{__('terms.P1.3.f')}}</li>
                                    <li>{{__('terms.P1.3.g')}}</li>
                                    <li>{{__('terms.P1.3.h')}}</li>
                                    <li>{{__('terms.P1.3.i')}}</li>
                                    <li>{{__('terms.P1.3.j')}}</li>
                                    <li>{{__('terms.P1.3.k')}}</li>
                                    <li>{{__('terms.P1.3.l')}}</li>
                                    <li>{{__('terms.P1.3.m')}}</li>
                                </ul>
                            </li>
							<li>{{__('terms.P1.4')}}</li>
							<li>{{__('terms.P1.5')}}</li>
							<li>{{__('terms.P1.6')}}</li>
							<li>{{__('terms.P1.7')}}</li>
							<li>{{__('terms.P1.8')}}</li>
						</ol>
					</li>
					<li>
                        <a id="terms-p2">{{__('terms.P2')}}</a>
						<ol>
							<li>{{__('terms.P2.1')}}</li>
							<li>{{__('terms.P2.2')}}</li>
							<li>{{__('terms.P2.3')}}</li>
							<li>{{__('terms.P2.4')}}</li>
							<li>{{__('terms.P2.5')}}</li>
							<li>{{__('terms.P2.6')}}</li>
							<li>{{__('terms.P2.7')}}</li>
							<li>{{__('terms.P2.8')}}</li>
							<li>{{__('terms.P2.9')}}</li>
							<li>{{__('terms.P2.10')}}</li>
							<li>{{__('terms.P2.11')}}</li>
							<li>{{__('terms.P2.12')}}</li>
							<li>{{__('terms.P2.13')}}
                                <ul>
                                    <li>{{__('terms.P2.13.a')}}</li>
                                    <li>{{__('terms.P2.13.b')}}</li>
                                </ul>
                            </li>
							<li>{{__('terms.P2.14')}}</li>
							<li>{{__('terms.P2.15')}}
                                <ol>
                                    <li>{{__('terms.P2.15.1')}}</li>
                                    <li>{{__('terms.P2.15.2')}}</li>
                                    <li>{{__('terms.P2.15.3')}}</li>
                                    <li>{{__('terms.P2.15.4')}}</li>
                                    <li>{{__('terms.P2.15.5')}}</li>
                                    <li>{{__('terms.P2.15.6')}}</li>
                                    <li>{{__('terms.P2.15.7')}}</li>
                                    <li>{{__('terms.P2.15.8')}}</li>
                                    <li>{{__('terms.P2.15.9')}}</li>
                                    <li>{{__('terms.P2.15.10')}}</li>
                                    <li>{{__('terms.P2.15.11')}}</li>
                                    <li>{{__('terms.P2.15.12')}}</li>
                                    <li>{{__('terms.P2.15.13')}}</li>
                                    <li>{{__('terms.P2.15.14')}}</li>
                                    <li>{{__('terms.P2.15.15')}}</li>
                                    <li>{{__('terms.P2.15.16')}}</li>
                                    <li>{{__('terms.P2.15.17')}}
                                        <ul>
                                            <li>{{__('terms.P2.15.17.a')}}</li>
                                            <li>{{__('terms.P2.15.17.b')}}</li>
                                            <li>{{__('terms.P2.15.17.c')}}</li>
                                            <li>{{__('terms.P2.15.17.d')}}</li>
                                            <li>{{__('terms.P2.15.17.e')}}</li>
                                            <li>{{__('terms.P2.15.17.f')}}</li>
                                            <li>{{__('terms.P2.15.17.g')}}</li>
                                            <li>{{__('terms.P2.15.17.h')}}</li>
                                            <li>{{__('terms.P2.15.17.i')}}</li>
                                            <li>{{__('terms.P2.15.17.j')}}</li>
                                            <li>{{__('terms.P2.15.17.k')}}</li>
                                            <li>{{__('terms.P2.15.17.l')}}</li>
                                            <li>{{__('terms.P2.15.17.m')}}</li>
                                            <li>{{__('terms.P2.15.17.n')}}</li>
                                            <li>{{__('terms.P2.15.17.o')}}</li>
                                            <li>{{__('terms.P2.15.17.p')}}</li>
                                            <li>{{__('terms.P2.15.17.q')}}</li>
                                            <li>{{__('terms.P2.15.17.r')}}</li>
                                        </ul>
                                        {{__('terms.P2.15.17.end')}}
                                    </li>
                                </ol>
                            </li>
							<li>{{__('terms.P2.16')}}
                                <ol>
                                    <li>{{__('terms.P2.16.1')}}</li>
                                    <li>{{__('terms.P2.16.2')}}</li>
                                    <li>{{__('terms.P2.16.3')}}</li>
                                    <li>{{__('terms.P2.16.4')}}</li>
                                    <li>{{__('terms.P2.16.5')}}</li>
                                    <li>{{__('terms.P2.16.6')}}</li>
                                </ol>
                            </li>
							<li>{{__('terms.P2.17')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p3">{{__('terms.P3')}}</a>
						<ol>
							<li>{{__('terms.P3.1')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p4">{{__('terms.P4')}}</a>
						<ol>
							<li>{{__('terms.P4.1')}}</li>
							<li>{{__('terms.P4.2')}}</li>
							<li>{{__('terms.P4.3')}}</li>
							<li>{{__('terms.P4.4')}}</li>
							<li>{{__('terms.P4.5')}}</li>
							<li>{{__('terms.P4.6')}}</li>
							<li>{{__('terms.P4.7')}}</li>
							<li>{{__('terms.P4.8')}}
                                <ul>
                                    <li>{{__('terms.P4.8.a')}}</li>
                                    <li>{{__('terms.P4.8.b')}}</li>
                                    <li>{{__('terms.P4.8.c')}}</li>
                                    <li>{{__('terms.P4.8.d')}}</li>
                                    <li>{{__('terms.P4.8.e')}}</li>
                                    <li>{{__('terms.P4.8.f')}}</li>
                                    <li>{{__('terms.P4.8.g')}}</li>
                                    <li>{{__('terms.P4.8.h')}}</li>
                                    <li>{{__('terms.P4.8.i')}}</li>
                                    <li>{{__('terms.P4.8.j')}}</li>
                                    <li>{{__('terms.P4.8.k')}}</li>
                                    <li>{{__('terms.P4.8.l')}}</li>
                                    <li>{{__('terms.P4.8.m')}}</li>
                                    <li>{{__('terms.P4.8.n')}}</li>
                                    <li>{{__('terms.P4.8.o')}}</li>
                                    <li>{{__('terms.P4.8.p')}}</li>
                                    <li>{{__('terms.P4.8.q')}}</li>
                                    <li>{{__('terms.P4.8.r')}}</li>
                                    <li>{{__('terms.P4.8.s')}}</li>
                                    <li>{{__('terms.P4.8.t')}}</li>
                                    <li>{{__('terms.P4.8.u')}}</li>
                                    <li>{{__('terms.P4.8.v')}}</li>
                                </ul>
                            </li>
							<li>{{__('terms.P4.9')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p5">{{__('terms.P5')}}</a>
						<ol>
							<li>{{__('terms.P5.1')}}
                                <ul>
                                    <li>{{__('terms.P5.1.a')}}</li>
                                    <li>{{__('terms.P5.1.b')}}</li>
                                    <li>{{__('terms.P5.1.c')}}</li>
                                    <li>{{__('terms.P5.1.d')}}</li>
                                    <li>{{__('terms.P5.1.e')}}</li>
                                    <li>{{__('terms.P5.1.f')}}</li>
                                    <li>{{__('terms.P5.1.g')}}</li>
                                    <li>{{__('terms.P5.1.h')}}</li>
                                    <li>{{__('terms.P5.1.i')}}</li>
                                    <li>{{__('terms.P5.1.j')}}</li>
                                    <li>{{__('terms.P5.1.k')}}</li>
                                    <li>{{__('terms.P5.1.l')}}</li>
                                </ul>
                            </li>
						</ol>
					</li>
					<li>
						<a id="terms-p6">{{__('terms.P6')}}</a>
						<ol>
							<li>{{__('terms.P6.1')}}</li>
							<li>{{__('terms.P6.2')}}</li>
							<li>{{__('terms.P6.3')}}</li>
							<li>{{__('terms.P6.4')}}</li>
							<li>{{__('terms.P6.5')}}
                                <ul>
                                    <li>{{__('terms.P6.5.a')}}</li>
                                    <li>{{__('terms.P6.5.b')}}</li>
                                    <li>{{__('terms.P6.5.c')}}</li>
                                    <li>{{__('terms.P6.5.d')}}</li>
                                    <li>{{__('terms.P6.5.e')}}</li>
                                </ul>
                            </li>
							<li>{{__('terms.P6.6')}}
                                <ul>
                                    <li>{{__('terms.P6.6.a')}}</li>
                                    <li>{{__('terms.P6.6.b')}}</li>
                                    <li>{{__('terms.P6.6.c')}}</li>
                                    <li>{{__('terms.P6.6.d')}}</li>
                                </ul>
                            </li>
							<li>{{__('terms.P6.7')}}</li>
							<li>{{__('terms.P6.8')}}</li>
							<li>{{__('terms.P6.9')}}</li>
							<li>{{__('terms.P6.10')}}</li>
							<li>{{__('terms.P6.11')}}</li>
							<li>{{__('terms.P6.12')}}</li>
							<li>{{__('terms.P6.13')}}</li>
							<li>{{__('terms.P6.14')}}</li>
							<li>{{__('terms.P6.15')}}</li>
							<li>{{__('terms.P6.16')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p7">{{__('terms.P7')}}</a>
						<ol>
							<li>{{__('terms.P7.1')}}</li>
							<li>{{__('terms.P7.2')}}</li>
							<li>{{__('terms.P7.3')}}</li>
							<li>{{__('terms.P7.4')}}
                                <ul>
                                    <li>{{__('terms.P7.4.a')}}</li>
                                    <li>{{__('terms.P7.4.b')}}</li>
                                </ul>
                                {{__('terms.P7.4.end')}}
                            </li>
							<li>{{__('terms.P7.5')}}</li>
							<li>{{__('terms.P7.6')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p8">{{__('terms.P8')}}</a>
						<ol>
							<li>{{__('terms.P8.1')}}</li>
							<li>{{__('terms.P8.2')}}</li>
							<li>{{__('terms.P8.3')}}</li>
							<li>{{__('terms.P8.4')}}</li>
							<li>{{__('terms.P8.5')}}</li>
							<li>{{__('terms.P8.6')}}</li>
							<li>{{__('terms.P8.7')}}</li>
							<li>{{__('terms.P8.8')}}</li>
							<li>{{__('terms.P8.9')}}</li>
							<li>{{__('terms.P8.10')}}</li>
							<li>{{__('terms.P8.11')}}</li>
							<li>{{__('terms.P8.12')}}</li>
							<li>{{__('terms.P8.13')}}</li>
							<li>{{__('terms.P8.14')}}</li>
							<li>{{__('terms.P8.15')}}</li>
							<li>{{__('terms.P8.16')}}</li>
							<li>{{__('terms.P8.17')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p9">{{__('terms.P9')}}</a>
						<ol>
							<li>{{__('terms.P9.1')}}</li>
							<li>{{__('terms.P9.2')}}</li>
							<li>{{__('terms.P9.3')}}</li>
							<li>{{__('terms.P9.4')}}
                                <ol>
                                    <li>{{__('terms.P9.4.1')}}</li>
                                    <li>{{__('terms.P9.4.2')}}</li>
                                </ol>
                            </li>
							<li>{{__('terms.P9.5')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p10">{{__('terms.P10')}}</a>
						<ol>
							<li>{{__('terms.P10.1')}}</li>
						</ol>
					</li>
					<li>
						<a id="terms-p11">{{__('terms.P11')}}</a>
						<ol>
							<li>{{__('terms.P11.1')}}</li>
							<li>{{__('terms.P11.2')}}</li>
							<li>{{__('terms.P11.3')}}</li>
							<li>{{__('terms.P11.4')}}</li>
							<li>{{__('terms.P11.5')}}</li>
							<li>{{__('terms.P11.6')}}</li>
							<li>{{__('terms.P11.7')}}</li>
						</ol>
					</li>
				</ol>
			</article>
		</div>
	</div>
@endsection
