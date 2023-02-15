<?php
if ( wamm_fs()->is__premium_only() ) {
    if ( wamm_fs()->can_use_premium_code() ) {
/* Afffiliate page */
require_once __DIR__. '/class-admin-affiliate.php';
/* Find pages where link Used */
require_once __DIR__. '/class-admin-findlink.php';
/* Find Broken Links */
require_once __DIR__. '/class-admin-broken-link.php';
/* Impressions Page */
require_once __DIR__. '/class-admin-impression.php';
/* Default Setting Page */
require_once __DIR__. '/class-default-options.php';
/* Duplicate Title and link */
require_once __DIR__. '/class-admin-duplicate-title.php';
	}
}