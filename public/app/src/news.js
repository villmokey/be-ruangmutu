'use strict'

import { createData, updateData, updatePutData, deleteData, showData } from "../api";

var DataNews = function () {

    var initTable1 = function () {
        var table = $('#kt_table_news');

        // begin first table
        table.DataTable({
            language: {
                url : 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: '/data-news',
            columns: [
                { data: 'DT_RowIndex' },
                { data: 'title_id' },
                {},
                {},
            ],
            columnDefs: [
                {
                    targets: 0,
                    "searchable": false,
                    orderable: false,
                },
                {
                    targets: -2,
                    render: function (data, type, full, meta) {
                        let isCheck = '';
                        if (full.is_publish === true)
                            isCheck = 'checked';

                        return '<span class="kt-switch"><label><input type="checkbox" onclick="checkPublish(this)" ' + isCheck + ' data-id="' + full.id + '"><span></span></label></span>';
                    }
                },
                {
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return `
                        <a href="/news/` + full.id + `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                          <i class="fa fa-pencil-alt"></i>
                        </a>
                        <a data=` + full.id + ` href="#" onclick="deleteNews(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                          <i class="fa fa-trash-alt"></i>
                        </a>`;
                    },
                },
            ],
        });
    };

    return {

        //main function to initiate the module
        init: function () {
            initTable1();
        },

    };

}();

jQuery(document).ready(function () {
    DataNews.init();
});
