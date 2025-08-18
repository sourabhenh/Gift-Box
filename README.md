# Mastery Box Direct WordPress Plugin

An interactive WordPress plugin game where users can **instantly play and win prizes** without filling any forms. Simply pick a box and see if you've won!

## 🎮 Key Difference from Original
- **NO FORMS REQUIRED** - Users can play immediately without registration
- **Instant Play** - Direct access to the game
- **Simplified Experience** - Perfect for quick engagement

## Features

### Direct User Journey
1. **Instant Game Access** - Users see the game boxes immediately
2. **One-Click Play** - Pick a box and play instantly  
3. **Immediate Results** - See win/lose status and prize information

### Admin Backend Functionality
- **Gift Management** - Add, edit, remove gifts with win percentages and images
- **Analytics Dashboard** - View statistics, entry data, and gift distribution  
- **Settings Panel** - Configure game behavior, messages, and box images
- **CSV Export** - Export game data for analysis

## Installation

1. Upload the `mastery-box-direct` folder to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure your gifts and settings in the admin panel
4. Use the shortcodes to display the game on your pages

## Shortcodes

### Game Shortcode
```
[masterybox_direct_game]
```
Display the game interface with gift boxes. Users can play instantly.

**Optional parameters:**
- `boxes` - Number of boxes to display (overrides global setting)

### Result Shortcode  
```
[masterybox_direct_result]
```
Display the game result page showing win/lose status and prize information.

## Usage Example

1. Create a page called "Play Game" and add: `[masterybox_direct_game]`
2. Create a page called "Game Results" and add: `[masterybox_direct_result]`  
3. Configure your gifts in the admin panel
4. Start collecting plays!

## Database Tables

The plugin creates two custom tables:
- `wp_masterybox_direct_gifts` - Stores gift information and win percentages
- `wp_masterybox_direct_entries` - Stores game results (no user data)

## Security Features

- Nonce verification for all actions
- Input sanitization and validation  
- SQL injection prevention
- XSS protection

## Customization

The plugin includes CSS classes for easy customization:
- `.mastery-box-game-container` - Game area styling
- `.mastery-box` - Individual box styling  
- `.gift-quality-[quality]` - Gift quality badges
- `.mastery-box-result-page` - Results page styling

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## Admin Panel

Access through **Mastery Box Direct** in your WordPress admin menu:

- **Dashboard** - Overview statistics and shortcode instructions
- **Gifts** - Manage prizes with images, quantities, and win percentages
- **Entries** - View all game plays and export data  
- **Settings** - Configure game boxes, messages, and images

## Winning System

The plugin uses a weighted random system:
1. Only gifts with win percentage > 0% and quantity > 0 are eligible
2. Random number (0-100) is generated
3. Gifts are checked in order of win percentage
4. First matching gift wins and quantity decreases

## Perfect For

- **Quick Promotions** - No barriers to entry
- **Social Media Campaigns** - Easy sharing and instant play
- **Website Engagement** - Keep visitors engaged
- **Mobile Users** - Fast loading, touch-friendly interface

## Support

For support and documentation, visit the plugin settings page in your WordPress admin.

## Changelog

### 1.0.0
- Initial release
- Direct play functionality (no forms)
- Interactive game interface  
- Admin dashboard and analytics
- Gift management with images
- Responsive design
- Security features
