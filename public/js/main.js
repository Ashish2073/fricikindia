function validateForm(form, options) {
    let defaults = {
        ignore: [],
        rules: {},
        errorElement: "div",
        errorClass: "invalid-feedback",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        messages: {},
        errorPlacement: function (error, element) {
            if (element.hasClass("select")) {
                element = element.next();
            }
            error.insertAfter(element);
        },
    };
    options = $.extend(defaults, options);
    form.validate(options);
}

function log(message) {
    let debug = true;
    if (debug) {
        console.log(message);
    }
}
function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
/**
 *
 * @param {json} url Url to get content from
 * @param {function} callback called callback function after ajax request complete.
 */
function getContent(options) {
    let defaults = {
        beforeSend: function () {},
    };
    options = $.extend(defaults, options);
    hideMessage();
    $.ajax(options);
}

/**
 *
 * @param {string} url Url to get content from
 * @param {function} callback called callback function after ajax request complete.
 */
function submitForm(form, options) {
    if (form.length === 0) {
        return;
    }
    hideMessage();
    let defaults = {
        beforeSubmit: function () {},
        success: function () {},
    };
    options = $.extend(defaults, options);
    form.ajaxForm(options);
}

function showMessage(msg, msgType) {
    let button = "";
    button +=
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    button += '<span aria-hidden="true">&times;</span>';
    button += "</button>";
    let message =
        "<div role='alert' class='text-center alert alert-dismissible alert-" +
        msgType +
        "'>" +
        msg +
        button +
        "</div>";
    $("#ajax-message").html(message).removeClass("hide").addClass("show");
}

/**
 *
 * @param {type} type success|error|info|warning
 * @param {msg} msg Message
 * @param {Title} title Title
 */
function toast(type, msg, title) {
    toastr[type](msg, title);
}
function hideMessage() {
    $("#ajax-message").addClass("hide").removeClass("show");
}

function modalLoader() {
    $(".dynamic-body").html("<h1>Loading...</h1>");
}
function modalReset() {
    $(".dynamic-body").html("");
    $("#dynamic-modal").modal("hide");
}

function submitLoader(ele = "") {
    if (ele === "") {
        $("*[type='submit']").attr("disabled", true).html("Loading...");
    } else {
        $(ele)
            .attr("disabled", true)
            .prepend("<i class='fa fa-spinner fa-spin'></i> ");
    }
}

function initDatatable() {
    if ($(".custom-row").find("select").length > 0) {
        $(".custom-row").find("select").selectpicker();
    }
}
function setAlert(data) {
    if (data.code) {
        toast(data.code, data.message, data.title);
    }
}
function submitReset(ele, text) {
    $(ele).find("i:first").remove();
    $(ele).removeAttr("disabled");
    // $(ele).removeAttr('disabled').html(text);
}

if ($(".calling-code-picker").length) {
    $(".calling-code-picker")
        .selectpicker()
        .ajaxSelectPicker({
            ajax: {
                // data source
                url: window.Laravel.appUrl + "/api/countries/calling-codes",

                // ajax type
                type: "POST",

                // data type
                dataType: "json",

                // Use "" as a placeholder and Ajax Bootstrap Select will
                // automatically replace it with the value of the search query.
                data: {
                    q: "91",
                },
            },

            // function to preprocess JSON data
            preprocessData: function (data) {
                var i,
                    l = data.length,
                    array = [];
                if (l) {
                    for (i = 0; i < l; i++) {
                        array.push(
                            $.extend(true, data[i], {
                                text: data[i].Name,
                                value: data[i].Email,
                                data: {
                                    subtext: data[i].Email,
                                },
                            })
                        );
                    }
                }
                // You must always return a valid array when processing data. The
                // data argument passed is a clone and cannot be modified directly.
                return array;
            },
        });
}

// if no Webkit browser
(function () {
    try {
        let isChrome =
            /Chrome/.test(navigator.userAgent) &&
            /Google Inc/.test(navigator.vendor);
        let isSafari =
            /Safari/.test(navigator.userAgent) &&
            /Apple Computer/.test(navigator.vendor);
        let scrollbarDiv = document.querySelector(".scrollbar");
        if (!isChrome && !isSafari) {
            scrollbarDiv.innerHTML = "You need Webkit browser to run this code";
        }
    } catch (e) {}
})();

//select ajax options
