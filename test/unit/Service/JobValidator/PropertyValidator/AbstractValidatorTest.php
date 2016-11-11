<?php
/**
 * @package: chapi
 *
 * @author:  msiebeneicher
 * @since:   2016-11-11
 *
 */

namespace unit\Service\JobValidator\PropertyValidator;


use Chapi\Entity\Chronos\JobEntity;
use Chapi\Service\JobValidator\PropertyValidatorInterface;
use ChapiTest\src\TestTraits\JobEntityTrait;

abstract class AbstractValidatorTest extends \PHPUnit_Framework_TestCase
{
    use JobEntityTrait;

    /**
     * @param PropertyValidatorInterface $oValidator
     * @param string $sProperty
     * @param mixed $mValidValue
     * @param mixed $mInvalidValue
     */
    protected function handleTestGetLastErrorMessageMulti(
        PropertyValidatorInterface $oValidator,
        $sProperty,
        $mValidValue,
        $mInvalidValue
    )
    {
        $_oJobEntity = $this->getValidScheduledJobEntity();

        $_oJobEntity->{$sProperty} = $mInvalidValue;
        $this->assertFalse($oValidator->isValid($sProperty, $_oJobEntity));
        $this->assertContains($sProperty, $oValidator->getLastErrorMessage());

        $_oJobEntity->{$sProperty} = $mValidValue;
        $this->assertTrue($oValidator->isValid($sProperty, $_oJobEntity));
        $this->assertEmpty($oValidator->getLastErrorMessage());
    }
}