<?php
/**
* Class to define options for HSC Disable Comments
*/

class hsc_DisableComments {

    /**
     * Constructor.
     *
     * @param string $template_path
     */
    public function __construct() {
        if(is_admin()){
            add_action( 'admin_menu', [ $this, 'hsc_register_disable_comments_page' ] );
            add_action( 'admin_init', [ $this, 'hsc_plugin_register_settings'] );
            add_action( 'admin_init', [ $this, 'hsc_disable_comments_action'] );
        }
    }

    /**
    * Get the title of the admin page.
    *
    * @return string
    */
    public function get_page_title() {
        return 'Disable Comments Settings';
    }
    
    /**
    * Get the slug used by the admin page.
    *
    * @return string
    */
    public function get_slug() {
        return 'hsc_disable_comments';
    }

    /**
    * Get the registered post types.
    *
    * @return array
    */
    public function fetch_post_types() {
        $args = array( 
            'public'   => true,
            '_builtin' => true
        );
         
        $post_types = get_post_types( $args, 'objects', 'and' );
        return $post_types;
    }
    /**
    * Get the registered post types.
    *
    * @return array
    */
    public function hsc_plugin_register_settings() {
        add_option( 'hsc_disable_comments', 'Choose post type from the list');
        register_setting( $this->get_slug(), 'hsc_disable_comments' );
    }

    /**
    * Add subpage in the dashboard.
    *
    * @return array
    */
    public function hsc_register_disable_comments_page() {
        return add_options_page( 
            __( 'Disable Comments', 'hsc' ),
            __( 'HSC Disable Comments', 'hsc' ),
            'manage_options',
            $this->get_slug(),
            [ $this, 'hsc_disable_comments_setting_action' ]
        );
        
    }

    /**
    * Plugin page functionality.
    *
    * @return array
    */
    public function hsc_disable_comments_setting_action() {
        ?>
        <div class="wrap" id="myplugin-admin">
            <div id="icon-tools" class="icon32"><br></div>
            <h2><?php echo $this->get_page_title(); ?></h2>
            <?php if (!empty($_GET['updated'])) : ?>
                <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
                    <p><strong><?php _e('Settings saved.') ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>
            <?php endif; ?>
            <form action="options.php" method="POST">
                <?php settings_fields($this->get_slug()); ?>
                <?php $types = $this->fetch_post_types(); ?>
                <?php $postTypes = get_option('hsc_disable_comments'); ?>
                <?php foreach($types as $type):?>
                <div>
                <input type="checkbox" value="<?php echo $type->name; ?>" name="hsc_disable_comments[]" <?php if(in_array($type->name, $postTypes)) echo 'checked';?>>
                <label><?php echo $type->labels->singular_name; ?></label>
                </div>
                <?php endforeach;?>
                <?php do_settings_sections($this->get_slug()); ?>
                <?php submit_button(__('Save')); ?>
            </form>
        </div>
        <?php
    }

    public function hsc_disable_comments_action() {
        $types = $this->get_slug();

        foreach ($types as $type) {
            if(post_type_supports($type, 'comments')) {
                remove_post_type_support($type, 'comments');
                remove_post_type_support($type, 'trackbacks');
            }
        }
    }

}
