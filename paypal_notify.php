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
 * @since     Available since 0.7dev - 2011-06-08
 */

$base_path = '../../';
require_once $base_path . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

//if we've received some informations from paypal website, we can proceed
require_once 'classes/paypal-history.class.php';
if( isset($_POST) && isset($_POST['mc_gross']) && isset($_POST['item_name'])) {
    $ph = new PaypalHistory();
    $ph->add($_POST);

    $log->log(
        'An entry has been added in paypal history',
        PEAR_LOG_INFO
    );

    $s = null;
    foreach ( $_POST as $k=>$v ) {
        if ( $s != null ) {
            $s .= ' | ';
        }
        $s .= $k . '=' . $v;
    }

    $log->log(
        $s,
        PEAR_LOG_DEBUG
    );
} else {
    $log->log(
        'Paypal notify URL call without required arguments!',
        PEAR_LOG_WARNING
    );
}
?>
