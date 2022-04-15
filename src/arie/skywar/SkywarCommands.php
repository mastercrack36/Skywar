<?php
/*
 * Copyright (c) 2022 Arisify
 *
 * This program is freeware, so you are free to redistribute and/or modify
 * it under the conditions of the MIT License.
 *
 * @author Arisify
 * @link   https://github.com/Arisify
 * @license https://opensource.org/licenses/MIT MIT License
 *
 * \    /\
 *  )  ( ') ᵐᵉᵒʷˢ
 * (  /  )
 *  \(__)|
 *
*/

namespace arie\skywar;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use arie\skywar\arena\Arena;

class SkywarCommands extends Command{

	private Skywar $plugin;

	public function __construct(Skywar $plugin){
		parent::__construct("sw", "Allow you to modify and join skywar", "/sw addmap|join|list|ui", []);
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		$arenaManager = $this->plugin->getArenaManager();
		if (!isset($args[0])) {
			$sender->sendMessage($this->getUsage());
			return false;
		}
		switch ($args[0]) {
			case "addmap":
				if (!isset($args[1]) && !$sender instanceof Player) {
					$sender->sendMessage("Usage: /sw addmap");
				}
				break;
			case "join":
				if (!$sender instanceof Player) {
					$sender->sendMessage("This command can only be unused in game");
					return false;
				}
				$match = isset($args[1]) ? $arenaManager->getArena($args[1]) : $arenaManager->getAvailableArena();
				if ($match === null) {
					return false;
				}
				$match->join($sender);
				break;
			case "start":

				break;
			case "set":
				if (isset($args[1])) {
					$sender->sendMessage("/sw set <map id>");
					return false;
				}
				$arena = $arenaManager->getArena($args[1]);
				if (!$arena instanceof Arena) {
					$sender->sendMessage("Arena $args[1] is not exist or they have been removed from the game!");
					return false;
				}
				break;
			case "list":
				foreach ($arenaManager->getAllArena() as $arena) {
					$sender->sendMessage($arena->getName() . ":      $arena->getId()        Active");
				}
				break;
			case "ui":
				if (!$sender instanceof Player) {
					$sender->sendMessage("This command can only be used in-game!");
					break;
				}
				$sender->sendForm($this->plugin->getSkywarManagerUI());
				break;
			case "lang":
				if (!isset($args[1])) {
					if ($sender instanceof Player) {
						$sender->sendForm($this->plugin->getLanguageUI());
						break;
					}
					$sender->sendMessage($this->plugin->getLanguage()->getMessage("command.help.usage"));
					break;
				}
				$this->plugin->getLanguage()->setLanguage($args[1]);
				break;
			default:
				$sender->sendMessage($this->getUsage());
		}
		return false;
	}

	/**
	 * @return Skywar
	 */
	public function getPlugin() : Skywar{
		return $this->plugin;
	}
}
