<?php
	if(isset($this)){
		global $dom, $curPage;
		$this->head=$dom->getElementById("head");
		$this->head->addElement("script", null, array("type"=>"text/javascript", "src"=>"plugins/".$this->dir."/fancybox/jquery.fancybox-1.3.4.pack.js"));
		$this->head->addElement("link", null, array("rel"=>"stylesheet", "href"=>"plugins/".$this->dir."/fancybox/jquery.fancybox-1.3.4.css", "type"=>"text/css", "media"=>"screen"));
		$this->head->addElement("script", null, array("type"=>"text/javascript", "src"=>"plugins/".$this->dir."/script.js", "id"=>"fancyBoxPluginScript"));
		$this->main=$dom->getElementById("main");
		$this->folder="plugins/".$this->dir."/img/".$curPage."/";
		if (is_dir($this->folder) && $handle = opendir($this->folder)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && $file != ".svn") {
				$this->main->addElement("a", null, array("class"=>"gallery", "rel"=>"prefetch", "href"=>"plugins/".$this->dir."/img/".$curPage."/".$file));
				}
			}
		closedir($handle);
		}		
	}
?>
