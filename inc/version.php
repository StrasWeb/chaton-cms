<?php
/**
 * Variables containing the version number
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($config)) {
    /*Dans un fichier à part pour être accessible
     * à la fois par l'index et l'install*/
    //Il faudra voir ce qu'on en fait pour les mises à jour
    $config->chaton_ver="0.4pre";
    $config->chaton_shortver="0.4";
    $config->pre_ver=true;
}
?>
