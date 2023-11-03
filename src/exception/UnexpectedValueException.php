<?php declare(strict_types=1);
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 · http://github.com/froq/froq-common
 */
namespace froq\common\exception;

use froq\common\interface\Thrownable;
use froq\common\trait\ThrownableTrait;

/**
 * @package froq\common\exception
 * @class   froq\common\exception\UnexpectedValueException
 * @author  Kerem Güneş
 * @since   4.0
 */
class UnexpectedValueException extends \UnexpectedValueException implements Thrownable
{
    use ThrownableTrait;
}
