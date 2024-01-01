<?php
/*
 * Plugin Name: MB Synchronize Product Gallery Image
 * Description: This plugin synchronizes all products from a database
 * Version: 0.0.1
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: CanSoft
 * Author URI: https://cansoft.com/
 */

function mb_products_gallery_image_sync() {
    if (isset($_POST['mb-product-image-sync-cron'])) {
        // Get all the posts of type 'product' (customize post type as needed).
        $products = get_posts(array(
            'post_type' => 'product', // Customize to your post type.
            'numberposts' => -1,
        ));

        // Loop through each product.
        foreach ($products as $product) {

            $uploads_dir = wp_upload_dir();
            $image_urls = glob($uploads_dir['basedir'] . '/feature-img/*.jpg');
            $sku = get_post_meta($product->ID, '_sku', true); // Customize for your SKU field.
   
            // Construct the image filename based on product title and SKU.
            $image_filename = $sku . '-';
        
            //$image_filename = site_url('/wp-content/uploads/' . $image_filename);
            //dd($image_url);
            $mb_gallery_imgs = array();
            foreach ($image_urls as $image_item) {
        
        
                if (strpos($image_item, $image_filename) !== false) {

                    $imageBasename = basename($image_item);
                    $imageUrl = site_url('/wp-content/uploads/feature-img/' . $imageBasename);
                    $mb_gallery_imgs[] = $imageUrl;
                }
            }
           echo "<pre>"; 
           print_r($sku);
           echo "</pre>";
           echo "<pre>"; 
           print_r($mb_gallery_imgs);
           echo "</pre>";

            // Find and link images based on filename.
            link_gallery_images_to_product($product, $mb_gallery_imgs);
        }
    }
    ?>
    <div class="wrap">
        <h1>This Page for Synchronize all product images</h1><br>
        <div class="d-flex">
            <form method="POST">
                <?php 
                    submit_button('Start Product Image Cron Now', 'primary', 'mb-product-image-sync-cron');
                ?>
            </form>
        </div>
    </div>
    <?php 
}


function link_gallery_images_to_product($product, $mb_gallery_imgs) {
    $product_id = $product->ID;
    // Make sure the product exists
    // Create a new attachment post for the image URL
    $image_g_ids = array();
    foreach ($mb_gallery_imgs as $image_url) {
       
        $attachment_id = wp_insert_attachment(array(
            'post_title'     => 'Product Gallery Image', // Change the title as needed
            'post_type'      => 'attachment',
            'post_mime_type' => 'image/jpg', // Adjust the mime type if needed
            'guid'           => $image_url,
        ), $image_url, $product_id);

        // Add the attachment to the product's gallery
        if (!is_wp_error($attachment_id)) {
            $gallery_images = get_post_meta($product_id, '_product_image_gallery', true);
            if (empty($gallery_images) || !is_array($gallery_images)) {
                $gallery_images = array();
                
            }
            $gallery_images[] = $attachment_id;

            $image_gallery = implode(',', $gallery_images);

            update_post_meta($product_id, '_product_image_gallery', $image_gallery);
        }

        $image_g_ids[] =$attachment_id;
    }

    $image_g_ids = implode(',', $image_g_ids);
    update_post_meta($product_id, '_product_image_gallery', $image_g_ids);

 # code...
}





// function link_gallery_images_to_product($product, $mb_gallery_imgs) {
//     $product_id = $product->ID;
//     if (!post_exists($product_id)) {
//         return;
//     }

//     // Loop through the gallery image URLs
//     update_post_meta($product_id, '_product_image_gallery', implode(',', $mb_gallery_imgs));
//     wp_update_post(array('ID' => $product_id));



   

//     $product_id = $product->ID;

//     // Find and link images to the product by replacing image filenames.
//     $attachment_ids = array(); // An array to store attachment IDs
//     foreach ($mb_gallery_imgs as $image_link) {

//         //dd($image_link);
//         // Set the image path
//         $image_path = str_replace('\\', '/', $image_link);
//         $image_base = "feature-img/".basename($image_path);
//         $image_url = site_url('/wp-content/uploads/feature-img/' . $image_base);

//         // Prepare the attachment data
//         $attachment_data = array(
//             'post_title' => basename($image_base),
//             'post_status' => 'inherit',
//             'guid' => $image_url,
//         );

//         // Insert the attachment
//         $attachment_id = wp_insert_attachment($attachment_data, $image_base, $product_id);
//         // echo "<pre>";
//         // print_r($attachment_id);
//         // echo "</pre>";
//         // Set the image as a gallery image
//         $result = add_post_meta($product_id, '_product_image_gallery', $attachment_id, true);
//         $attachment_ids[] = $attachment_id;

//         // echo "<pre>";
//         // print_r($result);
//         // echo "</pre>";
//     }

//     $image_gallery = implode(',', $attachment_ids);

//     //dd($image_gallery);
//     // Update the product's _product_image_gallery post meta field
//     update_post_meta($product_id, '_product_image_gallery', $image_gallery);

// // Update the product to save changes
// wp_update_post(array('ID' => $product_id));

    
// }

function mb_product_gallery_image_sync_menu_pages() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Product Gallery Image Sync',
        'Product Gallery Image Sync',
        'manage_options',
        'product-gallery-image-sync',
        'mb_products_gallery_image_sync'
    );
}
add_action('admin_menu', 'mb_product_gallery_image_sync_menu_pages');



$activeSaleprice;
$regularSalePrice;
$activediscount;
$matchcatseg;
$salePriceLevelDiscountPrecent;
$regPriceLevelDiscountPercent;

if ($activeSaleprice) {
    if ($activediscount && $matchcatseg ) {
        $activeSaleprice-$salePriceLevelDiscountPrecent;
    }else{
        $activeSaleprice;
    }
    
}else{
    if ($activediscount && $matchcatseg) {
        $regularSalePrice-$regPriceLevelDiscountPercent;
    }else{
        $regularSalePrice;
    }
}
