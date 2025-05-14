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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $color;

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
     * @var string
     */
    private $create_datetime;

    /**
     * @var string
     */
    private $update_datetime;

    /**
     * @var string
     */
    private $activity_datetime;

    /**
     * @var string|null
     */
    private $uuid;

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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = (string) $color;

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

    /**
     * @return string
     */
    public function getCreateDatetime()
    {
        return $this->create_datetime;
    }

    /**
     * @param $create_datetime
     *
     * @return pocketlistsLocation
     */
    public function setCreateDatetime($create_datetime)
    {
        $this->create_datetime = $create_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdateDatetime()
    {
        return $this->update_datetime;
    }

    /**
     * @param $update_datetime
     *
     * @return pocketlistsLocation
     */
    public function setUpdateDatetime($update_datetime)
    {
        $this->update_datetime = $update_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getActivityDatetime()
    {
        return $this->activity_datetime;
    }

    /**
     * @param $activity_datetime
     *
     * @return pocketlistsLocation
     */
    public function setActivityDatetime($activity_datetime)
    {
        $this->activity_datetime = $activity_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param $uuid
     * @return pocketlistsLocation
     */
    public function setUuid($uuid)
    {
        $this->uuid = (empty($uuid) ? null : trim($uuid));

        return $this;
    }
}
