<?php
/*---------------------------------------------------------
Plugin Name: WP Infinite Scroll Post 
Author: carlosramosweb
Author URI: http://criacaocriativa.com
Donate link: http://donate.criacaocriativa.com/
Description: Usando o Ajax é feito rolagem infinita na lista de posts do WordPress.
Text Domain: wp-infinite-scroll-post
Domain Path: /languages/
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
------------------------------------------------------------*/

/*
 * Sair se o arquivo for acessado diretamente
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'register_wp_infinite_scroll_post_submenu_page', 70 );
function register_wp_infinite_scroll_post_submenu_page() {
    add_submenu_page( 'edit.php', 'Infinite Scroll Post', 'Infinite Scroll Post', 'manage_options', 'wp-infinite-scroll-post', 'wp_infinite_scroll_post_callback' ); 
}

function wp_infinite_scroll_post_callback() { 

	 if( isset( $_POST['_update'] ) && isset( $_POST['_wpnonce'] ) ) {
		$_update = sanitize_text_field( $_POST['_update'] );
		$_wpnonce = sanitize_text_field( $_POST['_wpnonce'] );
	 }
	 
	$message = "";
	if( isset( $_wpnonce ) && isset( $_update )) {
		if ( ! wp_verify_nonce( $_wpnonce, "wp-infinite-scroll-post-update" ) ) {
			$message = "error";			
		} else {
			if( isset( $_POST['wp_infinite_scroll_post_enabled'] ) ) {
				$wp_infinite_scroll_post_enabled = sanitize_text_field( $_POST['wp_infinite_scroll_post_enabled'] );			
				update_option( 'wp_infinite_scroll_post_enabled', $wp_infinite_scroll_post_enabled );
			} else {
				update_option( 'wp_infinite_scroll_post_enabled', '' );
			}
			
			update_option( 'wp_infinite_scroll_post_posts', 'yes' );	
			
			if( isset( $_POST['wp_infinite_scroll_post_categories'] ) ) {
				$wp_infinite_scroll_post_categories = sanitize_text_field( $_POST['wp_infinite_scroll_post_categories'] );			
				update_option( 'wp_infinite_scroll_post_categories', $wp_infinite_scroll_post_categories );
			} else {
				update_option( 'wp_infinite_scroll_post_categories', '' );
			}
			
			if( isset( $_POST['posts_per_page'] ) ) {
				$posts_per_page = sanitize_text_field( $_POST['posts_per_page'] );			
				update_option( 'posts_per_page', $posts_per_page );
			} else {
				update_option( 'posts_per_page', '10' );
			}
			
			$message = "updated";	
		}
	}
	$wp_infinite_scroll_post_enabled = esc_attr( get_option( 'wp_infinite_scroll_post_enabled' ) );
	$wp_infinite_scroll_post_posts = esc_attr( get_option( 'wp_infinite_scroll_post_posts' ) );
	$wp_infinite_scroll_post_categories = esc_attr( get_option( 'wp_infinite_scroll_post_categories' ) );
	$posts_per_page = esc_attr( get_option( 'posts_per_page' ) );	
	
	
?>
<div id="wpwrap">
<!--start-->
    <h1><?php echo __( 'Infinite Scroll Post', 'wp-infinite-scroll-post' ); ?></h1>
    <p><?php echo __( 'Sistema de <strong>Infinite Scroll</strong> para posts do WordPress.', 'wp-infinite-scroll-post' ); ?><p/>
    
    <!--start message-->
    <?php if( isset( $message ) ) { ?>
        <div class="wrap">
    	<?php if( $message == "updated" ) { ?>
            <div id="message" class="updated notice is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Atualizações feita com sucesso!', 'wp-infinite-scroll-post' ) ; ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Dispensar este aviso.', 'wp-infinite-scroll-post' ) ; ?>
                    </span>
                </button>
            </div>
            <?php } ?>
            <?php if( $message == "error" ) { ?>
            <div id="message" class="updated error is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Erro! Não conseguimos fazer as atualizações!', 'wp-infinite-scroll-post' ) ; ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Dispensar este aviso.', 'wp-infinite-scroll-post' ) ; ?>
                    </span>
                </button>
            </div>
        <?php } ?>
    	</div>
    <?php } ?>
    <!--end message-->
    
    <div class="wrap woocommerce">
    		<!--nav-->
            <nav class="nav-tab-wrapper wc-nav-tab-wrapper">
            <?php
			if( isset( $_GET['tab'] ) ) {
				$tab = esc_attr( $_GET['tab'] );
			}
			?>
           		<a href="<?php echo esc_url( admin_url( 'edit.php?page=wp-infinite-scroll-post' ) ); ?>" class="nav-tab <?php if( $tab == "" ) { echo "nav-tab-active"; }; ?>">
					<?php echo __( 'Configurações', 'wp-infinite-scroll-post' ) ; ?>
                </a>
            	<a href="<?php echo esc_url( admin_url( 'edit.php?page=wp-infinite-scroll-post&tab=wpn-doacao' ) ); ?>" class="nav-tab <?php if( $tab == "wpn-doacao") { echo "nav-tab-active"; }; ?>">
					<?php echo __( 'Doação', 'wp-infinite-scroll-post' ) ; ?>
                </a>
            </nav>
            <!--end nav-->
            <?php if(!isset($tab)) { ?>
            <!--form-->
        	<form method="POST" id="mainform" name="mainform" enctype="multipart/form-data">
                <!---->
                <table class="form-table">
                    <tbody>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Habilita/Desabilita', 'wp-infinite-scroll-post' ) ; ?>
                                </label>
                            </th>
                            <td>
                                <label>
                                    <input type="checkbox" name="wp_infinite_scroll_post_enabled" value="yes" <?php if( $wp_infinite_scroll_post_enabled == "yes" ) { echo 'checked="checked"'; } ?>>
                                    <?php echo __( 'Ativar notificações', 'wp-infinite-scroll-post' ) ; ?>
                                </label>
                           </td>
                        </tr>  
                        <!----->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Nº de posts', 'wp-infinite-scroll-post' ) ; ?>
                                </label>
                            </th>
                            <td>
                                <label>
                                    <input type="number" style=" width:70px;" name="posts_per_page" value="<?php echo $posts_per_page; ?>">
                                </label>
                                <span>
                                    <span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: middle;"></span>
                                    <?php echo __( 'Número de posts que será apresenado por vez.', 'wp-infinite-scroll-post' ) ; ?>
                                </span>
                            </td>
                        </tr>
                        <!---->						
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Páginas', 'wp-infinite-scroll-post' ) ; ?>
                                </label>
                            </th>
                            <td>
								<hr/>
                                <label>
                                    <input type="checkbox" name="wp_infinite_scroll_post_posts" value="yes" checked="checked" disabled>
                                    <?php echo __( 'Posts', 'wp-infinite-scroll-post' ) ; ?>
                                </label>
                                <hr/>
                                <label>
                                    <input type="checkbox" name="wp_infinite_scroll_post_categories" value="yes" <?php if(esc_attr($wp_infinite_scroll_post_categories) == "yes") { echo 'checked="checked"'; } ?>>
                                    <?php echo __( 'Categorias', 'wp-infinite-scroll-post' ) ; ?>
                                </label>
								<hr/>
                            </td>
                        </tr>
                        <!---					
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Fixar categoria', 'wp-infinite-scroll-post' ) ; ?>
                                </label>
                            </th>
                            <td>
								<?php $categories = get_categories(); ?>
								<select name="multiSelect" multiple style=" width:400px;">
								<?php foreach ( $categories as $category ) { ?>
									<option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
								<?php } ?>
								</select>
								<br/>
                                <span>
                                    <span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: middle;"></span>
                                    <?php echo __( 'Ao escolher uma ou mais o sistema fica travado no loop dos posts.', 'wp-infinite-scroll-post' ) ; ?>
                                </span>
                            </td>
                        </tr>
                        --->
                   </tbody>
                </table>
                <!---->
                <hr/>
                <div class="submit">
                    <button class="button-primary" type="submit"><?php echo __( 'Salvar Alterações', 'wp-infinite-scroll-post' ) ; ?></button>
                    <input type="hidden" name="_update" value="1">
                    <input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-infinite-scroll-post-update' ) ); ?>">
                    <!---->
                    <span>
                        <span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: middle;"></span>
                        <?php echo __( 'Não esqueça de <strong>salvar suas alterações</strong>.', 'wp-infinite-scroll-post' ) ; ?>
                    </span>
                </div>
                <!---->  
            </form>
            <!--end form-->   
        <?php } else if($tab == "wpn-doacao") { ?>
            <h2><?php echo __( 'Oba! Fique a vontade.', 'wp-infinite-scroll-post' ) ; ?></h2>
        	<div class="">
            	<p><?php echo __( '<strong>É totalmente seguro!</strong> Ajude a manter esse plugin sempre atualizado com seu incentivo.', 'wp-infinite-scroll-post' ) ; ?></p>
            </div>
			<!---->
            <table class="form-table">
                <tbody>
                    <!---->
                    <tr valign="top">
                        <th scope="row">
                            <button class="button-primary" onClick="window.open('http://donate.criacaocriativa.com')">
                            <?php echo __( 'Quero doar agora', 'wp-infinite-scroll-post' ) ; ?>
                            </button>
                        </th>
                        <td>
                            <label>
							<span>
								<span class="dashicons dashicons-warning" style="vertical-align: middle;"></span>
								<?php echo __( 'Você será direcionado para um site seguro.', 'wp-infinite-scroll-post' ) ; ?> 
							</span> 
                            </label>
                        </td>
                    </tr>
                    <!---->
                </tbody>
            </table>
            <!---->
        <?php } ?>
        <!---->
     <!---->      
     </div>
    
</div>
<?php
}

function wp_infinite_scroll_post_scripts() {
	wp_enqueue_script( 'code-jquery-wp-infinite-scroll', 'https://code.jquery.com/jquery-3.5.1.js', array(), '3.5.1', true );
}
add_action( 'wp_enqueue_scripts', 'wp_infinite_scroll_post_scripts' );

if( ! is_admin() ) {
	$wp_infinite_scroll_post_enabled = esc_attr( get_option( 'wp_infinite_scroll_post_enabled' ) );
	if( $wp_infinite_scroll_post_enabled == "yes" ) {
		
	//
//	function wp_infinite_scroll_post_loop( $query ) {
//		if ( $query->is_home() && $query->is_main_query() ) {
//			$query->set( 'cat', array(29) );
//		}
//	}
//	add_action( 'pre_get_posts', 'wp_infinite_scroll_post_loop' );
	
	//
	add_action('wp_footer', 'wp_infinite_scroll_post_scripts_head');
	
	function wp_infinite_scroll_post_scripts_head() {
		global $post;
		$wp_infinite_scroll_post_posts = esc_attr( get_option( 'wp_infinite_scroll_post_posts' ) );
		$wp_infinite_scroll_post_categories = esc_attr( get_option( 'wp_infinite_scroll_post_categories' ) );
		$wp_infinite_scroll_post_posts_per_page = esc_attr( get_option( 'wp_infinite_scroll_post_posts_per_page' ) );
		
		if( ! is_admin() && 'post' == get_post_type() && ! is_single() && !is_category() 
			or  ! is_admin() && 'post' == get_post_type() && is_category() && $wp_infinite_scroll_post_categories == "yes" ) {	
?>
<style>
.navigation.pagination { display:none; }
#nav-below.paging-navigation { display:none; }
.alert-secondary { color: #383d41; background-color: #e2e3e5; border-color: #d6d8db; }
.alert { position: relative; padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; }
#loading35 { display:block; margin:0 auto; }
</style>
<?php 
$posts_per_page = get_option( 'posts_per_page' );
if( $wp_infinite_scroll_post_categories == "yes" && is_category() ) {
	$categories = get_the_category();
	$posts = get_posts(
		array(
			'posts_per_page' => -1,
			'post_status'   => 'any',
			'post_type'     => 'post',
			'category'      => $categories[0]->term_id,
		)
	);
} else {
	$posts = get_posts(
		array(
			'posts_per_page'=> -1,
			'post_status'   => 'any',
			'post_type'     => 'post',
			//'category'      => array(29, 20),
		)
	);
}
?>
<script>
jQuery( "#main" ).append( '<div id="post-last">&nbsp;</div>' );
jQuery( "#nav-below" ).remove();

function show_infinite_scroll_posts() {	
	jQuery( "#loading35" ).remove();
	jQuery( "#main" ).append( '<img src="<?php echo esc_url( plugins_url( 'images/loading35px.gif', __FILE__ ) ); ?>" id="loading35">' );
	
	var count_article = document.querySelectorAll(".post").length;	
	var number = Math.round( ( count_article / <?php echo $posts_per_page; ?> ) );
	var number_int = ( count_article / <?php echo $posts_per_page; ?> );
	
	if( number > 0 ) {
		number += 1;
	}
	
	if( number > 1 && count_article < <?php echo count( $posts ); ?> ) {
		jQuery( "#post-last" ).remove();	
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			data: {
				'action': 'wp_infinite_scroll_post_show',
				'paged': number,
				'posts_per_page': '<?php echo $posts_per_page; ?>',
				<?php 
				if( $wp_infinite_scroll_post_categories == "yes" && is_category() ) { 
				$categories = get_the_category();
				?>
				'category': '<?php echo $categories[0]->term_id; ?>',
				<?php } else { ?>
				'category': '',
				<?php } ?>
			},
			success:function( response ) {
				if( response != '' ) {
					jQuery( "#main" ).append( response );
					jQuery( "#post-last" ).remove();
					jQuery( "#main" ).append( '<div id="post-last">&nbsp;</div>' );	
					jQuery( "#loading35" ).remove();			
				} else {
					jQuery( "#loading35" ).remove();
					jQuery( "#post-last" ).remove();
					jQuery( "#main" ).append( '<div class="alert alert-secondary"><?php echo __( 'Não encontramos mais posts.', 'wp-infinite-scroll-post' ) ; ?></div>' );
				}
			}
		});
	} else {
		jQuery( "#loading35" ).remove();
		jQuery( "#post-last" ).remove();
		jQuery( "#main" ).append( '<div class="alert alert-secondary"><?php echo __( 'Não encontramos mais posts.', 'wp-infinite-scroll-post' ) ; ?></div>' );
	}
};


jQuery(window).scroll(function() {
	var window_scrollTop = jQuery(window).scrollTop();
	var post_last = document.getElementById("post-last");
	
	window_scrollTop += 550;
	if( window_scrollTop > post_last.offsetTop ) {
		show_infinite_scroll_posts();
	}
});
</script>
<?php
			}
		}
	}
}

function wp_infinite_scroll_post_show_callback() {
    global $post;
	if( isset( $_POST['paged'] ) ) {
		$paged = $_POST['paged'];
	}	
	
	if( isset( $_POST['category'] ) ) {
		$category = $_POST['category'];
	}	
	
	if( isset( $_POST['posts_per_page'] ) ) {
		$posts_per_page = $_POST['posts_per_page'];
	}
	
	if( ! empty( $category ) ) {
		
		$posts = get_posts(
			array(
				'posts_per_page' => $posts_per_page,
				'post_status'   => 'any',
				'post_type'     => 'post',
				'paged'     	=> $paged,
				'category'      => $category,
			)
		);
	} else {
		$posts = get_posts(
			array(
				'posts_per_page' => $posts_per_page,
				'post_status'   => 'any',
				'post_type'     => 'post',
				'paged'     	=> $paged,
				//'category'      => array(29, 20),
			)
		);
	}
	if( $posts ) {
		$result = "";
		foreach ( $posts as $post ) { setup_postdata($post);
			// start foreach
			$post_class = get_post_class();
			$get_permalink = esc_url( get_permalink() );
			$get_the_title = get_the_title();
			$get_the_content = substr( get_the_content(), 0, 300);
			$featured_img_url = esc_url( get_the_post_thumbnail_url( $post->ID ) );
			
			$categories = get_categories( array(
				'orderby' => 'name',
				'order'   => 'ASC'
			) );
			 
			foreach( $categories as $category ) {
				$get_category_link = esc_url( get_category_link( $category->term_id ) );
				$list_category .= '<a href="' . $get_category_link . '" rel="tag">' . $category->name . '</a>, ';
			}
			
			$tags = get_tags();
			foreach ( $tags as $tag ) {
				$get_tag_link = get_tag_link( $tag->term_id );
				$list_tags .= '<a href="' . $get_tag_link . '" rel="tag">' . $tag->name . '</a>, ';
			}
			
			foreach ( $post_class as $class ) {
				$class_ .=  $class . " ";
			}
			$result .= '<article id="post-' . $post->ID .'" class="post ' . $class_ . '" itemtype="https://schema.org/CreativeWork" itemscope="">';
			$result .= '<div class="inside-article">';
			
			$result .= '<header class="entry-header">';
			$result .= '<h2 class="entry-title" itemprop="headline"><a href="' . $get_permalink . '" rel="bookmark">' . $get_the_title . '</a></h2>';
			$result .= '<div class="entry-meta"></div>';			
			$result .= '</header>';
			
			if ( !empty( $featured_img_url ) ) {
				$result .= '<div class="post-image">';
				$result .= '<a href="' . $get_permalink . '">';
				$result .= '<img src="' . $featured_img_url . '" class="attachment-full size-full wp-post-image lazyloaded" alt="" itemprop="image">';
				$result .= '</a>';
				$result .= '</div>';
			}
			
			$result .= '<div class="entry-summary" itemprop="text">';
			$result .= $get_the_content . '...';
			
			$result .= '<p class="read-more-container">';
			$result .= '<a title="' . $get_the_title . '" class="read-more button" href="' . $get_permalink . '/#more-' . $post->ID . '">Leia mais';
			$result .= '<span class="screen-reader-text">' . $get_the_title . '</span>';
			$result .= '</a></p>';
			
			$result .= '</div>';
			
			$result .= '<footer class="entry-meta">';
			if ( !empty( $list_category ) ) {
				$result .= '<span class="cat-links">';
				$result .= '<span class="screen-reader-text">Categorias</span>';
				$result .= $list_category;
				$result .= '</span>';
			}
			if ( !empty( $list_tag ) ) {
				$result .= '<span class="tags-links">';
				$result .= '<span class="screen-reader-text">Tags </span>';
				$result .= $list_tag;
				$result .= '</span>';
			}
			$result .= '</footer>';
			
			$result .= '</div>';
			$result .= '</article>';
			// end foreach
			$list_category = "";
			$list_tags = "";
		}
		echo $result;
		exit();
    }
	exit();
}
add_action( 'wp_ajax_wp_infinite_scroll_post_show', 'wp_infinite_scroll_post_show_callback' );
add_action( 'wp_ajax_nopriv_wp_infinite_scroll_post_show', 'wp_infinite_scroll_post_show_callback' );