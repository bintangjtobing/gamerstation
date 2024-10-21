<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($page_title) ? __($page_title) : __('Dashboard') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oxanium:wght@200;300;400;500;600;700;800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-all.css') }}">


    @include('partials.header-asset')
    @stack('css')
</head>

<body>


    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Preloader
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    @include('frontend.partials.preloader')
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Preloader
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start body overlay
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="body-overlay" class="body-overlay"></div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End body overlay
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

    <div class="page-wrapper bg_img" data-background="{{ asset('public/frontend') }}/images/element/bg1.jpg"
        style="background-image: url(&quot;{{ asset('public/frontend') }}/images/element/bg1.jpg&quot;);">

        @include('user.partials.side-nav')

        <div class="main-wrapper">
            <div class="main-body-wrapper">

                @include('user.partials.top-nav')


                @yield('content')

            </div>
        </div>
    </div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

    @include('partials.footer-asset')
    @include('admin.partials.notify')
    @include('user.partials.auth-control')
    <script src="{{ asset('public/frontend/js/apexcharts.js') }}"></script>
    <script>
        function openDeleteModal(URL, target, message, actionBtnText = "Remove", method = "DELETE") {
            if (URL == "" || target == "") {
                return false;
            }

            if (message == "") {
                message = "Are you sure to delete ?";
            }
            var method = `<input type="hidden" name="_method" value="${method}">`;

            openModalByContent({
                    content: `<div class="card text-dark modal-alert border-0">
                      <div class="card-body">
                          <form method="POST" action="${URL}">
                              <input type="hidden" name="_token" value="${laravelCsrf()}">
                              ${method}
                              <div class="head mb-3">
                                  ${message}
                                  <input type="hidden" name="target" value="${target}">
                              </div>
                              <div class="foot d-flex align-items-center justify-content-between">
                                  <button type="button" class="modal-close btn btn--info">{{ __('Close') }}</button>
                                  <button type="submit" class="alert-submit-btn btn btn--danger btn-loading">${actionBtnText}</button>
                              </div>
                          </form>
                      </div>
                  </div>`,
                },

            );
        }
    </script>
    @stack('script')



</body>

</html>
