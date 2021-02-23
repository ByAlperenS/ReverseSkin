<?php

namespace ByAlperenS\ReverseSkin;

use ByAlperenS\ReverseSkin\Command\ReverseSkinCommand;
use ByAlperenS\ReverseSkin\Event\ReverseSkinListener;
use pocketmine\entity\Skin;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

    private $config;

    /** @var array */
    private $skin = [];

    /** @var array */
    public $data = [];

    public function onEnable(){
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "Geometry/");
        $this->saveResource("config.yml");
        $this->saveResource("Geometry/reverseskin.json");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register("reverseskin", new ReverseSkinCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new ReverseSkinListener($this), $this);
    }

    /**
     * @param Player $sender
     * @param bool $status
     */
    public function setPlayerSkin(Player $sender, bool $status = true){
        if ($status){
            $this->skin[$sender->getName()] = [$sender->getSkin()->getGeometryName(), $sender->getSkin()->getGeometryData()];
            $sender->setSkin(new Skin($sender->getSkin()->getSkinId(), $sender->getSkin()->getSkinData(), "", "geometry.reverseskin", file_get_contents($this->getDataFolder() . "Geometry/reverseskin.json")));
            $sender->sendSkin();
        }else{
            $sender->setSkin(new Skin($sender->getSkin()->getSkinId(), $sender->getSkin()->getSkinData(), "", $this->skin[$sender->getName()][0], $this->skin[$sender->getName()][1]));
            $sender->sendSkin();
            unset($this->skin[$sender->getName()]);
        }
    }

    /**
     * @return mixed
     */
    public function onMainConfig(){
        return $this->config;
    }
}
