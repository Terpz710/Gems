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
            $sender->sendMessage("This command can only be used in-game!");
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
        $amount = (int)$amount;

        if ($amount <= 0) {
            $sender->sendMessage(TextFormat::RED . "The amount must be a positive number...");
            return false;
        }

        $gemManager = Gems::getInstance()->getGemManager();

        $senderBalance = $gemManager->seeGemBalance($sender);
        if ($senderBalance < $amount) {
            $sender->sendMessage(TextFormat::RED . "You do not have enough gems...");
            return false;
        }

        $data = $gemManager->data->getAll();
        $found = false;

        foreach ($data as $uuid => $info) {
            if (strtolower($info["name"]) === strtolower($targetName)) {
                $gemManager->removeGem($sender, $amount);
                $gemManager->data->setNested("$uuid.balance", $info["balance"] + $amount);
                $gemManager->data->save();

                $sender->sendMessage(TextFormat::GREEN . "You paid $amount gems to $targetName");
                $found = true;
                break;
            }
        }

        if (!$found) {
            $sender->sendMessage(TextFormat::RED . "Player $targetName not found in the database");
        }

        return true;
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}