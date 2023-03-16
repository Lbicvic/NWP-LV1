<?php

include('./simple_html_dom.php');
include ("./DiplomskiRadovi.php");

class DataController{
    private $url;
    private $htmlParser;
    private $diplomskiRadovi;

    public function __construct($url){
        $this->url = $url;
        $this->htmlParser = new simple_html_dom();
        $this->diplomskiRadovi = new DiplomskiRadovi(array(
            'naziv_rada' => "",
            'tekst_rada' => "",
            'link_rada' => "",
            'oib_tvrtke' => ""));
    }

    public function fetchData(){
        //Dohvaćanje podataka pomoću curl-a
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($curl);
        
        curl_close($curl);

    return $this->parseData($result);
    }

    private function parseData($data){
        $oibs = [];
        $hrefs = [];
        $titles = [];
        $texts = [];

    $html = $this->htmlParser->load($data);

    // Dohvaćanje OIB-a
    foreach ($html->find('img') as $img) {
        if (strpos($img, "logos") !== false) {
            array_push($oibs, preg_replace('/[^0-9]/', '', $img->src));
        }
    }
    //Dohvaćanje linkova i naziva radova
    foreach($html->find('article') as $article) {
                            
    foreach($article->find('ul.slides img') as $img) {
    }
    foreach($article->find('h2.entry-title a') as $link) {
            array_push($hrefs, $link->href);
            array_push($titles, $link->plaintext);
    }}
   //Dohvaćanje tekstova radova
    $texts = $this->getTexts($hrefs);
    return array($oibs, $hrefs,$titles,$texts );
    }

    private function getTexts($hrefs) {
        $texts = [];
        // za svaki pronađeni link dohvaća se tekst (tekst rada)
        foreach($hrefs as $href){
            $curl = curl_init($href);
            curl_setopt($curl, CURLOPT_FAILONERROR, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            $result = curl_exec($curl);
            curl_close($curl);
    
            $html = $this->htmlParser->load($result);
            
            foreach($html->find('.post-content') as $text) {
                array_push($texts,$text->plaintext);
            }
    
        }
        return $texts;
    }

}
?>