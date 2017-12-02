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

    /**
     * @param $val
     * @return string
     */
    private function getToken($val){
        $salt = "Broadlink:290";
        $token = $salt.$val;
        $shaToken = sha1($token,true);
        $encodedToken = base64_encode($shaToken);
        return md5($encodedToken);
    }

    /**
     * @return array
     */
    private function createSignedQuery():array {
        $timestamp = ceil(microtime(true)*1000);
        $query['timestamp'] = $timestamp;
        $query['token'] = $this->getToken($timestamp);
        return $query;
    }

    /**
     * Search remotes
     * @param $key
     * @return CatalogRemote[]
     */
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

    /**
     * @return string
     */
    public function getSavePath(){
        return $this->savePath;
    }

    /**
     * @param $path
     * @return bool
     */
    public function isRemoteExists($path){
        return file_exists($this->getRemotePath($path));
    }

    /**
     * @param $path
     * @return string
     */
    private function getRemoteFileName($path):string {
        return md5($path).'.zip';
    }

    /**
     * @param $path
     * @return string
     */
    private function getRemotePath($path){
        return $this->getSavePath().$this->getRemoteFileName($path);
    }

    /**
     * @param $path
     * @return bool
     */
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