<?php
/**
 * Tumblr3 theme missing blocks.
 *
 * @package Tumblr3
 */

defined( 'ABSPATH' ) || exit;

/**
 * WordPress does not support playcount tracking for attached audio files.
 * This would need to be implemented as a custom meta field on the attachment.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#audio-posts
 */

/**
 * WordPress doesn't support a panorama post format out of the box.
 * Could this be safely mapped to the Image post format?
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#panorama-posts
 */

/**
 * WordPress doesn't have a following system for noting other blogs you follow.
 * Previously we had the "links" CPT that was used for making blogrolls.
 * Perhaps we could bring that back?
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#following
 */

/**
 * WordPress doesn't have a highligted posts system.
 * Perhaps we could just use the sticky posts system?
 * This seems to exist purely to fulfill a widget in Optica and loads Photo posts only.
 *
 * @see https://github.tumblr.net/Tumblr/tumblr/blob/5e69aae5fd71f2a151078abf11a4d146d3aa6bd7/app/controllers/tumblelog.php#L4778
 */

/**
 * WordPress does not have a related tags system.
 * On Tumblr this appears to be handled by Redis.
 *
 * @see https://github.tumblr.net/Tumblr/tumblr/blob/12a34ac17d5a80eaec05b486f670fc80214d083d/app/controllers/tumblelog/utils/ThemeItemHelper.php#L551
 */

/**
 * WordPress does not have a featured tags system.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#featured-tags
 */

/**
 * WordPress does not have a related posts system by default.
 * Jetpack has a related posts module, perhaps we could integrate with that?
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#related-posts
 */

/**
 * WordPress does not have a native submission system.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#submissions
 */

/**
 * WordPress does not support question/answer systems.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#answer-posts
 */

/**
 * WordPress does not support likes.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#likes
 */

/**
 * WordPress does not support reblogs.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#reblogs
 */

/**
 * WordPress doesn't have a day by day archive system,
 * but date archives are available and traverse with the regular pagination block.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#day-pages
 */

/**
 * WordPress does not support content sources.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#content-sources
 */
return array(
	'block_playcount',
	'block_panorama',
	'block_sharefollowing',
	'block_followingpage',
	'block_following',
	'block_followed',
	'block_hashighlightedposts',
	'block_highlightedposts',
	'block_hasrelatedtags',
	'block_relatedtags',
	'block_relatedposts',
	'block_submissionsenabled',
	'block_submission',
	'block_submitpage',
	'block_submitenabled',
	'block_askenabled',
	'block_askpage',
	'block_answerer',
	'block_likes',
	'block_nolikes',
	'block_haslikedposts',
	'block_likedposts',
	'block_likespage',
	'block_reblog',
	'block_reblogs',
	'block_rebloggedfrom',
	'block_rebloggedfromreblog',
	'block_isoriginalentry',
	'block_isactive',
	'block_isdeactivated',
	'block_haspermalink',
	'block_hasnopermalink',
	'block_hasavatar',
	'block_daypagination',
	'block_previousdaypage',
	'block_nextdaypage',
	'block_newdaydate',
	'block_samedaydate',
	'block_contentsource',
	'block_sourcelogo',
	'block_nosourcelogo',
	'block_showadsonthispage',
	'block_newcta',
	'block_nuopticablogcardsenabled',
	'block_nuopticablogcardsdisabled',
	'block_supplylogging',
	'block_actions',
	'block_livephoto',
	'block_blogs',
	'block_nofollowing',
	'block_answer',
	'block_taggedpage',
);
