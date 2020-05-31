<?php

/*
Element Description: vcCharacterFilter
*/
 
// Element Class 
class vcCharacterFilter extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_custom_mapping' ) );
        add_shortcode( 'dc_character_filter', array( $this, 'vc_custom_html' ) );
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
                'base' => 'dc_character_filter',
                'description' => __('Character Filter', 'dc'), 
                'category' => __('DC Core Elements', 'dc'),   
                'icon' => get_template_directory_uri() . '/vc_icons/vc.character.filter.png',            
                'params' => array(   
                         
                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Title", 'dc' ),
                        'param_name' => 'character_title',
                        'value' => __( "Title", 'dc' ),
                        'description' => __( "Title", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    ),
                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Subtitle", 'dc' ),
                        'param_name' => 'character_subtitle',
                        'value' => __( "Subtitle", 'dc' ),
                        'description' => __( "Subtitle", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    ),
                    array(
                        'type' => 'checkbox',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Type Filter", 'dc' ),
                        'param_name' => 'type_filter',
                        'value' => __( "Type Filter", 'dc' ),
                        'description' => __( "Type Filter", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    ),
                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Character Count", 'dc' ),
                        'param_name' => 'character_count',
                        'value' => __( "5", 'dc' ),
                        'description' => __( "Character Count", 'dc' ),
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
                    'character_title'   => '',
                    'character_subtitle'   => '',
                    'character_count' => '',
                    'type_filter' => ''
                ), 
                $atts
            )
        );		
        ob_start();

        $posts_per_page = is_numeric(trim($character_count)) ? $character_count : 5;
        
        $args = array(
            'post_type' => 'character',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page
        );
        
        $query = new WP_Query( $args );
        ?>
            <div class="dc-character-loop">
                <?php if ( $query->have_posts() ) { ?>
                    <h2 class="dc-heading">
                        <div class="dc-heading__secondary">
                            <span><?php echo $character_title; ?></span>
                        </div>
                        <div class="dc-heading__primary">
                            <span><?php echo $character_subtitle; ?></span>
                        </div>
                    </h2>
                    <?php if($type_filter){ ?>
                        <div class="dc-character-filter">
                            <div class="dc-character-count">
                                <h2> <?php echo __( "Characters", 'dc' ); ?>
                                <span> (<?php echo $query->post_count; ?>) </span> </h2>
                            </div>
                            <div class="dc-character-filter">
                                <form class="dc-character-form">
                                    <input type="text" placeholder="<?php echo __( "ej Batman..", 'dc' ); ?> ">
                                    <select>
                                        <option> Villain </option>
                                        <option> Hero </option>
                                    </select>
                                    <button type="submit" value="search">
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="dc-character-loop_content">
                        <div class="dc-character-preloader">
                            <div class="dc-character-icon">
                              <i class="fas fa-sync fa-spin"></i>
                            </div>
                        </div>
                        <?php while ( $query->have_posts() ) { ?>
                            <?php 
                                $query->the_post(); 
                                $title = get_the_title();
                                $permalink = get_the_permalink();
                                $featured_img_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
                                $last_action = get_post_meta(get_the_ID(), 'last_action' , true ); 
                                $source_powers = get_post_meta( get_the_ID(), 'source_powers', true ); 
                                $weakness = get_post_meta( get_the_ID(), 'weakness', true ); 
                                $taxonomies = get_the_terms( get_the_ID(), 'character_type' );
                                $taxonomy_icon = resolve_character_icon($taxonomies);
                            ?>
                                <a class="dc-character-item" href="<?php echo $permalink; ?>">
                                    <div class="_dc-character-item">
                                        <div class="card">
                                            <?php if(has_post_thumbnail()){ ?>
                                                <img class="card-img-top" src="<?php echo $featured_img_url[0]; ?>" alt="<?php echo $title; ?>">
                                            <?php } ?>
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <i class="fas <?php echo $taxonomy_icon; ?>"></i>
                                                    <?php echo $title; ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div> 
                                </a>
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