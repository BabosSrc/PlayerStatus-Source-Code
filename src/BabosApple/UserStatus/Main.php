<?php

namespace BabosApple\UserStatus;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\utils\Config;
use pocketmine\Player;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		@mkdir($this->getDataFolder() . "players/");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$this->cfg = new Config($this->getDataFolder() . "stats/" . strtolower($player->getName()), Config::YAML, array(
			"deaths" => 0,
			"jumps" => 0
		));
		$this->cfg;
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$this->cfg->set("deaths", $this->cfg->get("deaths") + 1);
		$this->cfg->save();
	}

	public function onJump(PlayerJumpEvent $event){
		$player = $event->getPlayer();
		$this->cfg->set("jumps", $this->cfg->get("jumps") + 1);
		$this->cfg->save();
	}

	public function onCommand(CommandSender $sender, Command $command, String $label, Array $args) : bool {

		switch($command->getName()){
			case "stats":
			 if($sender instanceof Player){
			 	$this->status($sender);
			 }
		}
	return true;
	}

	public function status($player){
		$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $player, int $data = null){
			if($data === null){
				return true;
			}
		});
		$form->setTitle("Your Status");
		$form->setContent("Hello " . $player->getName() . "\n=====\nJumps: " . $this->cfg->get("jumps") . "\nDeaths: " . $this->cfg->get("deaths"));
		$form->addButton("Back");
		$form->sendToPlayer($player);
		return $form;
	}

}