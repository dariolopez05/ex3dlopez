<?php

class Lamp {
    protected $id;
    protected $name;
    protected $state;
    protected $model;
    protected $vatios;
    protected $ubication;

    function __construct($id, $name, $state, $model, $vatios, $ubication){
        $this->id = $id;
        $this->name = $name;
        $this->state = $state;
        $this->model = $model;
        $this->vatios = $vatios;
        $this->ubication = $ubication;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getVatios()
    {
        return $this->vatios;
    }

    public function setVatios($vatios)
    {
        $this->vatios = $vatios;
    }

    public function getUbication()
    {
        return $this->ubication;
    }

    public function setUbication($ubication)
    {
        $this->ubication = $ubication;
    }
}

?>