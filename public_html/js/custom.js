/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
$(function () {
    $(document).on("click", ".btn-delete-record", function (e) {
        $button = $(this);
        bootbox.confirm({
            message: "Are you sure to delete the record?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                // SMM Panle
                if (result) {
                    $button.parents('form').submit();
                }
            }
        });
    });
});
