<?php

namespace Brd4\CommonBundle;

use Sleepness\Shifter;
use JMS\Serializer\Serializer;

class DataTransferPrepare
{
    /** @var Serializer $serializer */
    public $serializer;

    /** @var Shifter $shifter */
    public $shifter;

    /**
     * @param array|object $data
     * @param string $className
     * @param string $outputFormat
     * @return array
     */
    public function serialize($data, $className, $outputFormat = 'json')
    {
        // TODO: check if $data is object or array

        $itemArray = [];
        foreach ($data as $item) {
            $dto = $this->shifter->toDto($item, new $className());
            $serializedData = $this->serializer->serialize($dto, $outputFormat);
            $itemArray[] = (array)json_decode($serializedData);
        }

        return $itemArray;
    }
}