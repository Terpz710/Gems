<?php

declare(strict_types=1);

namespace terpz710\gems\manager;

use pocketmine\player\Player;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

use terpz710\gems\Gems;

use terpz710\gems\scorehud\GemScoreHud;

final class GemManager {
    use SingletonTrait;

    public $data;

    private $plugin;

    public function __construct() {
        $this->plugin = Gems::getInstance();
        $this->data = new Config($this->plugin->getDataFolder() . "gems.json", Config::JSON);
    }

    public function createGemBalance(Player $player, int $startAmount) : void{
        $uuid = $player->getUniqueId()->toString();
        if (!$this->hasGemBalance($player)) {
            $this->data->set($uuid, [
                "name" => $player->getName(),
                "balance" => $startAmount
            ]);
            $this->data->save();
        }
    }

    public function hasGemBalance(Player $player) : bool{
        return $this->data->exists($player->getUniqueId()->toString());
    }

    public function seeGemBalance(Player|string $player) : int{
        $uuid = $player instanceof Player ? $player->getName() : $player;
        return $this->data->getNested("$uuid.balance");
    }

    public function seeFormattedBalance(Player|string $player) : string{
        $balance = $this->seeGemBalance($player);
        return number_format($balance);
    }

    public function giveGem(Player|string $player, int $amount) : void{
        $uuid = $player instanceof Player ? $player->getNamd() : $player;
        $current = $this->seeGemBalance($player);
        $tag = new GemScoreHud();
        $this->data->setNested("$uuid.balance", $current + $amount);
        $this->data->save();
        $tag->updateScoreTag($player);
    }

    public function removeGem(Player|string $player, int $amount) : void{
        $uuid = $player instanceof Player ? $player->getName() : $player;
        $current = $this->seeGemBalance($player);
        $newBalance = max(0, $current - $amount);
        $tag = new GemScoreHud();
        $this->data->setNested("$uuid.balance", $newBalance);
        $this->data->save();
        $tag->updateScoreTag($player);
    }

    public function setGem(Player|string $player, int $amount) : void{
        $uuid = $player instanceof Player ? $player->getName() : $player;
        $tag = new GemScoreHud();
        $this->data->setNested("$uuid.balance", $amount);
        $this->data->save();
        $tag->updateScoreTag($player);
    }

    public function updatePlayerName(Player $player) : void{
        $uuid = $player->getUniqueId()->toString();
        if ($this->hasGemBalance($player)) {
            $currentData = $this->data->get($uuid);
            if ($currentData["name"] !== $player->getName()) {
                $currentData["name"] = $player->getName();
                $this->data->set($uuid, $currentData);
                $this->data->save();
            }
        }
    }

    public function getLeaderboard() : array{
        $allData = $this->data->getAll();
        $leaderboard = [];

        foreach ($allData as $playerData) {
            $leaderboard[] = [
                "name" => $playerData["name"],
                "balance" => $playerData["balance"]
            ];
        }

        usort($leaderboard, function ($a, $b) {
            return $b["balance"] <=> $a["balance"];
        });

        return array_slice($leaderboard, 0, 10);
    }
}
