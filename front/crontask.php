<?php
/*
 * @version $Id: computer.php 8027 2009-02-28 17:08:00Z moyo $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Remi Collet
// Purpose of file: Search engine from cron tasks
// ----------------------------------------------------------------------


$NEEDED_ITEMS = array ('admininfo', 'computer', 'crontask', 'device', 'document', 'enterprise',
   'group', 'infocom', 'mailgate', 'mailing', 'monitor', 'networking', 'ocsng', 'peripheral',
   'phone', 'printer', 'registry', 'reminder', 'reservation', 'rulesengine',
   'rule.dictionnary.dropdown', 'rule.dictionnary.software', 'rule.ocs',
   'rule.softwarecategories', 'rule.tracking', 'search', 'setup', 'software', 'tracking', 'user');

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

checkRight("config", "w");

if (isset($_GET['execute'])) {
   if (is_numeric($_GET['execute'])) {
      $name = CronTask::launch(0,$_GET['execute']);
   } else {
      $name = CronTask::launch(0,1,$_GET['execute']);
   }
   if ($name) {
      addMessageAfterRedirect($LANG['crontask'][40]." : ".$name);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
}
commonHeader($LANG['crontask'][0],$_SERVER['PHP_SELF'],"config","crontask");

$crontask = new CronTask();
//if ($crontask->getNeedToRun(CRONTASK_MODE_EXTERNAL)) {
if ($crontask->getNeedToRun()) {
   displayTitle(GLPI_ROOT.'/pics/warning.png',$LANG['crontask'][41],
      $LANG['crontask'][41]."&nbsp;: ".$crontask->fields['name'],
      array($_SERVER['PHP_SELF']."?execute=1"=>$LANG['buttons'][57]));
} else {
   displayTitle(GLPI_ROOT.'/pics/ok.png',$LANG['crontask'][43],$LANG['crontask'][43]);
}

manageGetValuesInSearch(CRONTASK_TYPE);
searchForm(CRONTASK_TYPE,$_GET);
showList(CRONTASK_TYPE,$_GET);

commonFooter();
?>
