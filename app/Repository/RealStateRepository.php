<?php

namespace App\Repository;

class RealStateRepository extends Repository {

    private $location = [];

    public function setLocation(array $location)
    {
        $this->location = $location;
    }

    public function get(int $perPage = 15)
    {
        $this->query->whereHas('address', function($query) {
            if(isset($this->location['state'])) {
                $query->where('state_id', $this->location['state']);
            }
            if(isset($this->location['city'])) {
                $query->where('city_id', $this->location['city']);
            }
        })->get();

        $finalQuery = $this->query;
        $this->query = $this->newQuery();      
        return parent::executeQuery($finalQuery, $perPage);
    }
}