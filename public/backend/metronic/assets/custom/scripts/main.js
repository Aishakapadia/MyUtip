var main = {
    baseUrl: location.protocol + "//" + location.hostname + (location.port && ":" + location.port) + "/",
    adminUrl: function (uri) {
        return this.baseUrl + 'panel/' + uri
    },
    only_digit_allowed: function (el) {
        return el.value = el.value.replace(/[^0-9]/, '');
    },
    only_price_allowed: function (el) {
        return el.value = el.value.replace(/[^0-9.]/, '');
    },
    only_text_allowed: function (el) {
        return el.value = el.value.replace(/[^a-zA-Z ]/, '');
    },
    log: function (data) {
        if (this.baseUrl != 'http://local.ims.com/') {
            return false;
        }
        console.log(data);
    },
    showLoader: function () {
        App.blockUI();
    },
    hideLoader: function () {
        App.unblockUI();
    },
    ajax: function (url, type, data, success, error, beforeSend, complete, ajaxOptions) {
        ajaxOptions = ajaxOptions || {};
        beforeSend = beforeSend || function () {
            // Show loader
            main.showLoader();
        };
        complete = complete || function () {
            // Hide loader
            main.hideLoader();
        };
        var options = {
            url: url,
            type: type,
            data: data,
            beforeSend: beforeSend,
            complete: complete,
            success: success,
            error: error
        };
        jqAjaxOptions = $.extend(options, ajaxOptions);
        var jqXHR = jQuery.ajax(jqAjaxOptions);
        return jqXHR;
    },
    adminAjax: function (url, method, data, success, error, beforeSend, complete, ajaxOptions) {
        ajaxOptions = ajaxOptions || {};
        beforeSend = beforeSend || function () {
            main.showLoader();
        };
        complete = complete || function () {
            main.hideLoader();
        };
        var options = {
            url: main.adminUrl(url),
            method: method,
            data: data,
            success: success,
            error: error,
            beforeSend: beforeSend,
            complete: complete
        };
        jqAjaxOptions = $.extend(options, ajaxOptions);
        var jqXHR = jQuery.ajax(jqAjaxOptions);
        return jqXHR;
    },
    custom_ajax: function (options, callback) {
        var defaults = { //set the defaults
            success: function (data) { //hijack the success handler
                if (check(data)) { //checks
                    callback(data); //if pass, call the callback
                }
            }
        };
        $.extend(options, defaults); //merge passed options to defaults
        return $.ajax(options); //send request
    },
    loadingMsg: '<h2>Please wait...</h2>',
    convertToSlug: function (text) {
        return text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
    },
    setCookie: function (cookie_name, cookie_value, expiry_days) {
        var d = new Date();
        d.setTime(d.getTime() + (expiry_days * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cookie_name + "=" + cookie_value + ";" + expires + ";path=/";
    },
    getCookie: function (cookie_name) {
        var name = cookie_name + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    },
    delCookie: function (cookie_name) {
        document.cookie = cookie_name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    },
    initHorizontalScroll: function (selector) {
        var speed = 0;
        var scroll = 0;
        var container = $(selector);
        var container_w = container.width();
        var max_scroll = container[0].scrollWidth - container.outerWidth()  + 55;
        var prev_frame = new Date().getTime();

        var pointers = '<div class="scroll-pointer" style="background: #36c6d3; display: block; width: 10px; position: absolute; z-index: 9985; opacity: 0.1; height: 100%; top: 0px; left: 16px;"></div>\n' +
            '<div class="scroll-pointer" style="background: #36c6d3; display: block; width: 10px; position: absolute; z-index: 9985; opacity: 0.1; height: 100%; top: 0px; right: 16px; "></div>';

        $('.portlet-datatable').prepend(pointers);
        $('.portlet-datatable').css({'margin-bottom': '0px'});

        // container.on('mousemove', function (e) {
        //     var mouse_x = e.pageX - container.offset().left;
        //     var mouseperc = 100 * mouse_x / container_w;
        //     speed = mouseperc - 50;
        // }).on('mouseleave', function () {
        //     speed = 0;
        // });

        // var table_height = $(selector + ' table').height;
        // $('.scroll-pointer').css('height', table_height + 'px');

        // $(selector + ' table').on('mouseover', function () {
        //     $('.scroll-pointer').show();
        // }).on('mouseleave', function() {
        //     $('.scroll-pointer').hide();
        // });

        $('.scroll-pointer').on('mousemove', function (e) {
            var mouse_x = e.pageX - container.offset().left;
            var mouseperc = 100 * mouse_x / container_w;
            speed = mouseperc - 50;
        }).on('mouseleave', function () {
            speed = 0;
        });


        function updatescroll() {
            var cur_frame = new Date().getTime();
            var time_elapsed = cur_frame - prev_frame;
            prev_frame = cur_frame;
            if (speed !== 0) {
                scroll += speed * time_elapsed / 50;
                if (scroll < 0) scroll = 0;
                if (scroll > max_scroll) scroll = max_scroll;
                $(selector).scrollLeft(scroll);
            }
            window.requestAnimationFrame(updatescroll);
        }

        window.requestAnimationFrame(updatescroll);
    }
};
/**
 * Filter datatable contents by Enter
 */
$('.form-filter').on('keypress', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        $('.filter-submit').click();
    }
});
/**
 * Filter datatable contents by on-change
 */
$('.form-filter').on('change', function () {
    $('.filter-submit').click();
});
//region Product Edit
$(document).on('click', '.jq_set_default', function () {
    var self = $(this);
    var data = {
        'product_id': self.data('product_id'),
        'product_image_id': self.data('product_image_id')
    };
    main.ajax(main.adminUrl('product/set-default'), 'POST', data, function (json) {
        console.log(json);
        if (true === json.status) {
        }
    }, function (error) {
    });
});
// TODO:: Develop remove image functionality.
$(document).on('click', '.jq_remove_image', function () {
    var self = $(this);
    var data = {
        'product_id': self.data('product_id'),
        'product_image_id': self.data('product_image_id')
    };
    // console.log(data);
    main.ajax(main.adminUrl('product/remove-image'), 'POST', data, function (json) {
        console.log(json);
        if (true === json.success) {
            self.parent().parent().fadeOut();
        }
    }, function (error) {
    });
});
/** Product pricing calculations */
var cost_buying = $('#cost_buying'); // +
var cost_discount = $('#cost_discount'); // -
var cost_handling = $('#cost_handling'); // +
var cost_shipping = $('#cost_shipping'); // +
var cost_old = $('#cost_old');
var cost = $('#cost');
cost_buying_val = 0;
cost_discount_val = 0;
cost_handling_val = 0;
cost_shipping_val = 0;
cost_old_val = 0;
cost_val = 0;
$('#cost_buying').on('change keyup', function () {
    cost_buying_val = parseFloat(cost_buying.val());
    cost_discount_val = parseFloat(cost_discount.val());
    cost_handling_val = parseFloat(cost_handling.val());
    cost_shipping_val = parseFloat(cost_shipping.val());
    cost_old_val = cost_buying_val + cost_handling_val + cost_shipping_val;
    cost_val = cost_buying_val + cost_handling_val + cost_shipping_val - cost_discount_val;
    //$('#cost_old').val(parseFloat(cost_old_val));
    $('#cost').val(parseFloat(cost_val));
});
$('#cost_discount').on('change keyup', function () {
    cost_buying_val = parseFloat(cost_buying.val());
    cost_discount_val = parseFloat(cost_discount.val());
    cost_handling_val = parseFloat(cost_handling.val());
    cost_shipping_val = parseFloat(cost_shipping.val());
    cost_old_val = cost_buying_val + cost_handling_val + cost_shipping_val;
    cost_val = cost_buying_val + cost_handling_val + cost_shipping_val - cost_discount_val;
    // $('#cost_old').val(parseFloat(cost_old_val));
    $('#cost').val(parseFloat(cost_val));
});
$('#cost_handling').on('change keyup', function () {
    cost_buying_val = parseFloat(cost_buying.val());
    cost_discount_val = parseFloat(cost_discount.val());
    cost_handling_val = parseFloat(cost_handling.val());
    cost_shipping_val = parseFloat(cost_shipping.val());
    cost_old_val = cost_buying_val + cost_handling_val + cost_shipping_val;
    cost_val = cost_buying_val + cost_handling_val + cost_shipping_val - cost_discount_val;
    // $('#cost_old').val(parseFloat(cost_old_val));
    $('#cost').val(parseFloat(cost_val));
});
$('#cost_shipping').on('change keyup', function () {
    cost_buying_val = parseFloat(cost_buying.val());
    cost_discount_val = parseFloat(cost_discount.val());
    cost_handling_val = parseFloat(cost_handling.val());
    cost_shipping_val = parseFloat(cost_shipping.val());
    cost_old_val = cost_buying_val + cost_handling_val + cost_shipping_val;
    cost_val = cost_buying_val + cost_handling_val + cost_shipping_val - cost_discount_val;
    // $('#cost_old').val(parseFloat(cost_old_val));
    $('#cost').val(parseFloat(cost_val));
});
// $('.jq_product_categories').on('change keyup', function() {
//     var self = $(this);
//     $('.jq_other_attributes_container').removeClass('hide').addClass('show');
//     main.ajax(main.baseUrl + 'panel/product/generate-attributes', 'POST', {
//         category: self.val()
//     }, function(json) {
//         console.log(json);
//         $('.jq_other_attributes').html(json);
//     }, function(error) {
//         $('.jq_other_attributes_container').removeClass('show').addClass('hide');
//     });
// });
$('.tab_attributes').on('click', function () {
    var category_id = $('.jq_product_categories').val();
    //console.log(category_id[0]);
    $('.jq_other_attributes_container').removeClass('hide').addClass('show');
    main.ajax(main.adminUrl('product/generate-attributes'), 'POST', {
        category: category_id,
        product_id: $('#product_id').val()
    }, function (json) {
        // console.log(json);
        $('.jq_other_attributes').html(json);
    }, function (error) {
        $('.jq_other_attributes_container').removeClass('show').addClass('hide');
    });
});
$(document).on('click', '.checkAll', function () {
    var self = $(this);
    var attribute_id = self.data('id');
    console.log(attribute_id);
    $('input:checkbox.child-' + attribute_id).prop('checked', this.checked).uniform();
    // $.uniform.update();
    // $.uniform.update('.checkAll');
});
//endregion


/**
 * @author Khalil
 * jQuery Parent Child Plugin, for selecting parent if all children selected vice versa.
 *
 * @usage
 * 1) element should be .kjq_parent_child
 * 2) parent class should be .kjq_parent
 * 3) child class should be .kjq_child
 */
$(document).on('change', '.kjq_child', function () {
    var element = $(this).parents('.kjq_parent_child');
    if ($(element).find('.kjq_child').length == $(element).find('.kjq_child:checked').length) {
        $(element).find('.kjq_parent').prop('checked', true);
    } else {
        $(element).find('.kjq_parent').prop('checked', false);
    }
});