<?php
/**
 * Gallery plugin
 * Admin page
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom)) {
    if (isset($_GET["page"]) && $_GET["page"]=="help") {
        include "help.php";
    } else {
        $dom->html->body->div->div
            ->addElement("h2", null, array("class"=>"subtitle"))
            ->addElement(
                "a", _("Gallery"),
                array("href"=>"index.php?tab=plugin&dir=".$_GET["dir"])
            );
        $dom->html->body->div->div->h2
            ->addElement("form", null, array(), true)
            ->addElement(
                "button", _("Help"),
                array("type"=>"submit", "class"=>"help-button")
            );
        $dom->html->body->div->div->h2->form->addElement(
            "input", null,
            array("type"=>"hidden", "value"=>"plugin", "name"=>"tab")
        );
        $dom->html->body->div->div->h2->form->addElement(
            "input", null,
            array("type"=>"hidden", "value"=>$_GET["dir"], "name"=>"dir")
        );
        $dom->html->body->div->div->h2->form->addElement(
            "input", null,
            array("type"=>"hidden", "value"=>"help", "name"=>"page")
        );
        include_once "Gallery.php";
        include_once "GalleryImage.php";
        if (isset($_FILES["image"]) && $_FILES["image"]["size"] != 0) {
            if (isset($_POST["over"])) {
                if ($_POST["over"]=="yes") {
                    $dest="/plugins/".$_GET["dir"]."/covers/".$_POST["gallery"].
                    "_over.".GalleryImage::getExt($_FILES["image"]["type"]);
                } else {
                    $dest="/plugins/".$_GET["dir"]."/covers/".$_POST["gallery"].
                    ".".GalleryImage::getExt($_FILES["image"]["type"]);
                }
            } else {
                $dest="/plugins/".$_GET["dir"]."/images/".GalleryImage::add(
                    $_GET["gallery"], $_FILES["image"]["type"]
                ).".".GalleryImage::getExt($_FILES["image"]["type"]);
            }
            if (move_uploaded_file($_FILES["image"]["tmp_name"], getcwd().$dest)) {
                trigger_error(_("Image successfully uploaded"), E_USER_NOTICE);
                header("Refresh: 2;URL=index.php?tab=plugin&dir=".$_GET["dir"]);
            } else {
                trigger_error(
                    _("Unable to copy the file. Please check folder permissions."),
                    E_USER_WARNING
                );
            }
            
            
        } else if (isset($_GET["edited"])) {
            if (isset($_GET["image"])) {
                $image = new GalleryImage($_GET["gallery"], $_GET["image"]);
                $image->name=$_GET["name"];
                $image->desc=$_GET["desc"];
                if ($image->update()) {
                    $dom->html->body->div->div->addElement(
                        "div", _("Gallery successfully updated!"),
                        array("class"=>"modified")
                    );
                }
            } else {
                $gallery = new Gallery($_GET["id"]);
                $gallery->name=$_GET["name"];
                $gallery->desc=$_GET["desc"];
                if ($gallery->update()) {
                    $dom->html->body->div->div->addElement(
                        "div", _("Gallery successfully updated!"),
                        array("class"=>"modified")
                    );
                }
            }
        }
        if (isset($_POST["order"])) {
            if ($_POST["order"]=="image") {
                $images=GalleryImage::getImages($_GET["gallery"]);
                foreach ($images as $image) {
                    $image=new GalleryImage($image->id_gallery, $image->id);
                    $image->num=$_POST[$image->id];
                    $image->update();
                }
            } else if ($_POST["order"]=="gallery") {
                $galleries=Gallery::getAll();
                foreach ($galleries as $gallery) {
                    $gallery=new Gallery($gallery->id);
                    $gallery->num=$_POST[$gallery->id];
                    $gallery->update();
                }
            }
        }
        if (isset($_GET["cover"])) {
            $dom->html->body->div->div
                ->addElement(
                    "form", null,
                    array("method"=>"POST",
                    "action"=>"?tab=plugin&dir=gallery&gallery=".$_GET["gallery"],
                    "enctype"=>"multipart/form-data")
                );
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"radio", "name"=>"over", "value"=>"no",
                    "id"=>"radiofalse", "checked"=>true)
                );
            $dom->html->body->div->div->form
                ->addElement("label", _("Base cover"), array("for"=>"radiofalse"));
            $dom->html->body->div->div->form->addElement("br");
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"radio", "name"=>"over",
                    "value"=>"yes", "id"=>"radiotrue")
                );
            $dom->html->body->div->div->form
                ->addElement(
                    "label", _("Rollover cover"), array("for"=>"radiotrue")
                );
            $dom->html->body->div->div->form->addElement("br");
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null, array("type"=>"file", "name"=>"image")
                );
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "name"=>"gallery",
                    "value"=>$_GET["gallery"])
                );
            $dom->html->body->div->div->form
                ->addElement("input", null, array("type"=>"submit"));	
        } else if (isset($_GET["delete"])) {
            if (isset($_GET["image"])) {
                $image=new GalleryImage($_GET["gallery"], $_GET["image"]);
                $image->delete();
                header(
                    "Location: index.php?tab=plugin&dir=gallery&gallery=".
                    $_GET["gallery"]
                );
            } else {
                $gallery=new Gallery($_GET["gallery"]);
                $gallery->delete();
                header("Location: index.php?tab=plugin&dir=gallery");
            }
        } else if (isset($_GET["edit"])) {
            $dom->html->body->div->div->addElement("form");
            if (isset($_GET["image"])) {
                $dom->html->body->div->div->form->addElement(
                    "input", null, array("type"=>"hidden",
                    "value"=>$_GET["image"], "name"=>"image")
                );
                $dom->html->body->div->div->form->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>$_GET["gallery"],
                    "name"=>"gallery")
                );
                $item=new GalleryImage($_GET["gallery"], $_GET["image"]);
            } else {
                $item=new Gallery($_GET["gallery"]);
                $dom->html->body->div->div->form
                    ->addElement(
                        "input", null, array("type"=>"hidden",
                        "value"=>$_GET["gallery"], "name"=>"id")
                    );
            }
            
            $dom->html->body->div->div->form
                ->addElement("label", _("Name:")." ", array("for"=>"name"));
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("name"=>"name", "id"=>"name", "value"=>$item->name)
                );
            $dom->html->body->div->div->form->addElement("br");
            $dom->html->body->div->div->form
                ->addElement("label",  _("Description:")." ", array("for"=>"desc"));
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("name"=>"desc", "id"=>"desc", "value"=>$item->desc)
                );
            $dom->html->body->div->div->form->addElement("br");
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>true, "name"=>"edited")
                );
            $dom->html->body->div->div->form->addElement(
                "input", null,
                array("type"=>"hidden", "value"=>"plugin", "name"=>"tab")
            );
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>"gallery", "name"=>"dir")
                );
            $dom->html->body->div->div->form
                ->addElement("input", null, array("type"=>"submit"));
        } else if (isset($_GET["added"])) {
            $gallery = new Gallery();
            $gallery->name=$_GET["name"];
            $gallery->desc=$_GET["desc"];
            $gallery->add();
            $dom->html->body->div->div->addElement(
                "div", _("Gallery successfully added!"),
                array("class"=>"modified")
            );
            header(
                "Refresh: 2; URL=index.php?tab=plugin&dir=".$_GET["dir"]
            );
        } else if (isset($_GET["title"])) {
            if ($plugin->setParam("gallery_title", $_GET["title"])) {
                $dom->html->body->div->div->addElement(
                    "div", _("Title successfully updated!"),
                    array("class"=>"modified")
                );
            }
        } else if (isset($_GET["gallery"])) {
            $images=GalleryImage::getImages($_GET["gallery"]);
            if (!empty($images)) {
                $dom->html->body->div->div->addElement(
                    "form", null,
                    array("method"=>"POST",
                    "action"=>"?tab=plugin&dir=".$_GET["dir"].
                    "&gallery=".$_GET["gallery"])
                );
                $dom->html->body->div->div->form->addElement("ul");
                foreach ($images as $image) {
                    $dom->html->body->div->div->form->ul
                        ->addElement("li")->addElement("span", $image->name);
                    $dom->html->body->div->div->form->ul->li->addElement("br");
                    $dom->html->body->div->div->form->ul->li
                        ->addElement(
                            "input", null,
                            array("size"=>1, "maxlength"=>2,
                            "value"=>$image->num, "name"=>$image->id)
                        );
                    $dom->html->body->div->div->form->ul->li->addElement("br");
                    if (substr($image->type, 0, 5)=="video") {
                        $dom->html->body->div->div->form->ul->li
                            ->addElement(
                                "object", null,
                                array("data"=>"../plugins/".$_GET["dir"].
                                "/jw-player/player.swf",
                                "width"=>200, "height"=>150,
                                "type"=>"application/x-shockwave-flash")
                            )->addElement(
                                "param", null,
                                array("name"=>"flashvars",
                                "value"=>"file=../images/".$image->id.
                                ".".GalleryImage::getExt($image->type))
                            );
                        $dom->html->body->div->div->form->ul->li->object
                            ->addElement(
                                "param", null,
                                array("name"=>"allowfullscreen", "value"=>"true")
                            );
                        $dom->html->body->div->div->form->ul->li->object
                            ->addElement(
                                "param", null,
                                array("name"=>"movie",
                                "value"=>"../plugins/".$_GET["dir"].
                                "/jw-player/player.swf")
                            );
                        $dom->html->body->div->div->form->ul->li->object
                            ->addElement(
                                "video", null,
                                array("src"=>"plugins/".$_GET["dir"]."/images/".
                                $image->id.".".GalleryImage::getExt($image->type),
                                "width"=>200, "height"=>150)
                            );

                    } else {
                        $dom->html->body->div->div->form->ul->li
                            ->addElement(
                                "img", null,
                                array("src"=>"../plugins/".$_GET["dir"].
                                "/images/".$image->id.".".
                                GalleryImage::getExt($image->type),
                                "width"=>"200")
                            );
                    }
                    $dom->html->body->div->div->form->ul->li
                        ->addElement("br");
                    $dom->html->body->div->div->form->ul->li
                        ->addElement(
                            "a", _("Delete"),
                            array("href"=>"?tab=plugin&dir=".$_GET["dir"].
                            "&image=".$image->id."&delete=1&gallery=".
                            $_GET["gallery"], "class"=>"deleteBtn")
                        );
                    $dom->html->body->div->div->form->ul->li
                        ->addElement(
                            "a", _("Edit title/description"),
                            array("href"=>"?tab=plugin&dir=".$_GET["dir"].
                            "&image=".$image->id."&edit=1&gallery=".$_GET["gallery"])
                        );
                }   
                $dom->html->body->div->div->form->addElement(
                    "input", null,
                    array("type"=>"hidden", "name"=>"order", "value"=>"image")
                );
                $dom->html->body->div->div->form->addElement(
                    "input", null,
                    array("type"=>"submit", "value"=>_("Save order"))
                );
            }
            $dom->html->body->div->div
                ->addElement(
                    "form", null,
                    array("method"=>"POST",
                    "action"=>"?tab=plugin&dir=".$_GET["dir"].
                    "&gallery=".$_GET["gallery"],
                    "enctype"=>"multipart/form-data")
                )->addElement("input", null, array("type"=>"file", "name"=>"image"));
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"submit", "value"=>_("Upload an image"))
                );
        } else if (isset($_GET["add"])) {
            $dom->html->body->div->div->addElement("form")
                ->addElement("label", _("Name:")." ", array("for"=>"name"));
            $dom->html->body->div->div->form
                ->addElement("input", null, array("name"=>"name", "id"=>"name"));
            $dom->html->body->div->div->form->addElement("br");
            $dom->html->body->div->div->form
                ->addElement("label",  _("Description:")." ", array("for"=>"desc"));
            $dom->html->body->div->div->form
                ->addElement("input", null, array("name"=>"desc", "id"=>"desc"));
            $dom->html->body->div->div->form->addElement("br");
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>true, "name"=>"added")
                );
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>"plugin", "name"=>"tab")
                );
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>"gallery", "name"=>"dir")
                );
            $dom->html->body->div->div->form
                ->addElement("input", null, array("type"=>"submit"));
        
        } else {
            $dom->html->body->div->div->h2
                ->addElement("form", null, array(), true)
                ->addElement(
                    "button", _("Add a new gallery"),
                    array("type"=>"submit", "class"=>"add-button")
                );
            $dom->html->body->div->div->h2->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>"plugin", "name"=>"tab")
                );
            $dom->html->body->div->div->h2->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>$_GET["dir"], "name"=>"dir")
                );
            $dom->html->body->div->div->h2->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>true, "name"=>"add")
                );
            if (isset($plugin)) {
                $title=$plugin->getParam("gallery_title");
            }
            $dom->html->body->div->div->addElement("form")
                ->addElement(
                    "label", _("Gallery title:")." ",
                    array("for"=>"title")
                );
            $dom->html->body->div->div->form
                ->addElement("input", null, array("value"=>$title, "name"=>"title"));
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null, 
                    array("type"=>"hidden", "value"=>"plugin", "name"=>"tab")
                );
            $dom->html->body->div->div->form
                ->addElement(
                    "input", null,
                    array("type"=>"hidden", "value"=>$_GET["dir"], "name"=>"dir")
                );
            $dom->html->body->div->div->form
                ->addElement("input", null, array("type"=>"submit"));
            $galleries=Gallery::getAll();
            if (!empty($galleries)) {
                $dom->html->body->div->div->addElement(
                    "form", null, array(
                        "method"=>"POST", "action"=>"?tab=plugin&dir=".$_GET["dir"]
                    )
                );
                $dom->html->body->div->div->form->addElement("ul");
                foreach ($galleries as $gallery) {
                    $dom->html->body->div->div->form->ul->addElement("li")
                        ->addElement(
                            "a", _("Delete"), array("href"=>"?tab=plugin&dir=".
                            $_GET["dir"]."&gallery=".$gallery->id."&delete=1",
                            "class"=>"deleteBtn")
                        );
                    $dom->html->body->div->div->form->ul->li
                        ->addElement("span", "  ");
                    $dom->html->body->div->div->form->ul->li
                        ->addElement(
                            "a", null, array("href"=>"?tab=plugin&dir=".$_GET["dir"].
                            "&gallery=".$gallery->id)
                        )->addElement("strong", $gallery->name);
                    $dom->html->body->div->div->form->ul->li
                        ->addElement("span", "  ");
                    $dom->html->body->div->div->form->ul->li
                        ->addElement(
                            "a", _("Edit title/description"),
                            array("href"=>"?tab=plugin&dir=".$_GET["dir"].
                            "&gallery=".$gallery->id."&edit=1")
                        );
                    $dom->html->body->div->div->form->ul->li->addElement(
                        "a", _("Upload a new cover"),
                        array(
                            "href"=>"?tab=plugin&dir=".$_GET["dir"]."&gallery=".
                            $gallery->id."&cover=1"
                        )
                    );
                    $dom->html->body->div->div->form->ul->li
                        ->addElement(
                            "input", null, array(
                                "size"=>1, "maxlength"=>2, "value"=>$gallery->num,
                                "name"=>$gallery->id
                            )
                        );
                }
                $dom->html->body->div->div->form
                    ->addElement(
                        "input", null, 
                        array("type"=>"hidden", "name"=>"order", "value"=>"gallery")
                    );
                $dom->html->body->div->div->form
                    ->addElement(
                        "input", null,
                        array("type"=>"submit", "value"=>_("Save order"))
                    );
            }
        }
    }
}
?>
