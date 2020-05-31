<?php

/*
Element Description: vcCharacterFilter
*/
 
// Element Class 
class vcCharacterFilter extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_custom_mapping' ) );
        add_shortcode( 'custom_pair_icon', array( $this, 'vc_custom_html' ) );
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
                'name' => __('Character Filter', 'dc'),
                'base' => 'custom_pair_icon',
                'description' => __('Character Filter', 'dc'), 
                'category' => __('DC Core Elements', 'dc'),   
                'icon' => get_template_directory_uri() . '/vc_icons/vc.character.filter.png',            
                'params' => array(   
                         
                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'field-class',
                        'heading' => __( "#1 Title", 'dc' ),
                        'param_name' => 'icon_title1',
                        'value' => __( "#1 Title", 'dc' ),
                        'description' => __( "#1 Title", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    ),

                ),
            )
        );                                
        
    }
       
    // Element HTML
    public function vc_custom_html( $atts ) {
        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'icon_title1'   => '',
                ), 
                $atts
            )
        );		
        ob_start();
        
        $args = array(
            'post_type' => 'character',
            'post_status' => 'publish'
        );
        
        $query = new WP_Query( $args );
        ?>
            <div class="dc-character-loop">
                <?php if ( $query->have_posts() ) { ?>
                    <h2 class="dc-heading">
                        <div class="dc-heading__secondary">
                            <span>Character</span>
                        </div>
                        <div class="dc-heading__primary">
                            <span>List</span>
                        </div>
                    </h2>
                    <div class="row dc-character-loop_content">
                        <?php while ( $query->have_posts() ) { ?>
                            <?php 
                                $query->the_post(); 
                                $title = get_the_title();
                                $permalink = get_the_permalink();
                                $excerpt_content = get_the_excerpt();
                                $featured_img_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
                            ?>
                                <div class="col-md-4">
                                    <div class="card">
                                        <?php if(has_post_thumbnail()){ ?>
                                            <img class="card-img-top" src="<?php echo $featured_img_url[0]; ?>" alt="<?php echo $title; ?>">
                                        <?php } ?>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?php echo $title; ?>
                                            </h5>
                                            <p class="card-text">
                                                <?php echo $excerpt_content; ?>
                                            </p>
                                            <a href="<?php echo $permalink; ?>" class="btn btn-primary">
                                                <?php echo __('Read more', 'dc');  ?>
                                            </a>
                                        </div>
                                    </div>
                                </div> 
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php wp_reset_postdata(); ?>
            </div>
        <?php
        
        return ob_get_clean();
    }
} // End Element Class
 
// Element Class Init
new vcCharacterFilter(); 