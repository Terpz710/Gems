<?php

declare(strict_types=1);

namespace terpz710\gems;

use pocketmine\plugin\PluginBase;

use terpz710\gems\manager\GemManager;

use terpz710\gems\commands\AddGemCommand;
use terpz710\gems\commands\RemoveGemCommand;
use terpz710\gems\commands\SetGemCommand;
use terpz710\gems\commands\MyGemCommand;
use terpz710\gems\commands\SeeGemCommand;
use terpz710\gems\commands\PayGemCommand;

final class Gems extends PluginBase {
    
    protected static $instance;

    private $gemManager;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->gemManager = new GemManager();
        $this->saveDefaultConfig();
        $this->registerCommands();
    }

    private function registerCommands() : void{
        $this->getServer()->getCommandMap()->registerAll("Gems", [
            new AddGemCommand(),
            new RemoveGemCommand(),
            new SetGemCommand(),
            new MyGemCommand(),
            new SeeGemCommand(),
            new PayGemCommand()
        ]);
    }

    public static function getInstance() : self{
        return self::$instance;
    }

    public function getGemManager() : GemManager{
        return $this->gemManager;
    }
}
