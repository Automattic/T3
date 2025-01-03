<?php
/**
 * TumblrThemeGarden theme missing blocks.
 *
 * @package TumblrThemeGarden
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
	'ttgarden_block_playcount',
	'ttgarden_block_panorama',
	'ttgarden_block_sharefollowing',
	'ttgarden_block_followingpage',
	'ttgarden_block_following',
	'ttgarden_block_followed',
	'ttgarden_block_hashighlightedposts',
	'ttgarden_block_highlightedposts',
	'ttgarden_block_hasrelatedtags',
	'ttgarden_block_relatedtags',
	'ttgarden_block_relatedposts',
	'ttgarden_block_submissionsenabled',
	'ttgarden_block_submission',
	'ttgarden_block_submitpage',
	'ttgarden_block_submitenabled',
	'ttgarden_block_askenabled',
	'ttgarden_block_askpage',
	'ttgarden_block_answerer',
	'ttgarden_block_likes',
	'ttgarden_block_nolikes',
	'ttgarden_block_haslikedposts',
	'ttgarden_block_likedposts',
	'ttgarden_block_likespage',
	'ttgarden_block_reblog',
	'ttgarden_block_reblogs',
	'ttgarden_block_rebloggedfrom',
	'ttgarden_block_rebloggedfromreblog',
	'ttgarden_block_isoriginalentry',
	'ttgarden_block_isactive',
	'ttgarden_block_isdeactivated',
	'ttgarden_block_haspermalink',
	'ttgarden_block_hasnopermalink',
	'ttgarden_block_hasavatar',
	'ttgarden_block_daypagination',
	'ttgarden_block_previousdaypage',
	'ttgarden_block_nextdaypage',
	'ttgarden_block_newdaydate',
	'ttgarden_block_samedaydate',
	'ttgarden_block_contentsource',
	'ttgarden_block_sourcelogo',
	'ttgarden_block_nosourcelogo',
	'ttgarden_block_showadsonthispage',
	'ttgarden_block_newcta',
	'ttgarden_block_nuopticablogcardsenabled',
	'ttgarden_block_nuopticablogcardsdisabled',
	'ttgarden_block_supplylogging',
	'ttgarden_block_actions',
	'ttgarden_block_livephoto',
	'ttgarden_block_blogs',
	'ttgarden_block_nofollowing',
	'ttgarden_block_answer',
	'ttgarden_block_taggedpage',
);
