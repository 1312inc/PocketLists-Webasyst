<?php

/**
 * Class pocketlistsSearchLists
 *
 * @method pocketlistsList[] getResults()
 */
class pocketlistsSearchLists extends pocketlistsSearch
{
    /**
     * @return static
     *
     * @throws waDbException
     * @throws waException
     */
    public function search()
    {
        if (mb_strlen($this->term) < 3) {
            return $this;
        }

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);

        $data = pl2()->getModel(pocketlistsList::class)->getByTerm($this->term, $this->foundCount);

        if ($this->getFoundCount()) {
            $this->results = $listFactory->generateWithData($data, true);
        }

        return $this;
    }
}
