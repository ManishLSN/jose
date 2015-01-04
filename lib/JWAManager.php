<?php

namespace SpomkyLabs\JOSE;

use Jose\JWAManager as Base;
use Jose\JWAInterface;

class JWAManager extends Base
{
    protected $algorithms = array();

    public function getAlgorithms()
    {
        return $this->algorithms;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlgorithm($algorithm)
    {
        return array_key_exists($algorithm, $this->algorithms)?$this->algorithms[$algorithm]:null;
    }

    public function addAlgorithm(JWAInterface $algorithm)
    {
        if (!$this->isAlgorithmSupported($algorithm->getAlgorithmName())) {
            $this->algorithms[$algorithm->getAlgorithmName()] = $algorithm;
        }
        return $this;
    }

    public function removeAlgorithm($algorithm)
    {
        $name = null;
        if ($algorithm instanceof JWAInterface) {
            $name = $algorithm->getAlgorithmName();
        } elseif (is_string($algorithm)) {
            $name = $algorithm;
        }else {
            throw new \InvalidArgumentException("Argument must be a string or a JWAInterface object.");
        }
        if (array_key_exists($name, $this->algorithms)) {
            unset($this->algorithms[$name]);
        }
        return $this;
    }
}
