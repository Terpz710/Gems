<?php

declare(strict_types=1);

namespace terpz710\gems;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener {

    private $plugin;

    public function __construct() {
        $this->plugin = Gems::getInstance();
    }

    public function join(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        $gemManager = $this->plugin->getGemManager();
        $startAmount = $this->plugin->getConfig()->get("starting_gem_amount");

        if (!$gemManager->hasGemBalance($player)) {
            $gemManager->createGemBalance($player, $startAmount);
        }

        $gemManager->updatePlayerName($player);
    }
}