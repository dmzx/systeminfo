<?php
/**
* @package System Info
* @copyright (c) 2015 dmzx - http://dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace dmzx\systeminfo\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template)
	{
		$this->config = $config;
		$this->template = $template;
	}

	static public function getSubscribedEvents()
	{

		return array(
			'core.user_setup' => 'load_language_on_setup',
			'core.index_modify_page_title'	=> 'main',
		);
	}

	public function main($event)
	{

	
    $uptimeindex     = shell_exec("cut -d. -f1 /proc/uptime");
    $daysindex       = floor($uptimeindex/60/60/24);
    $hoursindex      = $uptimeindex/60/60%24;
    $minsindex       = $uptimeindex/60%60;
    $secsindex       = $uptimeindex%60;
    $load            = sys_getloadavg();
    $os              = php_uname('s') . ' ' . php_uname('r');


		$this->template->assign_vars(array(
   'UPTIME_DAYS'          => $daysindex,
   'UPTIME_HOURS'         => $hoursindex,
   'UPTIME_MINS'          => $minsindex,
   'UPTIME_SECS'          => $secsindex,
   'AVG_LOAD0'            => $load[0],
   'AVG_LOAD1'            => $load[1],
   'AVG_LOAD2'            => $load[2],
   'PHP_OS'               => $os,
   'BOARD_VERSION'		  => $this->config['version'],
		));
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dmzx/systeminfo',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
}
