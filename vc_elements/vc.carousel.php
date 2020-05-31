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
                    ),
                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Carousel height", 'dc' ),
                        'param_name' => 'carousel_min_height',
                        'value' => __( "300", 'dc' ),
                        'description' => __( "Carousel height", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    ),
                    array(
                        'type' => 'checkbox',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Show overlay", 'dc' ),
                        'param_name' => 'show_overlay',
                        'value' => __( "Show overlay", 'dc' ),
                        'description' => __( "Show overlay", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    ),
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
                    'carousel_min_height' => '',
                    'show_overlay' => ''
                ), 
                $atts
            )
        );		
        ob_start();
        
        $gallery_items = explode( ",", $gallery_items );
        $carousel_min_height = is_numeric(trim($carousel_min_height)) ? $carousel_min_height : 400;
        $overlay_class = !$show_overlay ? 'no_overlay' : '';
        ?>
            <div id="dcCustomCarousel" class="carousel dc-carousel slide <?php echo $overlay_class; ?>" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php if( count($gallery_items) > 1){ ?>
                        <?php foreach($gallery_items as $key => $item ){  ?>
                            <?php $activeClass = $key === 0 ? 'active' : ''; ?>
                            <li data-target="#dcCustomCarousel" data-slide-to="<?php echo $key; ?>" class="<?php echo $activeClass; ?>"></li>
                        <?php } ?>
                    <?php } ?>
                </ol>
                <div class="carousel-inner" role="listbox" style="height:<?php echo $carousel_min_height; ?>px">
                    <?php 
                        foreach($gallery_items as $key => $item){ 
                        $gallery_img = wp_get_attachment_image_src( $item, 'full' );
                        $attachment_title = get_the_title( $item );
                        $activeClass = $key === 0 ? 'active' : '';
                    ?>
                        <div class="carousel-item <?php echo $activeClass; ?>" style="height:<?php echo $carousel_min_height; ?>px;background-image: url('<?php echo $gallery_img[0]; ?>')">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="display-4"><?php echo $attachment_title; ?></h2>
                                <p class="lead">&nbsp;</p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if( count($gallery_items) > 1){ ?>
                    <a class="carousel-control-prev" href="#dcCustomCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#dcCustomCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                <?php } ?>
            </div>
        <?php
        return ob_get_clean();
    }
} // End Element Class
 
// Element Class Init
new vcHeading(); 