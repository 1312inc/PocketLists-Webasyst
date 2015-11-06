<?php

class pocketlistsPocketModel extends waModel
{
    protected $table = 'pocketlists_pocket';

    public function getAllPockets()
    {
        return $this->getAll();
    }
}