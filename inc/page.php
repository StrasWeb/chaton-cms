<?php
/**
 * Display a page
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom)) {
    if (isset($page->id)) {
        $dom->html->head->title->nodeValue.=" - ".$page->title;
        $dom->html->body->div->div->section
            ->addElement(
                "div", null, array(
                    "id"=>"page", "lang"=>$page->lang, "itemscope"=>null,
                    "itemtype"=>"http://schema.org/WebPage"
                )
            )->addElement(
                "h2", null, array("class"=>"page-title", "itemprop"=>"name")
            )->addElement("span",  $page->title);
        $dom->html->body->div->div->section->div
            ->addElement(
                "div", null, array(
                    "class"=>"text", "id"=>"pageText",
                    "itemprop"=>"mainContentOfPage", "itemscope"=>null,
                    "itemtype"=>"http://schema.org/WebPageElement"
                )
            );
        $dom->html->body->div->div->section->div->div->content
            =$dom->createDocumentFragment();
        $dom->html->body->div->div->section->div->div->content
            ->appendXML(stripslashes($page->content));
        $dom->html->body->div->div->section->div->div
            ->appendChild(
                $dom->html->body->div->div->section->div->div->content
            );
    } else {
        include "404.php";
    }
}
?>
