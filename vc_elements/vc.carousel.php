<?php

/*
Element Description: vcHeading
*/
 
// Element Class 
class vcHeading extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_custom_mapping' ) );
        add_shortcode( 'dc_carousel', array( $this, 'vc_custom_html' ) );
    }
     
    // Element Mapping
    public function vc_custom_mapping() {
         
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }
         
        // Map the block with vc_map()
        vc_map( 
            array(
                'name' => __('DC Carousel', 'dc'),
                'base' => 'dc_carousel',
                'description' => __('DC Carousel', 'dc'), 
                'category' => __('DC Core Elements', 'dc'),   
                'icon' => get_template_directory_uri().'/assets/img/vc-icon.png',            
                'params' => array(   
                    array(
                        'type' => 'attach_images',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Gallery Items", 'dc' ),
                        'param_name' => 'gallery_items',
                        'value' => __( "Gallery Items", 'dc' ),
                        'description' => __( "Gallery Items", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    )
                )
            )
        );                                
        
    }
       
    // Element HTML
    public function vc_custom_html( $atts ) {
        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'gallery_items'   => '',
                ), 
                $atts
            )
        );		
        ob_start();
        ?>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active" style="background-image: url('https://i.redd.it/af5uskv42v3z.jpg')">
                        <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-4">First Slide</h2>
                        <p class="lead">This is a description for the first slide.</p>
                        </div>
                    </div>
                    <div class="carousel-item" style="background-image: url('https://i.pinimg.com/originals/90/5d/5e/905d5e54da2cc37a6f7c6f6db124402f.jpg')">
                        <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-4">First Slide</h2>
                        <p class="lead">This is a description for the first slide.</p>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                    </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                    </a>
            </div>
        <?php
        return ob_get_clean();
    }
} // End Element Class
 
// Element Class Init
new vcHeading(); 