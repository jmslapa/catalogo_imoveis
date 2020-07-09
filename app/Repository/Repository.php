<?php

namespace App\Repository;

abstract class Repository {

    /**
     * Model class for repository
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Undocumented variable
     *
     * @var query
     */
    protected $query;

    public function __construct(string $modelClass) 
    {
        $this->modelClass = $modelClass;
        $this->query = app($modelClass)->newQuery();     
    }

    protected function newQuery() {
        return app($this->modelClass)->newQuery();
    }

    protected function executeQuery($query = null, int $perPage = 15) {
        
        if(is_null($query)) {
            $query = $this->newQuery();
        }

        if($perPage > 0) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getModelClass()
    {
        return $this->modelClass;
    }

    public function selectFields($fields)
    {   
        $this->query = $this->query->selectRaw($fields);
    }

    public function filterBy($filters)
    {
        $expressions = explode(';', $filters);
        foreach($expressions as $e) {
            $filter = explode(':', $e);
            $this->query = $this->query->where($filter[0], $filter[1], $filter[2]);
        }
    }
    
    public function get(int $perPage = 15)
    {   
        $finalQuery = $this->query;
        $this->query = $this->newQuery();      
        return $this->executeQuery($finalQuery, $perPage);
    }
}