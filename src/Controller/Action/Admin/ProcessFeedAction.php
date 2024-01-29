<?php

declare(strict_types=1);

namespace Setono\SyliusFeedPlugin\Controller\Action\Admin;

use Setono\SyliusFeedPlugin\Message\Command\ProcessFeed;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProcessFeedAction
{
    public function __construct(
        protected MessageBusInterface $commandBus,
        protected UrlGeneratorInterface $urlGenerator,
        protected RequestStack $requestStack,
        protected TranslatorInterface $translator
    ) {
    }

    public function __invoke(int $id): RedirectResponse
    {
        $this->commandBus->dispatch(new ProcessFeed($id));

        $this->requestStack->getSession()->getBag('flashes')->add(
            'success',
            $this->translator->trans('setono_sylius_feed.feed_generation_triggered'),
        );

        return new RedirectResponse($this->urlGenerator->generate('setono_sylius_feed_admin_feed_show', ['id' => $id]));
    }
}
