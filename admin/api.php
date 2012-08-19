<?php
/**
 * API to access Chaton from an external application
 *
 * PHP Version 5.3.6
 * 
 * @category Admin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
chdir("..");
require_once "classes/Config.php";
$config=new Config();
require_once "classes/Article.php";
if (isset($_GET["article"])) {
    $article=new Article($config->lang, $_GET["article"]);
    print(json_encode($article));
} else {
    $articles=Article::getList();
    print(json_encode($articles));
}
?>
