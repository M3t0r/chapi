<?php
/**
 * @package: chapi
 *
 * @author:  msiebeneicher
 * @since:   2015-07-31
 *
 */

namespace Chapi\Service\JobRepository;

use Chapi\Component\DatePeriod\DatePeriodFactoryInterface;
use Chapi\Entity\Chronos\JobEntity;
use Chapi\Exception\DatePeriodException;

class JobEntityValidatorService implements JobEntityValidatorServiceInterface
{
    /**
     * @var DatePeriodFactoryInterface
     */
    private $oDatePeriodFactory;

    /**
     * @param DatePeriodFactoryInterface $oDatePeriodFactory
     */
    public function __construct(
        DatePeriodFactoryInterface $oDatePeriodFactory
    )
    {
        $this->oDatePeriodFactory = $oDatePeriodFactory;
    }

    /**
     * @param JobEntity $oJobEntity
     * @return bool
     */
    public function isEntityValid(JobEntity $oJobEntity)
    {
        $_aValidProperties = [];

        foreach ($oJobEntity as $_sProperty => $mValue)
        {
            switch ($_sProperty)
            {
                case 'name':
                case 'command':
                case 'description':
                case 'owner':
                case 'ownerName':
                    $_aValidProperties[$_sProperty] = (!empty($oJobEntity->{$_sProperty}));
                    break;

                case 'async':
                case 'disabled':
                case 'softError':
                case 'highPriority':
                    $_aValidProperties[$_sProperty] = (is_bool($oJobEntity->{$_sProperty}));
                    break;

                case 'schedule':
                    $_aValidProperties[$_sProperty] = (
                        (!empty($oJobEntity->schedule) && empty($oJobEntity->parents))
                        || (empty($oJobEntity->schedule) && !empty($oJobEntity->parents))
                    );

                    if (!empty($oJobEntity->schedule))
                    {
                        try
                        {
                            $_oDataPeriod = $this->oDatePeriodFactory->createDatePeriod($oJobEntity->schedule, $oJobEntity->scheduleTimeZone);
                            if (!$_oDataPeriod)
                            {
                                $_aValidProperties[$_sProperty] = false;
                            }
                        }
                        catch(DatePeriodException $oException)
                        {
                            $_aValidProperties[$_sProperty] = false;
                        }
                    }
                    break;

                case 'parents':
                    $_aValidProperties[$_sProperty] = (is_array($oJobEntity->{$_sProperty}));
                    break;

                case 'retries':
                    $_aValidProperties[$_sProperty] = ($oJobEntity->{$_sProperty} >= 0);
                    break;
            }


        }

        return (!in_array(false, $_aValidProperties));
    }
}