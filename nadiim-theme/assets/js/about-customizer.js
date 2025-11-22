/**
 * About Page Customizer JavaScript
 *
 * @package Nadiim
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Timeline Repeater Control
     */
    function initTimelineRepeater() {
        var timelineControl = $('#customize-control-about_timeline_json');
        if (!timelineControl.length) return;

        var timelineTextarea = timelineControl.find('textarea');
        var timelineData = [];

        // Parse existing data
        try {
            var existingData = timelineTextarea.val();
            if (existingData) {
                timelineData = JSON.parse(existingData);
            }
        } catch (e) {
        }

        // Create repeater UI
        var repeaterHTML = '<div class="nadiim-repeater timeline-repeater" style="margin-top: 12px;">';
        repeaterHTML += '<div class="repeater-items" style="margin-bottom: 12px;"></div>';
        repeaterHTML += '<button type="button" class="button add-item" style="width: 100%;">' + nadiimAboutCustomizer.strings.addEvent + '</button>';
        repeaterHTML += '</div>';

        timelineTextarea.after(repeaterHTML);
        timelineTextarea.hide();

        var repeaterContainer = timelineControl.find('.repeater-items');
        var addButton = timelineControl.find('.add-item');

        // Render existing items
        function renderItems() {
            repeaterContainer.empty();
            timelineData.forEach(function(item, index) {
                renderItem(item, index);
            });
        }

        // Render single item
        function renderItem(item, index) {
            var itemHTML = '<div class="repeater-item" data-index="' + index + '" style="background: #f9f9f9; padding: 16px; margin-bottom: 12px; border-radius: 4px; border-right: 4px solid #339063;">';
            itemHTML += '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">';
            itemHTML += '<strong style="font-size: 14px;">' + (item.title || 'حدث ' + (index + 1)) + '</strong>';
            itemHTML += '<div>';
            itemHTML += '<button type="button" class="button button-small move-up" ' + (index === 0 ? 'disabled' : '') + ' style="margin-left: 4px;">↑</button>';
            itemHTML += '<button type="button" class="button button-small move-down" ' + (index === timelineData.length - 1 ? 'disabled' : '') + ' style="margin-left: 4px;">↓</button>';
            itemHTML += '<button type="button" class="button button-small remove-item" style="margin-left: 4px; color: #dc3232;">' + nadiimAboutCustomizer.strings.removeEvent + '</button>';
            itemHTML += '</div>';
            itemHTML += '</div>';

            itemHTML += '<div style="display: grid; gap: 8px;">';
            itemHTML += '<input type="text" class="item-date" placeholder="' + nadiimAboutCustomizer.strings.eventDate + '" value="' + (item.date || '') + '" style="width: 100%; padding: 6px 8px;">';
            itemHTML += '<input type="text" class="item-title" placeholder="' + nadiimAboutCustomizer.strings.eventTitle + '" value="' + (item.title || '') + '" style="width: 100%; padding: 6px 8px;">';
            itemHTML += '<input type="text" class="item-short-desc" placeholder="' + nadiimAboutCustomizer.strings.eventShortDesc + '" value="' + (item.short_description || '') + '" style="width: 100%; padding: 6px 8px;">';
            itemHTML += '<textarea class="item-full-desc" placeholder="' + nadiimAboutCustomizer.strings.eventFullDesc + '" rows="3" style="width: 100%; padding: 6px 8px;">' + (item.full_description || '') + '</textarea>';
            itemHTML += '<input type="url" class="item-image" placeholder="' + nadiimAboutCustomizer.strings.eventImage + '" value="' + (item.image || '') + '" style="width: 100%; padding: 6px 8px;">';
            itemHTML += '<input type="url" class="item-link" placeholder="' + nadiimAboutCustomizer.strings.eventLink + '" value="' + (item.link || '') + '" style="width: 100%; padding: 6px 8px;">';
            itemHTML += '</div>';
            itemHTML += '</div>';

            repeaterContainer.append(itemHTML);
        }

        // Update data
        function updateData() {
            timelineData = [];
            repeaterContainer.find('.repeater-item').each(function() {
                var item = {
                    date: $(this).find('.item-date').val(),
                    title: $(this).find('.item-title').val(),
                    short_description: $(this).find('.item-short-desc').val(),
                    full_description: $(this).find('.item-full-desc').val(),
                    image: $(this).find('.item-image').val(),
                    link: $(this).find('.item-link').val()
                };
                timelineData.push(item);
            });

            timelineTextarea.val(JSON.stringify(timelineData)).trigger('change');
        }

        // Add item
        addButton.on('click', function() {
            var newItem = {
                date: '',
                title: '',
                short_description: '',
                full_description: '',
                image: '',
                link: ''
            };
            timelineData.push(newItem);
            renderItems();
        });

        // Remove item
        repeaterContainer.on('click', '.remove-item', function() {
            var index = $(this).closest('.repeater-item').data('index');
            timelineData.splice(index, 1);
            renderItems();
            updateData();
        });

        // Move up
        repeaterContainer.on('click', '.move-up', function() {
            var index = $(this).closest('.repeater-item').data('index');
            if (index > 0) {
                var temp = timelineData[index];
                timelineData[index] = timelineData[index - 1];
                timelineData[index - 1] = temp;
                renderItems();
                updateData();
            }
        });

        // Move down
        repeaterContainer.on('click', '.move-down', function() {
            var index = $(this).closest('.repeater-item').data('index');
            if (index < timelineData.length - 1) {
                var temp = timelineData[index];
                timelineData[index] = timelineData[index + 1];
                timelineData[index + 1] = temp;
                renderItems();
                updateData();
            }
        });

        // Update on input change
        repeaterContainer.on('input change', 'input, textarea', function() {
            updateData();
        });

        // Initial render
        renderItems();
    }

    /**
     * Initialize when Customizer is ready
     */
    wp.customize.bind('ready', function() {
        // Wait a bit for controls to be fully rendered
        setTimeout(function() {
            initTimelineRepeater();
        }, 500);
    });

})(jQuery);
