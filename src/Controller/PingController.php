<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PingController extends AbstractController
{
    #[Route('/ping', name: 'app_ping')]
    public function index(Request $request): Response
    {
        $host = $request->query->get('host', 'riot.de');
        $startTime = microtime(true);
        $socket = fsockopen($host, 80, $errno, $errstr, 10);
        $endTime = microtime(true);

        if ($socket) {
            fclose($socket);
            $ping = round(($endTime - $startTime) * 1000); // Convert to ms
            $errorMessage = null;
        } else {
            $ping = null;
            $errorMessage = "Unable to reach $host. Error: $errstr ($errno)";
        }

        return $this->render('ping/index.html.twig', [
            'host' => $host,
            'ping' => $ping,
            'errorMessage' => $errorMessage,
        ]);
    }
}
