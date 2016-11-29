<?php

class Living_Flea_Galleries {

    function __construct() {
        add_filter( 'post_gallery', array( $this, 'render_post_gallery' ), 10, 3 );
    }

    public function render_post_gallery( $gallery, $attr, $instance ) {
        global $post;
        $image_ids = explode( ',', $attr['ids'] );
        if( count( $image_ids ) === 1 ) {
            return $this->render_gallery_single( $post->ID, $image_ids[0] );
        } else {
            return $this->render_gallery_multi( $post->ID, $attr );
        }
    }

    public function render_gallery_single( $post_id, $attachment_id ) {
        $attachment = get_post( $attachment_id );
        ob_start();
        ?>
        <div id="gallery-<?php echo $post_id; ?>" class="gallery-single">
            <?php $this->render_image( $attachment ); ?>
            <?php if ( ! empty( $attachment->post_excerpt ) ) : ?>
                <?php $this->render_caption( $attachment ); ?>
            <?php endif; ?>
        </div>
        <?php
        $single = ob_get_contents();
        ob_end_clean();
        return $single;
    }

    public function render_gallery_multi( $post_id, $attr ) {
        $attachments = get_posts( array(
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
            'order'          => 'ASC',
            'orderby'        => $attr['orderby'],
            'include'        => $attr['include'],
        ) );
        ob_start(); ?>
        <div id="gallery_<?php echo $post_id; ?>" class="gallery-multi">
            <div class="slideshow">
                <div id="images_<?php echo $post_id; ?>">
                    <?php array_map( array( $this, 'render_image' ), $attachments, array_keys( $attachments ) ); ?>
                </div>
                <div class="slideshow-buttons" id="slideshow-buttons_<?php echo $post_id; ?>">
                    <a class="prev hidden" data-id="<?php echo $post_id; ?>" data-total="<?php echo count( $attachments ); ?>">
                        <img src="<?php echo LIFL_PATH; ?>images/prev.png" width="190" height="115" alt="Previous Image" />
                    </a>
                    <a class="next" data-id="<?php echo $post_id; ?>" data-total="<?php echo count( $attachments ); ?>">
                        <img src="<?php echo LIFL_PATH; ?>images/next.png" width="190" height="115" alt="Next Image" />
                    </a>
                </div>
            </div>
            <div id="slideshow-captions_<?php echo $post_id; ?>">
                <?php array_map( array( $this, 'render_caption' ), $attachments, array_keys( $attachments ) ); ?>
            </div>
        </div>
        <?php
        $gallery = ob_get_contents();
        ob_end_clean();
        return $gallery;
    }

    public function render_image( $attachment, $index = false ) {
        $img = wp_get_attachment_image_src( $attachment->ID, 'large' );
        $hidden = ( $index > 0 ) ? 'hidden' : '';
        $exif = exif_read_data( $img[0] );
        $orientation = isset( $exif['Orientation'] ) ? (int) $exif['Orientation'] : 0;
        switch( $orientation ) {
            case 5:
            case 6:
            case 7:
            case 8:
                $vertical = 'vertical';
                break;
            default:
                $ratio = $img[1] / $img[2];
                $vertical = ( $ratio <= 1.2 ) ? 'vertical' : '';
        }
        echo "<img src='" . $img[0] . "' alt='' class='gallery-image $hidden $vertical' width='" . $img[1] . "' height='" . $img[2] . "' />";
    }

    public function render_caption( $attachment, $index = false ) {
        $hidden = ( $index > 0 ) ? 'hidden' : '';
        $caption = empty( $attachment->post_excerpt ) ? 'Image ' . ($index + 1) : wptexturize( strip_tags( $attachment->post_excerpt ) );
        echo "<p class='gallery-caption $hidden'>$caption</p>";
    }

}

$living_flea_uploads = new Living_Flea_Galleries();