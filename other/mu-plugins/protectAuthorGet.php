<?php
function protectAuthorGet() {
    if( isset($_GET['author']) ) {
        wp_redirect(home_url(), 301);

        die();
    }
}