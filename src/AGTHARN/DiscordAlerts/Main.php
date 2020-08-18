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
			
			// to do config version check
			$configversion = $this->getConfig()->get("config-version");
			
			if ($this->getConfig()->get("enable-startup-alert") === false) return;
			
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
		
		public function onDisable() {
			if ($this->getConfig()->get("enable-shutdown-alert") === false) return;
			
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