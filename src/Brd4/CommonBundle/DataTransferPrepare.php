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
     * @param string $dtoClassName
     * @param string $outputFormat
     * @return array
     */
    public function serialize($data, $dtoClassName, $outputFormat = 'json')
    {
        // TODO: check if $data is object or array

        $itemArray = [];
        foreach ($data as $item) {
            $dto = $this->shifter->toDto($item, new $dtoClassName());
            $serializedData = $this->serializer->serialize($dto, $outputFormat);
            $itemArray[] = (array)json_decode($serializedData);
        }

        return $itemArray;
    }

    /**
     * @param $data
     * @param $dtoClassName
     * @param string $inputFormat
     */
    public function deserialize($data, $dtoClassName, $inputFormat = 'json')
    {
//        $dto = $this->shifter->fromDto(new $dtoClassName(), $data);
//        $serializedData = $this->serializer->deserialize($dto, $inputFormat);
//        $itemArray[] = (array)json_decode($serializedData);
//
//        return $itemArray;
    }
}