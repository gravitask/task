<?php

namespace Gravitask\Task\Formatter;


class BaseFormatter
{
    /** @var array An array of flag values. */
    private $flags = [];


    /**
     * Set the list of flags to the provided array list.
     *
     * @param array $flags
     */
    public function setFlags(array $flags) {
        $this->flags = $flags;
    }

    /**
     * Add a single flag to the list of parser flags.
     *
     * @param int $flag
     */
    public function addFlag($flag) {
        $flagList = $this->getFlags();
        if(in_array($flag, $flagList) === false) {
            $flagList[] = $flag;
            $this->setFlags($flagList);
        }
    }

    /**
     * Remove a single flag from the list of parser flags.
     *
     * @param int $flag
     */
    public function removeFlag($flag) {
        $flagList = $this->getFlags();
        $flagIndex = array_search($flag, $flagList);

        if($flagIndex !== false) {
            array_splice($flagList, $flagIndex, 1);
            $this->setFlags($flagList);
        }
    }

    /**
     * Retrieve an array of the set flags.
     *
     * @return array
     */
    public function getFlags() {
        return $this->flags;
    }

    /**
     * Determine whether the provided flag exists in the
     * list of flags.
     *
     * @param int $flag The flag value to search for.
     * @return bool
     */
    public function hasFlag($flag) {
        return in_array($flag, $this->getFlags());
    }
}