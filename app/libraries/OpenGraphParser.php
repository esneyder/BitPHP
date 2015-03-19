<?php

	class OpenGraphParser {

		public $domain;
		public $meta;

		public function __construct() {
			
			$this->meta = [
				'title' => null,
				'type'  => null,
				'image' => null,
				'url'   => null,
				'audio' => null,
				'video' => null,
				'locale' => null,
				'site_name' => null,
				'determiner'  => null,
				'description' => null,
				'keywords' => null,
				'subject'  => null,
				'languaje' => null,
			];
		}

		public function parse($url) {

			if(substr($url, 0, 4) != 'http') {
				$url = 'http://' . $url;
			}

			$this->domain = explode('/', $url)[2];
			$this->url = $url;

			$html = @file_get_contents($url);

			$document = new DOMDocument();
			@$document->loadHTML($html);

			//Namespaces que nos interesan
			$ns = ['og', 'fb', 'twitter'];

			$metas = $document->getElementsByTagName('meta');

			if(!empty($metas)) {

				for ($i = 0; $i < $metas->length; $i++) {
					
					$meta = $metas->item($i);
					foreach (['name', 'property'] as $attr) {
						
						$name = explode(':', $meta->getAttribute($attr));
						$content = $meta->getAttribute('content');

						if(in_array($name[0], $ns)) {
							//Verifica que no exista ya
							if(empty($this->meta[$name[1]])) {
								$this->meta[$name[1]] = $content;
							}
						} else {
							$name = strtolower($name[0]);

							if(empty($this->meta[$name])) {
								$this->meta[$name] = $content;
							}
						}
					}
				}
			}

			return 1;
		}
	}
?>
