<?php

namespace yii2lab\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;

class ClassHelper {

    public static function getInstanceOfClassName($class, $classname) {
        $class = self::getClassName($class, $classname);
        if(empty($class)) {
            return null;
        }
        if(class_exists($class)) {
            return new $class();
        }
        return null;
    }

    public static function getNamespaceOfClassName($class) {
        $lastSlash = strrpos($class, '\\');
        return substr($class, 0, $lastSlash);
    }

    public static function extractNameFromClass($class, $type) {
        $lastPos = strrpos($class, '\\');
        $name = substr($class, $lastPos + 1, 0 - strlen($type));
        return $name;
    }

    /**
     * @param       $type
     * @param array $params
     * @param null  $interface
     *
     * @return object
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public static function createObject($type, array $params = [], $interface = null) {
        if(empty($type)) {
            throw new InvalidConfigException('Empty class config');
        }
        if(class_exists('Yii')) {
            $object = Yii::createObject($type, $params);
        } else {
            $type = self::normalizeComponentConfig($type);
            $object = new $type['class'];
            self::configure($object, $params);
            self::configure($object, $type);
        }
        if(!empty($interface)) {
            self::checkInterface($object, $interface);
        }
        return $object;
    }

    /**
     * @param $object
     * @param $interface
     *
     * @throws ServerErrorHttpException
     */
    public static function checkInterface($object, $interface) {
        if(!is_object($object)) {
            throw new ServerErrorHttpException('Object not be object type');
        }
        if(!$object instanceof $interface) {
            throw new ServerErrorHttpException('Object not be instance of "'.$interface.'"');
        }
    }

    public static function configure($object, $properties)
    {
        if(empty($properties)) {
            return $object;
        }
        foreach ($properties as $name => $value) {
            if($name != 'class') {
                $object->{$name} = $value;
            }
        }
        return $object;
    }

    static function getClassName($className, $namespace) {
        if(empty($namespace)) {
            return $className;
        }
        if(! ClassHelper::isClass($className)) {
            $className = $namespace . '\\' . ucfirst($className);
        }
        return $className;
    }

    public static function getNamespace($name) {
        $name = trim($name, '\\');
        $arr = explode('\\', $name);
        array_pop($arr);
        $name = implode('\\', $arr);
        return $name;
    }

    static function normalizeComponentListConfig($config) {
        foreach($config as &$item) {
            $item = self::normalizeComponentConfig($item);
        }
        return $config;
    }

    static function normalizeComponentConfig($config, $class = null) {
        if(empty($config) && empty($class)) {
            return null;
        }
        if(!empty($class)) {
            $config['class'] = $class;
        }
        if(is_array($config)) {
            return $config;
        }
        if(self::isClass($config)) {
            $config = ['class' => $config];
        }
        return $config;
    }

    static function isClass($name) {
        return is_string($name) && strpos($name, '\\') !== false;
    }



}