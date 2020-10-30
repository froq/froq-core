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

namespace froq\common\traits;

use Throwable, Error, Exception;

/**
 * Throwable Trait.
 * @package froq\common\traits
 * @object  froq\common\traits\AttributeTrait
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   4.0
 */
trait ThrowableTrait
{
    /**
     * Constructor.
     *
     * @param string|Throwable  $message
     * @param string|array|null $messageParams
     * @param int|null          $code
     * @param Throwable|null    $previous
     */
    public function __construct($message = null, $messageParams = null, int $code = null,
        Throwable $previous = null)
    {
        if ($message != null) {
            if (is_string($message)) {
                // Eg: throw new Exception('@error').
                if ($message === '@error') {
                    $error         = self::getLastError();
                    $code          = $code ?? $error['code'];
                    $messageParams = [$error['message']];
                    $message = vsprintf('Error: %s', $messageParams);
                }
                // Eg: throw new Exception('Error: %s', ['The error!'] or ['@error']).
                elseif ($messageParams) {
                    $message = preg_replace_callback('~["\']%s["\']~',
                        fn($s) => "'". trim($s[0], '"\'') ."'", $message);
                    $messageParams = (array) $messageParams;

                    foreach ($messageParams as $i => $messageParam) {
                        if ($messageParam === '@error') {
                            $error             = self::getLastError();
                            $code              = $code ?? $error['code'];
                            $messageParams[$i] = $error['message'];
                            break;
                        }
                    }

                    $message = vsprintf($message, $messageParams);
                }
            } elseif (is_object($message) && $message instanceof Throwable) {
                $code     = $code ?? $message->getCode();
                $previous = $previous ?? $message->getPrevious();
                $message  = $message->getMessage();
            } else {
                throw new Exception(sprintf(
                    'Invalid message type "%s" given to "%s", string and Throwable messages are allowed only',
                    is_object($message) ? get_class($message) : gettype($message), static::class
                ));
            }
        }

        parent::__construct((string) $message, (int) $code, $previous);
    }

    /**
     * String magic.
     *
     * @return string
     */
    public function __toString()
    {
        // Eg: Exception: ... => Exception(404): ...
        return preg_replace('~^(.+?): *(.+)~', '\1('. $this->code .'): \2',
            parent::__toString());
    }

    /**
     * Gets the class of.
     *
     * @return string
     */
    public function getClass(): string
    {
        return get_class($this);
    }

    /**
     * Gets the trace string of (alias of getTraceAsString()).
     *
     * @return string
     */
    public function getTraceString(): string
    {
        return $this->getTraceAsString();
    }

    /**
     * Gets a string representation of.
     *
     * @param  bool $pretty
     * @return string
     */
    public function toString(bool $pretty = false): string
    {
        [$class, $code, $line, $file, $message, $messageTrace] = [
            $this->getClass(), $this->code, $this->line, $this->file,
            $this->getMessage(), $this->getTraceString()
        ];

        if ($pretty) {
            $file = str_replace('.php', '', $file);
            $class = str_replace('\\', '.', $class);

            // Change dotable stuffs and remove php extensions.
            $message = preg_replace(['~(\w)(?:\\\|::|->)(\w)~', '~\.php~'],
                ['\1.\2', ''], $message);
            $messageTrace = preg_replace_callback('~(?:\.php[(]|(?:\\\|::|->))~',
                fn($m) => ($m[0] == '.php(') ? '(' : '.', $messageTrace);
        }

        return sprintf(
            "%s\n%s\n\n%s(%d): %s at %s:%d\n-\n%s",
            sprintf("%s.", trim($message, '.')),
            sprintf("Code: %d | Line: %d | File: %s", $code, $line, $file),
            $class, $code, $message,
            $file, $line, $messageTrace
        );
    }

    /**
     * Checks the self object whether instance of Error or not.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return ($this instanceof Error);
    }

    /**
     * Checks the self object whether instance of Exception or not.
     *
     * @return bool
     */
    public function isException(): bool
    {
        return ($this instanceof Exception);
    }

    /**
     * Gets last internal error if exists.
     *
     * @return array<string, string|int>
     */
    public static final function getLastError(): array
    {
        // Better calling when sure there is an error happened.
        $error = error_get_last();

        return [
            'code'    => $error['type'] ?? null,
            'message' => strtolower($error['message'] ?? 'unknown')
        ];
    }
}
