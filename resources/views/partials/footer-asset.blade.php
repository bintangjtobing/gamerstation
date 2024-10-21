<!-- jquery -->
<script src="{{ asset('public/frontend') }}/js/jquery-3.5.1.js"></script>
<!-- bootstrap js -->
<script src="{{ asset('public/frontend') }}/js/bootstrap.bundle.js"></script>
<!-- swipper js -->
<script src="{{ asset('public/frontend') }}/js/swiper.js"></script>
<!-- odometer js -->
<script src="{{ asset('public/frontend') }}/js/odometer.js"></script>
<!-- viewport js -->
<script src="{{ asset('public/frontend') }}/js/viewport.jquery.js"></script>
<!-- smooth scroll js -->
<script src="{{ asset('public/frontend') }}/js/smoothscroll.js"></script>
<!-- nice-select -->
<script src="{{ asset('public/frontend') }}/js/jquery.nice-select.js"></script>
<!-- lightcase js -->
<script src="{{ asset('public/frontend') }}/js/lightcase.js"></script>

{{-- common all project  --}}
<script src="{{ asset('public/backend/js/select2.js') }}"></script>
<!--  Popup -->
<script src="{{ asset('public/backend/library/popup/jquery.magnific-popup.js') }}"></script>
<!-- file holder js -->
<script src="https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-script.js" type="module"></script>
<script>
    var fileHolderAfterLoad = {};
</script>
<script type="module">
    import { fileHolderSettings } from "https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-settings.js";
    import { previewFunctions } from "https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-script.js";

    var inputFields = document.querySelector(".file-holder");
    fileHolderAfterLoad.previewReInit = function(inputFields) {
        previewFunctions.previewReInit(inputFields)
    };

    fileHolderSettings.urls.uploadUrl = "{{ setRoute('fileholder.upload') }}";
    fileHolderSettings.urls.removeUrl = "{{ setRoute('fileholder.remove') }}";
</script>
<script>
    function fileHolderPreviewReInit(selector) {
        var inputField = document.querySelector(selector);
        fileHolderAfterLoad.previewReInit(inputField);
    }
</script>
<script src="{{ asset('public/frontend') }}/js/main.js"></script>
<script>
    var allCountries = "";

    function getAllCountries(hitUrl, targetElement = $(".country-select"), errorElement = $(".country-select").siblings(
        ".select2")) {
        if (targetElement.length == 0) {
            return false;
        }
        var CSRF = $("meta[name=csrf-token]").attr("content");
        var data = {
            _token: CSRF,
        };
        $.post(hitUrl, data, function() {
            // success
            $(errorElement).removeClass("is-invalid");
            $(targetElement).siblings(".invalid-feedback").remove();
        }).done(function(response) {
            // Place States to States Field
            var options = "<option selected disabled>Select Country</option>";
            var selected_old_data = "";
            if ($(targetElement).attr("data-old") != null) {
                selected_old_data = $(targetElement).attr("data-old");
            }
            $.each(response, function(index, item) {
                options +=
                    `<option value="${item.name}" data-id="${item.id}" data-mobile-code="${item.mobile_code}" data-currency-name="${item.currency_name}" data-currency-code="${item.currency_code}" data-currency-symbol="${item.currency_symbol}" ${selected_old_data == item.name ? "selected" : ""}>${item.name}</option>`;
            });

            allCountries = response;

            $(targetElement).html(options);
        }).fail(function(response) {
            var faildMessage = "Something went worng! Please try again.";
            var faildElement = `<span class="invalid-feedback" role="alert">
                              <strong>${faildMessage}</strong>
                          </span>`;
            $(errorElement).addClass("is-invalid");
            if ($(targetElement).siblings(".invalid-feedback").length != 0) {
                $(targetElement).siblings(".invalid-feedback").text(faildMessage);
            } else {
                errorElement.after(faildElement);
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $(".show_hide_password .show-pass").on('click', function(event) {
            event.preventDefault();
            if ($(this).parent().find("input").attr("type") == "text") {
                $(this).parent().find("input").attr('type', 'password');
                $(this).find("i").addClass("la-eye-slash");
                $(this).find("i").removeClass("la-eye");
            } else if ($(this).parent().find("input").attr("type") == "password") {
                $(this).parent().find("input").attr('type', 'text');
                $(this).find("i").removeClass("la-eye-slash");
                $(this).find("i").addClass("la-eye");
            }
        });
    });
</script>
<script>
    function laravelCsrf() {
        return $("head meta[name=csrf-token]").attr("content");
    }
    //for popup
    function openAlertModal(URL, target, message, actionBtnText = "Remove", method = "DELETE") {
        if (URL == "" || target == "") {
            return false;
        }

        if (message == "") {
            message = "Are you sure to delete ?";
        }
        var method = `<input type="hidden" name="_method" value="${method}">`;
        openModalByContent({
                content: `<div class="card modal-alert border-0">
                        <div class="card-body">
                            <form method="POST" action="${URL}">
                                <input type="hidden" name="_token" value="${laravelCsrf()}">
                                ${method}
                                <div class="head mb-3 text-dark">
                                    ${message}
                                    <input type="hidden" name="target" value="${target}">
                                </div>
                                <div class="foot d-flex align-items-center justify-content-between">
                                    <button type="button" class="modal-close btn btn--info rounded text-light">Close</button>
                                    <button type="submit" class="alert-submit-btn btn btn--danger btn-loading rounded text-light">${actionBtnText}</button>
                                </div>
                            </form>
                        </div>
                    </div>`,
            },

        );
    }

    function openModalByContent(data = {
        content: "",
        animation: "mfp-move-horizontal",
        size: "medium",
    }) {
        $.magnificPopup.open({
            removalDelay: 500,
            items: {
                src: `<div class="white-popup mfp-with-anim ${data.size ?? "medium"}">${data.content}</div>`, // can be a HTML string, jQuery object, or CSS selector
            },
            callbacks: {
                beforeOpen: function() {
                    this.st.mainClass = data.animation ?? "mfp-move-horizontal";
                },
                open: function() {
                    var modalCloseBtn = this.contentContainer.find(".modal-close");
                    $(modalCloseBtn).click(function() {
                        $.magnificPopup.close();
                    });
                },
            },
            midClick: true,
        });
    }

    function openDeleteModal(URL, target, message, actionBtnText = "Remove", method = "DELETE") {
        if (URL == "" || target == "") {
            return false;
        }

        if (message == "") {
            message = "Are you sure toll delete ?";
        }
        var method = `<input type="hidden" name="_method" value="${method}">`;

        openModalByContent({
                content: `<div class="card modal-alert border-0">
                      <div class="card-body">
                          <form method="POST" action="${URL}">
                              <input type="hidden" name="_token" value="${laravelCsrf()}">
                              ${method}
                              <div class="head mb-3 text-dark">
                                  ${message}
                                  <input type="hidden" name="target" value="${target}">
                              </div>
                              <div class="foot d-flex align-items-center justify-content-between">
                                  <button type="button" class="modal-close btn btn--info rounded text-white">Close</button>
                                  <button type="submit" class="alert-submit-btn btn btn--danger btn-loading rounded text-white">${actionBtnText}</button>
                              </div>
                          </form>
                      </div>
                  </div>`,
            },

        );
    }
</script>
<script>
    $(".langSel").on("change", function() {
        window.location.href = "{{ route('index') }}/change/" + $(this).val();
    });
</script>
<script>
    /**
     * Function for search admin panel sidebar menu item
     */
    function sideBarSearch() {
        var menuLinks = $(".sidebar-menu a");
        var filterMenuItem = [];
        $.each(menuLinks, function(index, item) {
            // console.log(index, item)
            if ($(item).attr("href") != "javascript:void(0)") {
                filterMenuItem.push(item);
            }
        });


        $(".sidebar-search-input").keyup(function() {
            sideBarSearchWithInput($(this), filterMenuItem);
        })
    }
    sideBarSearch();


    function sideBarSearchWithInput(input, navItems) {
        var inputValue = input.val().toLowerCase();
        var searchResult = [];
        $.each(navItems, function(index, item) {
            var title = $(item).find("span").text().toLowerCase();
            var result = title.match(inputValue)
            if (result != null) {
                searchResult.push(item);
            }
        });
        $(".sidebar-search-result").html("");

        $.each(searchResult, function(index, item) {
            // console.log(item)
            var link = $(item).attr("href");
            var title = $(item).find("span").text();
            var iconClass = $(item).find("i").attr("class");
            var singleItem = `<div class="single-item">
                <a href="${link}">
                <i class="${iconClass}"></i>
                <span style="position:inherit">${title}</span>
                </a>
        </div>`
            $(".sidebar-search-result").append(singleItem);
        });



        // $(".header-search-wrapper .sidebar-search-input").click(function () {
        //     $(".header-search-wrapper .sidebar-search-result").addClass("active");
        // });




        // console.log(singleItem);
    }
</script>
