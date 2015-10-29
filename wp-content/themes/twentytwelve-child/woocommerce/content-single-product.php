<?php
/**
 * Single release content
 *
 * @package WordPress
 * @subpackage Twenty_Twelve_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$artists = array();
$links = array();
$data = get_field( 'product-artists' );
foreach ( $data as $d ) {
	$name = get_the_title( $d->ID );
	$artists[] = $name;
	$website_link = get_field( 'ac-artist-website', $d->ID );
	if ( ! empty( $website_link ) ) {
		$links[$name]['website'] = format_url( $website_link );
	}
	$facebook_link = get_field( 'ac-artist-facebook', $d->ID );
	if ( ! empty( $facebook_link ) ) {
		$links[$name]['facebook'] = format_url( $facebook_link );
	}
	$bandcamp_link = get_field( 'ac-artist-bandcamp', $d->ID );
	if ( ! empty( $bandcamp_link ) ) {
		$links[$name]['bandcamp'] = format_url( $bandcamp_link );
	}
	$soundcloud_link = get_field( 'ac-artist-soundcloud', $d->ID );
	if ( ! empty( $soundcloud_link ) ) {
		$links[$name]['soundcloud'] = format_url( $soundcloud_link );
	}
}

$sku = $product->get_sku();
$title = get_release_attribute_value( $post->ID, 'release-title' );
?>

<div itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" class="release-page">
	
	<div class="release-content">
		
		<div class="title">
			<h2><span class="artist"><?php echo implode( " & ", $artists ); ?></span>&nbsp;<span class="title"><?php echo $title; ?></span><span class="mobile-sku">&nbsp;-&nbsp;<?php echo $sku; ?></span></h2>
		</div>
		
		<hr class="small-line" />
		
		<?php 
		$record_cover = get_field( 'release-record_cover' );
		if( ! empty( $record_cover ) ) : ?>
			<img class="cover" src="<?php echo $record_cover['url']; ?>" alt="<?php echo $record_cover['alt']; ?>" />
		<?php endif; ?>
		 
		<div class="description">
			<p class="title">ABOUT</p>
			<?php $description = get_the_content_with_formatting(); ?>
			<?php echo add_read_more( clean_html( $description ) ); ?>
		</div>
		
		<div class="mobile-details">
			<div class="left"></div>
			<div class="right"></div>
		</div>
		
		<?php 
		$band_pic_1 = get_field( 'release-band_pic_1' );
		$band_pic_2 = get_field( 'release-band_pic_2' );
		if( ! empty( $band_pic_1 ) ) : ?>
			<div class="band-pics">
				<?php if( ! empty( $band_pic_2 ) ) : ?>
					<a href="<?php echo $band_pic_1['url']; ?>"><img class="first" width="49%" src="<?php echo $band_pic_1['url']; ?>" alt="<?php echo $band_pic_1['alt']; ?>" /></a>
					<a href="<?php echo $band_pic_2['url']; ?>"><img width="49%" src="<?php echo $band_pic_2['url']; ?>" alt="<?php echo $band_pic_2['alt']; ?>" /></a>
				<?php else: ?>
					<img width="100%" src="<?php echo $band_pic_1['url']; ?>" alt="<?php echo $band_pic_1['alt']; ?>" />
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<?php 
		$video_1 = array( 
			'ID' => get_field( 'release-video_id_1' ),
			'type' => get_field( 'release-video_type_1' )
		);
		$video_2 = array( 
			'ID' => get_field( 'release-video_id_2' ),
			'type' => get_field( 'release-video_type_2' )
		);
		if( ! empty( $video_1['ID'] ) ) : ?>
			<div class="videos">
				<?php if( ! empty( $video_2['ID'] ) ) : ?>
					<?php if( $video_1['type'] == 'youtube' ) : ?>
						<iframe class="video first" width="49%" src="https://www.youtube.com/embed/<?php echo $video_1['ID']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					<?php elseif( $video_1['type'] == 'vimeo' ) : ?>	
						<iframe class="video first" src="https://player.vimeo.com/video/<?php echo $video_1['ID']; ?>" width="49%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					<?php endif; ?>
					<?php if( $video_2['type'] == 'youtube' ) : ?>
						<iframe class="video" width="49%" src="https://www.youtube.com/embed/<?php echo $video_2['ID']; ?>" frameborder="0" allowfullscreen></iframe>
					<?php elseif( $video_2['type'] == 'vimeo' ) : ?>	
						<iframe class="video" src="https://player.vimeo.com/video/<?php echo $video_2['ID']; ?>" width="49%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					<?php endif; ?>
				<?php else: ?>
					<?php if( $video_1['type'] == 'youtube' ) : ?>
						<iframe width="100%" src="https://www.youtube.com/embed/<?php echo $video_1['ID']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					<?php elseif( $video_1['type'] == 'vimeo' ) : ?>	
						<iframe src="https://player.vimeo.com/video/<?php echo $video_1['ID']; ?>" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<?php 
		$record_pic_1 = get_field( 'release-record_pic_1' );
		$record_pic_2 = get_field( 'release-record_pic_2' );
		if( ! empty( $record_pic_1 ) ) : ?>
			<div class="record-pics">
				<?php if( ! empty( $record_pic_2 ) ) : ?>
					<a href="<?php echo $record_pic_1['url']; ?>"><img class="first" width="49%" src="<?php echo $record_pic_1['url']; ?>" alt="<?php echo $record_pic_1['alt']; ?>" /></a>
					<a href="<?php echo $record_pic_2['url']; ?>"><img width="49%" src="<?php echo $record_pic_2['url']; ?>" alt="<?php echo $record_pic_2['alt']; ?>" /></a>
				<?php else: ?>
					<a href="<?php echo $record_pic_1['url']; ?>"><img class="first aaaa" width="49%" src="<?php echo $record_pic_1['url']; ?>" alt="<?php echo $record_pic_1['alt']; ?>" /></a>
					<a href="<?php echo $record_pic_1['url']; ?>"><img width="49%" src="<?php echo $record_pic_1['url']; ?>" alt="<?php echo $record_pic_1['alt']; ?>" /></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	
	</div>
	
	<div class="release-sidebar ac-sidebar">
		<?php			
			/* get & process tracklist */
			$tracklist = get_field( 'release-tracklist' );
			if ( stripos( $tracklist, 'side a' ) !== false || stripos( $tracklist, 'side b' ) !== false ) {
				$tracklist_lines = preg_split( '/<br.*>/', $tracklist );
				$tracklist = "";
				$first = true;
				foreach ( $tracklist_lines as $line ) {
					if ( stripos( $line, 'side a' ) !== false || stripos( $line, 'side b' ) !== false ) {
						$tracklist .= "<p class='sub-title" . ( $first ? '': ' not-first' ) . "'>" . $line . "</p><p>";
						$first = false;
					} else {
						$tracklist .= $line . "<br/>";
					}
				}
				$tracklist .= "</p>";
			} else {
				$tracklist = "<p>" . $tracklist . "</p>";
			}
			
			/* get release buyability */
			$buy_txt = "<p>Go to <a href='/shop#" . $post->ID . "'>Shop</a></p>";
			$in_stock = ( $product->get_stock_quantity() == 0) ? false : true;
			$is_preorder = is_preorder( $post->ID );
			if ( $is_preorder ) {
				$buy_txt = "<p><a href='/shop#" . $post->ID . "'>PreOrder</a></p>";
			} else {
				$format = get_release_attribute_value( $post->ID, 'release-format' );
				$format = strtok( $format, " " );
				$buy_txt = "<p>" . $format . " = Sorry, sold out</p>";
				$digital_product_ID = get_product_id_by_sku( $sku . "d" );
				if ( ! empty( $digital_product_ID ) ) {
					$buy_txt .= "<p><a href='/shop#" . $post->ID . "'>Digital</a></p>";
				}
			}
			
			/* get other informations */	
			$info = get_field( 'release-info' );
			$credits = get_field( 'release-credits' );
			$soundcloud_set = get_field( 'release-soundcloud_set' );
		?>
			
		<div class="sku">
			<?php echo $sku; ?>
		</div>
		
		<hr class="small-line" />

		<? if ( ! empty( $soundcloud_set ) ) : ?>
			<div class="soundcloud-player">
				<a href="http://soundcloud.com/atelierciseaux/<?php echo $soundcloud_set; ?>" class="sc-player"></a>
			</div>
		<? endif; ?>
		
		<div class="info">
			<p class="title">INFO</p>
			<p>Out <?php echo date( 'F d, Y', strtotime( get_field( 'release-date' ) ) ); ?></p>
			<p><?php echo process_links( $info ); ?></p>
		</div>
		
		<hr class="small-line special" />

		<? if ( ! empty( $tracklist ) ) : ?>
			<div class="tracklist">
				<p class="title">TRACKLIST</p>
				<?php echo $tracklist; ?>
			</div>
			
			<hr class="small-line special" />
		<? endif; ?>
				
		<? if ( ! empty( $credits ) ) : ?>
			<div class="credits">
				<p class="title">CREDITS</p>
				<p><?php echo process_links( $credits ); ?></p>
			</div>
			
			<hr class="small-line special" />
		<? endif; ?>
		
		<? if ( ! empty( $links ) ) : ?>
			<div class="links">
				<p class="title">LINKS</p>
				<?php
				$first = true;
				foreach ( $links as $artist => $artist_links ) {
					if ( count( $links ) > 1 ) {
						echo "<p class='sub-title" . ( $first ? '': ' not-first' ) . "'>" . $artist . "</p>";
						$first = false;
					}
					foreach ( $artist_links as $type => $url ) {
						echo "<p><a href='" . $url . "' target='_blank'>" . ucfirst( $type ) . "</a></p>";
					}
				}
				?>
			</div>
			
			<hr class="small-line special" />
		<? endif; ?>
		
		<div class="buy">
			<p class="title">BUY</p>
			<?php echo $buy_txt; ?>
		</div>
				
	</div>

</div><!-- #product-<?php the_ID(); ?> -->
