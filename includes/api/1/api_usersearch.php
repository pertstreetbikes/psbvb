<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin Blog 4.2.1 - Licence Number VBF357ECB6
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2013 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
if (!VB_API) die;

class vB_APIMethod_api_usersearch extends vBI_APIMethod
{
	public function output()
	{
		global $vbulletin, $db;

		$vbulletin->input->clean_array_gpc('p', array('fragment' => TYPE_STR));

		$vbulletin->GPC['fragment'] = convert_urlencoded_unicode($vbulletin->GPC['fragment']);

		if ($vbulletin->GPC['fragment'] != '' AND strlen($vbulletin->GPC['fragment']) >= 3)
		{
			$fragment = htmlspecialchars_uni($vbulletin->GPC['fragment']);
		}
		else
		{
			$fragment = '';
		}

		if ($fragment != '')
		{
			$users = $db->query_read_slave("
				SELECT user.userid, user.username FROM " . TABLE_PREFIX . "user
				AS user WHERE username LIKE('" . $db->escape_string_like($fragment) . "%')
				ORDER BY username
				LIMIT 15
			");
			while ($user = $db->fetch_array($users))
			{
				$data[$user['userid']] = $user['username'];
			}
		}

		return $data;

	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 00:11, Tue Sep 24th 2013
|| # CVS: $RCSfile$ - $Revision: 26995 $
|| ####################################################################
\*======================================================================*/