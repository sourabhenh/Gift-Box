<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php if (!defined('WPINC')) die; ?>
<div class="wrap">
  <h1><?php echo esc_html__('Gifts', 'mastery-box-direct'); ?></h1>
  <?php if (isset($_GET['message'])): ?>
  <?php if ($_GET['message'] === 'created'): ?>
  <div class="notice notice-success is-dismissible">
    <p>
      <?php _e('Gift created successfully.', 'mastery-box-direct'); ?>
    </p>
  </div>
  <?php elseif ($_GET['message'] === 'updated'): ?>
  <div class="notice notice-success is-dismissible">
    <p>
      <?php _e('Gift updated successfully.', 'mastery-box-direct'); ?>
    </p>
  </div>
  <?php elseif ($_GET['message'] === 'deleted'): ?>
  <div class="notice notice-success is-dismissible">
    <p>
      <?php _e('Gift deleted.', 'mastery-box-direct'); ?>
    </p>
  </div>
  <?php endif; ?>
  <?php endif; ?>
  <h2><?php echo $edit_gift ? esc_html__('Edit Gift', 'mastery-box-direct') : esc_html__('Add New Gift', 'mastery-box-direct'); ?></h2>
  <form method="post" action="" style="max-width: 900px;" id="mastery-box-direct-gift-form">
    <?php wp_nonce_field('mastery_box_direct_gift_action', 'mastery_box_direct_nonce'); ?>
    <input type="hidden" name="submit_gift" value="1">
    <?php if (!empty($edit_gift->id)): ?>
    <input type="hidden" name="gift_id" value="<?php echo esc_attr($edit_gift->id); ?>">
    <?php endif; ?>
    <table class="form-table">
      <tbody>
        <tr>
          <th><label for="gift_name">
              <?php _e('Gift Name', 'mastery-box-direct'); ?>
            </label></th>
          <td><input type="text" name="gift_name" id="gift_name" class="regular-text" value="<?php echo isset($edit_gift->name) ? esc_attr($edit_gift->name) : ''; ?>" required></td>
        </tr>
        <tr>
          <th><label for="gift_description">
              <?php _e('Description', 'mastery-box-direct'); ?>
            </label></th>
          <td><textarea name="gift_description" id="gift_description" class="large-text" rows="3"><?php echo isset($edit_gift->description) ? esc_textarea($edit_gift->description) : ''; ?></textarea></td>
        </tr>
        <tr>
          <th><label for="gift_quality">
              <?php _e('Quality', 'mastery-box-direct'); ?>
            </label></th>
          <td><input type="text" name="gift_quality" id="gift_quality" class="regular-text" value="<?php echo isset($edit_gift->quality) ? esc_attr($edit_gift->quality) : ''; ?>"></td>
        </tr>
        <tr>
          <th><label for="gift_quantity">
              <?php _e('Quantity (leave empty for unlimited)', 'mastery-box-direct'); ?>
            </label></th>
          <td><input type="number" name="gift_quantity" id="gift_quantity" class="small-text" min="0" value="<?php echo isset($edit_gift->quantity) && $edit_gift->quantity !== null ? intval($edit_gift->quantity) : ''; ?>"></td>
        </tr>
        <tr>
          <th><label for="win_percentage">
              <?php _e('Win Percentage', 'mastery-box-direct'); ?>
            </label></th>
          <td><input type="number" step="0.01" min="0" max="100" name="win_percentage" id="win_percentage" class="small-text" value="<?php echo isset($edit_gift->win_percentage) ? esc_attr($edit_gift->win_percentage) : '0'; ?>">
            % </td>
        </tr>
        <tr>
          <th><label for="gift_image">
              <?php _e('Gift Image', 'mastery-box-direct'); ?>
            </label></th>
          <td><?php $gift_image = isset($edit_gift->gift_image) ? esc_url($edit_gift->gift_image) : ''; ?>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
              <input type="text" name="gift_image" id="gift_image" class="regular-text" value="<?php echo $gift_image; ?>" placeholder="<?php esc_attr_e('Image URL', 'mastery-box-direct'); ?>">
              <button type="button" class="button mastery-box-upload" data-target="gift_image">
              <?php _e('Upload/Select', 'mastery-box-direct'); ?>
              </button>
            </div>
            <div id="gift_image_preview" style="margin-top:8px;<?php echo $gift_image ? '' : 'display:none;'; ?>">
              <?php if ($gift_image): ?>
              <img src="<?php echo $gift_image; ?>" alt="" style="max-width:150px;height:auto;border:1px solid #ddd;padding:2px;background:#fff;">
              <?php endif; ?>
            </div>
            <p class="description">
              <?php _e('Upload an image for this gift that will be shown on the results page when won.', 'mastery-box-direct'); ?>
            </p></td>
        </tr>
      </tbody>
    </table>
    <?php submit_button($edit_gift ? __('Update Gift', 'mastery-box-direct') : __('Add Gift', 'mastery-box-direct')); ?>
  </form>
  <hr>
  <h2>
    <?php _e('All Gifts', 'mastery-box-direct'); ?>
  </h2>
  <?php if (!empty($gifts)): ?>
  <table class="wp-list-table widefat fixed striped">
    <thead>
      <tr>
        <th><?php _e('ID', 'mastery-box-direct'); ?></th>
        <th><?php _e('Image', 'mastery-box-direct'); ?></th>
        <th><?php _e('Name', 'mastery-box-direct'); ?></th>
        <th><?php _e('Quality', 'mastery-box-direct'); ?></th>
        <th><?php _e('Win %', 'mastery-box-direct'); ?></th>
        <th><?php _e('Quantity', 'mastery-box-direct'); ?></th>
        <th><?php _e('Actions', 'mastery-box-direct'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($gifts as $gift): ?>
      <tr>
        <td><?php echo esc_html($gift->id); ?></td>
        <td><?php if (!empty($gift->gift_image)): ?>
          <img src="<?php echo esc_url($gift->gift_image); ?>" style="width:48px;height:48px;object-fit:cover;border:1px solid #ccc;">
          <?php else: ?>
          <span style="color:#666;">No image</span>
          <?php endif; ?></td>
        <td><?php echo esc_html($gift->name); ?></td>
        <td><?php echo esc_html($gift->quality); ?></td>
        <td><?php echo esc_html($gift->win_percentage); ?>%</td>
        <td><?php echo $gift->quantity === null ? __('Unlimited', 'mastery-box-direct') : intval($gift->quantity); ?></td>
        <td><a class="button button-small" href="<?php echo esc_url(admin_url('admin.php?page=mastery-box-direct-gifts&edit=' . intval($gift->id))); ?>">
          <?php _e('Edit', 'mastery-box-direct'); ?>
          </a> <a class="button button-small button-link-delete" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=mastery-box-direct-gifts&action=delete&id=' . intval($gift->id)), 'delete_gift_' . intval($gift->id))); ?>" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this gift?', 'mastery-box-direct')); ?>');">
          <?php _e('Delete', 'mastery-box-direct'); ?>
          </a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <p>
    <?php _e('No gifts found.', 'mastery-box-direct'); ?>
  </p>
  <?php endif; ?>
</div>
<script>

 jQuery(function ($) {
     var masteryBoxMediaFrame = null;

     function openMedia(targetInputId, previewSelector) {
         if (masteryBoxMediaFrame) {
             masteryBoxMediaFrame.off('select'); 
			 
			 // avoid duplicate bindings 

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
                 var html = '<img src="' + attachment.url + '" alt="" style="max-width:150px;height:auto;border:1px solid #ddd;padding:2px;background:#fff;">';
                 $preview.html(html).show();
             }
         });
         masteryBoxMediaFrame.open();
     } 
	 
	 // Important: ensure 
     type = "button"
     prevents form submission $(document).on('click', '.mastery-box-upload', function (e) {
         e.preventDefault();
         e.stopPropagation();
         var target = $(this).data('target');
         var preview = '#gift_image_preview';
         if (target) {
             openMedia(target, preview);
         }
         return false;
     });
 });


</script> 
