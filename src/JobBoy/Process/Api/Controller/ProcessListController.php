<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Controller;

use JobBoy\Process\Api\Normalizer\ProcessNormalizer;
use JobBoy\Process\Api\Response\Collection;
use JobBoy\Process\Api\Security\RequiredRoleProvider;
use JobBoy\Process\Application\DTO\Process;
use JobBoy\Process\Application\Service\ListProcesses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProcessListController extends AbstractController implements JobBoyApiController
{

    private $requiredRoleProvider;
    private $listProcesses;
    private $processNormalizer;

    public function __construct(
        RequiredRoleProvider $requiredRoleProvider,
        ListProcesses $listProcesses,
        ProcessNormalizer $processNormalizer
    )
    {
        $this->requiredRoleProvider = $requiredRoleProvider;
        $this->listProcesses = $listProcesses;
        $this->processNormalizer = $processNormalizer;
    }

    /**
     * @Route("/v1/processes", name="jobboy_api_process_list", methods={"GET"})
     */
    public function execute(Request $request): Response
    {
        $this->denyAccessUnlessGranted($this->requiredRoleProvider->get());

        $processes = $this->listProcesses->execute();
        $processes = array_reverse($processes);

        $items = array_map(function (Process $process) {
            return $this->processNormalizer->normalize($process);
        }, $processes);

        $response = new Collection($items, count($items));

        return new JsonResponse($response->normalize());
    }

}
