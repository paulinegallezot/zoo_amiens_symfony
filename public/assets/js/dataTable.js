const HASDatablesGlobals = function(){

    const initEvents = function (){

            const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
            if (filterSearch) {
            filterSearch.addEventListener('keyup', function () {
                KTDatatablesServerSide.redraw();

            });
            }


         $(document).on('change', '[datatable-redraw="true"]', function () {
             KTDatatablesServerSide.redraw();
         });
    }

    return {

        initEvents: initEvents
    }
}();

const KTDatatablesServerSide = function () {


    let dt;

    const initDatatable = function (config) {
        dt = $("#kt_datatable").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            searching: false,
            bLengthChange: false,
            rowId: 'id',

            "language": {
                "url": "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },
            order: config.order || [[0, 'asc']],
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: jsCustomConfig.datatableUrl,
                data: function (d) {

                    d.search.value = $('[data-kt-docs-table-filter="search"]').val();

                    // Ajout des filtres dynamiques
                    if (config.filtermatch) {
                        d.filtermatch = {};
                        for (const [key, selector] of Object.entries(config.filtermatch)) {
                            const value = $(selector).find(':selected').val();
                            if (value) {
                                d.filtermatch[key] = value;
                            }
                        }
                    }
                    if (config.filterByValue) {
                        d.filterByValue = {};
                        for (const [key, selector] of Object.entries(config.filterByValue)) {
                            const value = $(selector).find(':selected').val();
                            if (value) {
                                d.filterByValue[key] = value;
                            }
                        }
                    }
                }
            },
            columns: config.columns,
            columnDefs: config.columnDefs

        });

    }

    return {
        redraw: function () {
            dt.draw();
        },
        init: function (config) {
            initDatatable(config);
            HASDatablesGlobals.initEvents()
        }
    }
}();