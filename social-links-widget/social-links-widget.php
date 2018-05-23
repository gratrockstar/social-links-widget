<?php
/*
Plugin Name: Social Links Widget
Plugin URI: http://damemedia.com
Description: Social Links Widget adds a configurable widget with links and icons to social media.
Author: Garrett Baldwin
Version: 1.0
Author URI: https://github.com/gratrockstar
*/

class SocialLinksWidget extends WP_Widget {
  
  /**
   * Array of services to add links to
   * @var array
   */
  private $services;
  
  
  /**
   * Array of service image defaults
   * @var array
   */
  private $serviceImg;
  
  /**
   * Array of service image dimensions defaults
   * @var [type]
   */
  private $serviceImgDimensions;
  
  /**
   * Constructor
   */
  function SocialLinksWidget() {
    
    // set our widget options
    $widget_ops = [
      'classname' => 'SocialLinksWidget', 
      'description' => 'Displays a Social Links Widget'
    ];
    
    // create widget
    parent::WP_Widget('SocialLinksWidget', 'Social Links Widget', $widget_ops);
    
    // Add list of services, wrapped in filter to allow to change order
    $this->services = apply_filters('social_links_widget_services', [
      'GooglePlus',
      'Facebook', 
      'Twitter',  
      'Instagram',
      'YouTube',       
      'Pinterest',  
      'Flickr',
      'LinkedIn'
    ]);
    
    // Set image dimensions, wrapped in a filter for editing dimensions
    $this->serviceImgDimensions = apply_filters('social_links_widget_service_image_dimensions', [
      'width' => '24px', 
      'height' => '24px',   
    ]);
    
    // add list of default SVGs, wrapped in filter to allow editing 
    // thanks to https://github.com/simple-icons/simple-icons
    $this->serviceImg = apply_filters('social_links_widget_service_icons', [
      'Facebook' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-facebook-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-facebook-icon">Facebook icon</title><path d="M22.676 0H1.324C.593 0 0 .593 0 1.324v21.352C0 23.408.593 24 1.324 24h11.494v-9.294H9.689v-3.621h3.129V8.41c0-3.099 1.894-4.785 4.659-4.785 1.325 0 2.464.097 2.796.141v3.24h-1.921c-1.5 0-1.792.721-1.792 1.771v2.311h3.584l-.465 3.63H16.56V24h6.115c.733 0 1.325-.592 1.325-1.324V1.324C24 .593 23.408 0 22.676 0"/></svg>', 
      'Twitter' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-twitter-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-twitter-icon">Twitter icon</title><path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"/></svg>', 
      'GooglePlus' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-googleplus-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-googleplus-icon">Google+ icon</title><path d="M7.635 10.909v2.619h4.335c-.173 1.125-1.31 3.295-4.331 3.295-2.604 0-4.731-2.16-4.731-4.823 0-2.662 2.122-4.822 4.728-4.822 1.485 0 2.479.633 3.045 1.178l2.073-1.994c-1.33-1.245-3.056-1.995-5.115-1.995C3.412 4.365 0 7.785 0 12s3.414 7.635 7.635 7.635c4.41 0 7.332-3.098 7.332-7.461 0-.501-.054-.885-.12-1.265H7.635zm16.365 0h-2.183V8.726h-2.183v2.183h-2.182v2.181h2.184v2.184h2.189V13.09H24"/></svg>', 
      'Instagram' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-instagram-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-instagram-icon">Instagram icon</title><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>', 
      'YouTube' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-youtube-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title id="simpleicons-youtube-icon">YouTube icon</title><path class="a" d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>',       
      'Pinterest' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-pinterest-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-pinterest-icon">Pinterest icon</title><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>', 
      'Flickr' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-flickr-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-flickr-icon">Flickr icon</title><path d="M0 12c0 3.074 2.494 5.564 5.565 5.564 3.075 0 5.569-2.49 5.569-5.564S8.641 6.436 5.565 6.436C2.495 6.436 0 8.926 0 12zm12.866 0c0 3.074 2.493 5.564 5.567 5.564C21.496 17.564 24 15.074 24 12s-2.492-5.564-5.564-5.564c-3.075 0-5.57 2.49-5.57 5.564z"/></svg>',
      'LinkedIn' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-linkedin-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-linkedin-icon">LinkedIn icon</title><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>', 
    ]);
    
  }
  
  /**
   * Build our widget form
   * @param  array  $instance Data for this widget instance
   * @return string           HTML for widget settings form
   */
  function form($instance) {
    
    // parse args
    $instance = wp_parse_args((array) $instance, array('title' => 'Follow Us'));
    
    // set instance title and create form field
    $title = $instance['title'];
    
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title:
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
      </label>
    </p>
    <?php 
    
    // loop through service and create form fields
    foreach ($this->services as $service) :
      
      // save instance service key to var
      $widget_field = $service . 'Widget';
      
      ?>
      <p>
        <label for="<?php echo $this->get_field_id($widget_field); ?>"><?php echo $service; ?> URL:
          <input class="widefat" id="<?php echo $this->get_field_id($widget_field); ?>" name="<?php echo $this->get_field_name($widget_field); ?>" type="text" value="<?php echo attribute_escape($instance[$widget_field]); ?>" />
        </label>
      <p>
      <?php
      
    endforeach;
    
  }
  
  /**
   * Update widget instance settings with new values
   * @param  array  $new_instance New instance values
   * @param  array  $old_instance Old instance values
   * @return array                Updated instance values
   */
  function update($new_instance, $old_instance) {
    
    // set our vars for saving
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    
    // loop through services and add them to $instance fpr saving
    foreach ($this->services as $service) {
      $widget_field = $service . 'Widget';
      $instance[$widget_field] = $new_instance[$widget_field];
    }
    
    // return $instance for saving
    return $instance;
  }
  
  
  /**
   * Display Widget
   * @param  array  $args     Arary of args
   * @param  array  $instance Arary of widget instance data
   * @return string           HTML for output
   */
  function widget($args, $instance) {
    
    extract($args, EXTR_SKIP);
    
    // init our html string
    $html = '';
    
    // add $before_widget to html string
    $html .= $before_widget;
    
    // set widget title, wrapped in filter for editing
    $title = empty($instance['title']) ? ' ' : apply_filters('social_links_widget_title', $instance['title']);
    
    // check if title is empty, if not we add $before_title, $title, and $after_title 
    // to our html string
    if (!empty($title))
        $html .= $before_title . $title . $after_title;
    
    // create array of list classes, wrapped in filter for editing
    $list_classes = apply_filters('social_links_widget_list_classes', ['list-inline', 'text-center']);
    
    // create array of list item classes, wrapped in filter for editing
    $list_item_classes = apply_filters('social_links_widget_list_item_classes', ['list-inline-item']);
    
    // add list opening to html string
    $html .= '<ul class="' . implode(' ', $list_classes) . '">';
    
    // loop through our services
    foreach ($this->services as $service) {
      
      // save instance service key to var
      $widget_field = $service . 'Widget';
      
      // check if service value has been set
      if (!empty($instance[$widget_field])) {
        
        // add service item to html string
        $html .= '  <li class="' . implode(' ', $list_item_classes) . '">
          <a href="' . $instance[$widget_field] . '" target="_blank">
            ' . $this->serviceImg[$service] . '
            <span class="sr-only">' . $service . '</span>
          </a>
        </li>';
        
      }
      
    }
    
    // add closing list tag to html string
    $html .= "</ul>";
    
    // add $after_widget to html string
    $html .= $after_widget;
    
    // echo our html, wrapped in a filter for editing
    echo apply_filters('social_links_widget_html', $html, $args, $instance);
  }
  
}

// Add our action to widgets_init
add_action( 'widgets_init', create_function('', 'return register_widget("SocialLinksWidget");') );

