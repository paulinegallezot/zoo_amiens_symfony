const DeleteItem = function(){
    const actionDelete = function(ids){


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
                } else {
                    alert(response.message)

                }
            },
            error: function (xhr) {
                const errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Erreur - ' + errorMessage);
            }
        });
    }
    const bind = function(){
        $(document).on('click','.action_delete',function(){
            const id = $(this).closest('tr').attr('id');

            bootbox.confirm({
                title: 'Suppression d\'un animal?',
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