const displayDate = function (date) {
    let dateFR = new Date(date);
    // Utilisez toLocaleString pour formater la date
    return dateFR.toLocaleString('fr-FR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

}
var DeleteItem = function(){
    var actionDelete = function(ids){


        $.ajax({
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': data_csrf_delete    ,
            },
            data: {ids: [ids]},
            dataType: 'json',
            url: jsCustomConfig.deleteUrl,
            success: function (response) {
                if (response.success === true) {
                    KTDatatablesServerSide.redraw();
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toastr-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    toastr.success("Enregistrement supprimé avec succès.");
                } else {
                    bootbox.alert({
                        title: 'Erreur',
                        message: response.message
                    });


                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Erreur - ' + errorMessage);
            }
        });
    }
    var bind = function(){
        $(document).on('click','.action_delete',function(){
            const id = $(this).closest('tr').attr('id');

            bootbox.confirm({
                title: 'Suppression d\'un enregistrement?',
                message: 'Prudence, cette opération est irréversible.',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Annuler'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Supprimer'
                    }
                },
                callback: function (result) {
                    if (result){
                        actionDelete(id);

                    }

                }
            });
            return false;
        })
    }
    return {
        init: function () {

            bind();
        }
    }
}();

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
    let dateRangesFilter = {};
    const initDatatable = function (config) {
        dt = $("#kt_datatable").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            searching: false,
            bLengthChange: false,
            rowId: 'id',
            pageLength: 25,
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
                    if (dateRangesFilter){
                        d.filterByDates = dateRangesFilter;
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
        },
        setDateRange: function setDateRange(filter,dateRanges){
            if (dateRanges === null){
                delete dateRangesFilter[filter];
            }else {
                dateRangesFilter[filter] = dateRanges;
            }
            dt.draw();
        }
    }
}();