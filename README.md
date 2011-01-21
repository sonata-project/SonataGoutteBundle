What is GoutteBundle ?
----------------------

GoutteBundle integrated the Goutte project from Fabien Potencier (https://github.com/fabpot/Goutte) into the Symfony2 project.


Configuration
-------------

* edit your config.yml and add these lines

        goutte.config:
            class: Sonata\GoutteBundle\Manager
            clients:
                default:
                    config:
                        adapter: Zend\Http\Client\Adapter\Socket

                curl:
                    config:
                        maxredirects: 0
                        timeout: 30
                        useragent: Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3
                        adapter: Bundle\GoutteBundle\Adapter\Curl
                        verbose_log: %kernel.logs_dir%/curl.log
                        verbose: true


Usage
-----

        public function fetchAction()
        {
            $client = $this->get('goutte')
                ->getNamedClient('curl');

            $crawler = $client->request('GET', 'http://symfony-reloaded.org/');

            $response = $client->getResponse();

            $content = $response->getContent();

            // do stuff with the crawler and related information
        }




Requirements
------------

* Symfony2
* PHP 5.3.2
* Zend

