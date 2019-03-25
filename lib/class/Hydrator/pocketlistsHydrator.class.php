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
     * @param       $object
     * @param array $fields
     *
     * @return array
     * @throws ReflectionException
     */
    public function extract($object, array $fields = [])
    {
        $reflection = $this->getReflectionClass(get_class($object));

        $result = [];

        foreach ($fields as $name) {
            $methodName = 'set'.$this->getMethodName($name);
            $method = $reflection->getMethod($methodName);

            if ($method && $method->isPublic()) {
                $result[$property->getName()] = $method->invoke($object);
            }
        }

        return $result;
    }

    /**
     * @param object $object
     * @param array  $data
     *
     * @return object
     * @throws ReflectionException
     */
    public function hydrate($object, array $data)
    {
        $reflection = $this->getReflectionClass($target);

        $object = is_object($target) ? $target : $reflection->newInstanceWithoutConstructor();

        foreach ($data as $name => $value) {
            $methodName = 'set'.$this->getMethodName($name);
            $method = $reflection->getMethod($methodName);

            if ($method && $method->isPublic()) {
                $method->invoke($object, $value);
            }
        }

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

    private function getMethodName($name)
    {
        $methodName = str_replace(' ', '', ucwords($name, '_'));
    }
}