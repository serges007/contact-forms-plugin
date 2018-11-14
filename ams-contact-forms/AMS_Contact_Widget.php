<?php

/**
 * Description of AMS_Contact_Widget
 *
 * @author amoungui
 */

class AMS_Contact_Widget extends WP_Widget{

    public function __construct(){
        parent::__construct('ams_contact_forms', 'Contact Forms', array('description' => 'Un formulaire de contact.'));
    }
    
    public function widget($args, $instance){
        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];
        ?>
<!--        <form action="" method="post">
            <p>
                <label for="zero_newsletter_email">Votre email :</label>
                <input id="zero_newsletter_email" name="zero_newsletter_email" type="email"/>
            </p>
            <input type="submit"/>
        </form>-->
        <form action="" method="post">
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control p-3 rounded-0" id="name" name="name">
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control p-3 rounded-0" id="email" name="email">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea cols="30" rows="10" class="form-control  p-3 rounded-0" id="message" name="message"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn pb_outline-dark pb_font-13 pb_letter-spacing-2  p-3 rounded-0" value="Send Message">
            </div>
        </form>
        <?php
        echo $args['after_widget'];
    }    
    
    public function form($instance){
        $title = isset($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo  $title; ?>" />
        </p>
        <?php
    }    
}
