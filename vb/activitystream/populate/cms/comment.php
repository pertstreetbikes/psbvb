<?php

/* ======================================================================*\
  || #################################################################### ||
  || # vBulletin 4.2.1 - Licence Number VBF357ECB6
  || # ---------------------------------------------------------------- # ||
  || # Copyright �2000-2013 vBulletin Solutions Inc. All Rights Reserved. ||
  || # This file may not be redistributed in whole or significant part. # ||
  || # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
  || # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
  || #################################################################### ||
  \*====================================================================== */

/**
 * Class to populate the activity stream from existing content
 *
 * @package	vBulletin
 * @version	$Revision: 57655 $
 * @date		$Date: 2012-01-09 12:08:39 -0800 (Mon, 09 Jan 2012) $
 */
class vB_ActivityStream_Populate_Cms_Comment extends vB_ActivityStream_Populate_Base
{
	/**
	 * Constructor - set Options
	 *
	 */
	public function __construct()
	{
		return parent::__construct();
	}

	/*
	 * Don't get: Deleted threads, redirect threads, CMS comment threads
	 *
	 */
	public function populate()
	{
		if (!vB::$vbulletin->products['vbcms'] OR !vB::$vbulletin->options['vbcmsforumid'])
		{
			return;
		}

		$typeid = vB::$vbulletin->activitystream['cms_comment']['typeid'];
		$this->delete($typeid);

		if (!vB::$vbulletin->activitystream['cms_comment']['enabled'])
		{
			return;
		}

		$timespan = TIMENOW - vB::$vbulletin->options['as_expire'] * 60 * 60 * 24;
		vB::$db->query_write("
			INSERT INTO " . TABLE_PREFIX . "activitystream
				(userid, dateline, contentid, typeid, action)
				(SELECT
					post.userid, post.dateline, post.postid, '{$typeid}', 'create'
				FROM " . TABLE_PREFIX . "post AS post
				INNER JOIN " . TABLE_PREFIX . "thread AS thread ON (post.threadid = thread.threadid)
				WHERE
					post.dateline >= {$timespan}
						AND
					post.postid <> thread.firstpostid
						AND
					thread.open <> 10
						AND
					thread.forumid = " . vB::$vbulletin->options['vbcmsforumid'] . "
				)
		");
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 00:11, Tue Sep 24th 2013
|| # CVS: $RCSfile$ - $Revision: 57655 $
|| ####################################################################
\*======================================================================*/