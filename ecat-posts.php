<?php
/**
*Plugin Name: Elegant Category Posts
*Plugin URI: http://github.com/awebdesigner/ecat-posts
*Author: David Wampamba (Gagawala Graphics)
*Author URI: https://profiles.wordpress.org/dwampamba
*Version: 1.0.0
*Text Domain: ecat-posts
*Description: A Widget to display posts from any category, set the count of posts for display in any sidebar. This widget is dedicated to http://ironblogger.cocoate.com, in response to Hagen Graf's request. 
*Tags: Posts, Categories, WordPress, any category, number of posts, ironblogger
*/
// Creating the widget
class ecats_posts extends WP_Widget{
function __construct() {
parent::__construct('ecats_posts', __('Elegant Category Posts', 'ecats-posts'),
 array( 'description' => __( 'Display posts from any category.', 'ecats-posts' ), ));
}
// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
// before and after widget arguments are defined by themes
$title    = apply_filters('widget_title',$instance['title']);
$ecat_posts = isset($instance['cats'])? $instance['cats']:'uncategorized';
$pcount   = isset($instance['p_count'])?$instance['p_count']:'2';
$wdtclass = isset($instance['wdt_class'])?$instance['wdt_class']:'elwdt_cls';
$wdtid    = isset($instance['wdt_id'])?$instance['wdt_id']:'elwdt_id';
$etpl     = isset($instance['e_tpl'])?$instance['e_tpl']:'default';
echo $args['before_widget'];
?>
<div class="<?php echo $wdtclass; ?>"><div id="<?php echo $wdtid; ?>">
<?php
if ( ! empty( $title ) ){
echo $args['before_title'] . $title . $args['after_title'];
}
$ecat_args = array('posts_per_page'=>$pcount,'category_name'=>$ecat_posts);
// The Query
$the_query = new WP_Query( $ecat_args );
// The Loop
if($the_query->have_posts() ) {
	while( $the_query->have_posts() ) {
		$the_query->the_post();
    $img_url = wp_get_attachment_image_src(get_post_thumbnail_id(),'500px');
?>
<div class="ecat_item">
<div class="ecat_item_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
<?php if(has_post_thumbnail()): ?>
<a href="<?php the_permalink(); ?>"><div class="ecat_item_img" style="background: url('<?php echo $img_url[0];?>') no-repeat; "></div></a>
<?php endif; ?>
<div class="ecat_item_intro"><?php echo strip_tags(substr(get_the_content(),0,40)).'... '; ?><a href="<?php the_permalink(); ?>">Read more</a></div>
<hr/>
</div>
<?php
	}
  	/* Restore original Post Data */
	wp_reset_postdata();
} else {
	// no posts found
}
?>
</div></div>
<?php
echo $args['after_widget'];
}
// Widget Backend
public function form( $instance ) {

//Get values in instance into our custom variables
  $title = !empty($instance['title'])? sanitize_text_field($instance['title']):'Widge Title';
  $wdtclass = !empty($instance['wdt_class'])? sanitize_text_field($instance['wdt_class']):'elwdt_cls';
  $wdtid = !empty($instance['wdt_id'])? sanitize_text_field($instance['wdt_id']):'elwdt_id';
  $cats  = !empty($instance['cats'])?sanitize_text_field($instance['cats']):'uncategorized';
  $pcount = isset($instance['p_count'])?$instance['p_count']:'3';
  $etpl   = !empty($instance['e_tpl'])?$instance['e_tpl']:'default';
// Widget admin form
?>
<h3>Display posts from any category.</h3>
<p>Choose settings below.</p>
<hr/>
<p>
<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:','elegant-cats-posts' ); ?></label>
<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>
<p>
<label for="<?php echo esc_attr($this->get_field_id( 'wdt_class' )); ?>"><?php _e( 'CSS class(es):','elegant-cats-posts' ); ?></label>
<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'wdt_class' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'wdt_class' )); ?>" type="text" value="<?php echo esc_attr( $wdtclass ); ?>" />
</p>
<p>
<label for="<?php echo esc_attr($this->get_field_id( 'wdt_id' )); ?>"><?php _e( 'CSS ID:','elegant-cats-posts' ); ?></label>
<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'wdt_id' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'wdt_id' )); ?>" type="text" value="<?php echo esc_attr( $wdtid ); ?>" />
</p>
<p>
<label for="<?php echo esc_attr($this->get_field_id( 'cats' )); ?>">Category Slugs</label><hr/>
<input type="text" id="<?php echo esc_attr($this->get_field_id( 'cats' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'cats' )); ?>" value="<?php echo esc_attr($cats); ?>" />
<span>Separate with commas</span>
</p>
<p>
<label for="<?php echo esc_attr($this->get_field_id( 'p_count' )); ?>"><?php _e( 'Post count:','elegant-cats-posts' ); ?></label>
<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'p_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'p_count' )); ?>" type="text" value="<?php echo esc_attr( $pcount ); ?>" />
</p>
<p>
<label for="<?php echo esc_attr($this->get_field_id( 'e_tpl' )); ?>"><?php _e( 'Template:','elegant-cats-posts' ); ?></label>
<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'e_tpl' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'e_tpl' )); ?>" >
<option value="default" <?php if($etpl=="default"){ echo 'selected';} ?>>default</option>
<option value="thumb_l" <?php if($etpl=='thumb_l'){ echo 'selected';}?>>Thumb left</option>
<option value="thumb_r" <?php if($etpl=='thumb_r'){ echo 'selected';} ?>>Thumb right</option>
<option value="thumb_t" <?php if($etpl=='thumb_t'){ echo 'selected';} ?>>Thumb top</option>
<option value="thumb_b" <?php if($etpl=='thumb_b'){ echo 'selected';}?> >Thumb buttom</option>
<option value="thumb_o" <?php if($etpl=='thumb_o'){ echo 'selected';}?> >Thumb only</option>
<option value="thumb_c" <?php if($etpl=='thumb_c'){ echo 'selected';}?> >Thumb columns</option>
</select>
</p>
<div id="tpl_holder">
Preview
</div>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance = $old_instance;
$instance['title'] = ( ! empty($new_instance['title']))?sanitize_text_field($new_instance['title'] ):$old_instance['title'];
$instance['wdt_class'] = ( ! empty( $new_instance['wdt_class'] ) ) ? sanitize_text_field($new_instance['wdt_class'] ) :$old_instance['wdt_class'];
$instance['wdt_id'] = ( ! empty( $new_instance['wdt_id'] ) ) ? sanitize_text_field($new_instance['wdt_id'] ) :$old_instance['wdt_id'];
$instance['cats'] = ( ! empty( $new_instance['cats'] ) ) ? sanitize_text_field($new_instance['cats'] ) : $old_instance['cats'];
$instance['p_count'] = ( ! empty( $new_instance['p_count'] ) ) ? intval(sanitize_text_field($new_instance['p_count'] )) : $old_instance['p_count'];
$instance['e_tpl'] = ( ! empty( $new_instance['e_tpl'] ) ) ? sanitize_text_field($new_instance['e_tpl'] ) : $old_instance['e_tpl'];
return $instance;
}
}
 // Class wpb_widget ends here
 //Planned for changing widget content theme...
add_action('wp_enqueue_scripts','tpl_switch');
function tpl_switch(){ //Switching widget themes;
  switch($elg):
    case 'default':
	   wp_enqueue_style('default_tpl',plugins_url('css/'.$elg.'.css',__FILE__));
      break;
    case 'thumb_l':
	   wp_enqueue_style('thumb_left',plugins_url('css/'.$elg.'.css',__FILE__));
      break;
    case 'thumb_r':
    wp_enqueue_style('thumb_right',plugins_url('css/'.$elg.'.css',__FILE__));
      break;
    case 'thumb_t':
    wp_enqueue_style('thumb_top',plugins_url('css/'.$elg.'.css',__FILE__));
      break;
    case 'thumb_b':
    wp_enqueue_style('thumb_bottom',plugins_url('css/'.$elg.'.css',__FILE__));
      break;
    case 'thumb_o':
    wp_enqueue_style('thumb_only',plugins_url('css/'.$elg.'.css',__FILE__));
      break;
    case 'thumb_c':
    wp_enqueue_style('thumb_columns',plugins_url('css/'.$elg.'.css',__FILE__));
      break;
    endswitch;
}
//Add default CSS to the widget
add_action('wp_head','default_style');
function default_style(){
?>
<style type="text/css">
.ecat_item{
  padding: 10px;
}
.ecat_item a{
    text-decoration: none;
}
.ecat_item hr{
  width: 100%;
  border: none;
  border-bottom: 1px solid #ccc;
  padding: 0;
  margin: 0;
  margin-bottom: 3px;
  margin-top: 3px;
}
.ecat_item .ecat_item_title{
  font-weight: bold;
}
.ecat_item .ecat_item_img{
  height: 250px !important;
  background-size:cover !important;
}
</style>
<?php
}
// Register and load the widget
add_action( 'widgets_init', 'ecats_posts_widget' );
function ecats_posts_widget() {
	register_widget( 'ecats_posts' );
}