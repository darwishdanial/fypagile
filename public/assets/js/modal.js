//for all modal

$(".modal").on('click', '.modalClose', function () {
    var modal = $(this).parents('.modal')
    modal.modal('toggle')
})

