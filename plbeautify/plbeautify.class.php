<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_plbeautify {

	var $isopen = '';
	var $lazyon = '';
	var $showlv = '';
	var $module_setting = array();
	var $lazymod = array();
	var $modules = array(
			'forumdisplay' => 1,
			'viewthread' => 2,
		);

	function plugin_plbeautify() {
		global $_G;
		$this->isopen = $_G['cache']['plugin']['plbeautify']['isopen'];
		$this->lazyon = $_G['cache']['plugin']['plbeautify']['lazyon'];
		$this->showlv = $_G['cache']['plugin']['plbeautify']['showlv'];
		$this->module_setting = (array)unserialize($_G['cache']['plugin']['plbeautify']['lazyloads']);
		$this->lazymod = array_intersect($this->module_setting, $this->modules);
	}

	function global_header() {
		$thestyle = '';
		if($this->isopen && CURMODULE == 'forumdisplay') {
			$thestyle = lang('plugin/plbeautify', 'thestyle');
		}
		if($this->lazyon && in_array($this->modules[CURMODULE], $this->lazymod)) {
			$thestyle .= lang('plugin/plbeautify', 'style_add');
		}
		return $thestyle;
	}
}

class plugin_plbeautify_forum extends plugin_plbeautify {

	function forumdisplay_author_output() {
		global $_G;

		$plbeautify = array();
		if($this->isopen) {
			foreach($_G['forum_threadlist'] as $member) {
				$avatarimg = '<img src="source/plugin/plbeautify/images/noavatar_small.gif">';
				if(!empty($member['author']) || $_G['adminid'] == 1) {
					$avatarimg = '<a href="home.php?mod=space&uid='.$member['authorid'].'" c="1">'.avatar($member['authorid'], 'small').'</a>';
				}
				$plbeautify[] = '<span class="pstyle">'.$avatarimg.'</span>';
			}
		}
		return $plbeautify;
	}

	function viewthread_sidetop_output() {
		global $_G, $postlist;

		$pllvl = array();
		if($this->showlv) {
			foreach($postlist as $post) {
				$lower = $_G['cache']['usergroups'][$post['groupid']]['creditslower'];
				$higher = $_G['cache']['usergroups'][$post['groupid']]['creditshigher'];
				$lvlup = round(($post['credits'] - $higher) / ($lower - $higher), 4);
				$needcredit = $lower < $post['credits'] ? 0 : $lower-$post['credits'];
				$width = $lvlup < 0 ? 0 : (50 * $lvlup);
				$cper = $lvlup < 0 ? 0 : ($lvlup * 100);
				$pllvl[] = $lower ? lang('plugin/plbeautify', 'lvup', array('$pcredits' => $post['credits'], '$needcredit' => $needcredit, '$width' => $width, '$cper' => $cper)) : '';
			}
		}

		return $pllvl;
	}

}
?>