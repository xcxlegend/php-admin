define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form, Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    "index_url": "server/notice/index",
                    "add_url": "server/notice/add",
                    "del_url": "server/notice/del",
                }
            });
            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: '',
                escape: false,
                columns: [
                    [
                        // {field: 'state', checkbox: true,},
                        {field: 'sort', title: '序号'},
                        // {field: 'server', title: __('服务器')},
                        // {field: 'version', title: __('版本')},
                        {field: 'content', title: __('内容')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                pagination: false,
                search: false,
                commonSearch: false,
                queryParams: function (params) {
                    params.version = $('#version').val();
                    params.server = $('#server').val();
                    return params;
                }
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            formatter: {},
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"), function (data) {
                    Fast.api.refreshmenu();
                });
            }
        }
    };
    return Controller;
});
