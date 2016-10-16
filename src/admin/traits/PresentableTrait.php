<?php

namespace rokorolov\parus\admin\traits;

use rokorolov\parus\admin\contracts\HasPresenter;
use rokorolov\parus\admin\exceptions\PresenterException;
use Yii;

/**
 * PresentableTrait
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait PresentableTrait
{
    /**
     * View presenter instance
     *
     * @var mixed
     */
    protected $presenterInstance;

    /**
     * Prepare a new or cached presenter instance
     *
     * @return mixed
     * @throws PresenterException
     */
    public function present()
    {
        if (!$this->presenterInstance){

            if (!$this instanceof HasPresenter) {
                throw new PresenterException('Class ' . self::class . ' must be an instance of the ' . HasPresenter::class);
            }
            $this->presenterInstance = Yii::$container->get($this->getPresenter(), [$this]);
        }
        return $this->presenterInstance;
    }
}
