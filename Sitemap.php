<?php
define("SERVER_ROOT", $_SERVER['DOCUMENT_ROOT']);
define("sitemap", "sitemap.xml");
class Sitemap{
    private $sitename;
    private $title;
    private $url;
    private $date;
    private $sitemap = SERVER_ROOT."/".sitemap;
    function __construct($sitename, $title, $url, $date) {
        $this->sitename = $sitename;
        $this->title = $title;
        $this->url = $url;
        $this->date = $date;


    }
    public function createXml(){
        $domDoc = new DOMDocument( "1.0", 'UTF-8' );
        $domDoc->preserveWhiteSpace = false;
		$domDoc->formatOutput = true;
        if (!file_exists(SERVER_ROOT."/".sitemap)){

            $rootElt = $domDoc->createElement('urlset');
            $rootNode = $domDoc->appendChild($rootElt);

            $rootEltAttrXmlns = $domDoc->createAttribute("xmlns");
            $rootEltAttrXmlnsVal = $domDoc->createTextNode('http://www.sitemaps.org/schemas/sitemap/0.9');
            $rootEltAttrXmlns->appendChild($rootEltAttrXmlnsVal);
            $rootElt->appendChild($rootEltAttrXmlns);
            $rootEltAttrXmlnsNews = $domDoc->createAttribute("xmlns:news");
            $rootEltAttrXmlnsNewsVal = $domDoc->createTextNode("http://www.google.com/schemas/sitemap-news/0.9");
            $rootEltAttrXmlnsNews->appendChild($rootEltAttrXmlnsNewsVal);
            $rootElt->appendChild($rootEltAttrXmlnsNews);

            $urlElt = $this->createElt($domDoc);
            $rootElt->appendChild($urlElt);

        }else {
            $domDoc->load($this->sitemap);
            $rootElt = $domDoc->getElementsByTagName('urlset')->item(0);
            $urlElt = $this->createElt($domDoc);
            $rootElt->appendChild($urlElt);
        }

        $this->createFile($domDoc->saveXML());
    }

    function createElt($domDoc){
        $urlElt = $domDoc->createElement('url');

        $urlEltAttrLastmod = $domDoc->createElement('lastmod');
        $urlEltAttrLastmodVal = $domDoc->createTextNode($this->date);
        $urlEltAttrLastmod->appendChild($urlEltAttrLastmodVal);
        $urlElt->appendChild($urlEltAttrLastmod);

        $urlEltAttrLoc = $domDoc->createElement('loc');
        $urlEltAttrLocVal = $domDoc->createTextNode($this->url);
        $urlEltAttrLoc->appendChild($urlEltAttrLocVal);
        $urlElt->appendChild($urlEltAttrLoc);

        $urlEltAttrNews = $domDoc->createElement('news:news');
        $urlElt->appendChild($urlEltAttrNews);

        $urlEltAttrNewsPubDate = $domDoc->createElement('news:publication_date');
        $urlEltAttrNewsPubDateVal = $domDoc->createTextNode($this->date);
        $urlEltAttrNewsPubDate->appendChild($urlEltAttrNewsPubDateVal);
        $urlEltAttrNews->appendChild($urlEltAttrNewsPubDate);

        $urlEltAttrNewsTitle = $domDoc->createElement('news:title');
        $urlEltAttrNewsTitleVal = $domDoc->createTextNode($this->title);
        $urlEltAttrNewsTitle->appendChild($urlEltAttrNewsTitleVal);
        $urlEltAttrNews->appendChild($urlEltAttrNewsTitle);

        $urlEltAttrNewsPub = $domDoc->createElement('news:publication');
        $urlEltAttrNewsName = $domDoc->createElement('news:name');
        $urlEltAttrNewsNameVal = $domDoc->createTextNode($this->sitename);
        $urlEltAttrNewsName->appendChild($urlEltAttrNewsNameVal);
        $urlEltAttrNewsPub->appendChild($urlEltAttrNewsName);
        $urlEltAttrNews->appendChild($urlEltAttrNewsPub);
        return $urlElt;
    }

    function createFile($data){
        $put = file_put_contents(SERVER_ROOT."/".sitemap, $data);
    }

}

?>
