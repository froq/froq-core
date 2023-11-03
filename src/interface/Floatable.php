<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-common
 */
namespace froq\common\interface;

/**
 * @package froq\common\interface
 * @class   froq\common\interface\Floatable
 * @author  Kerem Güneş
 * @since   5.0
 */
interface Floatable
{
    /**
     * @return float
     */
    public function toFloat(): float;
}
