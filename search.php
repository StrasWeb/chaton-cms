<?php
/**
 * Search script that redirects to a Google search
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($_GET['search_field'])) {
    $search=$_GET['search_field'];
    $fullpath=isset(
        $_SERVER["HTTPS"]
    )?$_SERVER["HTTPS"]:"http://".$_SERVER["HTTP_HOST"].substr(
        $_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1
    );
    $url="http://www.google.com/search?q=site:".$fullpath."+".$search;
    header("Location: ".$url);
}
?>
