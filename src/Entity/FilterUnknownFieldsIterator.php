<?php
/**
 * User: sbrueggen
 * Date: 12.04.18
 * Time: 01:09
 */

namespace Chapi\Entity;


class FilterUnknownFieldsIterator extends \FilterIterator {
    public function accept() {
        return parent::key() != "unknownFields";
    }
}
