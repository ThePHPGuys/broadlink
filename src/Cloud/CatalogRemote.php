<?php

namespace TPG\Broadlink\Cloud;


class CatalogRemote
{
    private $path;
    private $type;
    private $factory;
    private $model;
    private $desc;
    /**
     * @var Catalog
     */
    private $catalog;

    public function __construct(Catalog $catalog)
    {

        $this->catalog = $catalog;
    }

    public static function createFromArray(Catalog $catalog, array $data):CatalogRemote{
        $remote = new CatalogRemote($catalog);
        foreach($data as $key => $val) {
            if(property_exists($remote,$key)) {
                $remote->$key = $val;
            }
        }
        return $remote;
    }

    public function download(){
        $this->catalog->download($this->path);
    }


}