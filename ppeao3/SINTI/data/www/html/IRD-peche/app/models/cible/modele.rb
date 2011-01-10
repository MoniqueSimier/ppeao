class Cible::Modele < ActiveRecord::Base
establish_connection(
    :adapter => "postgresql",
    :database => APP_CONFIG['postgres_cible_database'],
    :username => APP_CONFIG['postgres_user'],
    :password => APP_CONFIG['postgres_password'],
    :host => APP_CONFIG['postgres_host'])
end
