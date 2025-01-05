<?php

declare(strict_types=1);

namespace terpz710\gems\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

use pocketmine\player\Player;

use pocketmine\utils\TextFormat;

use terpz710\gems\Gems;

class AddGemCommand extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("addgem");
        $this->setDescription("Add gems to a player");
        $this->setAliases(["addgems"]);
        $this->setUsage("Usage: /addgem <player> <amount>");
        $this->setPermission("gems.addgem");

        $this->plugin = Gems::getInstance();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if (!$this->testPermission($sender)) {
            return false;
        }

        if (count($args) < 2) {
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }

        [$playerName, $amount] = $args;

        $amount = intval($amount);
        if ($amount <= 0) {
            $sender->sendMessage(TextFormat::RED . "The amount must be a positive integer!");
            return false;
        }

        $player = $this->plugin->getServer()->getPlayerExact($playerName);
        $gemManager = Gems::getInstance()->getGemManager();

        if ($player !== null) {
            $gemManager->giveGem($player, $amount);
            $sender->sendMessage(TextFormat::GREEN . "Added " . number_format($amount) . " gems to " . $player->getName());
        } else {
            $offlinePlayerExists = $gemManager->seeGemBalance($playerName) !== null;
            if (!$offlinePlayerExists) {
                $sender->sendMessage(TextFormat::RED . $playerName . " does not exist or has no gem balance!");
                return false;
            }

            $gemManager->giveGem($playerName, $amount);
            $sender->sendMessage(TextFormat::GREEN . "Added " . number_format($amount) . " gems to " . $player->getName());
        }

        return true;
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }
}
