<?php

namespace Webkul\Midtrans\Resources;

abstract class AbstractResource
{
    abstract function getArray();

    protected function validateData()
    {
        $properties = $this->getPropertyArray();

        foreach ($properties as $property) {
            if (empty($property)) {
                throw new \Exception("Please fill all required property");
            }
        }

        return true;
    }

    public function getPropertyArray()
    {
        return get_object_vars($this);
    }
}
