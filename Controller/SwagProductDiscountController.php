<?php declare(strict_types=1);

namespace SwagProductDiscount\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopware\Api\ApiContext;
use Shopware\Api\ApiController;
use Shopware\Search\Criteria;
use Shopware\Search\Parser\QueryStringParser;
use SwagProductDiscount\Repository\SwagProductDiscountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(service="shopware.swag_product_discount.api_controller", path="/api")
 */
class SwagProductDiscountController extends ApiController
{
    /**
     * @var SwagProductDiscountRepository
     */
    private $swagProductDiscountRepository;

    public function __construct(SwagProductDiscountRepository $swagProductDiscountRepository)
    {
        $this->swagProductDiscountRepository = $swagProductDiscountRepository;
    }

    /**
     * @Route("/swagProductDiscount.{responseFormat}", name="api.swagProductDiscount.list", methods={"GET"})
     *
     * @param Request    $request
     * @param ApiContext $context
     *
     * @return Response
     */
    public function listAction(Request $request, ApiContext $context): Response
    {
        $criteria = new Criteria();

        if ($request->query->has('offset')) {
            $criteria->setOffset((int) $request->query->get('offset'));
        }

        if ($request->query->has('limit')) {
            $criteria->setLimit((int) $request->query->get('limit'));
        }

        if ($request->query->has('query')) {
            $criteria->addFilter(
                QueryStringParser::fromUrl($request->query->get('query'))
            );
        }

        $criteria->setFetchCount(true);

        $swagProductDiscounts = $this->swagProductDiscountRepository->search(
            $criteria,
            $context->getShopContext()->getTranslationContext()
        );

        return $this->createResponse(
            ['data' => $swagProductDiscounts, 'total' => $swagProductDiscounts->getTotal()],
            $context
        );
    }

    /**
     * @Route("/swagProductDiscount/{swagProductDiscountUuid}.{responseFormat}", name="api.swagProductDiscount.detail", methods={"GET"})
     *
     * @param Request    $request
     * @param ApiContext $context
     *
     * @return Response
     */
    public function detailAction(Request $request, ApiContext $context): Response
    {
        $uuid = $request->get('swagProductDiscountUuid');
        $swagProductDiscounts = $this->swagProductDiscountRepository->readDetail(
            [$uuid],
            $context->getShopContext()->getTranslationContext()
        );

        return $this->createResponse(['data' => $swagProductDiscounts->get($uuid)], $context);
    }

    /**
     * @Route("/swagProductDiscount.{responseFormat}", name="api.swagProductDiscount.create", methods={"POST"})
     *
     * @param ApiContext $context
     *
     * @return Response
     */
    public function createAction(ApiContext $context): Response
    {
        $createEvent = $this->swagProductDiscountRepository->create(
            $context->getPayload(),
            $context->getShopContext()->getTranslationContext()
        );

        $swagProductDiscounts = $this->swagProductDiscountRepository->read(
            $createEvent->getSwagProductDiscountUuids(),
            $context->getShopContext()->getTranslationContext()
        );

        $response = [
            'data' => $swagProductDiscounts,
            'errors' => $createEvent->getErrors(),
        ];

        return $this->createResponse($response, $context);
    }

    /**
     * @Route("/swagProductDiscount.{responseFormat}", name="api.swagProductDiscount.upsert", methods={"PUT"})
     *
     * @param ApiContext $context
     *
     * @return Response
     */
    public function upsertAction(ApiContext $context): Response
    {
        $createEvent = $this->swagProductDiscountRepository->upsert(
            $context->getPayload(),
            $context->getShopContext()->getTranslationContext()
        );

        $swagProductDiscounts = $this->swagProductDiscountRepository->read(
            $createEvent->getSwagProductDiscountUuids()['uuid'],
            $context->getShopContext()->getTranslationContext()
        );

        $response = [
            'data' => $swagProductDiscounts,
            'errors' => $createEvent->getErrors(),
        ];

        return $this->createResponse($response, $context);
    }

    /**
     * @Route("/swagProductDiscount.{responseFormat}", name="api.swagProductDiscount.update", methods={"PATCH"})
     *
     * @param ApiContext $context
     *
     * @return Response
     */
    public function updateAction(ApiContext $context): Response
    {
        $createEvent = $this->swagProductDiscountRepository->update(
            $context->getPayload(),
            $context->getShopContext()->getTranslationContext()
        );

        $swagProductDiscounts = $this->swagProductDiscountRepository->read(
            $createEvent->getSwagProductDiscountUuids(),
            $context->getShopContext()->getTranslationContext()
        );

        $response = [
            'data' => $swagProductDiscounts,
            'errors' => $createEvent->getErrors(),
        ];

        return $this->createResponse($response, $context);
    }

    /**
     * @Route("/swagProductDiscount/{swagProductDiscountUuid}.{responseFormat}", name="api.swagProductDiscount.single_update", methods={"PATCH"})
     *
     * @param Request    $request
     * @param ApiContext $context
     *
     * @return Response
     */
    public function singleUpdateAction(Request $request, ApiContext $context): Response
    {
        $payload = $context->getPayload();
        $payload['uuid'] = $request->get('swagProductDiscountUuid');

        $updateEvent = $this->swagProductDiscountRepository->update(
            [$payload],
            $context->getShopContext()->getTranslationContext()
        );

        if ($updateEvent->hasErrors()) {
            $errors = $updateEvent->getErrors();
            $error = array_shift($errors);

            return $this->createResponse(['errors' => $error], $context, 400);
        }

        $swagProductDiscounts = $this->swagProductDiscountRepository->read(
            [$payload['uuid']],
            $context->getShopContext()->getTranslationContext()
        );

        return $this->createResponse(
            ['data' => $swagProductDiscounts->get($payload['uuid'])],
            $context
        );
    }

    /**
     * @Route("/swagProductDiscount.{responseFormat}", name="api.swagProductDiscount.delete", methods={"DELETE"})
     *
     * @param ApiContext $context
     *
     * @return Response
     */
    public function deleteAction(ApiContext $context): Response
    {
        $result = ['data' => []];

        return $this->createResponse($result, $context);
    }

    protected function getXmlRootKey(): string
    {
        return 'swagProductDiscounts';
    }

    protected function getXmlChildKey(): string
    {
        return 'swagProductDiscount';
    }
}
