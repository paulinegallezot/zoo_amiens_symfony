const DateRanges = function(){

    const formatDate = function(date) {
        let year = date.getFullYear();
        let month = ('0' + (date.getMonth() + 1)).slice(-2);
        let day = ('0' + date.getDate()).slice(-2);
        return `${year}-${month}-${day}`;
    }
    let flatpickrInstances=[];


    const bind = function(){
        $(".action-datepicker").each(function() { // une instance par flatpickr à cause du clear
            const e = $(this).flatpickr({
                altInput: true,
                altFormat: "j M Y",
                //dateFormat: "Y-m-d",
                dateFormat: "Y-m-d",
                mode: "range",
                locale: "fr",

                onChange: function (selectedDates, dateStr, instance) {
                    const inputElement = instance.element;  // Référence à l'élément d'entrée
                    const filter = inputElement.getAttribute('data-filter');  // Récupération de l'attribut data-filter


                    if (selectedDates.length > 1) {
                        $('.action-datepicker-subtext-'+filter).removeClass("d-none");
                    } else {
                        $('.action-datepicker-subtext-'+filter).addClass("d-none");
                    }

                    if (selectedDates.length === 2) {
                        let dateFormated = [];
                        dateFormated[0] = formatDate(selectedDates[0]);
                        dateFormated[1] = formatDate(selectedDates[1]);


                        KTDatatablesServerSide.setDateRange(filter, dateFormated);
                    }
                }
            });
            flatpickrInstances.push(e);
        });
       $(".action-datepicker-delete").on("click", function(){
           const filter = $(this).attr("data-filter");
           KTDatatablesServerSide.setDateRange(filter,null);

           flatpickrInstances.forEach(fpInstance => {
               if (fpInstance.element.getAttribute('data-filter') === filter) {
                   fpInstance.clear();
               }
           });

       })
    }
    return {
        init: function () {

            bind();

        }
    }

}()