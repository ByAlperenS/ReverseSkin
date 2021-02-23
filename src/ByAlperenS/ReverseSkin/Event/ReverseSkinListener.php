<?php

namespace ByAlperenS\ReverseSkin\Event;

use ByAlperenS\ReverseSkin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class ReverseSkinListener implements Listener{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onPlayerQuit(PlayerQuitEvent $e){
        $sender = $e->getPlayer();

        if (in_array($sender->getName(), $this->plugin->data)){
            if ($this->plugin->data[$sender->getName()]){
                unset($this->plugin->data[$sender->getName()]);
            }
        }
    }
}
