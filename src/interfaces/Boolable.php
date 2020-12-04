<?php
/**
 * Copyright (c) 2015 · Kerem Güneş
 * Apache License 2.0 <https://opensource.org/licenses/apache-2.0>
 */
declare(strict_types=1);

namespace froq\common\interfaces;

/**
 * Boolable.
 *
 * @package froq\common\interfaces
 * @object  froq\common\interfaces\Boolable
 * @author  Kerem Güneş <k-gun@mail.com>
 * @since   5.0
 */
interface Boolable
{
    /**
     * To bool.
     * @return bool
     */
    public function toBool(): bool;
}
