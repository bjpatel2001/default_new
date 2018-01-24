/**
 * Created by Mobio Solutions on 18-01-2017.
 */

app.default = {

    events: {
        switch: function () {
            $(document).on('click', ".change_status", function () {
                var status = '1';
                if ($(this).closest("div.switch-button").children("input:checked").length > '0') {
                    status = '0';
                }

                var id = $(this).closest("div.switch-button").children("input").attr('rel');
                var spath = $(this).closest("div.switch-button").children("input").attr('formaction');
                var url = app.config.SITE_PATH + spath +'/change_status';
                app.changeStatus(id, url, status);
            });
        },

        delete: function () {
            $(document).on('click', ".deleteRecord", function () {
                var dpath = $(this).attr('formaction');
                var result = confirm("Are you sure you want to delete this "+dpath.toUpperCase()+"?");
                if (result) {
                    var id = $(this).attr('rel');
                    var url = app.config.SITE_PATH + dpath +'/delete';
                    app.deleteRecord(id, url);
                }
            });
        },

        init: function () {
            app.default.events.switch();
            app.default.events.delete();
            app.dataTable.search();
            app.dataTable.reset();

        },

    },

    init: function (param) {
        app.default.events.init();
        app.dataTable.custom(param.route);
        app.dataTable.eventFire();
    }
}

