<?php
/**
 * @package: chapi
 *
 * @author:  msiebeneicher
 * @since:   2016-11-04
 */

namespace Chapi\Entity\Chronos\JobEntity;

class ContainerVolumeEntity implements \JsonSerializable
{
    /**
     * @param array|object $jobData
     * @throws \InvalidArgumentException
     */
    public function __construct($jobData = [])
    {
        if (is_array($jobData) || is_object($jobData)) {
            foreach ($jobData as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                } else {
                    $this->unknownFields[$key] = $value;
                }
            }
        } else {
            throw new \InvalidArgumentException(sprintf('Argument 1 passed to "%s" must be an array or object', __METHOD__));
        }
    }

    public $unknownFields = [];
    
    /** @var string  */
    public $containerPath = '';

    /** @var string  */
    public $hostPath = '';

    /**
     * @var string
     *
     * read-write and read-only.
     * val RW, RO = Value
     */
    public $mode = '';

    public function jsonSerialize()
    {
        $return = (array) $this;

        $return += $this->unknownFields;
        unset($return['unknownFields']);

        return $return;
    }
}
