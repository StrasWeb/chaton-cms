<?php
if(isset($this)){
global $dom,$article,$page,$config;
if((isset($article->id)||isset($page->id))) {
  $dom->html->head->addElement("link",null,array("rel"=>"stylesheet","href"=>"plugins/".$this->dir."/style.css"));
  $this->article=$dom->getElementByID("main");
  $this->article->firstChild->firstChild->addElement("a",null,array("data-ajax"=>"false"),true)->addElement("img",null,array("src"=>"plugins/".$this->dir."/Adobe_PDF_Icon.svg","alt"=>"PDF","class"=>"toPDF","height"=>30,"title"=>_("Export to PDF")));
  if(isset($article)) {
    $this->article->firstChild->firstChild->a->setAttribute("href","plugins/".$this->dir."/toPDF.php?news=".$article->id."&lang=".$article->lang);
  }
  elseif(isset($page)) {
    $this->article->firstChild->firstChild->a->setAttribute("href","plugins/".$this->dir."/toPDF.php?page=".$page->id."&lang=".$page->lang);
  }
}
$this->PDFLink=$dom->getElementById("cartLinks");
if(!empty($this->PDFLink)){
$this->PDFLink->addElement("span", "  ", array("class"=>"space"));
	$this->PDFLink->addElement("input", null, array("type"=>"hidden", "name"=>"pdf", "value"=>$this->dir));
	$this->PDFLink->addElement("input", null, array("type"=>"submit", "name"=>"pdfButton", "value"=>_("Download order form"), "id"=>"PDFLink"));
}
}
?>
