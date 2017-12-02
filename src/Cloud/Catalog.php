<?php

namespace TPG\Broadlink\Cloud;


class Catalog
{
    /**
     * @var string
     */
    private $savePath;

    /**
     * Catalog constructor.
     */
    public function __construct($savePath=__DIR__.'/../../remotes/')
    {
        $this->savePath = $savePath;
    }

    private function getToken($val){
        $salt = "Broadlink:290";
        $token = $salt.$val;
        $shaToken = sha1($token,true);
        $encodedToken = base64_encode($shaToken);
        return md5($encodedToken);
    }

    private function createSignedQuery():array {
        $timestamp = ceil(microtime(true)*1000);
        $query['timestamp'] = $timestamp;
        $query['token'] = $this->getToken($timestamp);
        return $query;
    }

    public function search($key){
        $query = $this->createSignedQuery();
        $query['method'] = 'query';
        $query['keyword'] = $key;

        $url = 'http://ebackup.ibroadlink.com/rest/1.0/share?'.http_build_query($query);
        $content = file_get_contents($url);
        $remotes = json_decode($content,true)['list'];
        $searchResult = [];
        foreach ($remotes as $remote){
            $searchResult[] = CatalogRemote::createFromArray($this,$remote);
        }
        return $searchResult;
    }

    public function getSavePath(){
        return $this->savePath;
    }

    public function isRemoteExists($path){
        return file_exists($this->getRemotePath($path));
    }

    private function getRemoteFileName($path):string {
        return md5($path).'.zip';
    }

    private function getRemotePath($path){
        return $this->getSavePath().$this->getRemoteFileName($path);
    }

    public function download($path){
        if($this->isRemoteExists($path)){
            return true;
        }
        $query = $this->createSignedQuery();
        $query['method'] = 'download';
        $query['path'] = $path;
        $url = 'http://ebackup.ibroadlink.com/rest/1.0/share?'.http_build_query($query);
        $content = file_get_contents($url);
        file_put_contents($this->getRemotePath($path),$content);
        return true;
    }
}