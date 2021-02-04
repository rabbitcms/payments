<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\ServiceManager;

use DtKt\ServiceManager\Contracts\ServiceDescriptionInterface;
use DtKt\ServiceManager\Contracts\ServiceProviderAutoInterface;
use DtKt\ServiceManager\Contracts\ServiceProviderInterface;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use RabbitCMS\Payments\Facade\Payments;

class PaymentServiceProvider implements ServiceProviderInterface, ServiceProviderAutoInterface
{
    public function getCaption(): string
    {
        return 'Acquiring';
    }

    public function getNovaFields(NovaRequest $request): array
    {
        $providers = array_filter(Payments::getProviders(), fn($provider) => method_exists($provider, 'getNovaFields'));

        return [
            Select::make('Провайдер', 'provider')
                ->options(fn() => array_map(fn($provider) => $provider->getProviderName(), $providers))
                ->rules(['required']),
            ...array_values(array_map(fn($provider) => NovaDependencyContainer::make($provider->getNovaFields($request))
                ->dependsOn('provider', $provider->getProviderName()), $providers)),
        ];
    }

    public function check(ServiceDescriptionInterface $service): bool
    {
        $config = $service->getConfig();
        $provider = Payments::callCustomCreator($config['provider'], $config);
        return $provider->isValid();
    }

    /**
     * @param  Application  $application
     * @param  array<ServiceDescriptionInterface>  $services
     */
    public function register(Application $application, array $services): void
    {
        /** @var Repository $config */
        $config = $application->make('config');
        $default = null;

        foreach ($services as $service) {
            $config->set("payments.{$service->getName()}", $service->getConfig());
            if ($service->isDefault()) {
                $config->set("payments.default", $service->getName());
            }
        }
    }
}
