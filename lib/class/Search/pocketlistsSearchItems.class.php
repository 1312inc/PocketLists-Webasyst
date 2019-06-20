<?php

/**
 * Class pocketlistsSearchItems
 *
 * @method pocketlistsItem[] getResults()
 */
class pocketlistsSearchItems extends pocketlistsSearch
{
    /**
     * @return static
     * @throws waDbException
     * @throws waException
     */
    public function search()
    {
        if (mb_strlen($this->term) < 3) {
            return $this;
        }

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        $data = pl2()->getModel(pocketlistsItem::class)->getByTerm($this->term, $this->foundCount);

        if ($this->getFoundCount()) {
            $this->results = $itemFactory->generateWithData($data, true);
        }

        return $this;
    }
}
