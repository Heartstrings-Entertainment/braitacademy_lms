@extends(getTemplate().'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/css/css-stars.css">
    <link rel="stylesheet" href="/assets/default/vendors/video/video-js.min.css">
@endpush

@section('content')
    <section class="course-cover-container {{ empty($activeSpecialOffer) ? 'not-active-special-offer' : '' }}">
        <img src="{{ $course->getImageCover() }}" class="img-cover course-cover-img" alt="{{ $course->title }}"/>

        <div class="cover-content pt-40">
            <div class="container position-relative">
                @if(!empty($activeSpecialOffer))
                    @include('web.default.course.special_offer')
                @endif
            </div>
        </div>
    </section>

    @php
        $percent = $course->getProgress();
    @endphp

    <section class="container course-content-section {{ $course->type }} {{ ($hasBought or $percent) ? 'has-progress-bar' : '' }}">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="course-content-body user-select-none">
                    <div class="course-body-on-cover text-white">
                        <h1 class="font-30 course-title">
                            {{ $course->title }}
                        </h1>

                        @if(!empty($course->category))
                            <span class="d-block font-16 mt-10">{{ trans('public.in') }} <a href="{{ $course->category->getUrl() }}" target="_blank" class="font-weight-500 text-decoration-underline text-white">{{ $course->category->title }}</a></span>
                        @endif

                        @if($hasBought or $percent)
                            <div class="mt-30 d-flex align-items-center">
                                <div class="progress course-progress flex-grow-1 shadow-xs rounded-sm">
                                    <span class="progress-bar rounded-sm bg-warning" style="width: {{ $percent }}%"></span>
                                </div>

                                <span class="ml-15 font-14 font-weight-500">
                                    @if($hasBought and (!$course->isWebinar() or $course->isProgressing()))
                                        {{ trans('public.course_learning_passed',['percent' => $percent]) }}
                                    @elseif(!is_null($course->capacity))
                                        {{ $course->getSalesCount() }}/{{ $course->capacity }} {{ trans('quiz.students') }}
                                    @else
                                        {{ trans('public.course_learning_passed',['percent' => $percent]) }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-35">
                        <ul class="nav nav-tabs bg-secondary rounded-sm p-15 d-flex align-items-center justify-content-between" id="tabs-tab" role="tablist">
                            <li class="nav-item">
                                <div class="position-relative font-14 text-white active" id="information-tab"
                                   data-toggle="tab" href="#information" role="tab" aria-controls="information"
                                   aria-selected="true">Certificate of Completion</div>
                            </li>
                        </ul>
                        <div class="tab-content" id="nav-tabContent">
                            <div 
                                class="tab-pane show active" 
                                id="information" role="tabpanel"
                                aria-labelledby="information-tab"
                            >
                                <div class="accordion-row rounded-sm border mt-15 p-15">
                                    <div class="d-flex align-items-center justify-content-between" role="tab" id="files_110">
                                        <div class="d-flex align-items-center" href="#collapseFiles110" aria-controls="collapseFiles110" data-parent="#chaptersAccordion" role="button" data-toggle="collapse" aria-expanded="true">
                                            <span class="d-flex align-items-center justify-content-center mr-15">
                                                <span class="chapter-icon chapter-content-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-gray"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                                </span>
                                            </span>

                                            <span class="font-weight-bold text-secondary font-14 file-title">{{ $course->title }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="collapse show">
                                        <div class="panel-collapse">
                                            <div class="text-gray text-14">
                                                {{-- Certificate description or additional information can go here --}}
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-20">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center text-gray text-center font-14 mr-20">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download-cloud text-gray mr-5">
                                                            <polyline points="8 17 12 21 16 17"></polyline>
                                                            <line x1="12" y1="12" x2="12" y2="21"></line>
                                                            <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>
                                                        </svg>
                                                        <span class="line-height-1">1 MB</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    @if ($certificate && (is_null($course->certificate_price) || $course->certificate_price == 0))
                                                        <a href="{{ url('panel/certificates/webinars/' . ($certificate->id ?? '#') . '/show') }}" target="_blank" class="course-content-btns btn btn-sm btn-primary">
                                                            {{ $certificate ? 'Download' : 'No certificate available' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-content-sidebar col-12 col-lg-4 mt-25 mt-lg-0">
                <div class="rounded-lg shadow-sm">
                    <div class="course-img {{ $course->video_demo ? 'has-video' : '' }}">
                        <img src="{{ $course->getImage() }}" class="img-cover" alt="">
                    </div>

                    <div class="px-20 pb-30">
                        <form action="/cart/store" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="item_id" value="{{ $certificate->id }}">
                            <input type="hidden" name="item_name" value="certificate_id">

                            <!--  use this to check if certificate can be sold -->
                            @php
                                $canSaleCertificate = ($course->canSaleCertificate());
                            @endphp

                            @if(isset($course->certificate_price) && $course->certificate_price > 0)
                                <div id="priceBox" class="d-flex align-items-center justify-content-center mt-20 {{ !empty($activeSpecialOffer) ? ' flex-column ' : '' }}">
                                    <div class="text-center">
                                        @php
                                            $realPrice = handleCoursePagePrice($course->certificate_price);
                                        @endphp
                                        <span id="realPrice" data-value="{{ $course->certificate_price }}"
                                              data-special-offer="{{ !empty($activeSpecialOffer) ? $activeSpecialOffer->percent : ''}}"
                                              class="d-block @if(!empty($activeSpecialOffer)) font-16 text-gray text-decoration-line-through @else font-30 text-primary @endif">
                                            {{ $realPrice['price'] }}
                                        </span>

                                        @if(!empty($realPrice['tax']) and empty($activeSpecialOffer))
                                            <span class="d-block font-14 text-gray">+ {{ $realPrice['tax'] }} {{ trans('cart.tax') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                            @if (is_null($course->certificate_price) || $course->certificate_price == 0)
                                <div class="d-flex align-items-center justify-content-center mt-20">
                                    <span class="font-36 text-primary">Get Certificate</span>
                                </div>
                                <div class="mt-20 d-flex flex-column">
                                    <a href="{{ url('panel/certificates/webinars/' . $certificate->id . '/show') }}" target="_blank" class="course-content-btns btn btn-sm btn-primary">
                                        Download
                                    </a>
                                </div>
                            @endif
                            @endif

                            <div class="mt-20 d-flex flex-column">
                                @if($canSaleCertificate && !empty($course->certificate_price) && $course->certificate_price > 0)
                                    <button type="button" class="btn btn-primary js-course-add-to-cart-btn"> 
                                        {{ trans('public.add_to_cart') }}
                                    </button>

                                    @if($canSaleCertificate && !empty(getFeaturesSettings('direct_classes_payment_button_status')) )
               
                                        <button type="button" class="btn btn-outline-danger mt-20 js-course-direct-certificate-payment">
                                            Pay Now!
                                        </button>
                                    @endif
                                @else
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.default.course.share_modal')
    @include('web.default.course.buy_with_point_modal')
@endsection



@push('scripts_bottom')
    <script src="/assets/default/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/default/vendors/video/video.min.js"></script>
    <script src="/assets/default/vendors/video/youtube.min.js"></script>
    <script src="/assets/default/vendors/video/vimeo.js"></script>

    <script>
        var webinarDemoLang = '{{ trans('webinars.webinar_demo') }}';
        var replyLang = '{{ trans('panel.reply') }}';
        var closeLang = '{{ trans('public.close') }}';
        var saveLang = '{{ trans('public.save') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportSuccessLang = '{{ trans('panel.report_success') }}';
        var reportFailLang = '{{ trans('panel.report_fail') }}';
        var messageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var copiedLang = '{{ trans('public.copied') }}';
        var learningToggleLangSuccess = '{{ trans('public.course_learning_change_status_success') }}';
        var learningToggleLangError = '{{ trans('public.course_learning_change_status_error') }}';
        var notLoginToastTitleLang = '{{ trans('public.not_login_toast_lang') }}';
        var notLoginToastMsgLang = '{{ trans('public.not_login_toast_msg_lang') }}';
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
        var canNotTryAgainQuizToastTitleLang = '{{ trans('public.can_not_try_again_quiz_toast_lang') }}';
        var canNotTryAgainQuizToastMsgLang = '{{ trans('public.can_not_try_again_quiz_toast_msg_lang') }}';
        var canNotDownloadCertificateToastTitleLang = '{{ trans('public.can_not_download_certificate_toast_lang') }}';
        var canNotDownloadCertificateToastMsgLang = '{{ trans('public.can_not_download_certificate_toast_msg_lang') }}';
        var sessionFinishedToastTitleLang = '{{ trans('public.session_finished_toast_title_lang') }}';
        var sessionFinishedToastMsgLang = '{{ trans('public.session_finished_toast_msg_lang') }}';
        var sequenceContentErrorModalTitle = '{{ trans('update.sequence_content_error_modal_title') }}';
        var courseHasBoughtStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasBoughtStatusToastMsgLang = '{{ trans('site.you_bought_webinar') }}';
        var courseNotCapacityStatusToastTitleLang = '{{ trans('public.request_failed') }}';
        var courseNotCapacityStatusToastMsgLang = '{{ trans('cart.course_not_capacity') }}';
        var courseHasStartedStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasStartedStatusToastMsgLang = '{{ trans('update.class_has_started') }}';
        var joinCourseWaitlistLang = '{{ trans('update.join_course_waitlist') }}';
        var joinCourseWaitlistModalHintLang = "{{ trans('update.join_course_waitlist_modal_hint') }}";
        var joinLang = '{{ trans('footer.join') }}';
        var nameLang = '{{ trans('auth.name') }}';
        var emailLang = '{{ trans('auth.email') }}';
        var phoneLang = '{{ trans('public.phone') }}';
        var captchaLang = '{{ trans('site.captcha') }}';
    </script>

    <script src="/assets/default/js/parts/comment.min.js"></script>
    <script src="/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/default/js/parts/webinar_show.min.js"></script>

@endpush