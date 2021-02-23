<?php

namespace ByAlperenS\ReverseSkin\Command;

use ByAlperenS\ReverseSkin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class ReverseSkinCommand extends PluginCommand{

    /** @var Main */
    private $plugin;

    /**
     * ReverseSkinCommand constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin){
        parent::__construct("reverseskin", $plugin);
        $this->setAliases(["rs"]);
        $this->setUsage(C::GRAY . "/reverseskin [on/off]");
        $this->setDescription(C::GRAY . "Reverse Skin Command");
        $this->setPermission("reverseskin.permission");
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $config = $this->plugin->onMainConfig();
        $prefix = str_replace("&", "§", $config->get("Prefix"));
        $successon = str_replace(["&", "{name}"], ["§", $sender->getName()], $config->get("Successfully-On-Message"));
        $successoff = str_replace(["&", "{name}"], ["§", $sender->getName()], $config->get("Successfully-Off-Message"));
        $argsnotfound = str_replace(["&", "{name}"], ["§", $sender->getName()], $config->get("Args-Not-Found-Message"));
        $nopermission = str_replace(["&", "{name}"], ["§", $sender->getName()], $config->get("No-Permission"));
        $firston = str_replace(["&", "{name}"], ["§", $sender->getName()], $config->get("On-First-Message"));

        if (!$sender instanceof Player){
            return false;
        }
        if (!$sender->hasPermission("reverseskin.permission")){
            $sender->sendMessage($prefix . $nopermission);
            return false;
        }
        if (isset($args[0])){
            switch ($args[0]){
                case "on":
                    if (!in_array($sender->getName(), $this->plugin->data)){
                        $this->plugin->data[$sender->getName()] = true;
                        $this->plugin->setPlayerSkin($sender);
                        $sender->sendMessage($prefix . $successon);
                        return true;
                    }
                    if (!$this->plugin->data[$sender->getName()]){
                        $this->plugin->setPlayerSkin($sender);
                        $this->plugin->data[$sender->getName()] = true;
                        $sender->sendMessage($prefix . $successon);
                        return true;
                    }
                    break;
                case "off":
                    if (!in_array($sender->getName(), $this->plugin->data)){
                        $sender->sendMessage($prefix . $firston);
                        return false;
                    }
                    if ($this->plugin->data[$sender->getName()]){
                        $this->plugin->setPlayerSkin($sender, false);
                        unset($this->plugin->data[$sender->getName()]);
                        $sender->sendMessage($prefix . $successoff);
                    }
                    break;
                default:
                    $sender->sendMessage($prefix . $argsnotfound);
                    break;
            }
        }else{
            $sender->sendMessage($this->getUsage());
        }
        return true;
    }
}
