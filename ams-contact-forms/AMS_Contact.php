<?php
include_once plugin_dir_path( __FILE__ ).'/AMS_Contact_Widget.php';

/**
 * Description of AMS_Contact
 *
 * @author amoungui
 */

class AMS_Contact {
    public function __construct() {
        add_action('widgets_init', function(){register_widget('AMS_Contact_Widget');});
        add_action('wp_loaded', array($this, 'save_message'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
        add_action('admin_init', array($this, 'register_settings'));        
    }
    
    public function install() {
        global $wpdb;    
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}ams_contact_forms (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message TEXT NOT NULL);");        
    }
    
    public static function uninstall(){
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ams_contact_forms;");
    }    
    
    public function save_message(){
        if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['email']) && !empty($_POST['email']) && is_email($_POST['email']) && isset($_POST['message']) && !empty($_POST['message'])) {
            global $wpdb;
            $email = $_POST['email'];
            $name = $_POST['name'];
            $message = $_POST['message'];
            //var_dump($message);

            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ams_contact_forms WHERE email = '$email' and name ='$name' and message='$message'");
            if (is_null($row)) {
                $wpdb->insert("{$wpdb->prefix}ams_contact_forms", array(
                    'email' => $email,
                    'name' => $name,
                    'message' => $message
                ));
            }
        }
    }
    
    public function add_admin_menu(){
        $hook = NULL;
        add_submenu_page('ams', 'Contact Forms', 'Contact', 'manage_options', 'ams_contact', array($this, 'menu_html'));
        add_action('load-'.$hook, array($this, 'process_action'));
    }    
    
    public function menu_html(){
        echo '<h1>'.get_admin_page_title().'</h1>';
        ?>
            <form method="post" action="options.php">
            <?php settings_fields('ams_contact_settings') ?>
            <?php do_settings_sections('ams_contact_settings') ?>
            <?php submit_button(); ?>
            </form>
        <?php
    }    
    
public function register_settings()
{
    register_setting('ams_contact_settings', 'ams_contact_sender');
    register_setting('ams_contact_settings', 'ams_contact_object');
    register_setting('ams_contact_settings', 'ams_contact_content');

    add_settings_section('ams_contact_section', 'Contact parameters', array($this, 'section_html'), 'ams_contact_settings');
    add_settings_field('ams_contact_sender', 'Expéditeur', array($this, 'sender_html'), 'ams_contact_settings', 'ams_contact_section');
    add_settings_field('ams_contact_object', 'Objet', array($this, 'object_html'), 'ams_contact_settings', 'ams_contact_section');
    add_settings_field('ams_contact_content', 'Contenu', array($this, 'content_html'), 'ams_contact_settings', 'ams_contact_section');
}

    public function object_html(){
        ?>
        <input type="text" name="ams_contact_object" value="<?php echo get_option('ams_contact_object')?>"/>
        <?php
    }

    public function content_html(){
        ?>
        <textarea name="ams_contact_content"><?php echo get_option('ams_contact_content')?></textarea>
        <?php
    }

    public function section_html(){
        echo 'Renseignez les paramètres d\'envoi de mail.';
    }
    
    public function sender_html(){
        ?>
        <input type="text" name="ams_contact_sender" value="<?php echo get_option('ams_contact_sender')?>"/>
        <?php
    }    

    public function process_action(){
        if (isset($_POST['send_newsletter'])) {
            $this->send_newsletter();
        }
    }    
    
    public function send_newsletter(){
        global $wpdb;
        $recipients = $wpdb->get_results("SELECT email FROM {$wpdb->prefix}ams_contact_forms");
        $object = get_option('ams_contact_object', 'Newsletter');
        $content = get_option('ams_contact_content', 'Mon contenu');
        $sender = get_option('ams_contact_sender', 'no-reply@example.com');
        $header = array('From: '.$sender);

        foreach ($recipients as $_recipient) {
            $result = wp_mail($_recipient->email, $object, $content, $header);
        }
    }    
}
