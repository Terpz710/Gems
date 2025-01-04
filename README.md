<p align="center">
    <a href="https://github.com/Terpz710/Gems"><img src="https://github.com/Terpz710/Gems/blob/main/icon.png"></img></a><br>
    <b>Gems plugin for Pocketmine-MP</b>
<a 


Add a gem currency to your [PocketMine-MP](https://pmmp.io) server!

This plugin has the basic functions such as add gems, remove gems, set gems, pay gems and view your and other players gem balances.

Easy to use API for new or experienced developers! ❤️

**ScoreHud support**

This currency plugin supports [ScoreHud](https://poggit.pmmp.io/p/scorehud).

Tag: **{gems.balance}**

# API 🪙
**How to get the plugin instance**
```
There are 2 ways to retrieve it:

use terpz710\gems\Gems;

$api = Gems::getInstance()->getGemManager();

or

use terpz710\gems\manager\GemManager;

$api = GemManager::getInstance();
```

**How to get a player's gem balance**
```
$player is an instance of Player::class

$api = GameManager::getInstance();

$api->seeGemBalance($player);
```

**How to add gems to a player balance**
```
$player is an instance of Player::class

$api = GameManager::getInstance();

$amount = 100

$api->giveGem($player, $amount);

or

$api->giveGem($player, 100);
```

**How to remove gems from a players balance**
```
$player is an instance of Player::class

$api = GameManager::getInstance();

$amount = 100;

$api->removeGem($player, $amount);

or

$api->removeGem($player, 100);
```

**How to set a player's gem balance**
```
$player is an instance of Player::class

$api = GameManager::getInstance();

$amount = 100;

$api->setGem($player, $amount);

or

$api->setGem($player, 100);
```

**How to create a gem balance**
```
$player is an instance of Player::class

$api = GameManager::getInstance();

$amount = 100;

$api->createGemBalance($player, $amount);

or

$api->createGemBalance($player, 100);
```

**How to check to see if a player has a gem balance**
```
$player is an instance of Player::class

$api = GameManager::getInstance();

$api->hasGemBalance($player);

Example method:

How to verify to see if a player exist before creating a new balance.

if(!$api->hasGemBalance($player)) {
    $api->createGemBalance($player, 100);
}
```

# How to install
1. Download from Poggit or Github.
2. Put the .phar or unzipped .zip within the plugins folder
3. start/restart your server and enjoy!
