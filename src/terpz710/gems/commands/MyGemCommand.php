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

class MyGemCommand extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("mygem");
        $this->setDescription("Check your gem balance");
        $this->setAliases(["gems", "gem", "mygems"]);
        $this->setPermission("gems.mygem");

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

        $gemManager = Gems::getInstance()->getGemManager();
        $balance = $gemManager->seeGemBalance($sender);
        $sender->sendMessage(TextFormat::GREEN . "You have " . number_format($balance) . " gems");

        return true;
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}
