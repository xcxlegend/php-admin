define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form, Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    "index_url": "server/auth/index",
                    "add_url": "server/auth/add",
                    "del_url": "server/auth/del",
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
                        {field: 'field', title: '版本.服务器'},
                        // {field: 'server', title: __('服务器')},
                        // {field: 'version', title: __('版本')},
                        {field: 'content', title: __('网址')},
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
                commonSearch: false
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
