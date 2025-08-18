<?php if (!defined('WPINC')) die; ?>
<div class="wrap mastery-box-settings-sections">
  <h1><?php echo esc_html__('Settings', 'mastery-box-direct'); ?></h1>
  <?php if (isset($_GET['message']) && $_GET['message'] === 'updated'): ?>
  <div class="notice notice-success is-dismissible">
    <p>
      <?php _e('Settings updated successfully.', 'mastery-box-direct'); ?>
    </p>
  </div>
  <?php endif; ?>
  <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data" id="mastery-box-direct-settings-form">
    <input type="hidden" name="action" value="masterybox_direct_save_settings">
    <?php wp_nonce_field('mastery_box_direct_settings_action', 'mastery_box_direct_nonce'); ?>
    <div class="mastery-box-settings-section">
      <h2>
        <?php _e('Game Configuration', 'mastery-box-direct'); ?>
      </h2>
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"> <label for="number_of_boxes">
                <?php _e('Number of Boxes', 'mastery-box-direct'); ?>
              </label>
            </th>
            <td><input type="number" min="1" max="10" name="number_of_boxes" id="number_of_boxes" value="<?php echo esc_attr(get_option('mastery_box_direct_number_of_boxes', 3)); ?>" class="small-text">
              <p class="description">
                <?php _e('Number of boxes to display in the game (1-10).', 'mastery-box-direct'); ?>
              </p></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mastery-box-settings-section">
      <h2>
        <?php _e('Game Messages', 'mastery-box-direct'); ?>
      </h2>
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"> <label for="win_message">
                <?php _e('Win Message', 'mastery-box-direct'); ?>
              </label>
            </th>
            <td><textarea name="win_message" id="win_message" rows="3" class="large-text"><?php echo esc_textarea(get_option('mastery_box_direct_win_message', __('Congratulations! You won!', 'mastery-box-direct'))); ?></textarea>
              <p class="description">
                <?php _e('Message shown to winners on the results page.', 'mastery-box-direct'); ?>
              </p></td>
          </tr>
          <tr>
            <th scope="row"> <label for="lose_message">
                <?php _e('Lose Message', 'mastery-box-direct'); ?>
              </label>
            </th>
            <td><textarea name="lose_message" id="lose_message" rows="3" class="large-text"><?php echo esc_textarea(get_option('mastery_box_direct_lose_message', __('Better luck next time!', 'mastery-box-direct'))); ?></textarea>
              <p class="description">
                <?php _e('Message shown to players who don\'t win.', 'mastery-box-direct'); ?>
              </p></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mastery-box-settings-section">
      <h2>
        <?php _e('Box Images', 'mastery-box-direct'); ?>
      </h2>
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"> <label for="default_box_image">
                <?php _e('Default Box Image', 'mastery-box-direct'); ?>
              </label>
            </th>
            <td><?php $default_image = get_option('mastery_box_direct_default_box_image', ''); ?>
              <div style="margin-bottom: 10px;">
                <input type="text" name="default_box_image" id="default_box_image" value="<?php echo esc_attr($default_image); ?>" class="regular-text" placeholder="<?php esc_attr_e('Image URL', 'mastery-box-direct'); ?>">
                <button type="button" class="button mastery-box-upload" data-target="default_box_image">
                <?php _e('Upload/Select', 'mastery-box-direct'); ?>
                </button>
              </div>
              <div id="default_box_image_preview" style="<?php echo $default_image ? '' : 'display:none;'; ?>">
                <?php if ($default_image): ?>
                <img src="<?php echo esc_url($default_image); ?>" style="max-width: 100px; height: auto; border: 1px solid #ddd;">
                <?php endif; ?>
              </div>
              <p class="description">
                <?php _e('Default image used for all boxes if individual box images are not set.', 'mastery-box-direct'); ?>
              </p></td>
          </tr>
        </tbody>
      </table>
      <h3>
        <?php _e('Individual Box Images', 'mastery-box-direct'); ?>
      </h3>
      <?php
      $box_images = get_option( 'mastery_box_direct_box_images', array() );
      $num_boxes = ( int )get_option( 'mastery_box_direct_number_of_boxes', 3 );
      ?>
      <table class="form-table">
        <tbody>
          <?php for ($i = 1; $i <= 10; $i++): ?>
          <tr<?php echo $i > $num_boxes ? ' style="display:none;"' : ''; ?>>
            <th scope="row"> <label for="box_image_<?php echo $i; ?>"><?php printf(__('Box %d Image', 'mastery-box-direct'), $i); ?></label>
            </th>
            <td><?php $box_image = isset($box_images[$i]) ? $box_images[$i] : ''; ?>
              <div style="margin-bottom: 10px;">
                <input type="text" name="box_images[<?php echo $i; ?>]" id="box_image_<?php echo $i; ?>" value="<?php echo esc_attr($box_image); ?>" class="regular-text" placeholder="<?php esc_attr_e('Image URL', 'mastery-box-direct'); ?>">
                <button type="button" class="button mastery-box-upload" data-target="box_image_<?php echo $i; ?>">
                <?php _e('Upload/Select', 'mastery-box-direct'); ?>
                </button>
              </div>
              <div id="box_image_<?php echo $i; ?>_preview" style="<?php echo $box_image ? '' : 'display:none;'; ?>">
                <?php if ($box_image): ?>
                <img src="<?php echo esc_url($box_image); ?>" style="max-width: 80px; height: auto; border: 1px solid #ddd;">
                <?php endif; ?>
              </div></td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>
    <div class="mastery-box-settings-section mastery-box-usage-instructions">
      <h2>
        <?php _e('Usage Instructions', 'mastery-box-direct'); ?>
      </h2>
      <p>
        <?php _e('Follow these steps to set up your direct play game:', 'mastery-box-direct'); ?>
      </p>
      <div class="shortcode-example">
        <h4>
          <?php _e('Step 1: Create Game Page', 'mastery-box-direct'); ?>
        </h4>
        <p><code>[masterybox_direct_game]</code></p>
      </div>
      <div class="shortcode-example">
        <h4>
          <?php _e('Step 2: Create Results Page', 'mastery-box-direct'); ?>
        </h4>
        <p><code>[masterybox_direct_result]</code></p>
      </div>
    </div>
    <?php submit_button(__('Save Settings', 'mastery-box-direct')); ?>
  </form>
</div>
<script>

 jQuery(function ($) {
     var masteryBoxMediaFrame = null;

     function openMedia(targetInputId, previewSelector) {
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
                 var html = '<img src="' + attachment.url + '" alt="" style="max-width:120px;height:auto;border:1px solid #ddd;padding:2px;background:#fff;">';
                 $preview.html(html).show();
             }
         });
         masteryBoxMediaFrame.open();
     }
     $(document).on('click', '.mastery-box-upload', function (e) {
         e.preventDefault();
         e.stopPropagation();
         var target = $(this).data('target');
         var preview = '#' + target + '_preview';
         openMedia(target, preview);
         return false;
     });

     // Show/hide box image fields based on number of boxes 

     $('#number_of_boxes').on('change', function () {
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
 });

</script> 
