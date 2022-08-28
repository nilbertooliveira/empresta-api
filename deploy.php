<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';
require 'contrib/rsync.php';

///////////////////////////////////
// Config
///////////////////////////////////

set('application', 'Empresta test');
set('repository', 'git@github.com:nilbertooliveira/empresta-api.git'); // Git Repository
set('ssh_multiplexing', false);  // Speed up deployment
//set('default_timeout', 1000);

set('rsync_src', function () {
    return __DIR__; // If your project isn't in the root, you'll need to change this.
});

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add('rsync', [
    'exclude' => [
        '.git',
        '/vendor/',
        '/node_modules/',
        '.github',
        'deploy.php',
    ],
]);

// Set up a deployer task to copy secrets to the server.
// Grabs the dotenv file from the github secret
task('deploy:secrets', function () {
    file_put_contents(__DIR__ . '/.env', getenv('DOT_ENV'));
    upload('.env', get('deploy_path') . '/shared');
});

///////////////////////////////////
// Hosts
///////////////////////////////////

host('prod') // Name of the server
->setHostname('184.72.97.231') // Hostname or IP address
->set('remote_user', 'admin') // SSH user
->set('branch', 'master') // Git branch
->set('deploy_path', '/home/admin/projetos')// Deploy path
->set('http_user', 'admin')
->set('writable_use_sudo', false)
->set('writable_mode', 'chmod')
->set('composer_action', 'update');

after('deploy:failed', 'deploy:unlock');  // Unlock after failed deploy

///////////////////////////////////
// Tasks
///////////////////////////////////

desc('Start of Deploy the application');

task('deploy', [
    'deploy:prepare',
    'rsync',                // Deploy code & built assets
    'deploy:secrets',       // Deploy secrets
    'deploy:vendors',
    'deploy:shared',        //
    'deploy:cleanup',
    'deploy:publish',       //
]);

desc('End of Deploy the application');
