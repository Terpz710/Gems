<?php

declare(strict_types=1);

namespace terpz710\gems\scorehud;

use pocketmine\player\Player;

use terpz710\gems\manager\GemManager;

use Ifera\ScoreHud\ScoreHud;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;

final class GemScoreHud {

    public function __construct() {
        //noop
    }

    public function updateScoreTag(Player $player) : void{
        if (class_exists(ScoreHud::class)) {
            $balance = GemManager::getInstance()->seeGemBalance($player);
            $ev = new PlayerTagsUpdateEvent(
                $player,
                [
                    new ScoreTag("gems.balance", (string)$balance),
                ]
            );
            $ev->call();
        }
    }

    public function onTagResolve(TagsResolveEvent $event) : void{
        $player = $event->getPlayer();
        $tag = $event->getTag();
        $balance = GemManager::getInstance()->seeGemBalance($player);
        match ($tag->getName()) {
            "gems.balance" => $tag->setValue((string)$balance),
            default => null,
        };
    }
}
