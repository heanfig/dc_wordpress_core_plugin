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
        add_action( 'wp_ajax_dc_character_filter', array( $this, 'dc_character_filter' ) );
        add_action( 'wp_ajax_nopriv_dc_character_filter', array( $this, 'dc_character_filter' ) );
        add_action( 'wp_head', array( $this, 'custom_tpl_character' ) );
        $this->initAssets();
    }

    public function custom_tpl_character(){
        ob_start();
    ?>
        <script type="text/plain" id="custom_tpl_character">
            <div class="dc-character-preloader">
                <div class="dc-character-icon">
                    <i class="fas fa-sync fa-spin" aria-hidden="true"></i>
                </div>
            </div>
            <% if(characters.length === 0) { %>
                <div class="dc-character-not_found">
                    <span>Not found</span>
                </div>
            <% }; %>
            <% _.forEach(characters, function(user) { %>
                <a class="dc-character-item" href="<%- user.link %>">
                    <div class="_dc-character-item">
                        <div class="card">
                            <img class="card-img-top" src="<%- user.thumb ? user.thumb[0] : '' %>" alt="<%- user.title %>">
                            <div class="card-body">
                                <h5 class="card-title">
                                <i class="fas fa-mask" aria-hidden="true"></i>
                                    <%- user.title %>                                               
                                </h5>
                            </div>
                        </div>
                    </div>
                </a>
            <% }); %>
        </script>
    <?php
    }

    public function initAssets(){
        wp_enqueue_script( 'dc-theme', plugins_url( '../public/vc.character.filter.js', __FILE__ ), array('jquery'), _S_VERSION, true );
        wp_localize_script('dc-theme', 'ajax_script' ,array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

    public function dc_character_filter(){
        $character_type = $_GET["character_type"];
        $character_search = $_GET["character_search"];

        $args = array(
            'post_type' => 'character',
            'post_status' => 'publish',
            'posts_per_page' => 5
        );

        if( !empty($character_search) ){
            $args['s'] = $character_search;
        }

        if( !empty($character_type) && $character_type != 'all'){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'character_type',
                    'field' => 'slug',
                    'terms' => $character_type,
                    'operator' => 'IN',
                    'include_children' => true,
                )
            );
        }
        $results = array();
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $title = get_the_title();
                
                $featured_img_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
                $taxonomies = get_the_terms( get_the_ID(), 'character_type' );
                $taxonomy_icon = resolve_character_icon($taxonomies);
                $permalink = get_the_permalink();
                
                $results[] = array(
                    'title' => $title,
                    'thumb' => $featured_img_url,
                    'link'  => $permalink,
                    'tax'   => $taxonomy_icon
                );
            }
        }

        echo json_encode($results);

        die;
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
                        'type' => 'checkbox',
                        'holder' => 'div',
                        'class' => '',
                        'heading' => __( "Hall of Fame (only with tag hall) ", 'dc' ),
                        'param_name' => 'hall_of_fame',
                        'value' => __( "Hall of Fame (only with tag hall)", 'dc' ),
                        'description' => __( "Hall of Fame (only with tag hall)", 'dc' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'Field group',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __( "Hall of Fame (only with tag hall) ", 'dc' ),
                        'param_name' => 'character_type',
                        'admin_label' => false,
                        'value'       => array(
                            'villain' => 'villain',
                            'hero'    => 'hero',
                            'all'     => 'all'
                        ),
                        'description' => __( "Select Character Type", 'dc' ),
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
                    'type_filter' => '',
                    'character_type' => ''
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
        
        if( !empty($character_type) && $character_type != 'all'){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'character_type',
                    'field' => 'slug',
                    'terms' => $character_type,
                    'operator' => 'IN',
                    'include_children' => true,
                )
            );
        }

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
                                <span class="dc-character-count-number"> (<?php echo $query->post_count; ?>) </span> </h2>
                            </div>
                            <div class="dc-character-filter">
                                <form class="dc-character-form">
                                    <?php
                                        $terms = get_terms( array(
                                            'taxonomy' => 'character_type'
                                        ) );
                                    ?>
                                    <input class="dc-input character_search" type="text" placeholder="<?php echo __( "ej Batman..", 'dc' ); ?> ">
                                    <select class="dc-input dc-select character_type">
                                        <?php foreach($terms as $term){ ?>
                                            <option value="<?php echo $term->slug; ?>"> 
                                                <?php echo $term->name; ?> 
                                            </option>
                                        <?php } ?>
                                            <option value="all"> 
                                                <?php echo __( "All", 'dc' ); ?>
                                            </option>
                                    </select>
                                    <button type="submit" class="dc-input dc_action">
                                        <i class="fas fa-search"></i> 
                                        <?php echo __( "Search", 'dc' ); ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="dc-character-loop_content">
                        <div class="dc-character-preloader" style="display:none">
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
                <?php }else{ ?>
                    <div class="dc-character-loop_content">
                        <div class="dc-character-not_found">
                            <span>Not found</span>
                        </div>
                    </div>
                <?php }?>
                <?php wp_reset_postdata(); ?>
            </div>
        <?php
        
        return ob_get_clean();
    }
} // End Element Class
 
// Element Class Init
new vcCharacterFilter(); 