<?php
 /*Template Name: New Template
 */
 
// get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'invoice', );
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
 
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>

                <!-- Display Title and Author Name -->
                <strong>Title: </strong><?php the_title(); ?><br />
                <strong>Items: </strong>
            </br>
                <?php 
                echo "<strong>Получател</strong></br>";
                $chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
                echo esc_html( get_post_meta( $chosen_client, 'company_name', true ) ) . "</br>";
                echo esc_html( get_post_meta( $chosen_client, 'company_address', true ) ) . "</br>";
                echo esc_html( get_post_meta( $chosen_client, 'company_id', true ) ) . "</br>";
                echo esc_html( get_post_meta( $chosen_client, 'responsible_person', true ) ) . "</br>";
                $invoice_item_column_number = esc_html( get_post_meta( get_the_ID(), 'invoice_item_column_number', true ) );
                for ($item = 0; $item <= $invoice_item_column_number; $item++) {
                    echo esc_html( get_post_meta( get_the_ID(), 'name'.$item, true ));
                    echo "</br>";
                } 
                 ?><br />    
                <strong>Name: </strong>
                <?php echo esc_html( get_post_meta( get_the_ID(), 'client_name', true ) ); ?><br />     
                <strong>Genres: </strong><?php echo the_terms( $post->ID, 'Genres', ' ', ', ' ); ?>
                <br />
                <!-- Display yellow stars based on rating -->
                <strong>Rating: </strong>
                <?php
                $nb_stars = intval( get_post_meta( get_the_ID(), 'book_rating', true ) );
                for ( $star_counter = 1; $star_counter <= 5; $star_counter++ ) {
                    if ( $star_counter <= $nb_stars ) {
                        echo '<img src="' . plugins_url( 'Movie-Reviews/images/icon.png' ) . '" />';
                    } else {
                        echo '<img src="' . plugins_url( 'Movie-Reviews/images/grey.png' ). '" />';
                    }
                }
                ?>
            </header>
 
            <!-- Display movie review contents -->
            <div class="entry-content"><?php the_content(); ?></div>
        </article>
 
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); 
// get_footer(); ?>