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

class PayGemCommand extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("paygem");
        $this->setDescription("Pay gems to another player");
        $this->setAliases(["paygems"]);
        $this->setUsage("Usage: /paygem <player> <amount>");
        $this->setPermission("gems.paygem");

        $this->plugin = Gems::getInstance();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game!");
            return false;
        }

        if (!$this->testPermission($sender)) {
            return false;
        }

        if (count($args) < 2) {
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }

        [$targetName, $amount] = $args;
        $amount = intval($amount);

        if ($amount <= 0) {
            $sender->sendMessage(TextFormat::RED . "The amount must be a positive number!");
            return false;
        }

        $gemManager = $this->plugin->getGemManager();
        $senderBalance = $gemManager->seeGemBalance($sender);

        if ($senderBalance < $amount) {
            $sender->sendMessage(TextFormat::RED . "You do not have enough gems!");
            return false;
        }

        $targetPlayer = $this->plugin->getServer()->getPlayerExact($targetName);
        $targetUUID = $targetPlayer !== null 
            ? $targetPlayer->getUniqueId()->toString()
            : array_search(strtolower($targetName), array_map('strtolower', array_column($gemManager->data->getAll(), 'name')));

        if ($targetUUID === false) {
            $sender->sendMessage(TextFormat::RED . $targetName . " does not exist or has no gem balance!");
            return false;
        }

        $gemManager->removeGem($sender, $amount);
        $gemManager->giveGem($targetUUID, $amount);

        $sender->sendMessage(TextFormat::GREEN . "You paid " . number_format($amount) . " gems to " . $targetName);
        if ($targetPlayer !== null) {
            $targetPlayer->sendMessage(TextFormat::GREEN . "You received " . number_format($amount) . " gems from " . $sender->getName());
        }

        return true;
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }
}
