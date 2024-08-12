<?php

declare(strict_types=1);

final class lexorankRank
{
    public const MIN_CHAR = '0';
    public const MAX_CHAR = 'z';

    /** Usually, database like MySQL order using only the first 1024 chars */
    public const MAX_RANK_LEN = 1024;

    /**
     * @var non-empty-string
     * @psalm-readonly
     */
    private string $rank;

    /**
     * @param non-empty-string $rank
     */
    private function __construct(string $rank)
    {
        self::rankValidator($rank);

        $this->rank = $rank;
    }

    /**
     * @param non-empty-string $rank
     *
     * @psalm-pure
     */
    private static function rankValidator(string $rank): void
    {
        if (strlen($rank) > self::MAX_RANK_LEN) {
            throw new Exception('The length of Rank provided is too long. Rank Provided: '.$rank.' - Rank Length: '.strlen($rank).' - Max length: '.self::MAX_RANK_LEN);
        }

        $invalidChars = array_filter(
            str_split($rank),
            static function ($char) {
                return ord($char) < ord(self::MIN_CHAR) || ord($char) > ord(self::MAX_CHAR);
            }
        );

        if ($invalidChars !== []) {
            throw new Exception('Rank provided contains an invalid Char. Rank Provided: '.$rank.' - Invalid char: '.implode(', ', array_values($invalidChars)));
        }
    }

    /**
     * @return non-empty-string
     */
    public function get(): string
    {
        return $this->rank;
    }

    /**
     * @param non-empty-string $rank
     *
     * @psalm-pure
     */
    public static function fromString(string $rank): self
    {
        return new self($rank);
    }

    /**
     * @psalm-pure
     */
    public static function forEmptySequence(): self
    {
        return self::fromString(self::mid(self::MIN_CHAR, self::MAX_CHAR));
    }

    /**
     * @psalm-pure
     */
    public static function after(self $prevRank): self
    {
        $char = substr($prevRank->get(), -1);

        if (ord($char) + 1 >= ord(self::MAX_CHAR)) {
            return self::fromString(
                $prevRank->get().chr(ord(self::MIN_CHAR) + 1)
            );
        }

        $return = substr($prevRank->get(), 0, -1).chr(ord($char) + 1);

        return self::fromString($return);
    }

    /**
     * @psalm-pure
     */
    public static function before(self $nextRank): self
    {
        $char = substr($nextRank->get(), -1);

        if (ord($char) - 1 <= ord(self::MIN_CHAR)) {
            $return = substr($nextRank->get(), 0, -1).chr(ord($char) - 1).chr(ord(self::MAX_CHAR) - 1);

            return self::fromString($return);
        }

        $return = substr($nextRank->get(), 0, -1).chr(ord($char) - 1);

        return self::fromString($return);
    }

    /**
     * @psalm-pure
     */
    public static function betweenRanks(self $prevRank, self $nextRank): self
    {
        if (strcmp($prevRank->get(), $nextRank->get()) >= 0) {
            throw new Exception('Previous Rank ('.$prevRank->get().') is greater than or equals to Next ('.$nextRank->get().')');
        }

        $rank = '';
        $i    = 0;
        while ($i <= self::MAX_RANK_LEN) {
            $prevChar = $prevRank->getChar($i, self::MIN_CHAR);
            $nextChar = $nextRank->getChar($i, self::MAX_CHAR);
            $i++;

            $midChar = self::mid($prevChar, $nextChar);
            if (in_array($midChar, [$prevChar, $nextChar])) {
                $rank .= $prevChar;
                continue;
            }

            $rank .= $midChar;
            break;
        }

        return self::fromString($rank);
    }

    /**
     * @param 0|positive-int   $i
     * @param non-empty-string $defaultChar
     *
     * @return non-empty-string
     */
    private function getChar(int $i, string $defaultChar): string
    {
        return $this->rank[$i] ?? $defaultChar;
    }

    /**
     * @param non-empty-string $prev
     * @param non-empty-string $next
     *
     * @return non-empty-string
     *
     * @psalm-pure
     */
    private static function mid(string $prev, string $next): string
    {
        if (ord($prev) >= ord($next)) {
            return $prev;
        }

        return chr((int) ((ord($prev) + ord($next)) / 2));
    }
}
