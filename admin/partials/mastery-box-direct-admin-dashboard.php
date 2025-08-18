<?php if (!defined('WPINC')) die; ?>

<div class="wrap mastery-box-dashboard">
    <h1><?php echo esc_html__('Mastery Box Direct - Dashboard', 'mastery-box-direct'); ?></h1>

    <div class="mastery-box-stats-grid">
        <div class="mastery-box-stat-card">
            <div class="stat-icon">🎮</div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['total_plays']); ?></h3>
                <p><?php _e('Total Plays', 'mastery-box-direct'); ?></p>
            </div>
        </div>

        <div class="mastery-box-stat-card">
            <div class="stat-icon">🏆</div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['total_winners']); ?></h3>
                <p><?php _e('Winners', 'mastery-box-direct'); ?></p>
            </div>
        </div>

        <div class="mastery-box-stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['win_percentage']); ?>%</h3>
                <p><?php _e('Win Rate', 'mastery-box-direct'); ?></p>
            </div>
        </div>
    </div>

    <div class="mastery-box-dashboard-section">
        <h2><?php _e('Quick Actions', 'mastery-box-direct'); ?></h2>
        <div class="mastery-box-quick-actions">
            <a href="<?php echo admin_url('admin.php?page=mastery-box-direct-gifts'); ?>" class="button button-primary"><?php _e('Manage Gifts', 'mastery-box-direct'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=mastery-box-direct-entries'); ?>" class="button"><?php _e('View Entries', 'mastery-box-direct'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=mastery-box-direct-settings'); ?>" class="button"><?php _e('Settings', 'mastery-box-direct'); ?></a>
        </div>
    </div>

    <div class="mastery-box-dashboard-section mastery-box-shortcodes">
        <h2><?php _e('Shortcodes', 'mastery-box-direct'); ?></h2>

        <div class="shortcode-item">
            <h3><?php _e('Game Shortcode', 'mastery-box-direct'); ?></h3>
            <p><code>[masterybox_direct_game]</code></p>
            <p><?php _e('Display the game interface with gift boxes. Users can play instantly without filling forms.', 'mastery-box-direct'); ?></p>
            <p><strong><?php _e('Optional parameters:', 'mastery-box-direct'); ?></strong></p>
            <ul>
                <li><code>boxes</code> - <?php _e('Number of boxes to display (overrides global setting)', 'mastery-box-direct'); ?></li>
            </ul>
        </div>

        <div class="shortcode-item">
            <h3><?php _e('Result Shortcode', 'mastery-box-direct'); ?></h3>
            <p><code>[masterybox_direct_result]</code></p>
            <p><?php _e('Display the game result page showing win/lose status and prize information.', 'mastery-box-direct'); ?></p>
        </div>
    </div>

    <?php if (!empty($stats['gift_distribution'])): ?>
    <div class="mastery-box-dashboard-section">
        <h2><?php _e('Gift Distribution', 'mastery-box-direct'); ?></h2>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th><?php _e('Gift', 'mastery-box-direct'); ?></th>
                    <th><?php _e('Quality', 'mastery-box-direct'); ?></th>
                    <th><?php _e('Won Count', 'mastery-box-direct'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats['gift_distribution'] as $dist): ?>
                <tr>
                    <td><?php echo esc_html($dist->name); ?></td>
                    <td>
                        <?php if (!empty($dist->quality)): ?>
                            <span class="gift-quality gift-quality-<?php echo esc_attr(strtolower($dist->quality)); ?>">
                                <?php echo esc_html($dist->quality); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo esc_html($dist->count); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>