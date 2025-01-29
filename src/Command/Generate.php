<?php

use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class Generate
{
    public function handle(): void
    {

        $file = Yaml::parseFile(base_path('.scribe/endpoints/03.yaml'));

        $serviceName = Str::ucfirst('user');

        $EndpointsWithoutName = 0;
        foreach ($file['endpoints'] as $endpoint) {
            if (!preg_match('#^v(\d+)/(?P<route>internal|admin)/#', $endpoint['uri'], $matches)) {
                dd('Не повезло тебе');
            }

            $route = $this->cutVersion($endpoint['uri']);

            $version = "V$matches[1]";

            $matches['route'] === 'admin'
                ? $admin = true
                : $admin = false;

            $class = Str::studly($endpoint['metadata']['description']);
            if (empty($endpoint['metadata']['description'])) {
                $class = 'Endpoint' . (string) $EndpointsWithoutName;
                $EndpointsWithoutName++;
            }

            $method = Str::upper($endpoint['httpMethods'][0]);

            $basePath = base_path("InternalApiProtocol/{$serviceName}");

            $dirPath = "$basePath/$version";

            if ($admin) {
                $dirPath = "$basePath/Admin/$version";
            }

            $filepath = "$dirPath/{$class}Endpoint.php";

            if (file_exists($filepath)) {
                // $this->info("Skipping {$filePath}, already exists.");
                dump("Skipping $filePath, already exists.");
                continue;
            }

            $this->generateClass($version, $method, $route, $class, $serviceName, $admin);
        }

    }

    private function generateClass(
        string $version,
        string $method,
        string $route,
        string $class,
        string $serviceName,
        bool $admin,
    ): string {
        $admin
            ? $stub = file_get_contents(base_path('stubs/endpoint.admin.stub'))
            : $stub = file_get_contents(base_path('stubs/endpoint.stub'));

        $stub = str_replace(['{{version}}'], $version, $stub);
        $stub = str_replace(['{{method}}'], $method, $stub);
        $stub = str_replace(['{{route}}'], $route, $stub);
        $stub = str_replace(['{{class}}'], $class, $stub);
        $stub = str_replace(['{{serviceName}}'], $serviceName, $stub);
        dd($stub);
        return '';
    }

    private function cutVersion(string $route): string
    {
        $route = explode('/', $route);
        unset($route[0]);

        return implode('/', $route);
    }

}


