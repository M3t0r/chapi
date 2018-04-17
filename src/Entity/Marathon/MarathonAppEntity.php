<?php
/**
 * @package: chapi
 *
 * @author: bthapaliya
 * @since: 2016-10-16
 *
 */

namespace Chapi\Entity\Marathon;

use Chapi\Entity\JobEntityInterface;
use Chapi\Entity\FilterUnknownFieldsIterator;
use Chapi\Entity\Marathon\AppEntity\Container;
use Chapi\Entity\Marathon\AppEntity\HealthCheck;
use Chapi\Entity\Marathon\AppEntity\IpAddress;
use Chapi\Entity\Marathon\AppEntity\PortDefinition;
use Chapi\Entity\Marathon\AppEntity\UpgradeStrategy;

class MarathonAppEntity implements JobEntityInterface
{
    public $id = '';

    public $cmd = null;

    public $cpus = 0;

    public $mem = 0;

    public $args = null;

    /**
     * @var PortDefinition[]
     */
    public $portDefinitions = null;

    public $requirePorts = false;

    public $instances = 0;

    public $executor = '';

    /**
     * @var Container
     */
    public $container = null;

    public $env = null;

    /**
     * @var array
     */
    public $constraints = [];


    public $acceptedResourceRoles = null;

    public $labels = null;

    #public $uris = [];

    public $dependencies = [];

    /**
     * @var HealthCheck[]
     */
    public $healthChecks = null;

    public $backoffSeconds = 1;

    public $backoffFactor = 1.15;

    public $maxLaunchDelaySeconds = 3600;

    public $taskKillGracePeriodSeconds = 0;

    /**
     * @var UpgradeStrategy
     */
    public $upgradeStrategy = null;


    /**
     * @var IpAddress
     */
    public $ipAddress = null;

    public $unknownFields = [];

    public function __construct($data = null)
    {
        if (!$data) {
            // initialized with default values
            return;
        }

        // make sure data is array
        $dataArray = (array) $data;

        $this->unknownFields = MarathonEntityUtils::setAllPossibleProperties(
            $dataArray,
            $this,
            array(
                'portDefinitions' => MarathonEntityUtils::convArrayOfClass(PortDefinition::class),
                'container' => MarathonEntityUtils::convClass(Container::class),
                'healthChecks' => MarathonEntityUtils::convArrayOfClass(HealthCheck::class),
                'upgradeStrategy' => MarathonEntityUtils::convClass(UpgradeStrategy::class),
                'ipAddress' => MarathonEntityUtils::convClass(IpAddress::class),
                'env' => MarathonEntityUtils::convSortedObject(),
                'labels' => MarathonEntityUtils::convSortedObject(),

                # don't skip assigning these just because they are arrays or objects in $dataArray
                'constraints' => MarathonEntityUtils::noConv(),
                'args' => MarathonEntityUtils::noConv(),
                'uris' => MarathonEntityUtils::noConv(),
                'acceptedResourceRoles' => MarathonEntityUtils::noConv(),
                'dependencies' => MarathonEntityUtils::noConv()
            )
        );

        if (!isset($dataArray['upgradeStrategy'])) {
            $this->upgradeStrategy = new UpgradeStrategy();
        }

        if (!isset($dataArray['labels'])) {
            $this->upgradeStrategy = (object) [];
        }
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function jsonSerialize()
    {
        $return = (array) $this;

        $return += $this->unknownFields;
        unset($return['unknownFields']);

        // delete empty fields
        $return = array_filter(
            $return,
            function ($value, $key) {
                return !is_null($value) || empty($value);
            },
            ARRAY_FILTER_USE_BOTH // there is no ARRAY_FILTER_USE_VALUE
        );

        return $return;
    }

    /**
     * @inheritdoc
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->jsonSerialize());
        #return new FilterUnknownFieldsIterator(new \ArrayIterator($this));
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getSimpleArrayCopy()
    {
        $return = [];

        foreach ($this as $property => $value) {
            if ($property != "unknownFields") {
                $return[$property] = (is_array($value) || is_object($value)) ? json_encode($value) : $value;
            }
        }

        return $return;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isSchedulingJob()
    {
        return false;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isDependencyJob()
    {
        return count($this->dependencies) ? true : false;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return JobEntityInterface::MARATHON_TYPE;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->id;
    }
}
