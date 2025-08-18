<?php if (!defined('WPINC')) die; ?>

<div class="wrap mastery-box-entries-page">
    <h1><?php echo esc_html__('Entries', 'mastery-box-direct'); ?></h1>

    <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Entry deleted successfully.', 'mastery-box-direct'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($entries)): ?>
        <div style="margin-bottom: 20px;">
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="mastery_box_direct_export_entries">
                <?php wp_nonce_field('mastery_box_direct_export_entries', 'mastery_box_direct_export_nonce'); ?>
                <?php submit_button(__('Export to CSV', 'mastery-box-direct'), 'secondary', 'export_entries', false); ?>
            </form>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('ID', 'mastery-box-direct'); ?></th>
                    <th><?php _e('Box', 'mastery-box-direct'); ?></th>
                    <th><?php _e('Result', 'mastery-box-direct'); ?></th>
                    <th><?php _e('Gift', 'mastery-box-direct'); ?></th>
                    <th><?php _e('Date', 'mastery-box-direct'); ?></th>
                    <th><?php _e('IP Address', 'mastery-box-direct'); ?></th>
                    <th><?php _e('Actions', 'mastery-box-direct'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?php echo esc_html($entry->id); ?></td>
                        <td><?php echo esc_html($entry->chosen_box); ?></td>
                        <td>
                            <?php if ($entry->is_winner): ?>
                                <span class="mastery-box-winner"><?php _e('WIN', 'mastery-box-direct'); ?></span>
                            <?php else: ?>
                                <span class="mastery-box-loser"><?php _e('LOSE', 'mastery-box-direct'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($entry->gift_name): ?>
                                <?php echo esc_html($entry->gift_name); ?>
                                <?php if ($entry->gift_quality): ?>
                                    <br><span class="gift-quality gift-quality-<?php echo esc_attr(strtolower($entry->gift_quality)); ?>">
                                        <?php echo esc_html($entry->gift_quality); ?>
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #666;"><?php _e('No prize', 'mastery-box-direct'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html(date('M j, Y g:i A', strtotime($entry->created_at))); ?></td>
                        <td><?php echo esc_html($entry->ip_address); ?></td>
                        <td>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=mastery-box-direct-entries&action=delete&id=' . intval($entry->id)), 'delete_entry_' . intval($entry->id))); ?>" 
                               class="button button-small button-link-delete" 
                               onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete this entry?', 'mastery-box-direct')); ?>');">
                                <?php _e('Delete', 'mastery-box-direct'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php
                    echo paginate_links(array(
                        'base' => admin_url('admin.php?page=mastery-box-direct-entries&paged=%#%'),
                        'format' => '',
                        'current' => $page,
                        'total' => $total_pages
                    ));
                    ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="mastery-box-no-entries">
            <h2><?php _e('No entries yet', 'mastery-box-direct'); ?></h2>
            <p><?php _e('Players haven't started playing the game yet. Here's how to get started:', 'mastery-box-direct'); ?></p>
            <ul>
                <li><?php _e('Add some gifts in the Gifts section', 'mastery-box-direct'); ?></li>
                <li><?php _e('Create a page and add the game shortcode: ', 'mastery-box-direct'); ?><code>[masterybox_direct_game]</code></li>
                <li><?php _e('Share the game page with your audience', 'mastery-box-direct'); ?></li>
            </ul>
            <p><a href="<?php echo admin_url('admin.php?page=mastery-box-direct-gifts'); ?>" class="button button-primary"><?php _e('Add Gifts', 'mastery-box-direct'); ?></a></p>
        </div>
    <?php endif; ?>
</div>