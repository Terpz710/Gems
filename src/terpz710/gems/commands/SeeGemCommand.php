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

class SeeGemCommand extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("seegem");
        $this->setDescription("See another player's gem balance");
        $this->setAliases(["seegems"]);
        $this->setUsage("Usage: /seegem <player>");
        $this->setPermission("gems.seegem");

        $this->plugin = Gems::getInstance();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if (count($args) < 1) {
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }

        $targetName = $args[0];
        $gemManager = Gems::getInstance()->getGemManager();
        $data = $gemManager->data->getAll();
        $found = false;

        foreach ($data as $uuid => $info) {
            if (strtolower($info["name"]) === strtolower($targetName)) {
                $balance = $info["balance"];
                $sender->sendMessage(TextFormat::GREEN . $targetName . " has " . number_format($balance) . " gems");
                $found = true;
                break;
            }
        }

        if (!$found) {
            $sender->sendMessage(TextFormat::RED . $targetName . " doesn't exist...");
        }

        return true;
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}
