<?php

/**
 * Class pocketlistsLocation
 */
class pocketlistsLocation extends pocketlistsEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var float
     */
    private $location_latitude;

    /**
     * @var float
     */
    private $location_longitude;

    /**
     * @var float
     */
    private $location_radius;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return pocketlistsLocation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getLocationLatitude()
    {
        return $this->location_latitude;
    }

    /**
     * @param float $location_latitude
     *
     * @return pocketlistsLocation
     */
    public function setLocationLatitude($location_latitude)
    {
        $this->location_latitude = $location_latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLocationLongitude()
    {
        return $this->location_longitude;
    }

    /**
     * @param float $location_longitude
     *
     * @return pocketlistsLocation
     */
    public function setLocationLongitude($location_longitude)
    {
        $this->location_longitude = $location_longitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLocationRadius()
    {
        return $this->location_radius;
    }

    /**
     * @param float $location_radius
     *
     * @return pocketlistsLocation
     */
    public function setLocationRadius($location_radius)
    {
        $this->location_radius = $location_radius;

        return $this;
    }
}
