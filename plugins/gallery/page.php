<?php
if(isset($this)) {
  global $dom, $config;
  $dom->html->head->addElement("link",null,array("rel"=>"stylesheet","href"=>"plugins/".$this->dir."/style.css"));
  $dom->html->head->addElement("script",null,array("src"=>"plugins/".$this->dir."/jw-player/jwplayer.js"));
  $dom->html->head->addElement("script",null,array("src"=>"plugins/".$this->dir."/script.js"));
  $dom->html->body->div->div->section->addElement("div",null,array("class"=>"text","id"=>$this->dir."Page"));
  $this->wrapper=$dom->getElementById("wrapper");
  if(isset($_GET["gallery"])) {
    require_once("GalleryImage.php");
    $this->wrapper->setAttribute("class",$this->wrapper->getAttribute("class")." inside_gallery");
    $this->images=GalleryImage::getImages($_GET["gallery"]);
    if(isset($this->images[0])){
    $this->id=isset($_GET["image"])?$_GET["image"]:$this->images[0]->id;
    $this->image=new GalleryImage($_GET["gallery"],$this->id);
}
    if(isset($this->image->id)) {
      if(substr($this->image->type,0,5)=="video") {
        $dom->html->body->div->div->section->div->addElement("object",null,array("data"=>"plugins/".$this->dir."/jw-player/player.swf","width"=>640,"height"=>480,"type"=>"application/x-shockwave-flash"))->addElement("param",null,array("name"=>"flashvars","value"=>"file=../images/".$this->image->id.".".GalleryImage::getExt($this->image->type)."&skin=plugins/".$this->dir."/jw-player/skins/blanes/blanes.xml&controlbar.position=over&autostart=true"));
        $dom->html->body->div->div->section->div->object->addElement("param",null,array("name"=>"allowfullscreen","value"=>"true"));
        $dom->html->body->div->div->section->div->object->addElement("param",null,array("name"=>"movie","value"=>"plugins/".$this->dir."/jw-player/player.swf"));
        $dom->html->body->div->div->section->div->object->addElement("video",null,array("src"=>"plugins/".$this->dir."/images/".$this->image->id.".".GalleryImage::getExt($this->image->type)));
      }
      else if(substr($this->image->type,0,5)=="image") {
        $dom->html->body->div->div->section->div->addElement("a")->addElement("img",null,array("src"=>"plugins/".$this->dir."/images/".$this->image->id.".".GalleryImage::getExt($this->image->type), "alt"=>""));
      } else {
        $dom->html->body->div->div->section->div->addElement("object",null,array("data"=>"plugins/".$this->dir."/images/".$this->image->id.".".GalleryImage::getExt($this->image->type),"width"=>640,"height"=>480,"type"=>$this->image->type));
      }
      $dom->html->body->div->div->section->div->addElement("h4",$this->image->name);
      $dom->html->body->div->div->section->div->addElement("span",$this->image->desc);
      $dom->html->body->div->div->section->div->addElement("div",null,array("class"=>"links"));
      $i=1;
      $this->next=false;
      foreach($this->images as $image) {
        $dom->html->body->div->div->section->div->div->addElement("a",null,array("href"=>"?plugin=".$this->dir."&gallery=".$image->id_gallery."&image=".$image->id))->addElement("span",$i);
        if($this->next) {
          if(substr($this->image->type,0,5)=="image") {
            $dom->html->body->div->div->section->div->a->setAttribute("href","?plugin=".$this->dir."&gallery=".$image->id_gallery."&image=".$image->id);
          }
          $this->next=false;
        }
        if($image->id==$this->id) {
          $dom->html->body->div->div->section->div->div->a->setAttribute("class","current");
          $this->next=true;
        }
        $i++;
      }
    }
    else {
      $dom->html->body->div->div->section->div->addElement("span",_("This gallery is empty."));
    }
  }
  else {
    require_once("Gallery.php");
    $this->wrapper->setAttribute("class",$this->wrapper->getAttribute("class")." galleries_list");
    $dom->html->head->addElement("script",null,array("src"=>"plugins/".$this->dir."/script.js"));
    $this->galleries=Gallery::getAll();
    if(count($this->galleries)>1) {
      foreach($this->galleries as $gallery) {
        $dom->html->body->div->div->section->div->addElement("div",null,array("class"=>"gallery"))->addElement("a",null,array("href"=>"?plugin=".$this->dir."&gallery=".$gallery->id))->addElement("img",null,array("src"=>"plugins/".$this->dir."/covers/".$gallery->id.".jpg","width"=>300,"class"=>"cover","id"=>$gallery->id));
        $dom->html->body->div->div->section->div->div->a->addElement("h4",$gallery->name);
        $dom->html->body->div->div->section->div->div->addElement("span",$gallery->desc);
      }
    }
    else {
      header("Location: index.php?plugin=".$this->dir."&gallery=".$this->galleries[0]->id);
    }
  }
}
?>
