<?php //{{MediaWikiExtension}}<source lang="php">
/*
 * Livejournal_User.php - A MediaWiki tag extension which adds support for injecting <lj user=""/> and <lj community=""/>  tags
 * @author Jehy
 * @version 0.2.1
 * @copyright Copyright (C) 2008-2012 Jehy
 * @license The MIT License - http://www.opensource.org/licenses/mit-license.php
 * -----------------------------------------------------------------------
 * Description:
 *     This is a MediaWiki extension which adds support for injecting <lj user=""> and <lj community="">  tags
 *     into the page header.
 * Requirements:
 *     MediaWiki 1.6.x, 1.8.x, 1.9.x, ... , 1.19 or higher
 *     PHP 4.x, 5.x or higher
 * Installation:
 *     1. Drop this script (Livejournal_User.php) in $IP/extensions
 *         Note: $IP is your MediaWiki install dir.
 *     2. Enable the extension by adding this line to your LocalSettings.php:
 *         require_once('extensions/Livejournal_User.php');
 * Usage:
 *     Once installed, you may utilize Livejournal_User by adding the <lj user/> and <lj community/> tags to articles:
 *          <lj user="jehy"/> and <lj community="yaoi_ru"/>
 * Version Notes:
 *     version 0.2.1
 * -----------------------------------------------------------------------
 * Copyright (c) 2012 Jehy
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * -----------------------------------------------------------------------
 */

# Confirm MW environment
if( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is part of MediaWiki, it is not a valid entry point.\n";
	exit( 1 );
}

# Credits
$wgExtensionCredits['parserhook'][] = array(
    'name'=>'Livejournal_User',
	'author' => 'Jehy http://jehy.ru/index.en.html',
	'url' => 'http://jehy.ru/wiki-extensions.en.html',
    'description'=>'Adds cute tags (&lt;lj user=""/&gt; and &lt;lj community=""/&gt;) for linking to livejornal users and communities',
    'version'=>'0.2.1'
);


if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
	$wgHooks['ParserFirstCallInit'][] = 'setupLivejournal_UserParserHooks';
} else { // Otherwise do things the old fashioned way
	$wgExtensionFunctions[] = 'setupLivejournal_UserParserHooks';
}




/**
 * Sets up the Livejournal_User Parser hook and system messages
 */
function setupLivejournal_UserParserHooks() {
	global $wgParser, $wgMessageCache;
	$wgParser->setHook( 'lj', 'renderLivejournal_User' );
    /*$wgMessageCache->addMessage(
        'Livejournal_User-missing-content',
        'Error: &lt;keywords&gt; tag must contain a &quot;user&quot; or &quot;community&quot; attribute.'
    );*/
    return true;
}

function renderLivejournal_User( $text, $params, $parser )
{global $Livejournal_User_Remote_Images;
    # Short-circuit with error message if content is not specified.
    if (!($params['user'])&&!($params['community'])) {
        return
            '<div class="errorbox">'.
            wfMsgForContent('Livejournal_User-missing-content').
            '</div>';
    }
    if($params['user'])
    {
    	if($Livejournal_User_Remote_Images==1)
    		return ('<a rel="nofollow" href="http://users.livejournal.com/'. htmlspecialchars( $params['user'] ) .'/profile"><img src="http://p-stat.livejournal.com/img/userinfo.gif"></a>&nbsp;<b><a rel="nofollow" href="http://users.livejournal.com/'. htmlspecialchars( $params['user'] ) .'">'.$params['user'].'</a></b>');
    	else
    		return $parser->recursiveTagParse('[[Image:Livejournal_UserInfo.gif]]'."&nbsp;'''".'[http://users.livejournal.com/'.$params['user'].'/profile '.$params['user'].']'."'''");
    }
    
    
    elseif($params['community'])
    {
    	if($Livejournal_User_Remote_Images==1)
    		return ('<a rel="nofollow" href="http://community.livejournal.com/'. htmlspecialchars( $params['community'] ) .'/profile"><img src="http://p-stat.livejournal.com/img/community.gif"></a>&nbsp;<b><a rel="nofollow" href="http://community.livejournal.com/'. htmlspecialchars( $params['community'] ) .'">'.$params['community'].'</a></b>');
    	else
    		return $parser->recursiveTagParse('[[Image:Livejournal_CommunityInfo.gif]]'."&nbsp;'''".'[http://community.livejournal.com/'.$params['community'].'/profile '.$params['community'].']'."'''");
    }
     else return 'Here should be liveournal info... Why not?';
}


