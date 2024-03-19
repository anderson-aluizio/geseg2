@include('vendor/autoload.php')
@setup
    $env = isset($env) ? $env : "production";
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__,".env.$env");
    try {
    $dotenv->load();
    $dotenv->required(['DEPLOY_SERVER', 'DEPLOY_REPOSITORY', 'DEPLOY_PATH'])->notEmpty();
    } catch ( Exception $e ) {
    echo $e->getMessage();
    exit;
    }

    $server = $_ENV['DEPLOY_SERVER'];
    $repo = $_ENV['DEPLOY_REPOSITORY'];
    $path =$_ENV['DEPLOY_PATH'];
    $healthUrl = $_ENV['DEPLOY_HEALTH_CHECK'];

    if( substr($path, 0, 1) !== '/' ) throw new Exception('Careful - your deployment path does not begin with /');

    $date = ( new DateTime )->format('Ymd_His'); //If you want a clear format you can use 'Y-m-d_H:i:s'
    $branch = isset($branch) ? $branch : "main";
    $path = rtrim($path, '/');
    $release = $path.'/'.$date;
@endsetup

@servers(['web' => $server])

@task('init')
    if [ ! -d {{ $path }}/current ]; then
    cd {{ $path }}
    git clone {{ $repo }} --branch={{ $branch }} --depth=1 -q {{ $release }}
    echo "Repository cloned"
    mv {{ $release }}/storage {{ $path }}/storage
    ln -sv {{ $path }}/storage {{ $release }}/storage
    ln -sv {{ $path }}/storage/public {{ $release }}/public/storage
    echo "Storage directory set up"
    cp {{ $release }}/.env.example {{ $path }}/.env
    ln -sv {{ $path }}/.env {{ $release }}/.env
    echo "Environment file set up"
    rm -rf {{ $release }}
    echo "Deployment path initialised. Run 'envoy run deploy' now."
    else
    echo "Deployment path already initialised (current symlink exists)!"
    fi
@endtask

@story('deploy')
    deployment_start
    deployment_links
    deployment_composer
    build_assets
    deployment_migrate
    deployment_cache
    deployment_finish
    deployment_option_cleanup
@endstory

@story('deploy_cleanup')
    deployment_start
    deployment_links
    deployment_composer
    deployment_cache
    deployment_finish
    deployment_cleanup
@endstory

@story('rollback')
    deployment_rollback
    health_check
@endstory

@task('deployment_start')
    cd {{ $path }}
    echo "Deployment ({{ $date }}) started"
    git clone {{ $repo }} --branch={{ $branch }} --depth=1 -q {{ $release }}
    echo "Repository cloned"
@endtask

@task('deployment_links')
    cd {{ $path }}
    rm -rf {{ $release }}/storage
    ln -s {{ $path }}/storage {{ $release }}/storage
    ln -s {{ $path }}/storage/public {{ $release }}/public/storage
    echo "Storage directories set up"
    ln -s {{ $path }}/.env {{ $release }}/.env
    echo "Environment file set up"
@endtask

@task('deployment_composer')
    echo "Installing composer depencencies..."
    cd {{ $release }}
    composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader
@endtask

@task('deployment_migrate')
    php {{ $release }}/artisan migrate --env={{ $env }} --force --no-interaction
@endtask

@task('db_seed')
    php {{ $release }}/artisan db:seed --class=PermissionSeeder --force
@endtask

@task('deployment_cache')
    php {{ $release }}/artisan view:clear --quiet
    php {{ $release }}/artisan config:clear --quiet
    php {{ $release }}/artisan cache:clear --quiet
    php {{ $release }}/artisan config:cache --quiet
    php {{ $release }}/artisan optimize --quiet
    echo "Cache cleared"
@endtask

@task('deployment_finish', ['on' => 'web'])
    php {{ $release }}/artisan down --refresh=15
    php {{ $release }}/artisan queue:restart --quiet
    echo "Queue restarted"
    ln -nfs {{ $release }} {{ $path }}/current
    ln -nfs {{ $path }}/current/storage/app/public {{ $path }}/current/public/storage
    echo "Deployment ({{ $date }}) finished"
    echo "Exec Restart"
    bash /home/sanduba/app/nginxReloader.sh
    php {{ $release }}/artisan up
@endtask

@task('deployment_cleanup')
    cd {{ $path }}
    find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
    echo "Cleaned up old deployments"
@endtask

@task('deployment_option_cleanup')
    cd {{ $path }}
    @if (isset($cleanup) && $cleanup)
        find . -maxdepth 1 -name "20*" | sort | head -n -4 | xargs rm -Rf
        echo "Cleaned up old deployments"
    @endif
@endtask


@task('build_assets')
    echo "build assets"
    cd {{ $release }}
    npm install
    npm run build
@endtask

@task('permission_storage')
    sudo chown -R www-data:www-data {{ $path }}/storage
@endtask

@task('health_check')
    @if (!empty($healthUrl))
        if [ "$(curl --write-out "%{http_code}\n" --silent --output /dev/null {{ $healthUrl }})" == "200" ]; then
        printf "\033[0;32mHealth check to {{ $healthUrl }} OK\033[0m\n"
        else
        printf "\033[1;31mHealth check to {{ $healthUrl }} FAILED\033[0m\n"
        fi
    @else
        echo "No health check set"
    @endif
@endtask


@task('deployment_rollback')
    cd {{ $path }}
    ln -nfs {{ $path }}/$(find . -maxdepth 1 -name "20*" | sort | tail -n 2 | head -n1)
    {{ $path }}/current
    echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort | tail -n 2 | head -n1)"
@endtask

{{--
@finished
@slack($slack, '#deployments', "Deployment on {$server}: {$date} complete")
@endfinished
--}}
