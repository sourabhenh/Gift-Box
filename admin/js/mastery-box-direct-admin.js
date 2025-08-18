/** Admin JavaScript for the Mastery Box Direct plugin */
(function ($) {
    'use strict';

    var masteryBoxMediaFrame = null;

    $(document).ready(function () {
        initializeAdminFeatures();
        bindMediaUploadButtons();
        bindBoxCountToggler();
    });

    /** Initialize admin features  */
    function initializeAdminFeatures() {
        // Add confirmation dialogs for delete actions
        $(document).on('click', '.button-link-delete', function (e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        });

        // Auto-dismiss notices after 5 seconds
        setTimeout(function () {
            $('.notice.is-dismissible').fadeOut();
        }, 5000);

        // Win percentage validation
        if ($('#win_percentage').length) {
            initializeWinPercentageValidation();
        }
    }

    /** Initialize win percentage validation  */
    function initializeWinPercentageValidation() {
        $(document).on('input', '#win_percentage', function () {
            var value = parseFloat($(this).val());
            var $warning = $('#percentage-warning');

            // Remove existing warning
            $warning.remove();

            if (!isNaN(value) && value > 50) {
                $(this).after('<p id="percentage-warning" style="color: #d63638;">⚠️ High win percentage may result in too many winners.</p>');
            }


        });
    }


    /** Bind Upload/Select buttons to WordPress media frame Ensures buttons do not submit forms Updates input value and shows preview without reloading */
    function bindMediaUploadButtons() {
        // Ensure all upload buttons are non-submitting
        $('.mastery-box-upload').attr('type', 'button');

        $(document).on('click', '.mastery-box-upload', function (e) {
            e.preventDefault();
            e.stopPropagation();


            var targetInputId = $(this).data('target');
            if (!targetInputId) return;

            var previewSelector = '#' + targetInputId + '_preview';

            openMediaFrame(targetInputId, previewSelector);
            return false;

        });
    }

    /** Open/reuse a single media frame and handle selection */
    function openMediaFrame(targetInputId, previewSelector) {
        // Reuse a single frame; unbind previous select events to avoid duplicates
        if (masteryBoxMediaFrame) {
            masteryBoxMediaFrame.off('select');
        }

        masteryBoxMediaFrame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            library: {
                type: 'image'
            },
            multiple: false
        });

        masteryBoxMediaFrame.on('select', function () {
            var attachment = masteryBoxMediaFrame.state().get('selection').first().toJSON();
            var $input = $('#' + targetInputId);
            $input.val(attachment.url).trigger('change');


            if (previewSelector) {
                var $preview = $(previewSelector);
                var maxW = (/default_box_image/.test(targetInputId)) ? 120 : (/box_image_/.test(targetInputId) ? 80 : 150);
                var html = '<img src="' + attachment.url + '" alt="" style="max-width:' + maxW + 'px;height:auto;border:1px solid #ddd;padding:2px;background:#fff;">';
                $preview.html(html).show();
            }


        });

        masteryBoxMediaFrame.open();
    }

    /** Show/hide individual box image rows based on number_of_boxes in Settings  */
    function bindBoxCountToggler() {
        $(document).on('change', '#number_of_boxes', function () {
            var numBoxes = parseInt($(this).val(), 10) || 0;
            for (var i = 1; i <= 10; i++) {
                var $row = $('#box_image_' + i).closest('tr');
                if (i <= numBoxes) {
                    $row.show();
                } else {
                    $row.hide();
                }
            }
        });
    }

})(jQuery);
