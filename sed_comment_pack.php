<?php

$plugin['revision'] = '$LastChangedRevision$';

$revision = @$plugin['revision'];
if( !empty( $revision ) )
	{
	$parts = explode( ' ' , trim( $revision , '$' ) );
	$revision = $parts[1];
	if( !empty( $revision ) )
		$revision = '.' . $revision;
	}

$plugin['name'] = 'sed_comment_pack';
$plugin['version'] = '0.7' . $revision;
$plugin['author'] = 'Netcarver';
$plugin['author_uri'] = 'http://txp-plugins.netcarving.com';
$plugin['description'] = 'Additional comment tags.';

// Plugin types:
// 0 = regular plugin; loaded on the public web side only
// 1 = admin plugin; loaded on both the public and admin side
// 2 = library; loaded only when include_plugin() or require_plugin() is called
$plugin['type'] = 0;

@include_once('../zem_tpl.php');

if (0) {
?>
<!-- CSS
# --- BEGIN PLUGIN CSS ---
	<style type="text/css">
	div#sed_help td { vertical-align:top; }
	div#sed_help code { font-weight:bold; font: 105%/130% "Courier New", courier, monospace; background-color: #FFFFCC;}
	div#sed_help code.sed_code_tag { font-weight:normal; border:1px dotted #999; background-color: #f0e68c; display:block; margin:10px 10px 20px; padding:10px; }
	div#sed_help a:link, div#sed_help a:visited { color: blue; text-decoration: none; border-bottom: 1px solid blue; padding-bottom:1px;}
	div#sed_help a:hover, div#sed_help a:active { color: blue; text-decoration: none; border-bottom: 2px solid blue; padding-bottom:1px;}
	div#sed_help h1 { color: #369; font: 20px Georgia, sans-serif; margin: 0; text-align: center; }
	div#sed_help h2 { border-bottom: 1px solid black; padding:10px 0 0; color: #369; font: 17px Georgia, sans-serif; }
	div#sed_help h3 { color: #693; font: bold 12px Arial, sans-serif; letter-spacing: 1px; margin: 10px 0 0;text-transform: uppercase;}
	</style>
# --- END PLUGIN CSS ---
-->
<!-- HELP
# --- BEGIN PLUGIN HELP ---
<div id="sed_help">

h1. SED Comment Pack Plugin

v 0.7 December 3rd, 2007.

New in this version...

* Support for delayed comment posting and culling.

h2. Summary

I was inspired by some of the plugins from Compooter.org to write this set of comment helper tags. I particularly wanted to improve the situation for author checking in multi-author sites but what came out has application for every TXP installation with commenting enabled where the author of the article takes an active role in writing comments in reply to what others have said.

The *ajw_if_comment_author* plugin was my first port of call to try to setup my comment system. It does things pretty well but requires author data to be supplied in the TXP forms--a place where author data really shouldn't be appearing as it is difficult to maintain here and cannot handle multi-author sites easily.

I also found that the conditional tag complicated the logic in the form and wanted to simplify this. So, rather than having a conditional tag that split my TXP comments form into two logical branches, I wanted tags that simply returned the right thing, no matter if an author comment or not. These tags moved the data and processing from the form--simplifying it greatly.

These following tags allow me to do that&#8230;

* <code><txp:sed_get_comment_class/></code> tag specifically to calculate the class to apply to a comment block.
* <code><txp:sed_if_author_comment_string/></code> tag to conditionally output a string to mark up any author comments.
* <code><txp:sed_comment_number/></code> tag to output the sequential comment number on the page.
* <code><txp:sed_comment_time/></code> tag upgrades the default TXP tag with wraptag and class.

In addition, the following tags are available&#8230;

* <code><txp:sed_if_comments/></code> tag is a replacement for the <code><txp:if_comments/></code> tag. It can be put in page templates.

All of them are designed for use in the TXP forms called *comments* and, _if you use it_, *comments_preview*.

Tracking new comments can now be achieved by using the <code><txp:sed_cp_new_comments_count /></code> and <code><txp:sed_cp_new_comments_digest /></code> tags in pages or forms.

<hr/>

h3. Content.

The following sections can be found in this remainder of this help file&#8230;
# Pre-requisites.
# Example TXP Forms: *comments*, *comments_preview*.
# Tag Directory and Examples.
# CSS classes and markup.
# To-Do and Feature Enhancements.
# Credits.

<hr/>

h1. Pre-requisites.

Make sure that your site admin preferences are setup to turn off ordered lists.

# *Admin* > *Preferences* > Comments: _'Present comments as a numbered list?'_ Set to *no*.
# Enable this plugin!
# Use the tags in the TXP forms called *comments* and *comments_preview*.

<hr/>

h1. Example TXP Forms: *comments*, *comments_preview*

h2. Example 'comments' Form.

| 1 | <code><div class="<txp:sed_get_comment_class />"></code> |
| 2 | <code><txp:comment_anchor /></code> |
| 3 | <code><h5><span class="comment-no"><txp:comment_permlink> #<txp:sed_comment_number/></txp:comment_permlink></span>
&middot;&nbsp;<txp:comment_name /><txp:sed_if_author_comment_string string=' (Author Comment)' /></h5></code> |
| 4 | <code><txp:sed_comment_time class='comment-time'/></code> |
| 5 | <code><txp:message /></code> |
| 6 | <code></div></code> |

h2. Example 'comments_preview' Form.

| 1 | <code><div class="<txp:sed_get_comment_class hide_odd_even='1' />"></code> |
| 2 | <code><h5><span class="comment-no"><txp:comment_permlink> #</txp:comment_permlink></span>&middot; &nbsp; <txp:comment_name /><txp:sed_if_author_comment_string string=' (Author Comment)' /></h5></code> |
| 3 | <code><txp:sed_comment_time class='comment-time'/></code> |
| 4 | <code><txp:message /></code> |
| 5 | <code></div></code> |

h1. Tag Directory.

h3. <code><txp:sed_get_comment_class/></code>

For use in the TXP forms comments and comments_preview, this tag outputs a string for use in the CSS class string of the comment being processed.

This tag can take the following _optional_ attributes&#8230;

| *Attribute*     | *Default Value* | *Description* |
| 'class'         | 'comment'     | Name of the basic class applied to all comments. |
| 'author_class'  | 'author'      | Set to an empty string ('') to turn off the commentor-is-author checking otherwise enter the name of the class you wish your author comments to be tagged with. |
| 'odd_class'     | 'odd'         | The string to use to mark odd numbered comments. |
| 'even_class'    | 'even'        | The string for even numbered comments. |
| 'hide_odd_even' | ''            | Set to any non-empty value to surpress odd/even comment marking.<br/>_Useful in comment preview form._ |
| 'method'        | 'check-email' | Any value other than 'check-password' will cause the comment email to be checked against the author's email. |
| 'per_name'      | '1'           | Set to any non-empty value to turn on per-commentator class markup. |
| 'cmtr_prefix'   | 'commentator' | Prefix used to create the per-commentator css class. |

The format of the output is "*class* *[odd_class|even_class]* *[author_class]*"<br/>_Where the highlighted names are the names of the attributes of the tag._

Everything should work by default to give you one of these possible outputs&#8230;
* "comment"
* "comment odd"
* "comment even"
* "comment commentator_class"
* "comment odd commentator_class"
* "comment even commentator_class"
* "comment author"
* "comment odd author"
* "comment even author"

Here 'commentator_class' is appended if the 'per_name' attribute is not blank. It is built from the cmtr_prefix and the 'name' field the user entered in the comment form. For example, if the commentator enters 'Joe Smith' then the default style of 'commentator-joe-smith' will be appended to the comment. If Joe is one of your friends and you have added a css class to your site for him then that will be used to style his comment. Please note, however, that this styling does not involve any authentication of the name -- so anyone who enters the name of your friend can get his styling applied to their comment.

The value of the attribute *odd_class* is only output on odd numbered comments, *even_class* on even numbered comments though the odd/even row output can be turned off by setting the attribute *'hide_odd_even'* to a non-blank value.

The *'author_class'* value is only output if it is non-blank *and* the commentator's details match the article author's details stored in the internal TXP users table. Note, this can be turned off by setting the attribute *'author_class'* to a blank value.

If the checking is on then the commentator's name will be checked to see if it matches the *Real Name* of the article author. If it does then the attribute *'method'* controls how the matching of commentator's and author's details will be handled.  By default the input email is checked vs the article author's email. However, if you set this to 'check-password' then the content of the email field of the comment form will be compared against the article author's password. *NOTE* 'check-password' will leave a plain-text password trail in one of TXP's internal tables.

h2. Examples&#8230;

If you don't want to use the default class names then you can override them thus&#8230;
<code><txp:sed_get_comment_class class='foo-comments' author_class='bar' odd_class='o' even_class='e' /></code>

Standard comments, odd, will be marked: _'foo o'_.<br />
Standard comments, even, will be marked: _'foo e'_.<br />
Author's comments, even, will be marked: _'foo e bar'_.

If you don't want odd/even output&#8230;
<code><txp:sed_get_comment_class hide_odd_even='1' /></code>

If you don't want author checking output&#8230;
<code><txp:sed_get_comment_class author_class='' /></code>

If you don't want author checking or odd/even output&#8230;
<code><txp:sed_get_comment_class author_class='' hide_odd_even='1' /></code>
but then again, it would be easier just to fix the div class name to 'comments'!!!

If you want to check for author comments against the TXP users' passwords then&#8230;
<code><txp:sed_get_comment_class method='check-password' /></code>
*NOTE* This will record the TXP user's password in plaintext in the txp_discuss table. It's up to you if you want to do this but it might be better to stick with the default 'check-email' method and have each TXP user supply an un-publicised email address when their account is created in TXP.

<hr width="25%"/>

h3. <code><txp:sed_if_author_comment_string/></code>

I use this to append a string such as ' (Author Comment)' to all comments left by the author of the article, but you can customise the strings returned for author and non-author situations.

| *Attribute*   | *Default Value*     | *Description* |
| 'string'      | ' (Author Comment)' | This string is returned if there is an author match. |
| 'else_string' | ''                  | This string is returned if there is not.             |
| 'wraptag'     | 'span'              | Used to wrap the author string if it is returned.  |
| 'class'       | 'author-string'     | Used to set the class value of the wrapped author string. |
| 'method'      | 'check-email'       | Method to use when checking for an author match (see above). |

*Note:* The 'else_string' _is not_ 'wrapped and classed'.

<hr width="25%"/>

h3. <code><txp:sed_comment_number/></code>

Replacement for ajw_comment_num so that we don't have to load additional plugins to handle this common situation.

| *Attribute*   | *Default Value* | *Description* |
| 'count'       | 'up'            | Specifies how comment numbers will change, comment to comment.<br/>Valid values are 'up', 'down', 'guestbook'.<br/>Only use the 'guestbook' count mode for integration with the @sdr_guestbook@ plugin. |

<hr width="25%"/>

h3. <code><txp:sed_comment_time/></code>

Replacement for the native txp tag "comment_time":http://textpattern.net/wiki/index.php?title=Txp:comment_time_/ but extended to allow for a wraptag and class.

| *Attribute*   | *Default Value* | *Description* |
| 'wraptag'     | 'span'          | Used to wrap the returned time. |
| 'class'       | 'comment-time'  | Used to tag the returned structure for CSS markup. |
| 'format '	    | TXP default | The desired time in "strftime":http://php.net/strftime time format. |

<hr width="25%"/>

h3. <code><txp:sed_cp_new_comment_count/></code>

Place this tag in your page/form template to get a string showing the number of new comments posted since the visitor last came to the site.

| *Attribute*       | *Default Value*         | *Description* |
| 'string'          | 'New comments: {count}' | String used to format the count |
| 'show_zero_count' | 'n'                     | Set to 'y' to force the string to show, even if there are no new comments |
| 'max_visit'       | 7200                    | length of visit time-out in seconds |
| 'wraptag'         | 'p'                     | This is the normal TxP attribute    |

<hr width="25%"/>


h3. <code><txp:sed_cp_new_comment_digest/></code>

This tag outputs a list of the articles having comments that have been added since the user's last visit.
The number of new comments for each article is shown in parenthesis after the article title.

| *Attribute*       | *Default Value*         | *Description* |
| 'max_visit'       | 7200                    | length of visit time-out in seconds |
| 'class'           | __FUNCTION__            | Class to apply to the rendered list |
| 'wraptag'         | 'ul'                    | This is the normal TxP attribute    |
| 'break'           | 'li'                    | This is the normal TxP attribute    |
| 'label'           | ''                      | This is the normal TxP attribute    |
| 'labeltag'        | ''                      | This is the normal TxP attribute    |
| 'limit'           | '0'                     | Set this to a positive, non-zero, integer to limit the number of articles in the resulting list |
| 'more'            | ' &#8230;'              | This string will be appended to the last list item if the list has more items than the limit |

<hr width="25%"/>


h3. <code><txp:sed_comments/></code>

This is a straight replacement for the native @comments@ tag. All of its attributes
are the same. However, it introduces delayed posting and culling of comments based
upon the value of a packed custom field called @'sed per-article vars'@ in it's owning article.

| *Attribute*       | *Default Value*         | *Description* |
| 'sed_delay'       | '0'                     | Delay to visible in minutes with '0' meaning no delay. |
| 'sed_ttl'         | ''                      | Time to live in minutes. Empty means forever.          |
| 'sed_ttl_grace'   | ''                      | Use this to restrict culling. A grace period (minutes) from the saving of the owning article, during which any posted comments will not be culled according to the ttl and on_cull variables. Empty means forever. Zero means no grace period. |
| 'sed_on_cull'     | 'hide'                  | Action to take when comment is to be culled. hide/spam/delete   |

The 'sed_ttl_grace' period can be used to allow the author of an article to post comments to their own article that do not automatically get culled according to the TTL timeout.

The txp-plugins site uses the following on its public comments test page to delay posting of comments by 30 seconds and to hide all posted comments after two hours...

@ttl='120';ttl_grace='180';delay='0.5';on_cull='hide'@

*NB* I gave myself 3 hours of grace after first writing the article, in which to post my own comments that would avoid the culling rules.

<hr/>

h1. CSS classes and markup.

The following CSS classes are used in the output of this plugin&#8230;
| *Value* | *Location and use* |
| comment (*or your named class*) | Basic style applied to all comments |
| author (*or your named class*) | Style applied to comments that match the article author. |
| odd (*or your named class*) | Style for odd numbered comments. |
| even (*or your named class*) | Style for even numbered comments. |
| culled | Style for comments exceeding the per-article TTL. See <code><txp:sed_comments></code> |
| delay_queue | Style for comments still in the per-article delayed visibility state. See <code><txp:sed_comments></code> |
| comment-time (*or your named class*) | Style for comment times. |
| author_string (*or your named class*) | Style for appended author strings. |

*The CSS markup needed for integration with the guestbook is unsupported, please see the guestbook plugin's help file.*

<hr width="25%"/>

h3. Example CSS file entries&#8230;

Here is a snippet of a CSS file to style the output list in a nice way. Alternate row striping is done by defining a different background colour for the odd rows.

<pre><code>
.comment		{ border: 1px solid #eee; padding: 10px; margin: 5px 0; background-color: #ffffff; }
.odd  			{ background-color: #f0f0f0; }
.even			{  }
.author		 	{ border: 1px dotted #333; }
.comment h5 	{ margin-bottom: 0.1em; }
.comment:hover 	{ border: 1px solid #333; }
.author:hover	{ background-color: #e6e6fa; }
.comment-time 	{ width: 90%; text-align: right; font-size: smaller; color: orange; }
.author-string	{ font-variant: small-caps; font-weight: 100; }
.comment-no 	{ font-size: 1.5em; color: #999; }
</code></pre>

<hr/>

h1. Credits.

Guestbook integration was a collaboration with Els from the textpattern forum.

Inspired by the AJW_ family of plugins from "Compooter":http://compooter.org

</div>
# --- END PLUGIN HELP ---
-->
<?php
}

# --- BEGIN PLUGIN CODE ---

// ---------------- PRIVATE FUNCTIONS FOLLOW ------------------

function _sed_cp_get_sed_vars( $args )
	{
	$out = array();

	if( empty( $args ) )
		return $out;

	$chunks = explode( ';', $args );
	foreach( $chunks as $chunk ) {
		list( $key, $value ) = explode( '=', $chunk );
		$out[ 'sed_'.$key ] = trim( $value, " '\"" );
		}
	return $out;
	}

function _sed_cp_dump_trace()
	{
	global $logfile;
	$level = 0;
	$trace = debug_backtrace();

	if( !empty( $trace ) )
		foreach( $trace as $frame )
			{
			if( $level >= 0 )
				{
				extract( $frame );
				$msg = "$file:$line $function();";
				error_log( n.$msg, 3 , $logfile );
				}
			$level += 1;
			}
	}

function _sed_cp_log( $message , $line )
	{
	global $logging;
	global $logfile;

	if( $logging )
		error_log( n.$message.'['.$line.'].' , 3 , $logfile );
	}

function _sed_cp_if_author_comment( $method )
	{
	global $thiscomment, $thisarticle;
	static $_sed_cached_author_data;
	$out_result = FALSE;

	//
	//	Find out the commentor's name and what they entered in the email address field of the comment form.
	// NB: They might have entered a password!
	//
	$_commentors_name 			= strtolower($thiscomment['name']);
	$_commentors_mail_or_pwd 	= strtolower($thiscomment['email']);

	//	Proceed with author recognition...
	//
	$_author_id = strtolower($thisarticle['authorid']);
	//	$_author_id = $thisarticle['authorid'];	// perhaps the strtolower made the query match no rows!
	//	_sed_cp_log( "Article author ID [{$thisarticle['authorid']}], Auhtor_id [$_author_id]." , __LINE__ );
	if( !empty( $_author_id ) )
		{
		if( !isset($_sed_cached_author_data) or empty($_sed_cached_author_data) )
			{
			$safe_usr = _sed_cp_safe_pfx( 'txp_users' );
			if( 'check-password' == $method )
				{
				//	Pull out the author's real name IF the value in the email field of the form matches the author's password...
				//
				$q = "SELECT name,RealName,email FROM $safe_usr WHERE name='$_author_id' and (pass = password(lower('".doSlash($_commentors_mail_or_pwd)."')) or pass = password('".doSlash($_commentors_mail_or_pwd)."')) and (privs > 0) LIMIT 1";
				}
			else{
				//	Lookup the author's name and email using the author's id...
				//
				$q = "SELECT name,RealName,email FROM $safe_usr WHERE name='$_author_id' LIMIT 1";
				}
			$_sed_cached_author_data = getRow( $q );
			}
		if( $_sed_cached_author_data )
			{
			$_authors_name = strtolower($_sed_cached_author_data['RealName']);
			if( $_authors_name == $_commentors_name )
				{
				if( 'check-password' == $method )
					$out_result = TRUE;			//	TRUE as we have already checked the password as part of the SQL query.
				else
					{
					$_authors_mail = $_sed_cached_author_data['email'];
					if( !empty($_authors_mail) )
						{
						//	If the name & email address of the commentor and author match then this
						// is an 'author' comment.
						//
						if( $_authors_mail == $_commentors_mail_or_pwd )
							$out_result = TRUE;
						else{
							_sed_cp_log( "Author's email != Commentor's email: [$_authors_mail] != [$_commentors_mail_or_pwd]." , __LINE__ );
							}
						}
					else{
						_sed_cp_log( "Author's email is empty, cannot check it!" , __LINE__ );
						}
					}
				}
			else{
				_sed_cp_log( "Article author's name != Commentor's name: [$_authors_name] != [$_commentors_name]." , __LINE__ );
				}
			}
		else{
			_sed_cp_log( "Access to txp_users where name='$_author_id' returned no rows!" , __LINE__ );
			}
		}
	else{
		_sed_cp_log( "Author check failed: Empty author ID." , __LINE__ );
		}

	//	_sed_cp_log( "Finished Author Check, returning [$out_result]." , __LINE__ );
	return $out_result;
	}

function _sed_cp_safe_pfx( $table )
	{
	if( is_callable( 'safe_pfx' ) )
		$table = safe_pfx( $table );
	else
		$table = PFX.$table;

	return $table;
	}
function _sed_cp_get_comment_number( $count='up' )
	{
	global $thiscomment, $thisarticle , $start;			//	Pull these in.
	global $sed_last_comment_id, $sed_comment_number;	//	Define these.

	$result = 0;

	if( !isset( $start ) )
		$start = 0;

	if( empty($sed_last_comment_id) )
		$sed_last_comment_id = 0;
	if( empty($sed_comment_number) )
		$sed_comment_number = 0;

	if ($sed_last_comment_id!=$thiscomment['discussid'])
		$sed_comment_number++;

	$sed_last_comment_id = $thiscomment['discussid'];

	switch( $count )
		{
		case 'guestbook':	$result = $thisarticle['guest_c_count'] - $sed_comment_number + 1;
							break;
		case 'down': 		$result = $thisarticle['comments_count'] - $sed_comment_number + 1;
							break;
		default: 			$result = $sed_comment_number;
		}

	return $result;
	}

function _sed_cp_if_outside_period( $start_time, $period_in_mins, $time_to_check, &$remaining )
	{
	$out = false;
	$lifespan = intval(60 * floatval($period_in_mins) );
	$timesince = ( $time_to_check - $start_time );
	if ( $timesince > $lifespan )
		{
		$remaining = '';
		$out = true;
		}
	else{
		$timeleft = intval($lifespan - $timesince);

		$seconds = $timeleft % 60;
		$timeleft -= $seconds;
		$minutes = ($timeleft / 60) % 60;
		$timeleft -= ($minutes * 60);
		$hours = ($timeleft / 3600) % 24;
		$timeleft -= ($hours * 3600);
		$days = ($timeleft / (3600*24) );

		$remaining  = ($days)    ? ' '.$days.'d' : '';
		$remaining .= ($hours) 	 ? ' '.$hours.'h' : '';
		$remaining .= ($minutes) ? ' '.$minutes.'m' : '';
		$remaining .= ($seconds) ? ' '.$seconds.'s' : '';
		//		$remaining = " seconds";
		}
	return $out;
	}

function _sed_cp_get_comments( $quanta = 7200 )
	{
	global $_sed_cp_new_cmts;
	static $results;
	$cookie_name = 'sed_cp_last_visit';

	#
	#	Get the time now...
	#
	$year = (3600 * 24 * 365);
	$now = time();
	$expiry = $now + $year;

	#
	#	Attempt to read the last visit cookie...
	#
	$last_visit = cs( $cookie_name );
	if( !empty( $last_visit ) )
		{
		#
		#	Calc the time-span between $now and the last-visit time...
		#
		$diff = $now - $last_visit;
		#
		#	If it's beyond the 'visit' quanta, reset the cookie's value to now...
		#
		$new_visit = $diff > $quanta;
		}

	if( empty( $last_visit ) or $new_visit )
		{
		#
		#	Just set the cookie time to now. Nothing else to do!
		#
		setcookie( $cookie_name , $now , $expiry );
		$results = array();
		$_sed_cp_new_cmts = 0;
		}
	else
		{
		if( !isset( $results ) )
			{
			$safe_txp = _sed_cp_safe_pfx( 'textpattern' );
			$safe_cmt = _sed_cp_safe_pfx( 'txp_discuss' );
			$q = "	select		parentid,
								min(concat('',com.discussid)) first_new,
								count(*) new_com_count,
								art.Title
					from 		$safe_cmt com,
								$safe_txp art
					where 		unix_timestamp(com.posted) > $last_visit and
								com.visible=".VISIBLE." and
								com.parentid = art.ID
					group by	com.parentid
					order by 	com.posted desc";

			$rs = getRows($q , 0);
			if ($rs)
				{
				$results = $rs;
				$_sed_cp_new_cmts = 0;
				foreach( $results as $row )
					$_sed_cp_new_cmts += $row['new_com_count'];
				}
			else
				{
				$results = array();
				$_sed_cp_new_cmts = 0;
				}
			}
		}

	return $results;
	}

function _sed_cp_delete_comment( $comment ) {
	$id = $comment['discussid'];
	safe_delete("txp_discuss", "discussid = '$id'" );
	}

function _sed_cp_update_comment( $comment, $action ) {
	$actions = array( 'hide' => MODERATE, 'spam' => SPAM );
	$id = $comment['discussid'];
	$visible = $actions[$action];
	safe_update("txp_discuss", "visible = '$visible'", "discussid = '$id'");
	}

// ----------------  END PRIVATE FUNCTIONS  ------------------
	/*
	register_callback('_sed_cp_comment_save', 'comment.save');

	function _sed_cp_comment_save()
		{
		global $thisarticle, $prefs;

		//
		//	Is there a per-article variable forcing comment moderation on this article?
		//
		$sed_vars = _extract_sed_packed_variable_section( 'cp' );

		if( !empty($sed_vars['sed_moderate']) )
			{
			$evaluator =& get_comment_evaluator();
			$evaluator->add_estimate(MODERATE, 1);
			}
		}
	*/

// ------------------ TAG HANDLERS FOLLOW --------------------
function sed_if_author_comment_string( $atts )
	{
	global $logging;

	extract( lAtts( array(
		'string'		=> ' (Author Comment)',
		'else_string'	=> '',
		'wraptag'		=> 'span',
		'class'			=> 'author-string',
		'method'		=> 'check-email'
		), $atts));

	$logging = false;	// No logging from this call!

	$out_result = $else_string;

	if( !empty($string) )
		{
		if( _sed_cp_if_author_comment( $method ) )
			$out_result = doTag( $string, $wraptag, $class );
		}

	return $out_result;
	}

function sed_comment_time( $atts )
	{
	/*
	Extends the default txp:comment_time tag to include wraptag and class.
	*/
 	extract( lAtts( array(
		'class'			=> 'comment_time',
		'wraptag'		=> 'span',
		'format' 		=> '',
		'gmt'			=> '',
		'lang'	 		=> '',
		), $atts));

	if( isset( $atts['class']) )
		unset( $atts['class'] );
	if( isset( $atts['wraptag']) )
		unset( $atts['wraptag'] );
	return doTag( comment_time($atts) , $wraptag, $class );
	}

function sed_get_comment_class( $atts )
	{
	global $thiscomment, $thisarticle;
	global $logfile , $logging;
	$logfile = 'textpattern'.DS.'tmp'.DS.'sed_comment_pack.log.txt';


	//	print_r( "<br/>===== Start THIS COMMENT =====<br/>\n" );
	//	print_r( $thiscomment );
	//	print_r( "<br/>===== Start THIS ARTICLE =====<br/>\n" );
	//	print_r( $thisarticle );
	//	print_r( "<br/>==============================<br/><br/>\n" );

	extract( lAtts( array(
		'author_class'	=> 'author',
		'hide_odd_even'	=> '',	// Set to any value to surpress odd/even comment marking.
		'odd_class'		=> 'odd',
		'even_class'	=> 'even',
		'count'			=> 'up',
		'class'			=> 'comment',
		'method'		=> 'check-email',
		'per_name'		=> '1',
		'cmtr_prefix'	=> 'commentator',
		'log'			=> 'off',
		// check-password is possible but potentially more insecure that checking an unpublished (private) email address because all email addresses input are stored as part of the txp_discuss table in plain text.
		// It's one thing to have someone spoof your comments if they know your email but it's far worse if they can get access to your account in the system by observing data.
		), $atts));

	$logging = ('on'===$log);
	$out_result = $class;	// Every entry gets at least the base 'class'.

	//
	//	Process odd/even classes...
	//
	if( empty($hide_odd_even) )
		{
		$_comment_num = _sed_cp_get_comment_number( $count );
		if( 0 == ($_comment_num & 0x01) )
			$out_result .= ' '.$even_class;
		else
			$out_result .= ' '.$odd_class;
		}

	//
	//	Process the author_class...
	//
	if( !empty($author_class) and ( _sed_cp_if_author_comment($method) ) )
		$out_result .= " $author_class";
	else
		{
		//	Append a prefixed, dumbed-down, version of the commentator's name to the class defs.
		//
		if( !empty( $per_name ) )
			{
			$basic_name = stripSpace($thiscomment['name']);
			$out_result .= " $cmtr_prefix-$basic_name";
			}
		}

	//
	//	If there are any sed_class_extra variables (from the sed_comments tag handler) then append them too!
	//
	if( !empty($thiscomment['sed_class_extra']) )
		$out_result .= $thiscomment['sed_class_extra'];

	return $out_result;
	}

function sed_comment_number( $atts )
	{
	extract(lAtts(array(
		'count' => 'up',	// Optional, set to 'down' to get decrementing comment count (for example, for guestbook integration.
	),$atts));

	return _sed_cp_get_comment_number( $count );
	}

	//
	//	sed_if_comments is a version of TXP's native if_comments tag but it can
	// be called from a page template - it's not just limited to a TXP form.
	//
function sed_if_comments($atts, $thing)
	{
	global $thisarticle, $pretext;

	$count = 0;

	if( !isset( $thisarticle ) )
		{
		$id = gAtt($atts,'id',gps('id'));
		if (!$id && @$pretext['id'])
			$id = $pretext['id'];

		if( isset( $id ) )
			$count = safe_field( 'comments_count', 'textpattern', "id=$id" );
		}
	else
		$count = $thisarticle['comments_count'];

	return parse(EvalElse($thing, ( $count > 0)));
	}


function sed_cp_new_comment_count( $atts )
	{
	global $_sed_cp_new_cmts;

	/*
	Outputs the number of new comments since the viewer's last visit to the site (if any)...
	*/
	extract( lAtts( array(
		'string'			=> 'New comments: {count}',
		'wraptag'			=> 'p',
		'show_zero_count'	=> 'n',
		'max_visit'			=> 7200,	# length of visit time-out in seconds
		),$atts));

	$show_zero_count = ($show_zero_count === 'y');

	$new_comments = _sed_cp_get_comments( $max_visit );
	if( $_sed_cp_new_cmts > 0 || $show_zero_count )
		return tag( strtr( $string , array( '{count}' => $_sed_cp_new_cmts ) ) , $wraptag );
	}



function sed_cp_new_comment_digest( $atts )
	{
	global $_sed_cp_new_cmts;

	/*
	Outputs a digest of comments since the viewer's last visit to the site (if any)...
	*/
	extract( lAtts( array(
		'class'				=> __FUNCTION__ ,
		'wraptag'			=> 'ul',
		'break'				=> 'li',
		'label'				=> '',
		'labeltag'			=> '',
		'limit'				=> '0',			# set to a number > 0 to limit the list length
		'more'				=> ' &#8230;',
		'max_visit'			=> 7200,		# length of visit time-out in seconds
		),$atts));

	$new_comments = _sed_cp_get_comments( $max_visit );
	if( $_sed_cp_new_cmts > 0 )
		{
		$row_count = count($new_comments);

		if( $limit === '0'  ||  intval($limit) < 0  ||  $row_count === intval($limit) )
			$limit = $row_count+1;
		else
			$limit = intval( $limit );

		foreach( $new_comments as $comment )
			{
			extract( $comment );
			$item = href( escape_title($Title)."($new_com_count)" , permlinkurl_id($parentid).'#c'.$first_new );
			if( --$limit <= 0 )
				{
				$items[] = $item.$more;
				break;
				}
			else
				$items[] = $item;
			}
		return doLabel($label, $labeltag).doWrap($items, $wraptag, $break, $class);
		}
	return '';
	}


function sed_comments($atts)
	{
	global $thisarticle, $prefs, $comment_preview, $pretext;
	extract($prefs);

	extract(lAtts(array(
		'id'		=> @$pretext['id'],
		'form'		=> 'comments',
		'wraptag'	=> ($comments_are_ol ? 'ol' : ''),
		'break'		=> ($comments_are_ol ? 'li' : 'div'),
		'class'		=> __FUNCTION__,
		'breakclass'=> '',
		'sort'		=> 'posted ASC',
	),$atts));

	assert_article();

	if (is_array($thisarticle)) extract($thisarticle);

	if (@$thisid) $id = $thisid;

	//
	//	Extract the sed article overrides...
	//	Access the custom field that houses the vars and explode the string on ';' boundaries.
	//
	$sed_vars = _sed_cp_get_sed_vars( @$thisarticle['sed per-article vars'] );

	$sed_vars = lAtts( array(
		'sed_delay'			=> '0',		// delay to visible in minutes. Empty/zero means no delay.
		'sed_ttl'			=> '',		// time to live in minutes. Empty means forever.
		'sed_on_cull'		=> 'hide',	// action to take when comment is to be culled. hide/spam/delete
		'sed_ttl_grace'		=> '',		// use this to restrict culling. A grace period (minutes) during which any posted comments will not be culled according to the ttl and on_cull variables. Empty means forever. Zero means no grace period.
		), $sed_vars );
	extract( $sed_vars );

	if (!empty($comment_preview)) {
		$preview = psas(array('name','email','web','message','parentid','remember'));
		$preview['time'] = time();
		$preview['discussid'] = 0;
		$preview['message'] = markup_comment($preview['message']);
		$GLOBALS['thiscomment'] = $preview;
		$comments[] = parse_form($form).n;
		unset($GLOBALS['thiscomment']);
		$out = doWrap($comments,$wraptag,$break,$class,$breakclass);
		}
	else {
		$rs = safe_rows_start("*, unix_timestamp(posted) as time", "txp_discuss",
			'parentid='.intval($id).' and visible='.VISIBLE.' order by '.doSlash($sort));

		$out = '';

		if ($rs) {
			$comments = array();
			$culled_comments = array();

			while($vars = nextRow($rs)) {
				$culled = false;
				$show = true;
				$extra = '';
				$now = time();
				$remaining = '';

				//
				//	If the comment is in a deleting page then check if it is to be culled...
				//
				if( !empty($sed_ttl) ) {
					$do_cull_check = true;
					//
					//	Are we in any grace period???
					//
					if( !empty( $sed_ttl_grace ) && (0 !=$sed_ttl_grace) )
						$do_cull_check = _sed_cp_if_outside_period( $thisarticle['posted'],$sed_ttl_grace,$vars['time'],$remaining);
					//
					//	If not then do the cull checking...
					//
					if( $do_cull_check )
						$culled = _sed_cp_if_outside_period( $vars['time'], $sed_ttl, $now, $remaining );

					//
					//	Display how long to go before culling.
					//
					if( $do_cull_check && !$culled )
						$vars['message'] .= "<br/><br/><strong>[MARKED FOR DELETION IN $remaining.]</strong>";
					}

				if( $culled ) {
					$extra.= ' culled';
					$culled_comments[] = $vars;
					$vars['time'] = $now;
					$vars['message'] .= "<br/><br/><strong>[DELETED.]</strong>";
					}
				else {
					//
					//	See if the comment is in its "hidden" period.
					//	This is to try and discourage spam-robots that immediately see if their posts appear live.
					//
					if( !empty( $sed_delay ) && ($sed_delay > '0') )
						$show = _sed_cp_if_outside_period( $vars['time'], $sed_delay, $now, $remaining );

					//
					//	Still hidden so show a place-holder comment instead.
					//
					if( !$show ) {
						$extra.= ' delay_queue';
						$vars['name'] = "[DELAYED]";
						$vars['time'] = $now;
						$vars['message'] = "A comment has been recorded and is in the delay queue.";
						$vars['message'] .= "<br/><br/><strong>[REVEALED IN $remaining.]</strong>";
						}
					}

				//
				//	Save the additional css class markup for this comment in the vars before parsing the comment form.
				//
				$vars['sed_class_extra'] = $extra;

				$GLOBALS['thiscomment'] = $vars;
				$comments[] = parse_form($form).n;
				unset($GLOBALS['thiscomment']);
				}

			$out .= doWrap($comments,$wraptag,$break,$class,$breakclass);

			//
			//	Process the culled list...
			//
			if ( !empty($culled_comments) ) {
				foreach( $culled_comments as $comment )	{
					if( 'delete' == $sed_on_cull )
						_sed_cp_delete_comment( $comment );
					else
						_sed_cp_update_comment( $comment, $sed_on_cull );
					}
				update_comments_count($id);
				}
			}
		}

	return $out;
	}


// ------------------  END TAG HANDLERS  ---------------------

# --- END PLUGIN CODE ---

?>
