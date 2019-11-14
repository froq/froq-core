<?php
/**
 * MIT License <https://opensource.org/licenses/mit>
 *
 * Copyright (c) 2015 Kerem Güneş
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace froq\core\objects;

/**
 * Singletor.
 * @package froq\core\objects
 * @object  froq\core\objects\Singletor
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   4.0
 */
final class Singletor
{
    /**
     * Instances.
     * @var array<object>
     */
    private static array $instances = [];

    /**
     * Init.
     * @param  string $class
     * @param  ...    $classArgs
     * @return object
     */
    public static function init(string $class, ...$classArgs): object
    {
        return self::$instances[$class] ?? (
               self::$instances[$class] = new $class(...$classArgs)
        );
    }

    /**
     * Get instance.
     * @aliasOf init()
     */
    public static function getInstance(...$arguments)
    {
        return self::init(...$arguments);
    }

    /**
     * Get instances.
     * @return array<object>
     */
    public static function getInstances(): array
    {
        return self::$instances;
    }
}
