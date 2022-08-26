<?php
/*
Plugin Name: Social Links Widget
Plugin URI: https://github.com/gratrockstar/social-links-widget
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
    parent::WP_Widget( 'SocialLinksWidget', 'Social Links Widget', $widget_ops );
    
    // Add list of services, wrapped in filter to allow to change order
    $this->services = apply_filters( 'social_links_widget_services', [
      'Facebook', 
      'Twitter',  
      'Instagram',
      'YouTube',       
      'Pinterest',  
      'Flickr',
      'LinkedIn', 
      'TikTok', 
      'Twitch', 
      'SoundCloud', 
      'Medium', 
      'Vimeo', 
      'Snapchat', 
    ] );
    
    // Set image dimensions, wrapped in a filter for editing dimensions
    $this->serviceImgDimensions = apply_filters( 'social_links_widget_service_image_dimensions', [
      'width' => '24px', 
      'height' => '24px',   
    ] );
    
    // add list of default SVGs, wrapped in filter to allow editing 
    // thanks to https://github.com/simple-icons/simple-icons
    $this->serviceImg = apply_filters( 'social_links_widget_service_icons', [
      'Facebook' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-facebook-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-facebook-icon">Facebook</title><path d="M22.676 0H1.324C.593 0 0 .593 0 1.324v21.352C0 23.408.593 24 1.324 24h11.494v-9.294H9.689v-3.621h3.129V8.41c0-3.099 1.894-4.785 4.659-4.785 1.325 0 2.464.097 2.796.141v3.24h-1.921c-1.5 0-1.792.721-1.792 1.771v2.311h3.584l-.465 3.63H16.56V24h6.115c.733 0 1.325-.592 1.325-1.324V1.324C24 .593 23.408 0 22.676 0"/></svg>', 
      'Twitter' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-twitter-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-twitter-icon">Twitter</title><path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"/></svg>', 
      'Instagram' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-instagram-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-instagram-icon">Instagram</title><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>', 
      'YouTube' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-youtube-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title id="simpleicons-youtube-icon">YouTube</title><path class="a" d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>',       
      'Pinterest' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-pinterest-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-pinterest-icon">Pinterest</title><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>', 
      'Flickr' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-flickr-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-flickr-icon">Flickr</title><path d="M0 12c0 3.074 2.494 5.564 5.565 5.564 3.075 0 5.569-2.49 5.569-5.564S8.641 6.436 5.565 6.436C2.495 6.436 0 8.926 0 12zm12.866 0c0 3.074 2.493 5.564 5.567 5.564C21.496 17.564 24 15.074 24 12s-2.492-5.564-5.564-5.564c-3.075 0-5.57 2.49-5.57 5.564z"/></svg>',
      'LinkedIn' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-linkedin-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-linkedin-icon">LinkedIn</title><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>', 
      'TikTok' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-tiktok-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-tiktok-icon">TikTok</title><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>', 
      'Twitch' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-twitch-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-twitch-icon">Twitch</title><path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714Z"/></svg>', 
      'SoundCloud' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-soundcloud-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-soundcloud-icon">SoundCloud</title><path d="M1.175 12.225c-.051 0-.094.046-.101.1l-.233 2.154.233 2.105c.007.058.05.098.101.098.05 0 .09-.04.099-.098l.255-2.105-.27-2.154c0-.057-.045-.1-.09-.1m-.899.828c-.06 0-.091.037-.104.094L0 14.479l.165 1.308c0 .055.045.094.09.094s.089-.045.104-.104l.21-1.319-.21-1.334c0-.061-.044-.09-.09-.09m1.83-1.229c-.061 0-.12.045-.12.104l-.21 2.563.225 2.458c0 .06.045.12.119.12.061 0 .105-.061.121-.12l.254-2.474-.254-2.548c-.016-.06-.061-.12-.121-.12m.945-.089c-.075 0-.135.06-.15.135l-.193 2.64.21 2.544c.016.077.075.138.149.138.075 0 .135-.061.15-.15l.24-2.532-.24-2.623c0-.075-.06-.135-.135-.135l-.031-.017zm1.155.36c-.005-.09-.075-.149-.159-.149-.09 0-.158.06-.164.149l-.217 2.43.2 2.563c0 .09.075.157.159.157.074 0 .148-.068.148-.158l.227-2.563-.227-2.444.033.015zm.809-1.709c-.101 0-.18.09-.18.181l-.21 3.957.187 2.563c0 .09.08.164.18.164.094 0 .174-.09.18-.18l.209-2.563-.209-3.972c-.008-.104-.088-.18-.18-.18m.959-.914c-.105 0-.195.09-.203.194l-.18 4.872.165 2.548c0 .12.09.209.195.209.104 0 .194-.089.21-.209l.193-2.548-.192-4.856c-.016-.12-.105-.21-.21-.21m.989-.449c-.121 0-.211.089-.225.209l-.165 5.275.165 2.52c.014.119.104.225.225.225.119 0 .225-.105.225-.225l.195-2.52-.196-5.275c0-.12-.105-.225-.225-.225m1.245.045c0-.135-.105-.24-.24-.24-.119 0-.24.105-.24.24l-.149 5.441.149 2.503c.016.135.121.24.256.24s.24-.105.24-.24l.164-2.503-.164-5.456-.016.015zm.749-.134c-.135 0-.255.119-.255.254l-.15 5.322.15 2.473c0 .15.12.255.255.255s.255-.12.255-.27l.15-2.474-.165-5.307c0-.148-.12-.27-.271-.27m1.005.166c-.164 0-.284.135-.284.285l-.103 5.143.135 2.474c0 .149.119.277.284.277.149 0 .271-.12.284-.285l.121-2.443-.135-5.112c-.012-.164-.135-.285-.285-.285m1.184-.945c-.045-.029-.105-.044-.165-.044s-.119.015-.165.044c-.09.054-.149.15-.149.255v.061l-.104 6.048.115 2.449v.008c.008.06.03.135.074.18.058.061.142.104.234.104.08 0 .158-.044.209-.09.058-.06.091-.135.091-.225l.015-.24.117-2.203-.135-6.086c0-.104-.061-.193-.135-.239l-.002-.022zm1.006-.547c-.045-.045-.09-.061-.15-.061-.074 0-.149.016-.209.061-.075.061-.119.15-.119.24v.029l-.137 6.609.076 1.215.061 1.185c0 .164.148.314.328.314.181 0 .33-.15.33-.329l.15-2.414-.15-6.637c0-.12-.074-.221-.165-.277m8.934 3.777c-.405 0-.795.086-1.139.232-.24-2.654-2.46-4.736-5.188-4.736-.659 0-1.305.135-1.889.359-.225.09-.27.18-.285.359v9.368c.016.18.15.33.33.345h8.185C22.681 17.218 24 15.914 24 14.28s-1.319-2.952-2.938-2.952"/></svg>', 
      'Medium' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-medium-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-medium-icon">Medium</title><path d="M13.54 12a6.8 6.8 0 01-6.77 6.82A6.8 6.8 0 010 12a6.8 6.8 0 016.77-6.82A6.8 6.8 0 0113.54 12zM20.96 12c0 3.54-1.51 6.42-3.38 6.42-1.87 0-3.39-2.88-3.39-6.42s1.52-6.42 3.39-6.42 3.38 2.88 3.38 6.42M24 12c0 3.17-.53 5.75-1.19 5.75-.66 0-1.19-2.58-1.19-5.75s.53-5.75 1.19-5.75C23.47 6.25 24 8.83 24 12z"/></svg>', 
      'Vimeo' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-vimeo-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-vimeo-icon">Vimeo</title><path d="M23.9765 6.4168c-.105 2.338-1.739 5.5429-4.894 9.6088-3.2679 4.247-6.0258 6.3699-8.2898 6.3699-1.409 0-2.578-1.294-3.553-3.881l-1.9179-7.1138c-.719-2.584-1.488-3.878-2.312-3.878-.179 0-.806.378-1.8809 1.132l-1.129-1.457a315.06 315.06 0 003.501-3.1279c1.579-1.368 2.765-2.085 3.5539-2.159 1.867-.18 3.016 1.1 3.447 3.838.465 2.953.789 4.789.971 5.5069.5389 2.45 1.1309 3.674 1.7759 3.674.502 0 1.256-.796 2.265-2.385 1.004-1.589 1.54-2.797 1.612-3.628.144-1.371-.395-2.061-1.614-2.061-.574 0-1.167.121-1.777.391 1.186-3.8679 3.434-5.7568 6.7619-5.6368 2.4729.06 3.6279 1.664 3.4929 4.7969z"/></svg>', 
      'Snapchat' => '<svg height="' . $this->serviceImgDimensions['height'] . '" width="' . $this->serviceImgDimensions['width'] . '" aria-labelledby="simpleicons-snapchat-icon" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title id="simpleicons-snapchat-icon">Snapchat</title><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12 1.033-.301.165-.088.344-.104.464-.104.182 0 .359.029.509.09.45.149.734.479.734.838.015.449-.39.839-1.213 1.168-.089.029-.209.075-.344.119-.45.135-1.139.36-1.333.81-.09.224-.061.524.12.868l.015.015c.06.136 1.526 3.475 4.791 4.014.255.044.435.27.42.509 0 .075-.015.149-.045.225-.24.569-1.273.988-3.146 1.271-.059.091-.12.375-.164.57-.029.179-.074.36-.134.553-.076.271-.27.405-.555.405h-.03c-.135 0-.313-.031-.538-.074-.36-.075-.765-.135-1.273-.135-.3 0-.599.015-.913.074-.6.104-1.123.464-1.723.884-.853.599-1.826 1.288-3.294 1.288-.06 0-.119-.015-.18-.015h-.149c-1.468 0-2.427-.675-3.279-1.288-.599-.42-1.107-.779-1.707-.884-.314-.045-.629-.074-.928-.074-.54 0-.958.089-1.272.149-.211.043-.391.074-.54.074-.374 0-.523-.224-.583-.42-.061-.192-.09-.389-.135-.567-.046-.181-.105-.494-.166-.57-1.918-.222-2.95-.642-3.189-1.226-.031-.063-.052-.15-.055-.225-.015-.243.165-.465.42-.509 3.264-.54 4.73-3.879 4.791-4.02l.016-.029c.18-.345.224-.645.119-.869-.195-.434-.884-.658-1.332-.809-.121-.029-.24-.074-.346-.119-1.107-.435-1.257-.93-1.197-1.273.09-.479.674-.793 1.168-.793.146 0 .27.029.383.074.42.194.789.3 1.104.3.234 0 .384-.06.465-.105l-.046-.569c-.098-1.626-.225-3.651.307-4.837C7.392 1.077 10.739.807 11.727.807l.419-.015h.06z"/></svg>', 
    ] );
    
  }
  
  /**
   * Build our widget form
   * @param  array  $instance Data for this widget instance
   * @return string           HTML for widget settings form
   */
  function form( $instance ) {
    
    // parse args
    $instance = wp_parse_args( ( array ) $instance, array( 'title' => 'Follow Us' ) );
    
    // set instance title and create form field
    $title = $instance['title'];
    
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </label>
    </p>
    <?php 
    
    // loop through service and create form fields
    foreach ( $this->services as $service ) :
      
      // save instance service key to var
      $widget_field = $service . 'Widget';
      
      ?>
      <p>
        <label for="<?php echo $this->get_field_id( $widget_field ); ?>"><?php echo $service; ?> URL:
          <input class="widefat" id="<?php echo $this->get_field_id( $widget_field ); ?>" name="<?php echo $this->get_field_name( $widget_field ); ?>" type="text" value="<?php echo esc_url( $instance[$widget_field] ); ?>" />
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
  function update( $new_instance, $old_instance ) {
    
    // set our vars for saving
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    
    // loop through services and add them to $instance fpr saving
    foreach ( $this->services as $service ) {
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
  function widget( $args, $instance ) {
    
    extract( $args, EXTR_SKIP );
    
    // init our html string
    $html = '';
    
    // add $before_widget to html string
    $html .= $before_widget;
    
    // set widget title, wrapped in filter for editing
    $title = empty( $instance['title'] ) ? ' ' : apply_filters( 'social_links_widget_title', $instance['title'] );
    
    // check if title is empty, if not we add $before_title, $title, and $after_title 
    // to our html string
    if ( !empty( $title ) ) {
      $html .= $before_title . $title . $after_title;
    }
    
    // create array of list classes, wrapped in filter for editing
    $list_classes = apply_filters( 'social_links_widget_list_classes', ['list-inline', 'text-center'] );
    
    // create array of list item classes, wrapped in filter for editing
    $list_item_classes = apply_filters( 'social_links_widget_list_item_classes', ['list-inline-item'] );
    
    // add list opening to html string
    $html .= '<ul class="' . implode( ' ', $list_classes ) . '">';
    
    // loop through our services
    foreach ( $this->services as $service ) {
      
      // save instance service key to var
      $widget_field = $service . 'Widget';
      
      // check if service value has been set
      if ( !empty( $instance[$widget_field] ) ) {
        
        // add service item to html string
        $html .= '  <li class="' . implode( ' ', $list_item_classes ) . '">
          <a href="' . esc_url( $instance[$widget_field] ) . '" target="_blank">
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
    echo apply_filters( 'social_links_widget_html', $html, $args, $instance );
  }
  
}

// Add our action to widgets_init
add_action( 'widgets_init', create_function( '', 'return register_widget("SocialLinksWidget");' ) );

