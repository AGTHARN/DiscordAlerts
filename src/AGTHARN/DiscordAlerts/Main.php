<?php

/* 
 * DiscordAlerts, is a PocketMine-MP plugin.
 * Copyright (C) 2020 AGTHARN
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AGTHARN\DiscordAlerts;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class Main extends PluginBase implements Listener {
		
		public function onEnable() {
			$this->getServer()->getPluginManager()->registerEvents($this, $this);

			if ($this->getConfig()->get("config-version") != "1") {
				$this->getLogger()->warning("Your config is outdated! Please delete your old config to get the latest features! An alert has been sent to the webhook URL.");

				$webhook = new Webhook($this->getConfig()->get("webhook-url"));
				$colorval = hexdec("FF0000");

				$msg = new Message();
				$msg->setUsername($this->getConfig()->get("webhook-username"));
				$msg->setAvatarURL($this->getConfig()->get("webhook-avatar-url"));

				$embed = new Embed();
				$embed->setTitle("OUTDATED CONFIG");
				$embed->setColor($colorval);
				$embed->addField("PLEASE UPDATE YOUR CONFIG", "Server has started but a plugin error has occurred. Please let the server owner know that their current config is outdated. Thank you.");
				$embed->setThumbnail("https://user-images.githubusercontent.com/63234276/90614551-64307080-e23d-11ea-868a-c364ae8e9a37.png");
				$embed->setFooter("OFFICIAL PLUGIN MESSAGE", "https://user-images.githubusercontent.com/63234276/90614551-64307080-e23d-11ea-868a-c364ae8e9a37.png");
				$msg->addEmbed($embed);
				$webhook->send($msg);

				$this->getServer()->getPluginManager()->disablePlugin($this);
			}
			
			if ($this->getConfig()->get("enable-startup-alert") === false) return;
			if ($this->getConfig()->get("config-version") != "1") return;
			
			$webhook = new Webhook($this->getConfig()->get("webhook-url"));
			$colorval = hexdec($this->getConfig()->get("startup-embed-color"));
			
			$msg = new Message();
			$msg->setUsername($this->getConfig()->get("webhook-username"));
			$msg->setAvatarURL($this->getConfig()->get("webhook-avatar-url"));

			$embed = new Embed();
			$embed->setTitle($this->getConfig()->get("startup-message-title"));
			$embed->setColor($colorval);
			$embed->addField($this->getConfig()->get("startup-embed-field-title"), $this->getConfig()->get("startup-embed-field-message"));
			$embed->setThumbnail($this->getConfig()->get("startup-thumbnail-url"));
			$embed->setFooter($this->getConfig()->get("startup-footer-message"), $this->getConfig()->get("startup-footer-image-url"));
			$msg->addEmbed($embed);
			
			$webhook->send($msg);
		}

		public function onPlayerKick(PlayerKickEvent $event) {
			if ($event->getReason() === "Internal Server Error") {
				$player = $event->getPlayer();
				$playername = $player->getName();

				if ($this->getConfig()->get("enable-internal-error-alert") === false) return;

				$webhook = new Webhook($this->getConfig()->get("webhook-url"));
				$colorval = hexdec($this->getConfig()->get("internal-error-embed-color"));

				$msg = new Message();
				$msg->setUsername($this->getConfig()->get("webhook-username"));
				$msg->setAvatarURL($this->getConfig()->get("webhook-avatar-url"));

				$embed = new Embed();
				$embed->setTitle(str_replace("{name}", "$playername", $this->getConfig()->get("internal-error-message-title")));
				$embed->setColor($colorval);
				$embed->addField(str_replace("{name}", "$playername", $this->getConfig()->get("internal-error-embed-field-title")), str_replace("{name}", "$playername", $this->getConfig()->get("internal-error-embed-field-message")));
				$embed->setThumbnail($this->getConfig()->get("internal-error-thumbnail-url"));
				$embed->setFooter(str_replace("{name}", "$playername", $this->getConfig()->get("internal-error-footer-message")), $this->getConfig()->get("internal-error-footer-image-url"));
				$msg->addEmbed($embed);

				$webhook->send($msg);
			}
		}

		public function onPlayerJoin(PlayerJoinEvent $event) {
			$player = $event->getPlayer();
			$playername = $player->getName();

			if ($this->getConfig()->get("enable-join-alert") === false) return;

				$webhook = new Webhook($this->getConfig()->get("webhook-url"));
				$colorval = hexdec($this->getConfig()->get("join-embed-color"));

				$msg = new Message();
				$msg->setUsername($this->getConfig()->get("webhook-username"));
				$msg->setAvatarURL($this->getConfig()->get("webhook-avatar-url"));

				$embed = new Embed();
				$embed->setTitle(str_replace("{name}", "$playername", $this->getConfig()->get("join-message-title")));
				$embed->setColor($colorval);
				$embed->addField(str_replace("{name}", "$playername", $this->getConfig()->get("join-embed-field-title")), str_replace("{name}", "$playername", $this->getConfig()->get("join-embed-field-message")));
				$embed->setThumbnail($this->getConfig()->get("join-thumbnail-url"));
				$embed->setFooter(str_replace("{name}", "$playername", $this->getConfig()->get("join-footer-message")), $this->getConfig()->get("join-footer-image-url"));
				$msg->addEmbed($embed);

				$webhook->send($msg);
		}

		public function onPlayerLeave(PlayerQuitEvent $event) {
			$player = $event->getPlayer();
			$playername = $player->getName();

			if ($this->getConfig()->get("enable-leave-alert") === false) return;

				$webhook = new Webhook($this->getConfig()->get("webhook-url"));
				$colorval = hexdec($this->getConfig()->get("leave-embed-color"));

				$msg = new Message();
				$msg->setUsername($this->getConfig()->get("webhook-username"));
				$msg->setAvatarURL($this->getConfig()->get("webhook-avatar-url"));

				$embed = new Embed();
				$embed->setTitle(str_replace("{name}", "$playername", $this->getConfig()->get("leave-message-title")));
				$embed->setColor($colorval);
				$embed->addField(str_replace("{name}", "$playername", $this->getConfig()->get("leave-embed-field-title")), str_replace("{name}", "$playername", $this->getConfig()->get("leave-embed-field-message")));
				$embed->setThumbnail($this->getConfig()->get("leave-thumbnail-url"));
				$embed->setFooter(str_replace("{name}", "$playername", $this->getConfig()->get("leave-footer-message")), $this->getConfig()->get("leave-footer-image-url"));
				$msg->addEmbed($embed);

				$webhook->send($msg);
		}
		
		public function onDisable() {
			if ($this->getConfig()->get("enable-shutdown-alert") === false) return;
			if ($this->getConfig()->get("config-version") != "1") return;
			
			$webhook = new Webhook($this->getConfig()->get("webhook-url"));
			$colorval = hexdec($this->getConfig()->get("shutdown-embed-color"));
			
			$msg = new Message();
			$msg->setUsername($this->getConfig()->get("webhook-username"));
			$msg->setAvatarURL($this->getConfig()->get("webhook-avatar-url"));

			$embed = new Embed();
			$embed->setTitle($this->getConfig()->get("shutdown-message-title"));
			$embed->setColor($colorval);
			$embed->addField($this->getConfig()->get("shutdown-embed-field-title"), $this->getConfig()->get("shutdown-embed-field-message"));
			$embed->setThumbnail($this->getConfig()->get("shutdown-thumbnail-url"));
			$embed->setFooter($this->getConfig()->get("shutdown-footer-message"), $this->getConfig()->get("shutdown-footer-image-url"));
			$msg->addEmbed($embed);
			
			$webhook->send($msg);
		}
}
