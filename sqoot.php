<?php
/*
 * Plugin Name: Sqoot Daily Deal Widget
 * URI: http://www.sqoot.com/
 * Description: The Sqoot Daily Deal Widget makes it ridiculously easy to add daily deals to your blog.
 * Version: 1.0
 * Author: Sqoot
 * Author URI: http://www.sqoot.com/
 * License: GPL2+
 */

$sqoot_widget_loaded = false;

wp_register_sidebar_widget('widget_sqoot', 'Daily Deals', 'sqoot_widget');
add_action('admin_menu', 'sqoot_plugin_menu');
add_action('wp_footer', 'sqoot_widget_script');
add_filter('plugin_action_links', 'sqoot_plugin_action_links', 10, 2);

switch (get_option('sqoot_position', 'sidebar')) {
  case 'before':
    add_action('loop_start', 'sqoot_widget');
    break;
  case 'after':
    add_action('loop_end', 'sqoot_widget');
    break;
  case 'sidebar':
    add_action('dynamic_sidebar', 'sqoot_widget');
    break;
}

function sqoot_widget() {
  global $sqoot_widget_loaded;

  if (!$sqoot_widget_loaded) {

    if (get_option('sqoot_css')) {
    ?>

      <style type="text/css">
        <?php echo get_option('sqoot_css'); ?>
      </style>

    <?php
    }
    ?>

    <div id="sqoot"
         <?php if(get_option('sqoot_affiliate_token')) { ?> data-access_token="<?php echo get_option('sqoot_affiliate_token');?>"<?php } ?>
         <?php if(get_option('sqoot_category_active') || get_option('sqoot_category_beauty') || get_option('sqoot_category_entertainment') || get_option('sqoot_category_fashion') || get_option('sqoot_category_fitness') || get_option('sqoot_category_nightlife') || get_option('sqoot_category_restaurants') || get_option('sqoot_category_sports') || get_option('sqoot_category_travel')) { ?> data-categories="<?php echo get_option('sqoot_category_active');?><?php echo get_option('sqoot_category_beauty');?><?php echo get_option('sqoot_category_entertainment');?><?php echo get_option('sqoot_category_fashion');?><?php echo get_option('sqoot_category_fitness');?><?php echo get_option('sqoot_category_nightlife');?><?php echo get_option('sqoot_category_restaurants');?><?php echo get_option('sqoot_category_sports');?><?php echo get_option('sqoot_category_travel');?>"<?php } ?>
         data-per_page="<?php echo get_option('sqoot_per_page', 3);?>"
         <?php if(get_option('sqoot_location')) { ?> data-location="<?php echo get_option('sqoot_location');?>"<?php } ?>
         <?php if(get_option('sqoot_radius')) { ?> data-radius="<?php echo get_option('sqoot_radius');?>"<?php } ?>>
    </div>

    <?php

    $sqoot_widget_loaded = true;
  }
}

function sqoot_widget_script() {
?>

  <script type="text/javascript">
   (function() {
     if (document.getElementById('sqoot_widget_script')) return;
     var sqoot = document.createElement('script');
     sqoot.type = 'text/javascript'; sqoot.id = 'sqoot_widget_script'; sqoot.async = true;
     sqoot.src = 'http://widget.sqoot.com/v2/javascripts/offers.js';
     var s = document.getElementsByTagName('script')[0];
     s.parentNode.insertBefore(sqoot, s);
   })();
  </script>

<?php
}

function sqoot_plugin_menu() {
  add_options_page('Sqoot Widget Options', 'Sqoot Widget', 'manage_options', 'sqoot-options', 'sqoot_options');
  add_action('admin_init', 'sqoot_register');
}

function sqoot_register() {
  register_setting('sqoot-options', 'sqoot_affiliate_token');
  register_setting('sqoot-options', 'sqoot_position');
  register_setting('sqoot-options', 'sqoot_category_active');
  register_setting('sqoot-options', 'sqoot_category_beauty');
  register_setting('sqoot-options', 'sqoot_category_entertainment');
  register_setting('sqoot-options', 'sqoot_category_fashion');
  register_setting('sqoot-options', 'sqoot_category_fitness');
  register_setting('sqoot-options', 'sqoot_category_nightlife');
  register_setting('sqoot-options', 'sqoot_category_restaurants');
  register_setting('sqoot-options', 'sqoot_category_sports');
  register_setting('sqoot-options', 'sqoot_category_travel');
  register_setting('sqoot-options', 'sqoot_per_page');
  register_setting('sqoot-options', 'sqoot_radius');
  register_setting('sqoot-options', 'sqoot_location');
  register_setting('sqoot-options', 'sqoot_css');
}

function sqoot_plugin_action_links($links, $file) {
  static $this_plugin;
  if (!$this_plugin) {
    $this_plugin = plugin_basename(__FILE__);
  }

  if ($file == $this_plugin) {
    $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=sqoot-options">Settings</a>';
    array_unshift($links, $settings_link);
  }

  return $links;
}

function sqoot_options() { ?>
  <div class="wrap">
    <div id="icon-options-general" class="icon32">
      <br />
    </div>
  
    <h2>Sqoot Widget Options</h2>
  
    <table class="paddingless">
      <tr>
        <td>
          <p>The <a href="http://www.sqoot.com/">Sqoot Daily Deal Widget</a> makes it ridiculously easy to add daily deals to your blog. The widget uses JavaScript to inject content into your pages. With a few clicks, the deal feed can be customized to your readers' interests (e.g., beauty, restaurants). The widget automatically inherits your blog's look and feel (e.g., font, colors) and can be further customized with CSS. Really want to get fancy? Read Sqoot's <a href="http://widget.sqoot.com/">full widget documentation</a>.</p>
        </td>
        <td>
          <p style="text-align: center; border: 1px solid #4589b5; background: #7bafcd; padding: .5em; color: white; 0 1px 1px rgba(0, 0, 0, 0.4); font-weight: bold">
            Need help? <a href="mailto:geeks@sqoot.com" style="font-weight: bold; color: #154d6e">geeks@sqoot.com</a>
          </p>
        </td>
      </tr>
    </table>
  
    <form method="post" action="options.php">
      <?php settings_fields('sqoot-options'); ?>
  
      <h3>General Settings</h3>
      <table class="form-table" style="margin-top:-1em;">
        <tr valign="top">
          <th scope="row">
            <label for="sqoot_position">Widget Position</label>
          </th>
          <td>
            <select name="sqoot_position" id="sqoot_position">
              <option value="sidebar"<?php if(get_option('sqoot_position')=='sidebar') { ?>selected="selected"<?php } ?>>
                Top of the sidebar (default)
              </option>
              <option value="before"<?php if(get_option('sqoot_position')=='before') { ?>selected="selected"<?php } ?>>
                Top of each page
              </option>
              <option value="after"<?php if(get_option('sqoot_position')=='after') { ?>selected="selected"<?php } ?>>
                Bottom of each page
              </option>
              <option value="manual"<?php if(get_option('sqoot_position')=='manual') { ?>selected="selected"<?php } ?>>
                Manual
              </option>
            </select>
            <br />
            Customize placement in the sidebar by selecting "Manual" and positioning the widget under Appearance > Widgets.<br />
            To position the widget anywhere in your theme select "Manual" and insert <code>&#60;?php sqoot_widget(); ?&#62;</code> at the desired location within your themes source code.
          </td>
        </tr>
  
        <tr valign="top">
          <th scope="row">
            <label for="sqoot_affiliate_token">Affiliate Token (Optional)</label>
          </th>
          <td>
            <input name="sqoot_affiliate_token" type="text" id="sqoot_affiliate_token" value="<?php echo get_option('sqoot_affiliate_token'); ?>" class="text" />
            <br />
            <a href="http://www.sqoot.com" target="_blank">Get an affiliate token</a> to track traffic &amp; sales.
          </td>
        </tr>
      </table>
      
      <br />
      
      <h3>Customize Your Widget</h3>
  
      <table class="form-table" style="margin-top:-1em;">    
        <tr valign="top">
          <th scope="row"><label for="sqoot_per_page">Quantity</label></th>
          <td>
            Show <input name="sqoot_per_page" type="text" id="sqoot_per_page" size="2" maxlength="2" value="<?php echo get_option('sqoot_per_page', 3); ?>" class="small-text" /> deals (maximum of 10)
          </td>
        </tr>
  
        <tr valign="top">
          <th scope="row">
            Categories
            <p class="small-text">Pick categories your readers will love. Less is more!</p>
          </th>
          <td>
            <label for="sqoot_category_active"><input name="sqoot_category_active" type="checkbox" id="sqoot_category_active" value="Active," <?php if(get_option('sqoot_category_active')=='Active,') { ?>checked="checked"<?php } ?> /> Active</label><br />
            <label for="sqoot_category_beauty"><input name="sqoot_category_beauty" type="checkbox" id="sqoot_category_beauty" value="Beauty," <?php if(get_option('sqoot_category_beauty')=='Beauty,') { ?>checked="checked"<?php } ?> /> Beauty</label><br />
            <label for="sqoot_category_entertainment"><input name="sqoot_category_entertainment" type="checkbox" id="sqoot_category_entertainment" <?php if(get_option('sqoot_category_entertainment')=='Entertainment,') { ?>checked="checked"<?php } ?> value="Entertainment," /> Entertainment</label><br />
            <label for="sqoot_category_fashion"><input name="sqoot_category_fashion" type="checkbox" id="sqoot_category_fashion" value="Fashion," <?php if(get_option('sqoot_category_fashion')=='Fashion,') { ?>checked="checked"<?php } ?> /> Fashion</label><br />
            <label for="sqoot_category_fitness"><input name="sqoot_category_fitness" type="checkbox" id="sqoot_category_fitness" value="Fitness," <?php if(get_option('sqoot_category_fitness')=='Fitness,') { ?>checked="checked"<?php } ?> /> Fitness</label><br />
            <label for="sqoot_category_nightlife"><input name="sqoot_category_nightlife" type="checkbox" id="sqoot_category_nightlife" value="Nightlife," <?php if(get_option('sqoot_category_nightlife')=='Nightlife,') { ?>checked="checked"<?php } ?> /> Nightlife</label><br />
            <label for="sqoot_category_restaurants"><input name="sqoot_category_restaurants" type="checkbox" id="sqoot_category_restaurants" value="Restaurants," <?php if(get_option('sqoot_category_restaurants')=='Restaurants,') { ?>checked="checked"<?php } ?> /> Restaurants</label><br />
            <label for="sqoot_category_sports"><input name="sqoot_category_sports" type="checkbox" id="sqoot_category_sports" value="Sports," <?php if(get_option('sqoot_category_sports')=='Sports,') { ?>checked="checked"<?php } ?> /> Sports</label><br />
            <label for="sqoot_category_travel"><input name="sqoot_category_travel" type="checkbox" id="sqoot_category_travel" value="Travel" <?php if(get_option('sqoot_category_travel')=='Travel') { ?>checked="checked"<?php } ?> /> Travel</label>
          </td>
        </tr>
      
        <style type="text/css">
          table.paddingless td {
            padding: 0;
          }
          .small-text {
            line-height: 20px;
            font-size: 11px;
          }
        </style>
      
        <tr valign="top">
          <th scope="row">
            <label for="sqoot_radius">Location</label>
  
            <p class="small-text">By default, Sqoot auto-detects your readers' location. If your readers are highly local, you can set a specific location too.</p>
          </th>
          <td>
            <table class="paddingless" cellpadding="0" cellspacing="0">
              <tr>
                <td>Within&nbsp;</td>
                <td><input name="sqoot_radius" type="text" id="sqoot_radius" value="<?php echo get_option('sqoot_radius'); ?>" class="small-text" />&nbsp;</td>
                <td>of&nbsp;</td>
                <td><input name="sqoot_location" type="text" id="sqoot_location" style="width: 150px" value="<?php echo get_option('sqoot_location'); ?>" class="small-text" /></td>
              </tr>
              <tr>
                <td></td>
                <td style="text-align: center">miles</td>
                <td></td>
                <td style="text-align: center">address, city, zip, etc.</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
  
      <br />
  
      <h3>Stylize Widget with CSS</h3>
  
      <table class="form-table" style="margin-top:-1em;"><tr valign="top">
        <tr valign="top">
          <th scope="row">
            <label for="sqoot_css">Custom CSS (Optional)</label>
      
            <p class="small-text">
              By default, the widget inherits your site's look and feel. You can also make your own customizations by using CSS
            </p>
          </th>
          <td>
            <textarea name="sqoot_css" rows="10" cols="50" id="sqoot_css" class="code small-text"><?php echo get_option('sqoot_css', '/* Examples:
/* Add additional space under each deal:
/*  #sqoot .winning .offer {
/*    margin: 1em 0;
/*  }
/* Make the titles of the deals red:
/*  #sqoot .winning .offer .title a {
/*    color: red;
/*  }
 */'); ?></textarea>
          </td>
        </tr>
      </table>
  
      <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
    </form>
  </div>
<?php
}
