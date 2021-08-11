<?php

namespace Source\Models\Report;

use CoffeeCode\DataLayer\DataLayer;
/*use Source\Core\Model;*/

use Source\Core\Session;
use Source\Models\User;

/**
 * Class Online
 * @package Source\Models\Report
 */
class Online extends DataLayer
{
    /** @var int */
    private $sessionTime;

    /**
     * @var
     */
    private $now;

    /**
     * Online constructor.
     * @param int $sessionTime
     */

    public function __construct(int $sessionTime = 5)
    {
        $this->sessionTime = $sessionTime;
        $this->now = date_fmt("now", "Y-m-d H:i:s");
        parent::__construct("report_online", ["ip", "url", "agent"]);
    }

    /**
     * @param bool $count
     * @return array|int|null
     */
    public function findByActive(bool $count = false)
    {
        $find = $this->find("updated_at >= '{$this->now}' - INTERVAL {$this->sessionTime} MINUTE");
        if ($count) {
            return $find->count();
        }
        $find->order("updated_at DESC");
        return $find->fetch(true);
    }

    /**
     * @return Online
     */
    public function report(bool $clear = true): Online
    {
        $session = new Session();

        if ($clear) {
            $this->clear();
        }

        if (!$session->has("online")) {
            $this->user = ($session->authUser ?? null);
            $this->url = (filter_input(INPUT_GET, "route", FILTER_SANITIZE_STRIPPED) ?? "/");
            $this->ip = filter_input(INPUT_SERVER, "REMOTE_ADDR");
            $this->agent = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");

            $this->save();
            $session->set("online", $this->id);

            return $this;
        }

        $find = $this->findById($session->online);
        if (!$find) {
            $session->unset("online");
            return $this;
        }

        $find->user = ($session->authUser ?? null);
        $find->url = (filter_input(INPUT_GET, "route", FILTER_SANITIZE_STRIPPED) ?? "/");
        $find->pages += 1;
        $find->save();

        return $this;
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->delete("updated_at <= '{$this->now}' - INTERVAL {$this->sessionTime} MINUTE", null);
    }

    /**
     * @return DataLayer|null
     */
    public function user()
    {
        if ($this->data()->user) {
            return (new User())->findById($this->data()->user);
        }
        return null;
    }

}