<?php

namespace rokorolov\parus\admin\theme\widgets\statusaction\services;

use rokorolov\parus\admin\theme\widgets\statusaction\contracts\StatusInterface;
use yii\helpers\ArrayHelper;
use yii\base\InvalidParamException;

/**
 * StatusService is a service for manage the statuses
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class StatusService
{
    /**
     * @var type
     */
    private $status = false;

    /**
     * @var type
     */
    private $statuses;

    /**
     * @var type
     */
    private $statusManager;

    /**
     *
     * @param type $statuses
     * @param type $status
     * @param Status $statusManager
     */
    public function __construct(
        $statuses = null,
        $status = null,
        StatusInterface $statusManager
    ) {
        if (null !== $status) {
            $this->status = $status;
        }
        if (null !== $statuses) {
            $this->statuses = $statuses;
        }

        $this->statusManager = $statusManager;
    }

    /**
     *
     * @param type $status
     * @return type
     */
    public function getStatusName($status = null)
    {
        $status = $status === null ? $this->status : $status;
        return ArrayHelper::getValue($this->statusManager->getStatusOptions(), $status, null);
    }

    /**
     *
     * @param type $status
     * @return type
     */
    public function getStatusHtmlType($status = null)
    {
        $status = $status === null ? $this->status : $status;
        return ArrayHelper::getValue($this->statusManager->getStatusHtmlTypes(), $status, null);
    }

    /**
     *
     * @param type $statuses
     * @return type
     */
    public function getStatusOptions()
    {
        return $this->getItems($this->statuses, $this->statusManager->getStatusOptions());
    }

    /**
     * Default items for render StatusAction widget.
     *
     * @param array|string $statuses
     * @return array list of items for StatusAction widget.
     */
    public function getStatusActions()
    {
        return $this->getItems($this->statuses, $this->statusManager->getStatusActions());
    }

    /**
     *
     * @return type
     */
    public function getStatuses()
    {
        return array_keys($this->getStatusOptions());
    }

    /**
     *
     * @return type
     * @throws InvalidParamException
     */
    public function getStatus()
    {
        if (false === $this->status || array_key_exists($this->status, $this->getStatusOptions())) {
            return $this->status;
        }
        throw new InvalidParamException("Status '$this->status' not exists.");
    }

    /**
     * Get the statuses.
     *
     * @param array|string $value
     * @param array $options
     * @param mixed $default
     * @return array.
     */
    private function getItems($value, $options, $default = [])
    {
        if (!is_null($value) && !is_array($value)) {
            if (array_key_exists($value, $options)) {
                return [$value => $options[$value]];
            }
            return $default;
        } elseif (!is_null($value) && is_array($value) && !empty($value)) {
            $newOptions = [];
            foreach ($value as $v) {
                isset($options[$v]) && $newOptions[$v] = $options[$v];
            }
            return $newOptions;
        }
        return $options;
    }
}
