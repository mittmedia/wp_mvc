#require "bundler/capistrano"

load 'deploy'

# Uncomment if you are using Rails' asset pipeline
#load 'deploy/assets'

Dir['vendor/gems/*/recipes/*.rb','vendor/plugins/*/recipes/*.rb'].each { |plugin| load(plugin) }
#load 'config/deploy' # remove this line to skip loading any of the default tasks

# RVM
#set :rvm_ruby_string, 'ruby-1.9.2-p318'
#require "rvm/capistrano"

# MISC
set :application, "wp_mvc"
set :use_sudo, false

# SERVER
set :user, "mim-app"
set :deploy_to, "/www/mittmedia/deploy/#{application}"

# SCM
set :scm, :git
set :scm_username, "git"
set :branch, "master"
set :local_repository, "https://github.com/mittmedia/wp_mvc.git"
# a.github.com is needed since GitHub needs different SSH keys per
# application. See config in ~/.ssh/config on target environment.
set :repository, "https://github.com/mittmedia/wp_mvc.git"
set :deploy_via, :remote_cache

# TASKS
desc "Use stage environment"
task :stage do
  server "mim-stage01.sth.basefarm.net", :app, :web, :db, :primary => true

  # Remove all but the 5 latest releases
  after "deploy:restart", "deploy:cleanup"
end

desc "Use production environment"
task :production do
  server "mim-mysql01.sth.basefarm.net", :app, :web, :db, :primary => true

  # Remove all but the 5 latest releases
  after "deploy:restart", "deploy:cleanup"
end

#desc "Use production environment"
#task :production do
#  set :rails_env, "production"
#  server "mim-stage01.sth.basefarm.net", :app, :web, :db, :primary => true
#end
