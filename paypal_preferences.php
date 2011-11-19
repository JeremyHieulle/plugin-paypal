<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Paypal form
 *
 * This page can be loaded directly, or via ajax.
 * Via ajax, we do not have a full html page, but only
 * that will be displayed using javascript on another page
 *
 * PHP version 5
 *
 * Copyright © 2011 The Galette Team
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Plugins
 * @package   GalettePaypal
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2011 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7dev - 2011-06-05
 */

$base_path = '../../';
require_once $base_path . 'includes/galette.inc.php';

if ( !$login->isLogged() ) {
    header('location: ' . $base_path . 'index.php');
    die();
}
if ( !$login->isAdmin() ) {
    header('location: ' . $base_path . 'voir_adherent.php');
    die();
}

//Constants and classes from plugin
require_once '_config.inc.php';
require_once 'classes/paypal.class.php';

$paypal = new Paypal();

if ( isset($_POST['paypal_id']) ) {
    if ( isset($_POST['paypal_id']) ) {
        $paypal->setId($_POST['paypal_id']);
    }
    if ( isset($_POST['amount_id']) && isset($_POST['amounts']) ) {
        $paypal->setPrices($_POST['amount_id'], $_POST['amounts']);
    }
    if (isset($_POST['inactives']) ) {
        $paypal->setInactives($_POST['inactives']);
    } else {
        $paypal->unsetInactives();
    }
    $stored = $paypal->store();
}

$tpl->assign('page_title', _T("Paypal Settings"));

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$tpl->compile_id = PAYPAL_SMARTY_PREFIX;
$tpl->assign('paypal', $paypal);
$tpl->assign('amounts', $paypal->getAllAmounts());
if ( isset($stored) ) {
    $tpl->assign('stored', $stored);
}
$content = $tpl->fetch('paypal_preferences.tpl', PAYPAL_SMARTY_PREFIX);
$tpl->assign('content', $content);
//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', PAYPAL_SMARTY_PREFIX);

?>
