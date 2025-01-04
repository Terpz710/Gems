<?php

declare(strict_types=1);

namespace terpz710\gems\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

use pocketmine\player\Player;

use pocketmine\utils\TextFormat;

use terpz710\gems\manager\GemManager;

class GemLeaderboardCommand extends Command implements PluginOwned {

    private $plugin;

    public function __construct() {
        parent::__construct("gemsleaderboard");
        $this->setDescription("Displays the top 10 players with the most gems");
        $this->setPermission("gems.leaderboard");
        
        $this->plugin = Gems::getInstance();
    }

    public function execute(CommandSender $sender, string $label, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game!");
            return;
        }

        $leaderboard = GemManager::getInstance()->getLeaderboard();

        if (empty($leaderboard)) {
            $sender->sendMessage(TextFormat::YELLOW . "No data available for the leaderboard.");
            return;
        }

        $sender->sendMessage(TextFormat::AQUA . "===== Gem Leaderboard =====");

        foreach ($leaderboard as $rank => $entry) {
            $sender->sendMessage(TextFormat::GOLD . "#" . ($rank + 1) . " " . TextFormat::WHITE . $entry['name'] . ": " . TextFormat::GREEN . number_format($entry['balance']) . " gems");
        }

        $sender->sendMessage(TextFormat::AQUA . "===========================");
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }
}
