<?php
/**
** Get all the constant text
**/

define('notes', [
		'Redirect links help visitors and search engines find new content when moving content to a new URL or deleting old pages. Permanent redirects tell Search Engines and crawlers the old page is not available any longer and it shouldn’t be indexed. Generally, for Affiliate links, Temporary (307) redirects or ‘Cloaked’ links are best as they do not tell a search engine to make any permanent changes.',
		
		'This is the target URL that you want visitors to end up at. If you are given an affiliate URL from one of your affiliate partners, that URL should be pasted here.',
		
		'This is the shortened link that viewers will see on your site or see if given out in other methods. Users will click this link and be taken to your ‘Affiliate link’. Usually, this is a word or short phrase that has something to do with the Affiliate URL. For example:
		"(domain)/Bluehost" could be used to redirect to your Bluehost Affiliate URL',
		
		'If you want to add any notes for yourself about the affiliate link, you can do it here. Viewers will not see these notes. They are for your eyes only.',
		
		'Nofollow attributes are generally used if you don’t want search engines to associate your site with, or crawl the target url	 from your site. This keeps search engines from linking or adding any weight across affiliate links.',
		
		'This is an attribute that is used to identify paid content or paid links. If the link is part of an advertisement or paid placement, they should be marked as sponsored. This does not show up for viewers, rather it is an attribute that is added in the header of the request and seen by search engines.',
		
		'Use this to expire the link after a specified number of unique clicks. Once they are expired, this return a 404 "Missing Page" for the viewer.',
		
		'If you do not want an expired link to return a 404 page for the viewers, check this box and add a new URL in ‘Expire Redirect URL’ textbox. This will send viewers to this new link once all of the specified number of clicks have been consumed’',
		
		'If you have enabled your link to expire and you have also checked ‘Expire Redirect’, insert your new target URL the viewers will be directed to after the initial link has expired.',
		
		'Specify any words throughout your site that you would like this link to attach itself to. For example, “if I wanted to add my affiliate link to each time the word ‘Bluehost’ was used on my site, I would add that word here. ', 

		'Disable the new link and use the affiliate url to marketing to Amazon and other affiliate maketing rules.'
	]
);

?>