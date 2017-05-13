<?php

namespace rokorolov\parus\menu\components;

use Closure;
use yii\web\UrlRuleInterface;

/**
 * This is the BaseUrlRule
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 * @package rokorolov\parus\menu\components
 */
class BaseUrlRule implements UrlRuleInterface
{
    public $route;
    public $urlExtraPath;
    public $allowParseRequest = true;
    public $includeIdToUrl = true;
    public $excludePath = ['admin'];

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === $this->route && isset($params['id'])) {
            $id = $params['id'];
            if (false === $slug = $this->getSlugById($id)) {
                return false;
            }
            if ('/' === $slug) {
                return '';
            }
            $url = '';
            if (null !== $this->urlExtraPath) {
                $url .= $this->urlExtraPath . '/';
            }
            if (true === $this->includeIdToUrl) {
                $url .= $id . '-';
            }
            return $url . $slug;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (!$this->allowParseRequest($manager, $request, $pathInfo)) {
            return false;
        }
        if (in_array($pathInfo, $this->excludePath)) {
            return false;
        }

        $baseUrl = false;
        if ($pathInfo) {
            foreach($this->excludePath as $excludePath) {
                if (0 === strpos($pathInfo, $excludePath)) {
                    return false;
                }
            }
            $pathSplit = explode('/', $pathInfo);
            if (null !== $this->urlExtraPath && 0 !== strpos($pathSplit[0], $this->urlExtraPath)) {
                return false;
            }
            $slug = array_pop($pathSplit);
        } else {
            $slug = '/';
            $baseUrl = true;
        }

        if (!$baseUrl && $this->includeIdToUrl) {
            if (preg_match('/^(\d+)(\-(.*))?$/', $slug, $matches)) {
                $id = $matches[1];
                if ((int)$id === (int)$this->getIdBySlug($matches[3])) {
                    return [$this->route, ['id' => $id]];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } elseif ($Id = $this->getIdBySlug($slug)) {
            return [$this->route, ['id' => $Id]];
        }

        return false;
    }

    /**
     * @param $manager
     * @param $request
     * @param $pathInfo
     * @return bool|mixed
     */
    private function allowParseRequest($manager, $request, $pathInfo)
    {
        if ($this->allowParseRequest instanceof Closure) {
            return call_user_func($this->allowParseRequest, $manager, $request, $pathInfo);
        }
        return $this->allowParseRequest;
    }
}