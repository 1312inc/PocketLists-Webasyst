<?php

/**
 * Class pocketlistsBot
 */
class pocketlistsBot extends pocketlistsContact
{
    const PL2BOT_ID = -1312;

    /**
     * @var string
     */
    protected $name = 'pl2bot';

    /**
     * @var string
     */
    protected $username = 'pl2bot';

    /**
     * @var int
     */
    protected $id = self::PL2BOT_ID;

    /**
     * @var string
     */
    protected $photoUrl = '/wa-apps/pocketlists/img/pl2bot.png';

    /**
     * @var string
     */
    protected $userPic = '/wa-apps/pocketlists/img/pl2bot.png';

    /**
     * @var string
     */
    protected $login = 'pl2bot';

    /**
     * pocketlistsContact constructor.
     *
     * @param waContact $contact
     */
    public function __construct(waContact $contact)
    {
        $this->contact = $contact;

        $this->init();
    }

    /**
     * @return $this
     */
    public function init()
    {
        return $this;
    }
}
