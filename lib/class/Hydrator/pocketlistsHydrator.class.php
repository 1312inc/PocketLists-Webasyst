<?php

/**
 * Class pocketlistsHydrator
 */
class pocketlistsHydrator implements pocketlistsHydratorInterface
{
    /**
     * @var array
     */
    private $reflectionClassMap = [];

    /**
     * @var array
     */
    private $fieldMethodMap = [];

    /**
     * @param pocketlistsHydratableInterface $object
     * @param array                          $fields
     * @param array                          $dbFields
     *
     * @return array|mixed
     * @throws ReflectionException
     */
    public function extract(pocketlistsHydratableInterface $object, array $fields = [], $dbFields = [])
    {
        $reflection = $this->getReflectionClass(get_class($object));

        $result = [];

        $objectFields = $reflection->getProperties();

        $object->beforeExtract($fields);

        foreach ($objectFields as $name) {
            $toExtractField = $name->getName();

            if ($fields && !in_array($toExtractField, $fields)) {
                continue;
            }

            if ($dbFields && !isset($dbFields[$toExtractField])) {
                continue;
            }

            foreach (['get', 'is'] as $methodPrefix) {
                $method = $this->getMethod($object, $methodPrefix, $toExtractField, $reflection);
                if ($method) {
                    $result[$toExtractField] = $method->invoke($object);
                }
            }
        }

        $object->afterExtract($result);

        return $result;
    }

    /**
     * @param pocketlistsHydratableInterface $object
     * @param array                          $data
     *
     * @return object|pocketlistsHydratableInterface
     * @throws ReflectionException
     */
    public function hydrate(pocketlistsHydratableInterface $object, array $data)
    {
        $reflection = $this->getReflectionClass($object);

        $object = is_object($object) ? $object : $reflection->newInstanceWithoutConstructor();

        $setDataFieldMethod = $this->getMethod($object, 'set', 'dataField', $reflection);
        foreach ($data as $name => $value) {
            $method = $this->getMethod($object, 'set', $name, $reflection);
            if ($method) {
                $method->invoke($object, $value);
            } elseif ($setDataFieldMethod) {
                $setDataFieldMethod->invoke($object, $name, $value);
            }
        }

        $object->afterHydrate($data);

        return $object;
    }


    /**
     * @param string|object $target
     *
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private function getReflectionClass($target)
    {
        $className = is_object($target) ? get_class($target) : $target;
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new ReflectionClass($className);
        }

        return $this->reflectionClassMap[$className];
    }

    /**
     * @param object|string   $target
     * @param string          $prefix
     * @param string          $name
     * @param ReflectionClass $reflection
     *
     * @return ReflectionMethod|false
     */
    private function getMethod($target, $prefix, $name, $reflection)
    {
        $className = is_object($target) ? get_class($target) : $target;

        if (!isset($this->fieldMethodMap[$className][$prefix][$name])) {
            $methodName = $prefix.str_replace('_', '', ucwords($name, '_'));
            $this->fieldMethodMap[$className][$prefix][$name] = false;
            try {
                if ($reflection->hasMethod($methodName)) {
                    $method = $reflection->getMethod($methodName);

                    if ($method->isPublic()) {
                        $this->fieldMethodMap[$className][$prefix][$name] = $method;
                    }
                }
            } catch (ReflectionException $ex) {
            }
        }

        return $this->fieldMethodMap[$className][$prefix][$name];
    }
}