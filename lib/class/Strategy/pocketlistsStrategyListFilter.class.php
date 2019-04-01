<?php

/**
 * Class pocketlistsStrategyListFilter
 */
class pocketlistsStrategyListFilter
{
    /**
     * @var pocketlistsList[]
     */
    private $nonArchived;

    /**
     * @var pocketlistsList[]
     */
    private $archived;

    /**
     * @param array $lists
     * @param bool  $archive
     *
     * @return pocketlistsList[]
     */
    public function filterArchive(array $lists, $archive = false)
    {
        if (empty($lists)) {
            return [];
        }

        if ($archive && $this->archived !== null) {
            return $this->archived;
        }

        if (!$archive && $this->nonArchived !== null) {
            return $this->nonArchived;
        }

        /** @var pocketlistsList $list */
        foreach ($lists as $i => $list) {
            if (!$archive && (int)$list->isArchived() === 0) {
                $this->nonArchived = $lists[$i];
            }

            if ($archive && (int)$list->isArchived() > 0) {
                $this->archived = $lists[$i];
            }
        }

        return $archive ? $this->archived : $this->nonArchived;
    }
}
