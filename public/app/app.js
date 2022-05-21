'use strict'

window.Swal = require('sweetalert2');
window.axios = require('axios');
window.toastr = require('toastr');
window.select2 = require('select2');
window.summernote = require('summernote')

window.baseUrlAsset =  'https://sim-cms.dev.pinteraktif.id/storage/'

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.messages = (message,url) => {
    Swal.fire({
        title: 'Success',
        text: message,
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke'
    }).then((result) => {
        if (result.value) {
            window.location.href = url;
        }
    })
}

window.beforeLoadingAttr = (el) => {
    $(el).addClass("btn btn-brand kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light");
}

window.afterLoadingAttr = (el) => {
    $(el).removeClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light");
}

Number.prototype.formatMoney = function(c, d, t) {
    var n = this;
    c = isNaN(c = Math.abs(c)) ? 2 : c;
    d = d === undefined ? "." : d;
    t = (t === undefined ? "," : t);
    var s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

window.getValue = (element) => {
    return document.getElementById(element).value
}

window.getRadioValue = (element) => {
    var radios = document.getElementsByName(element);
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            return radios[i].value;
        }
    }
}

var KTAvatarDemo = function () {
    // Private functions
    var initDemos = function () {
        var avatar1 = new KTAvatar('kt_avatar');
    }
    return {
        // public functions
        init: function () {
            initDemos();
        }
    };
}();

KTUtil.ready(function () {
    KTAvatarDemo.init();
});

export default messages
