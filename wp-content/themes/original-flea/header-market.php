<?php
    $market = get_queried_object();
?>
<div class="archive-header" id="flea-market-header">
   <?php the_flea_market_avatar( $market ); ?>
    <div class="info">

        <h2><?php echo $market->name; ?></h2>

        <?php the_flea_market_location( $market ); ?>

        <?php if ( ! empty( $market->description ) ) : ?>
            <p><?php echo $market->description; ?></p>
        <?php endif; ?>

    </div>
</div>

<p class="archive-count">
    <?php echo sprintf( _n( '%s story shared', '%s stories shared', $market->count ), $market->count ); ?>
</p>
