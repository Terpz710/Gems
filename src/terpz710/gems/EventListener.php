<?php

declare(strict_types=1);

namespace terpz710\gems;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityTeleportEvent;

use terpz710\gems\scorehud\GemScoreHud;

class EventListener implements Listener {

    private $plugin;

    public function __construct() {
        $this->plugin = Gems::getInstance();
    }

    public function join(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        $gemManager = $this->plugin->getGemManager();
        $startAmount = $this->plugin->getConfig()->get("starting_gem_amount");
        $scoreHud = new GemScoreHud();

        if (!$gemManager->hasGemBalance($player)) {
            $gemManager->createGemBalance($player, $startAmount);
        }

        $gemManager->updatePlayerName($player);
        $scoreHud->updateScoreTag($player);
    }

    public function teleport(EntityTeleportEvent $event) : void{
        $entity = $event->getEntity();
        $scoreHud = new GemScoreHud();

        if ($entity instanceof Player) {
            $scoreHud->updateScoreTag($entity);
        }
    }
}
