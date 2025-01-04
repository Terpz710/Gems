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

class SetGemCommand extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("setgem");
        $this->setDescription("Set the gem balance of a player");
        $this->setAliases(["setgems"]);
        $this->setUsage("Usage: /setgem <player> <amount>");
        $this->setPermission("gems.setgem");

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

        if ($amount < 0) {
            $sender->sendMessage(TextFormat::RED . "The amount must be a positive number...");
            return false;
        }

        $gemManager = Gems::getInstance()->getGemManager();
        $data = $gemManager->data->getAll();
        $found = false;

        foreach ($data as $uuid => $info) {
            if (strtolower($info["name"]) === strtolower($targetName)) {
                $gemManager->data->setNested("$uuid.balance", $amount);
                $gemManager->data->save();
                $sender->sendMessage(TextFormat::GREEN . "Set " . $targetName . " gem balance to " . number_format($amount));
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
