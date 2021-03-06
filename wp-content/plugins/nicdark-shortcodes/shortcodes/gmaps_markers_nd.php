<?php

/////////////////////////////////////////////////////////gmaps_markers/////////////////////////////////////////
add_shortcode('gmaps_markers_nd', 'nicdark_shortcode_gmaps_markers');
function nicdark_shortcode_gmaps_markers($atts, $content = null)
{  

   $atts = shortcode_atts(
      array(
         'height' => '',
         'locations_list' => '',
         'zoom' => '',
         'position' => ''
      ), $atts);
      
   $str = '';

   $nicdark_zoom = ( $atts['zoom'] == '' ) ? '14' : ''.$atts['zoom'].'';
   $nicdark_position = ( $atts['position'] == '' ) ? '40.726164, -73.993886' : ''.$atts['position'].'';
   $nicdark_theme_directory = get_template_directory_uri();

   $str .= '


          <!--START ALL JAVASCRIPT INCLUSION-->
          <!--first-->
          <script src="http://maps.googleapis.com/maps/api/js?v=3&amp;sensor=false"></script>
          

          <!--all datas-->
          <script type="text/javascript">      
          

            <!--pass images-->
            var theme_directory = "'.$nicdark_theme_directory.'";
            var markerImage = new google.maps.MarkerImage("'.$nicdark_theme_directory.'/img/gmaps/marker.png", null, null, null, new google.maps.Size(45,45));


            var data = {
             "photos": [

             ';

              $args = array(
                'post_type' => 'locations',
                'orderby' => 'name',
                'order' => 'ASC',
                'posts_per_page' => -1

              );
              $the_query = new WP_Query( $args ); 
              global $redux_demo;

              while ( $the_query->have_posts() ) : $the_query->the_post();

                //image
                $post_id = get_the_ID();
                $attachment_id = get_post_thumbnail_id( $post_id );
                $image_attributes = wp_get_attachment_image_src( $attachment_id, 'large' );
                
                //coordinates
                $locations_coordinates = $redux_demo['metabox_location_coordinates'];
                $locations_coordinates_ll = explode(",", $locations_coordinates);
                if ($locations_coordinates == '') {
                  $locations_coordinates_ll[0]  = '-37.854861';
                  $locations_coordinates_ll[1]  = '145.126308';
                }

                //datas
                $locations_title = $redux_demo['metabox_location_title'];
                $locations_subtitle = $redux_demo['metabox_location_subtitle'];
                $locations_description = $redux_demo['metabox_location_description'];

                $str .= '{"photo_id": '.$post_id.', "photo_title": "'.get_the_title().'", "locations_title": "'.$locations_title.'", "locations_subtitle": "'.$locations_subtitle.'", "locations_description": "'.$locations_description.'", "photo_url": "'.$image_attributes[0].'", "latitude": '.$locations_coordinates_ll[0].', "longitude": '.$locations_coordinates_ll[1].', "width": 500, "height": 375 },';
            
              endwhile;

               
              $qnt_results_posts = $the_query->found_posts;

          $str .= ']}

          var MY_MAPTYPE_ID = "custom_style";
          var nicdark_center_map = new google.maps.LatLng('.$nicdark_position.');
          var nicdark_zoom = '.$nicdark_zoom.';


          var options = {
            "zoom": nicdark_zoom,
            "center": nicdark_center_map,
            "mapTypeId": MY_MAPTYPE_ID,

            mapTypeControl: false,

            disableDefaultUI: true,
            scrollwheel: false

          };


          </script>

          <!--plugin-->
          <script src="'.$nicdark_theme_directory.'/js/plugins/gmaps/markerclusterer.js" type="text/javascript"></script>
          <!--Settings-->
          <script type="text/javascript" src="'.$nicdark_theme_directory.'/js/plugins/gmaps/speed_test.js"></script>
          <!--call the function settings-->
          <script type="text/javascript">      
          google.maps.event.addDomListener(window, "load", speedTest.init);
          </script>
          <!--END ALL JAVASCRIPT INCLUSION-->


          <!--ALL HTML CODE-->
          <!--Params-->
          <div style="display:none;">
            <input type="checkbox" checked="checked" id="usegmm"/>
            <select id="nummarkers"><option value="'.$qnt_results_posts.'" selected="selected">'.$qnt_results_posts.'</option><option value="3">3</option></select>
            <span>Time used: <span id="timetaken"></span> ms</span>
          </div>

          <div class="nicdark_focus">
          ';


          if ( $atts['locations_list'] == 0 ){

            $str .= '
              <div style="height:'.$atts['height'].'px; display:none;" class="nicdark_bg_greydark nicdark_focus nicdark_width_percentage20 nicdark_nicescrool" id="markerlist"></div>
              <div class="nicdark_focus nicdark_width_percentage100">
                <div class="nicdark_bg_bluedark" id="nicdark_gmaps_plus"><i class="icon-plus"></i></div>
                <div class="nicdark_bg_blue" id="nicdark_gmaps_minus"><i class="icon-minus"></i></div>
                <div class="nicdark_focus nicdark_width_percentage100" style="height:'.$atts['height'].'px;" id="map"></div>
              </div>
            ';

          }else{

            $str .= '
              <div style="height:'.$atts['height'].'px;" class="nicdark_bg_greydark nicdark_focus nicdark_width_percentage20 nicdark_nicescrool" id="markerlist"></div>
              <div class="nicdark_focus nicdark_width_percentage80 nicdark_relative">
                <div class="nicdark_bg_bluedark" id="nicdark_gmaps_plus"><i class="icon-plus"></i></div>
                <div class="nicdark_bg_blue" id="nicdark_gmaps_minus"><i class="icon-minus"></i></div>
                <div class="nicdark_focus nicdark_width_percentage100" style="height:'.$atts['height'].'px;" id="map"></div>
              </div>
            ';

          }
          

          $str .= '
          </div>
          <!--END HTML CODE-->


   ';

   return apply_filters('uds_shortcode_out_filter', $str);
}

//vc
add_action( 'vc_before_init', 'nicdark_gmaps_markers' );
function nicdark_gmaps_markers() {
   vc_map( array(
      "name" => __( "Gmaps Markers", "nicdark-shortcodes" ),
      "base" => "gmaps_markers_nd",
      'description' => __( 'Add title', 'nicdark-shortcodes' ),
      'show_settings_on_create' => true,
      "icon" => get_template_directory_uri() . "/vc_extend/gmaps.png",
      "class" => "",
      "category" => __( "Nicdark Shortcodes", "nicdark-shortcodes"),
      "params" => array(

         array(
            "type" => "textfield",
            "class" => "",
            "heading" => __( "Height", "nicdark-shortcodes" ),
            "param_name" => "height",
            'admin_label' => true,
            "value" => __( "", "nicdark-shortcodes" ),
            "description" => __( "Insert the map height", "nicdark-shortcodes" )
         ),
          array(
           'type' => 'checkbox',
            'heading' => "Insert locations List",
            'param_name' => 'locations_list',
            'value' => array( __( '', 'js_composer' ) => '1' ),
            'description' => __( "Check for insert locations list on the map", "nicdark-shortcodes" )
        ),
        array(
          "type" => "textfield",
          "class" => "",
          "heading" => __( "Zoom Map", "nicdark-shortcodes" ),
          "param_name" => "zoom",
          "description" => __( "Insert a different zoom of the map ( Ex: from 0 to 14 )", "nicdark-shortcodes" )
       ),
        array(
          "type" => "textfield",
          "class" => "",
          "heading" => __( "Center Map", "nicdark-shortcodes" ),
          "param_name" => "position",
          "description" => __( "Insert a different center position coordinates of the map ( Ex: 34.263079, 16.993682 )", "nicdark-shortcodes" )
       )

      )
   ) );
}
//end shortcode