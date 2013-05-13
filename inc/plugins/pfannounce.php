<?php 

/*	PFAnnounce 
 *	(C) 2013 sapslaj
 */
 
if(!defined('IN_MYBB'))
{
	die('Error: 404 not found. I swear. There isn\'t anything to see here. ');
}

$plugins->add_hook('index_start', 'pfannounce');
$plugins->add_hook('portal_start', 'pfannounce');

// Plugin info
function pfannounce_info()
{
    return array
    (
        'name'=>'PFAnnounce',
        'description'=>'Header Announcements',
        'website'=>'http://sapslaj.polyforums.com',
        'author'=>'sapslaj',
        'version'=>'1.0',
        'compatibilty'=>'1**' // Compat 1.4 - 1.8
    );
}

function pfannounce_activate()
{
    global $db;
    
    require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
    
    // I have no idea what I'm doing. 
	find_replace_templatesets('index','#'.preg_quote('{$pfannounce}').'#i','',0);
	find_replace_templatesets('portal','#'.preg_quote('{$pfannounce}').'#i','',0);
	find_replace_templatesets('index','#'.preg_quote('{$forums}').'#i','{$pfannounce}{$forums}');
	find_replace_templatesets('portal','#'.preg_quote('{$announcements}').'#i','{$pfannounce}{$announcements}');

    $settingsgroup = array
    (
        'gid' => 'NULL',
        'name' => 'PFAnnounce',
        'title' => 'PFAnnounce',
        'description' => 'PFAnnounce settings',
        'disporder' => "",
        'isdefault' => 'no',
    );
    $db->insert_query('settinggroups', $settingsgroup);

    $gid = $db->insert_id();
    $setting_onoff = array
    (
        'sid' => 'NULL',
        'name' => 'pfannounce_toggle',
        'title' => 'PFAnnounce Toggle',
        'description' => 'Turn plugin On/off',
        'optionscode' => 'onoff',
        'value' => '1',
        'disporder' => 1,
        'gid' => intval($gid),
    );
    
    $setting_head = array
    (
        'sid' => 'NULL',
        'name' => 'pfannounce_header',
        'title' => 'PFAnnounce Header',
        'description' => 'Set the header text',
        'optionscode' => 'textarea',
        'value' => '',
        'disporder' => 2,
        'gid' => intval($gid),
    );
    
    $setting_body = array
    (
        'sid' => 'NULL',
        'name' => 'pfannounce_body',
        'title' => 'PFAnnounce Body',
        'description' => 'Set the body text',
        'optionscode' => 'textarea',
        'value' => '',
        'disporder' => 3,
        'gid' => intval($gid),
    );
    
    $db->insert_query('settings', $setting_onoff);
    $db->insert_query('settings', $setting_head); 
    $db->insert_query('settings', $setting_body);  
}

function pfannounce_deactivate()
{
	require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
	
	// I seriously have no idea what's going on here. I'll figure it out later. Need moar caffeine.
	find_replace_templatesets('index','#'.preg_quote('{$pfannounce}').'#i','',0);
	find_replace_templatesets('portal','#'.preg_quote('{$pfannounce}').'#i','',0);

    global $db;
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN ('pfannounce_header')");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN ('pfannounce_body')");
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='PFAnnounce'");
}

function pfannounce()
{
    global $mybb;
    
    if($mybb->settings['pfannounce_toggle'])
    {
        global $theme, $pfannounce;
        // TODO: Use templates. Get rid of this repulsive hard coded HTML. 
        $pfannounce='<table border="0" cellspacing="'.$theme['borderwidth'].'" cellpadding="'.$theme['tablespace'].'" class="tborder">
	<thead>
		<tr>
			<td class="thead">
				<strong>'.$mybb->settings['pfannounce_header'].'</strong>
			</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="trow1">'.$mybb->settings['pfannounce_body'].'</td>
		</tr>
	</tbody>
</table>
<br />';
    }
}

?>
