<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'internal:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate Endpoint clases based on scribe yaml's ";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $file = Yaml::parseFile(base_path('.scribe/endpoints/03.yaml'));

        $serviceName = Str::ucfirst('user');

        $EndpointsWithoutName = 0;
        foreach ($file['endpoints'] as $endpoint) {
            if (!preg_match('#^v(\d+)/(?P<route>internal|admin)/#', $endpoint['uri'], $matches)) {
                continue;
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

            $basePath = "app/InternalApiProtocol/{$serviceName}";

            $dirPath = "$basePath/$version";

            if ($admin) {
                $dirPath = "$basePath/Admin/$version";
            }

            $filepath = "$dirPath/{$class}Endpoint.php";

            $this->dirExists($dirPath);

            if (file_exists($filepath)) {
                $this->info("Файл:($filepath) уже существует");
                $this->newLine();

                continue;
            }

            $generatedClass = $this->generateClass($version, $method, $route, $class, $serviceName, $admin);

            file_put_contents($filepath, $generatedClass);
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

        return $stub;
    }

    private function cutVersion(string $route): string
    {
        $route = explode('/', $route);
        unset($route[0]);

        return implode('/', $route);
    }

    private function dirExists(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

}




